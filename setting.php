<div class="wrap">
	<h2><?php _e('zarinpal Setting', 'zarinpal'); ?></h2>
	<form method="post" action="options.php">
		<table class="form-table">
			<?php wp_nonce_field('update-options');?>
			<tr><th colspan="2"><h3><?php _e('General Setting', 'zarinpal'); ?></h4></th></tr>
			

			<tr>
				<td width="20%"><?php _e('Merchant ID:', 'zarinpal'); ?></td>
				<td>
					<input type="text" dir="ltr" name="MerchantID" value="<?php echo get_option('MerchantID'); ?>"/>
					<br /><span style="font-size: 10px"><?php _e('Your Merchant ID in the zarinpal', 'zarinpal'); ?></span>
				</td>
			</tr>

			

			<tr>
				<td>
					<p class="submit">
					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="MerchantID,port_password" />
					<input type="submit" class="button-primary" name="Submit" value="به روز رساني" />
					</p>
				</td>
			</tr>
		</table>
	</form>
</div>