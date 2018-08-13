<?php
/**
 * This is just a sample file. It demonstrate how you need to include all components file in your plguin.
 * NOTE: Wcap : It is prefix of the Abandoned Cart Pro for WooCommerce plugin. You need to change according to your plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Wcap_All_Component' ) ) {
	/**
	 * It will Add all the Boilerplate component when we activate the plugin.
	 * 
	 */
	class Wcap_All_Component {
	    
		/**
		 * It will Add all the Boilerplate component when we activate the plugin.
		 */
		public function __construct() {

			$is_admin = is_admin();

			if ( true === $is_admin ) {

                /**
                 * Here component is a folder. You can include all individual components in a single folder.
                 * 
                 */
                require_once( "component/license-active-notice/ts-active-license-notice.php" );
                require_once( "component/WooCommerce-Check/ts-woo-active.php" );

                require_once( "component/tracking-data/ts-tracking.php" );
                require_once( "component/deactivate-survey-popup/class-ts-deactivation.php" );

                require_once( "component/welcome-page/ts-welcome.php" );
                require_once( "component/faq-support/ts-faq-support.php" );
                
                $wcap_plugin_name          = 'Abandoned Cart Pro for WooCommerce';
                $wcap_edd_license_option   = 'edd_sample_license_status_ac_woo';
                $wcap_license_path         = 'admin.php?page=woocommerce_ac_page&action=emailsettings';
                $wcap_locale               = 'woocommerce-ac';
                $wcap_file_name            = 'woocommerce-abandon-cart-pro/woocommerce-ac.php';
                $wcap_plugin_prefix        = 'wcap';
                $wcap_lite_plugin_prefix   = 'wcal';
                $wcap_plugin_folder_name   = 'woocommerce-abandon-cart-pro/';
                $wcap_plugin_dir_name      = WCAP_PLUGIN_PATH . '/woocommerce-ac.php' ;

                $wcap_blog_post_link       = 'https://www.tychesoftwares.com/docs/docs/abandoned-cart-pro-for-woocommerce/usage-tracking/';

                $wcap_get_previous_version = get_option( 'woocommerce_ac_db_version' );

                $wcap_plugins_page         = 'admin.php?page=woocommerce_ac_page';
                $wcap_plugin_slug          = 'woocommerce_ac_page';

                $wcap_settings_page        = 'admin.php?page=woocommerce_ac_page&action=emailsettings';
                $wcap_setting_add_on       = 'woocommerce_ac_page';
                $wcap_setting_section      = 'ac_general_settings_section';
                $wcap_register_setting     = 'woocommerce_ac_settings';
                $wcap_pro_file_name        = '';

                new wcap_active_license_notice ( $wcap_plugin_name, $wcap_edd_license_option, $wcap_license_path, $wcap_locale );
				
				new Wcap_TS_Woo_Active ( $wcap_plugin_name, $wcap_file_name, $wcap_locale );

                new Wcap_TS_tracking ( $wcap_plugin_prefix, $wcap_plugin_name, $wcap_blog_post_link, $wcap_locale, WCAP_PLUGIN_URL, $wcap_settings_page, $wcap_setting_add_on, $wcap_setting_section, $wcap_register_setting );

                new Wcap_TS_Tracker ( $wcap_plugin_prefix, $wcap_plugin_name );

                $wcap_deativate = new Wcap_TS_deactivate;
                $wcap_deativate->init ( $wcap_file_name, $wcap_plugin_name );

                $user = wp_get_current_user();
                
                if ( in_array( 'administrator', (array) $user->roles ) ) {
                    new Wcap_TS_Welcome ( $wcap_plugin_name, $wcap_plugin_prefix, $wcap_locale, $wcap_plugin_folder_name, $wcap_plugin_dir_name, $wcap_get_previous_version );
                }
                $ts_pro_faq = self::wcap_get_faq ();
                new Wcap_TS_Faq_Support( $wcap_plugin_name, $wcap_plugin_prefix, $wcap_plugins_page, $wcap_locale, $wcap_plugin_folder_name, $wcap_plugin_slug, $ts_pro_faq );
                
                /**
                 * This is only for the lite version of the plguins.
                 */
                if ( in_array('woocommerce/woocommerce.php', get_option('active_plugins') ) ) {
                    $ts_pro_notices = self::wcap_get_notice_text ();
                    new Wcap_ts_pro_notices( $wcap_plugin_name, $wcap_lite_plugin_prefix, $wcap_plugin_prefix, $ts_pro_notices, $wcap_file_name, $wcap_pro_file_name );
                }
            }
        }
        
        /**
         * It will Display the notices in the admin dashboard for the pro vesion of the plugin.
         * @return array $ts_pro_notices All text of the notices
         */
        public static function wcap_get_notice_text () {
            $ts_pro_notices = array();

            $wcap_locale               = self::ts_get_plugin_locale();

            $message_first = wp_kses_post ( __( 'Thank you for using WooCommerce Print Invoice & Delivery Note plugin! Now make your deliveries more accurate by allowing customers to select their preferred delivery date & time from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Get it now!</a></strong>', $wcap_locale ) );  

            $message_two = wp_kses_post ( __( 'Never login to your admin to check your deliveries by syncing the delivery dates to the Google Calendar from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Get it now!</a></strong>', $wcap_locale ) );

            $message_three = wp_kses_post ( __( 'You can now view all your deliveries in list view or in calendar view from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Get it now!</a></strong>.', $wcap_locale ) );

            $message_four = wp_kses_post ( __( 'Allow your customers to pay extra for delivery for certain Weekdays/Dates from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Have it now!</a></strong>.', $wcap_locale ) );

            $message_five = wp_kses_post ( __( 'Customers can now edit the Delivery date & time on cart and checkout page or they can reschedule the deliveries for the already placed orders from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=WpContentCopyPlugin">Have it now!</a></strong>.', $wcap_locale ) );

		// message six
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=sixth&utm_campaign=WpContentCopyPlugin';
            $message_six = wp_kses_post ( __( 'Boost your sales by recovering up to 60% of the abandoned carts with our Abandoned Cart Pro for WooCommerce plugin. You can capture customer email addresses right when they click the Add To Cart button. <strong><a target="_blank" href= "'.$_link.'">Grab your copy of Abandon Cart Pro plugin now</a></strong>.', $wcap_locale ) );
            
            $wpccpl_message_six = array ( 'message' => $message_six, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );
		// message seven
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=seventh&utm_campaign=WpContentCopyPlugin';
            $message_seven = wp_kses_post ( __( 'Don\'t loose your sales to abandoned carts. Use our Abandon Cart Pro plugin & start recovering your lost sales in less then 60 seconds.<br> 
            <strong><a target="_blank" href= "'.$_link.'">Get it now!</a></strong>', $wcap_locale ) );
            $wpccpl_message_seven = array ( 'message' => $message_seven, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );
        
        // message eight
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=eight&utm_campaign=WpContentCopyPlugin';
            $message_eight = wp_kses_post ( __( 'Send Abandoned Cart reminders that actually convert. Take advantage of our fully responsive email templates designed specially with an intent to trigger conversion. <br><strong><a target="_blank" href= "'.$_link.'">Grab your copy now!</a></strong>', $wcap_locale ) );
            $wpccpl_message_eight = array ( 'message' => $message_eight, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );

		// message nine
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpnotice&utm_medium=ninth&utm_campaign=WpContentCopyPlugin';
            $message_nine = wp_kses_post ( __( 'Increase your store sales by recovering your abandoned carts for just $119. No profit sharing, no monthly fees. Our Abandoned Cart Pro plugin comes with a 30 day money back guarantee as well. :). Use coupon code ACPRO20 & save $24!<br>
            <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong>', $wcap_locale ) );
            $wpccpl_message_nine = array ( 'message' => $message_nine, 'plugin_link' => 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );
            
		// message ten  
	        $_link = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wpnotice&utm_medium=tenth&utm_campaign=WpContentCopyPlugin';
            $message_ten = wp_kses_post ( __( 'Allow your customers to select the Delivery Date & Time on the Checkout Page using our Order Delivery Date Pro for WooCommerce Plugin. <br> 
            <strong><a target="_blank" href= "'.$_link.'">Shop now</a></strong> & be one of the 20 customers to get 20% discount on the plugin price. Use the code "ORDPRO20". Hurry!!', $wcap_locale ) );
            $wpccpl_message_ten = array ( 'message' => $message_ten, 'plugin_link' => 'order-delivery-date/order_delivery_date.php' );

		// message eleven
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/?utm_source=wpnotice&utm_medium=eleven&utm_campaign=WpContentCopyPlugin';
            $message_eleven = wp_kses_post ( __( ' Allow your customers to book an appointment or rent an apartment with our Booking and Appointment for WooCommerce plugin. You can also sell your product as a resource or integrate with a few Vendor plugins. <br>Shop now & Save 20% on the plugin with the code "BKAP20". Only for first 20 customers. <strong><a target="_blank" href= "'.$_link.'">Have it now!</a></strong>', $wcap_locale ) );
            $wpccpl_message_eleven = array ( 'message' => $message_eleven, 'plugin_link' => 'woocommerce-booking/woocommerce-booking.php' );

		// message 12
            $_link = 'https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/?utm_source=wpnotice&utm_medium=twelve&utm_campaign=WpContentCopyPlugin';
            $message_twelve = wp_kses_post ( __( ' Allow your customers to pay deposits on products using our Deposits for WooCommerce plugin.<br>
            <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong> & Grab 20% discount with the code "DFWP20". The discount code is valid only for the first 20 customers.', $wcap_locale ) );
            $wpccpl_message_twelve = array ( 'message' => $message_twelve, 'plugin_link' => 'woocommerce-deposits/deposits-for-woocommerce.php' );

		// message 13 
	        $_link = 'https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpnotice&utm_medium=thirteen&utm_campaign=WpContentCopyPlugin';
            $message_thirteen = wp_kses_post ( __( 'Allow your customers to select the Delivery Date & Time for your WooCommerce products using our Product Delivery Date Pro for WooCommerce Plugin. <br> 
            <strong><a target="_blank" href= "'.$_link.'">Shop now</a></strong>', $wcap_locale ) );
            $wpccpl_message_thirteen = array ( 'message' => $message_thirteen, 'plugin_link' => 'product-delivery-date/product-delivery-date.php' );

            $ts_pro_notices = array (
                1 => $message_first,
                2 => $message_two,
                3 => $message_three,
                4 => $message_four,
                5 => $message_five,
                6 => $wpccpl_message_six,
                7 => $wpccpl_message_seven,
                8 => $wpccpl_message_eight,
                9 => $wpccpl_message_nine,
                10 => $wpccpl_message_ten,
                11 => $wpccpl_message_eleven,
                12 => $wpccpl_message_twelve,
                13 => $wpccpl_message_thirteen
            );

            return $ts_pro_notices;
        }
		
		/**
         * It will contain all the FAQ which need to be display on the FAQ page.
         * @return array $ts_faq All questions and answers.
         * 
         */
        public static function wcap_get_faq () {

            $ts_faq = array ();

            $ts_faq = array(
                1 => array (
                        'question' => 'When would a customer’s cart be considered as abandoned?',
                        'answer'   => 'For all users, the cart is considered as abandoned after the set cut-off time has passed. The default value for this is X minutes. 
                        <br><br>
                        For a logged-in user, the cart is tracked right after the product is added to his cart. It is considered abandoned as soon as the cut-off time has passed since adding the product to cart. Whereas, for a guest user, if Add to cart popup modal is enabled then the cart will be abandoned once the email address is entered in popup modal. If Add to cart modal is disabled then the cart can be abandoned only after the guest user reaches the checkout page and mentions his first name, last name, phone number, and email address.'
                    ), 
                2 => array (
                        'question' => 'How can I offer discount codes to customers in the abandon cart reminder emails?',
                        'answer'   => 'When you add or edit an email template, it has a <strong> "Enter coupon code to add into email"</strong>  Here you need to enter a coupon code that you have created from WooCommerce > Coupons page. You would also need to add the <strong> {{coupon.code}}</strong> merge tag in the Body section of the template. This will send the same coupon code to all customers to whom that abandon cart email is sent to.
                        <br/><br/>
                            If you want to send a unique coupon code to each customer, then you need to enable the <strong>"Generate unique coupon codes"</strong> setting. In addition, you still need to enter a parent coupon code in the "Enter a coupon code to add into email" setting. The unique coupon codes will be generated based on the settings added for the parent coupon code.
                        <br/><br/>
                            You can learn more about the coupon code settings <a href="https://www.tychesoftwares.com/docs/docs/abandoned-cart-pro-for-woocommerce/understanding-coupon-codes/?utm_source=userwebsite&utm_medium=link&utm_campaign=AbandonedCartProFAQTab" target="_blank"> here</a>.'
                    ),
                3 => array (
						'question' => 'What would happen when an order is claimed by the user?',
						'answer'   => 'When an order is claimed, it is removed from the Abandoned Orders list and moved to Recovered Order tab under WooCommerce > Abandoned Carts. No further emails will be sent to any customer who has already claimed their abandoned cart.'
                ),
                4 => array (
						'question' => 'Can I know which customers have received the email or which email have been sent?',
						'answer'   => 'Yes. You can view which email templates are sent to which customers from the<strong> Sent Emails</strong> tab. You can also view which customers have opened the email.
						<br/><br/>
						Alternatively, for email sent to customers, you can also send a copy of it to the site admin or to an external email address too. This can be done from the add/edit email template page in the "Send the Abandoned cart emails to:" field.'
                ),
                5 => array (
						'question' => 'How does the Abandon cart plugin send out the cart recovery emails? Do I need a special setup?',
						'answer'   => 'Our plugin uses the<a href="https://codex.wordpress.org/Function_Reference/wp_cron" target="_blank"> WP-Cron</a>  which will send the abandoned cart reminder email to customers automatically. The email sending is done at every 15 minutes. If you want, you can change this interval from the "Setting for sending emails using WP Cron" under "Email Sending Settings" page in Settings.'
                ),
                6 => array (
						'question' => 'I have a few abandoned carts. But why is the abandoned cart email not sent to those customers?',
						'answer'   => 'There are several reasons abandoned cart reminder emails not being sent from your website.
						<br/><br/>
						It could be that WP-Cron is disabled on your site, or your server has some restrictions which does not allow WP-Cron, or the emails are actually sent, but the email server has failed to deliver them to the recipient. If you see the emails under the "Sent Emails" section but if they are still not being received, then it’s likely a problem with your email server. You can contact your web host about this.
						<br/> <br/>
						You can refer to <a href="https://www.tychesoftwares.com/docs/docs/abandoned-cart-pro-for-woocommerce/wp_alternate_cron/?utm_source=userwebsite&utm_medium=link&utm_campaign=AbandonedCartProFAQTab" target="_blank">this post</a> for fixing the WP-Cron issue.'
                ),
                7 => array (
						'question' => 'The test emails have wrong or dummy data. Is it supposed to work this way?',
						'answer'   => 'Yes, that is dummy data. The merge tags in the email template are replaced with the data of the store, like admin email address, two simple products of the store and other information are fetched and replaced with the merge tags. When the actual abandon cart recovery emails are sent out, these merge tags will be replaced with the customer’s cart data.'
                ),
                8 => array (
						'question' => 'How does the Add To Cart Popup Modal work?',
						'answer'   => 'Once the customer clicks on the add to cart button, our plugin opens the popup modal for capturing the customer’s email address. If the email address capture is set to be mandatory in admin, then the popup modal will close if the email address is not provided by the customer & the item will not be added to cart. Once the email address is provided by the customer, only then the product will be added to the cart. The email capture can be set as non-mandatory, which means that the popup to ask for email address will be displayed, but the customer can simply click on the "No thanks" button & proceed to adding the item to the cart.
						<br/><br/>
						You can learn more about the Add to cart popup modal<a href="https://www.tychesoftwares.com/capture-guest-user-email-address-before-checkout-page-with-woocommerce-abandoned-cart-pro/?utm_source=userwebsite&utm_medium=link&utm_campaign=AbandonedCartProFAQTab" target="_blank"> here</a>.'
                ),
                9 => array (
						'question' => 'Why does the Add to cart popup modal not show when I click on Add to Cart? I have all the settings done correctly.',
						'answer'   => 'If you are using custom buttons for Add to Cart, then the popup won\'t appear. The Add to Cart popup appears with the default Add to Cart buttons.
						<br/><br/>
						It is also possible that you are using the Add to Cart button on a custom page & not one of the default pages like WooCommerce’s shop page, product page, category page and on the home page.
						<br/><br/>
						You can contact our support team to get this resolved as each custom button needs a different solution.'
                ),
                10 => array (
						'question' => 'For multi-lingual websites, can your plugin send email templates to customers in the language in which the cart was abandoned when browsing the website?',
						'answer'   => 'Yes, our plugin is compatible with WPML. You can send the abandoned cart reminder emails in different languages using WPML. Our plugin will send the abandoned cart reminder emails in the respective language in which the customer has abandoned the cart. You can learn more about it <a href="https://www.tychesoftwares.com/docs/docs/abandoned-cart-pro-for-woocommerce/multilingual/?utm_source=userwebsite&utm_medium=link&utm_campaign=AbandonedCartProFAQTab" target="_blank">here</a>.'
                )    
            );

            return $ts_faq;
        }
	}
	$Wcap_All_Component = new Wcap_All_Component();
}
