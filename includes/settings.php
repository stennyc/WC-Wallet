<?php
if(!ABSPATH){
	exit;
}

if( isset( $_POST["submit"] ) ){
	if( wcw_update_form( $_POST ) ){
		wcw_plugin_success_msg( "Settings Saved." );
	}	
}

$wcw_payment_method 		= explode( ",",get_option('wcw_payment_method') );
$wcw_transfer_only 			= json_decode( get_option('wcw_transfer_only'), true );
$wcw_apply_tax_yes 			= get_option('wcw_apply_tax') == 1 				? 'checked' : '';
$wcw_apply_tax_no 			= get_option('wcw_apply_tax') == 0 				? 'checked' : '';
$wcw_notify_admin 			= get_option('wcw_notify_admin') == 1 			? 'checked' : '';
$wcw_remining_credits 		= get_option('wcw_remining_credits') == 1 		? 'checked' : '';
$wcw_restrict_max 			= get_option('wcw_restrict_max');
$wcw_new_user_credits		= get_option('wcw_new_user_credits');
$wcw_cancel_req 			= get_option('wcw_cancel_req') == 1 			? 'checked' : '';
$wcw_automatic_cancel_req 	= get_option('wcw_automatic_cancel_req') == 1 	? 'checked' : '';
$wcw_is_float_value_no		= get_option('wcw_is_float_value') == 0			? 'checked' : '';
$wcw_is_float_value_yes		= get_option('wcw_is_float_value') == 1			? 'checked' : '';

?>
<style>
.checkboxes{
	width: 100%;
	float: left;
	margin-top: 10px;
}

.w200{
	width: 200px;
	float: left;
}
</style>
<div class = "wrap">
<h1>WC Wallet Settings</h1>
<form method="post">
	<h3 class="title"><?php _e( 'General Settings', WC_WALLET_TEXT ); ?></h3>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="wcw_payment_method"><?php _e( 'Credits Applicable If The Order Are In These Method', WC_WALLET_TEXT ); ?></label></th>
			<td>
				<?php 
					$array = new WC_Payment_Gateways();
					$methods = $array->get_available_payment_gateways();
					if( count($methods) != 0 ){
						foreach( $methods as $key => $method ){
				?>
						<label class = "checkboxes" for="dashboard_right_now-hide_<?php echo $key;?>">
							<input class="hide-postbox-tog" name="wcw_payment_method[]" type="checkbox" <?php if( in_array($key, $wcw_payment_method) ){ echo "checked"; } ?> id="dashboard_right_now-hide_<?php echo $key;?>" value="<?php echo $key; ?>">
							<?php echo $method->title; ?>						
						</label>
				<?php 
						}
					}else{
						_e("No Payment Methods found.!", WC_WALLET_TEXT);
					}
				?>
				<p class="description" id="tagline-description"><?php _e( 'When order is cancelled, if the above checked method are there, the cancel request can be processed.', WC_WALLET_TEXT ); ?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_transfer_only"><?php _e( 'Credits Transfered When Order Status is in', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<?php 
					$sts = wc_get_order_statuses();
					$array = new WC_Payment_Gateways();
					$methods = $array->get_available_payment_gateways();
					if( count($methods) != 0 ){
						echo "<table>";
						foreach( $methods as $key => $method ){
							$current_status = $wcw_transfer_only["$key"];
							echo "<tr>";
							echo "<td style ='padding-left: 0px; vertical-align: top;'>" . $method->title . "</td><td>";
							foreach( $sts as $keys => $status ){
								?>
								<label class = "w200" for="dashboard_right_now-hide_<?php echo $keys;?>_<?php echo $key;?>">
									<input class="hide-postbox-tog" name="wcw_transfer_only[<?php echo $key; ?>][]" type="checkbox" <?php if( $current_status&&in_array($keys, $current_status) ){ echo "checked"; }  ?> id="dashboard_right_now-hide_<?php echo $keys;?>_<?php echo $key;?>" value="<?php echo $keys; ?>">
									<?php echo $status; ?>						
								</label>
								<?php 
							}
							echo "</td></tr>";
						}
						echo "</table>";
					}else{
						echo "No Payment Methods found.!";
					}
				?>
				<p class="description" id="tagline-description"><?php _e( 'This option is used to filter transfer of credits only if the checked status are given.', WC_WALLET_TEXT );  ?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_apply_tax"><?php _e( 'Apply Credits For Tax and Shipping?', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input type = "radio" id = "wcw_apply_tax" name = "wcw_apply_tax" value = "1" <?php echo $wcw_apply_tax_yes; ?>> Yes 
				<input type = "radio" id = "wcw_apply_tax" name = "wcw_apply_tax" value = "0" <?php echo $wcw_apply_tax_no; ?>> No
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_is_float_value"><?php _e( 'Validate round value', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input type = "radio" id = "wcw_is_float_value" name = "wcw_is_float_value" value = "1" <?php echo $wcw_is_float_value_yes; ?>> Yes 
				<input type = "radio" id = "wcw_is_float_value" name = "wcw_is_float_value" value = "0" <?php echo $wcw_is_float_value_no; ?>> No
				<p class="description" id="tagline-description"><?php _e('Enabling this option, can values can be give as 200.99 and disabling this, validated as 200 or 210 ( No decimal ).', WC_WALLET_TEXT); ?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_restrict_max"><?php _e( 'Restrict Maximum credits', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input name="wcw_restrict_max" type="number" id="wcw_restrict_max" value = "<?php echo $wcw_restrict_max; ?>" class="regular-text">
				<p class="description" id="tagline-description"><?php _e( 'This can restrict maximum credits usage for a person. Leave blank for no restrict.', WC_WALLET_TEXT );?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_notify_admin"><?php _e( 'Notify Admin', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input type = "checkbox" id = "wcw_notify_admin" name = "wcw_notify_admin" value = "1" <?php echo $wcw_notify_admin; ?>> Yes 
				<p class="description" id="tagline-description"><?php _e( 'Notify admin if any changes happen in user wallet.', WC_WALLET_TEXT ); ?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_remining_credits"><?php _e('Show Credits Remining In Cart', WC_WALLET_TEXT); ?></label>
			</th>
			<td>
				<input type = "checkbox" id = "wcw_remining_credits" name = "wcw_remining_credits" <?php echo $wcw_remining_credits; ?> value = "1"> Yes 
				<p class="description" id="tagline-description"><?php _e( 'Hide or Show the remaining credits available for a user in the cart page.', WC_WALLET_TEXT ); ?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_new_user_credits"><?php _e( 'New user credits', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input name="wcw_new_user_credits" type="number" id="wcw_new_user_credits" value = "<?php echo $wcw_new_user_credits; ?>" class="regular-text">
				<p class="description" id="tagline-description"><?php _e( 'Offer credits for new users, just like coupon.', WC_WALLET_TEXT );  ?></p>
			</td>
		</tr>
		
	</table>
	
	<h3 class="title"><?php _e( 'Send Cancel Request Option', WC_WALLET_TEXT ); ?></h3>
	<table class="form-table">		
		<tr>
			<th scope="row">
				<label for="wcw_cancel_req"><?php _e( 'Enable "Send Cancel Request"', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input type = "checkbox" id = "wcw_cancel_req" name = "wcw_cancel_req" <?php echo $wcw_cancel_req; ?> value = "1"> Yes 
				<p class="description" id="tagline-description"><?php _e( 'Enabling this will add a button called "Send Cancel Request" in my-account page.', WC_WALLET_TEXT ); ?></p>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				<label for="wcw_automatic_cancel_req"><?php _e( 'Automatically Cancel Order On "Send Cancel Request" and Refund Credits', WC_WALLET_TEXT ); ?></label>
			</th>
			<td>
				<input type = "checkbox" id = "wcw_automatic_cancel_req" name = wcw_automatic_cancel_req <?php echo $wcw_automatic_cancel_req; ?> value = "1"> Yes 
				<p class="description" id="tagline-description"><?php _e( 'If this option is not enabled, you need to customly click refund under Cancel Request Page.', WC_WALLET_TEXT ); ?></p>
			</td>
		</tr>
	</table>
	
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', WC_WALLET_TEXT ); ?>">
	</p>
</form>
</div>