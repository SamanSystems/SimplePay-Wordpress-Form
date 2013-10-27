<?php
/*
Plugin Name: zarinpal
Plugin URI: https://ir.zarinpal.com
Description: Zarinpal getway for wordpress
Version: 1.0
Author: Masoud Amini
Author URI: https://ir.zarinpal.com
License: GPL2
*/
	load_plugin_textdomain('zarinpal','wp-content/plugins/zarinpal/langs');

	include_once('inc/zarinpal.class.php');
	$zarinpal = new zarinpal;

	if(get_option('MerchantID')) {
		$zarinpal->MerchantID = get_option('MerchantID');
		
	}

	function zarinpal_menu() {
		if (function_exists('add_options_page')) {
			add_menu_page(__('zarinpal', 'zarinpal'), __('zarinpal', 'zarinpal'), 'manage_options', 'zarinpal/setting.php', 'zarinpal_menupage', plugin_dir_url( __FILE__ ).'/images/zarinpal_favicon.png');
			add_submenu_page('zarinpal/setting.php', __('zarinpal Setting', 'zarinpal'), __('zarinpal Setting', 'zarinpal'), 'manage_options', 'zarinpal/setting.php', 'zarinpal_menupage');
		}
	}
	add_action('admin_menu', 'zarinpal_menu');

	function zarinpal_menupage() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'zarinpal'));
		}

		settings_fields('zarinpal_options');
		function register_zarinpal() {
			register_setting('zarinpal_options', 'MerchantID');
			
		}

		include_once('setting.php');
	}

	function zarinpal_form() {

		if(is_user_logged_in()) {

			global $current_user, $zarinpal;
			include_once('inc/form.php');

			if($_POST['submit_payment']) {

				if($_POST['payer_name'] && $_POST['payer_email'] && $_POST['payer_mobile'] && $_POST['payer_price'] && $_POST['description_payment']) {

					$zarinpal->Price = $_POST['payer_price'];
					$zarinpal->ReturnPath = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
					$zarinpal->ResNumber = preg_replace("/[^0-9]/", "", uniqid());
					$zarinpal->Description = ' | ' . $_POST['description_payment'] . ' |  ' . $_POST['payer_name'] . ' |  ' . $_POST['payer_email'] . ' |  ' .$_POST['payer_mobile'] . '';
					$zarinpal->Paymenter = $_POST['payer_name'];
					$zarinpal->Email = $_POST['payer_email'];
					$zarinpal->Mobile = $_POST['payer_mobile'];

					if($zarinpal->Request()) {

						switch($zarinpal->Request()) {

							case '-1':
								echo '<p class="error-payment">' . __('Error! No action has been.', 'zarinpal') . '</p>';
								break;

							case '-2':
								echo '<p class="error-payment">' . __('Error! Your port is disabled.', 'zarinpal') . '</p>';
								break;

							case '-3':
								echo '<p class="error-payment">' . __('Error! Your port is invalid.', 'zarinpal') . '</p>';
								break;

							case '-4':
								echo '<p class="error-payment">' . __('Error! Your port is blocked.', 'zarinpal') . '</p>';
								break;

						}
					} else {
						add_option('user_price_' . $current_user->ID, $_POST['payer_price']);
						update_option('user_price_' . $current_user->ID, $_POST['payer_price']);
					}
				} else {
					echo '<p class="error-payment">' . __('Error! Please Complate all field.', 'zarinpal') . '</p>';
				}
			}

			$zarinpal->Price = get_option('user_price_' . $current_user->ID);

			switch($zarinpal->Verify()) {

				case '-2':
					echo '<p class="error-payment">' . __('Error! No action has been.', 'zarinpal') . '</p>';
					
					continue;

				case '-11':
					echo '<p class="error-payment">' . __('Error! Paid the amount requested is not equa.', 'zarinpal') . '</p>';
					continue;

				case '-12':
					echo '<p class="error-payment">' . __('Error! Has already been paid.', 'zarinpal') . '</p>';
					continue;

				case '-1':
					echo '<p class="error-payment">' . __('Error! Receipt number is not acceptable.', 'zarinpal') . '</p>';
					continue;

				case '1':
					echo '<p class="success-payment">' . sprintf(__('Transaction was successful. <br /> Your tracking number: %s <br /> Payment price : %s <br /> Your Order ID: %s', 'zarinpal'), $zarinpal->RefNumber, number_format($zarinpal->PayPrice, 0, '.', ''), $zarinpal->ResNumber) . '</p>';
					$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
					$mail_headers .= "From: admin <admin>\r\n";
					$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
					wp_mail($current_user->user_email, "پرداخت شما تایید شد", "پرداخت شما تایید شد <br />   ", $mail_headers);
					continue;
			}

			delete_option('user_price_' . $current_user->ID);

		} else {
			echo sprintf(__('This form is for registeration users. please <a href="%s">login</a> to site.', 'zarinpal'), wp_login_url());
		}
	}
	
	add_shortcode('zarinpal', 'zarinpal_form');
	add_filter('widget_text', 'do_shortcode');
?>