<?php

class ts_pro_notices {

	/**
	* Plugin's Name
	* 
	* @access public
	* @since 3.5
	*/
	public static $plugin_name = "";

	/**
	* Plugin's unique prefix
	*
	* @access public
	* @since 3.5
	*/

	public static $plugin_prefix = '';

	/**
	* Pro plugin's unique prefix
	*
	* @access public
	* @since 3.5
	*/

	public static $pro_plugin_prefix = 'orddd_pro';

	/** 
	* Default Constructor
	* 
	* @since 3.5
	*/

	public function __construct( $ts_plugin_name = '', $ts_plugin_prefix = '', $ts_pro_plugin_prefix = '' ) {
		self::$plugin_name   	 = $ts_plugin_name;
		self::$plugin_prefix 	 = $ts_plugin_prefix;
		self::$pro_plugin_prefix = $ts_pro_plugin_prefix;

		//Initialize settings
		register_activation_hook( __FILE__,  array( &$this, 'ts_notices_activate' ) );

		//Add pro notices
        add_action( 'admin_notices', array( 'ts_pro_notices', 'ts_notices_of_pro' ) );
        add_action( 'admin_init', array( 'ts_pro_notices', 'ts_ignore_pro_notices' ) );
	}

	/**
	* Add an option which stores the timestamp when the plugin is first activated
	*
	* @since 3.5
	*/
	public static function ts_notices_activate() {
		//Pro admin Notices
        if( !get_option( self::$plugin_prefix . 'activate_time' ) ) {
            add_option( self::$plugin_prefix . '_activate_time', current_time( 'timestamp' ) );
        }
	}

	public static function ts_notices_of_pro() {
		$activate_time = get_option ( self::$plugin_prefix . '_activate_time' );
        $sixty_days    = strtotime( '+60 Days', $activate_time );
		$current_time  = current_time( 'timestamp' );
		$add_query_arguments = '';
		$message             = '';
        if( !is_plugin_active( 'order-delivery-date/order_delivery_date.php' ) && 
            ( false === $activate_time || ( $activate_time > 0 && $current_time >= $sixty_days ) ) ) {
        	global $current_user ;
			$user_id = $current_user->ID;
			
			if( ! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) ) {
				$_link = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=OrderDeliveryDateLitePlugin';
			    $message = wp_kses_post ( __( 'Thank you for using Order Delivery Date for WooCommerce! Never login to your admin to check your deliveries by syncing the delivery dates to the Google Calendar from Order Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "' . $_link . '">Get it now!</a></strong>', 'order-delivery-date' ) );

				$add_query_arguments = add_query_arg( self::$pro_plugin_prefix . '_first_notice_ignore', '0' );
				
				$class = 'updated notice-info point-notice one';
				$style = 'position:relative';
				$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
				printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
			}

			if ( get_user_meta( get_current_user_id(),  self::$pro_plugin_prefix . '_first_notice_ignore' ) && 
				! get_user_meta( get_current_user_id(),  self::$pro_plugin_prefix . '_second_notice_ignore' ) &&
				! is_plugin_active( 'post-purchase-experience/post-purchase-experience.php' ) ) {

				$first_ignore_time = get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore_time' );
				$fifteen_days = strtotime( '+15 Days', $first_ignore_time[0] );

				if ( $current_time > $fifteen_days ) {
                    $post_purchase_link = 'https://www.tychesoftwares.com/store/premium-plugins/post-delivery-product-reviews-addon-order-delivery-date-woocommerce/checkout?edd_action=add_to_cart&download_id=278278&utm_source=wpnotice&utm_medium=second&utm_campaign=OrderDeliveryDateLitePlugin';

                    $message = wp_kses_post ( __( 'Send Product review emails to the customers on the next day of delivery using Post Delivery Product Reviews Addon for Order Delivery Date plugin. <strong><a target="_blank" href= "' . $post_purchase_link . '">Have it now!</a></strong>', 'order-delivery-date' ) );

					$add_query_arguments = add_query_arg( self::$pro_plugin_prefix . '_second_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice two';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}

				
			} 

			if ( get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) &&  
				! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore' ) &&
				is_plugin_active( 'post-purchase-experience/post-purchase-experience.php' ) &&
				! is_plugin_active( 'woocommerce-abandon-cart-pro/woocommerce-ac.php' ) && 
				! is_plugin_active( 'woocommerce-abandoned-cart/woocommerce-ac.php' ) ) {
				
				$first_ignore_time = get_user_meta( get_current_user_id(),  self::$pro_plugin_prefix . '_first_notice_ignore_time' );
				$ts_fifteen_days = strtotime( '+15 Days', $first_ignore_time[0] );

				if ( $current_time > $ts_fifteen_days ) {
                    $orddd_wcal_lite_link = admin_url( '/plugin-install.php?s=abandoned+cart+tyche+softwares&tab=search&type=term' );

                    $message = wp_kses_post ( __( 'Boost your sales by recovering the abandoned carts with our FREE Abandoned Cart for WooCommerce plugin. <strong><a target="_blank" href= "'.$orddd_wcal_lite_link.'">Install it now.</a></strong>', 'order-delivery-date' ) );

					$add_query_arguments = add_query_arg( self::$pro_plugin_prefix . '_third_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}

			} 

			if ( get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) &&  
				! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fourth_notice_ignore' ) &&
				is_plugin_active( 'post-purchase-experience/post-purchase-experience.php' ) &&
				( is_plugin_active( 'woocommerce-abandon-cart-pro/woocommerce-ac.php' ) || 
				is_plugin_active( 'woocommerce-abandoned-cart/woocommerce-ac.php' ) ) ) {
				
				$ts_first_ignore_time = get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore_time' );
				$ts_fifteen_days = strtotime( '+15 Days', $ts_first_ignore_time[0] );

				if ( $current_time > $ts_fifteen_days ){
                    $_link = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=fourth&utm_campaign=OrderDeliveryDateLitePlugin';

                    $message = wp_kses_post ( __( 'Create Delivery Settings by Shipping Zones & Shipping Classes using Order Delivery Date Pro for WooCommerce plugin. <br>Use discount code "ORDPRO20" and grab 20% discount on the purchase of the plugin. The discount code is valid only for the first 20 customers. <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong>.', 'order-delivery-date' ) );

					$add_query_arguments = add_query_arg( self::$pro_plugin_prefix . '_fourth_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}
			}

			// Ac Lite //
			if ( get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) &&
				 get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore' ) &&
				! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore' ) &&
				! is_plugin_active( 'woocommerce-abandon-cart-pro/woocommerce-ac.php' ) && 
				! is_plugin_active( 'woocommerce-abandoned-cart/woocommerce-ac.php' ) ) {

				$ts_second_ignore_time = get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore_time' );
				$ts_seven_days = strtotime( '+7 Days', $ts_second_ignore_time[0] );				

				if ( $current_time > $ts_seven_days ) {
                    $orddd_wcal_lite_link = admin_url( '/plugin-install.php?s=abandoned+cart+tyche+softwares&tab=search&type=term' );

                    $message = wp_kses_post ( __( 'Boost your sales by recovering the abandoned carts with our FREE Abandoned Cart for WooCommerce plugin. <strong><a target="_blank" href= "'.$orddd_wcal_lite_link.'">Install it now.</a></strong>.', 'order-delivery-date' ) );
					$add_query_arguments = add_query_arg(  self::$pro_plugin_prefix . '_third_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}
			}

            if ( get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) &&
                 get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore' ) &&
                 ! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fourth_notice_ignore' ) &&
                 ( is_plugin_active( 'woocommerce-abandon-cart-pro/woocommerce-ac.php' ) ||
                 is_plugin_active( 'woocommerce-abandoned-cart/woocommerce-ac.php' ) ) ) {

            	$ts_second_ignore_time = get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore_time' );
                $ts_fifteen_days = strtotime( '+15 Days', $ts_second_ignore_time[0] );

                if ( $current_time > $ts_fifteen_days ) {
                    $_link = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=fourth&utm_campaign=OrderDeliveryDateLitePlugin';

                    $message = wp_kses_post ( __( 'Create Delivery Settings by Shipping Zones & Shipping Classes using Order Delivery Date Pro for WooCommerce plugin. <br>Use discount code "ORDPRO20" and grab 20% discount on the purchase of the plugin. The discount code is valid only for the first 20 customers. <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong>.', 'order-delivery-date' ) );

					$add_query_arguments = add_query_arg(  self::$pro_plugin_prefix . '_fourth_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}
				

				
            }

            if ( get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) &&
                get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore' ) &&
                get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore' ) &&
                ! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fourth_notice_ignore' ) ) {
            	
            	$ts_third_ignore_time = get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore_time' );
            	$ts_seven_days = strtotime( '+7 Days', $ts_third_ignore_time[0] );
                
                if ( $current_time > $ts_seven_days ) {
                    $_link = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=fourth&utm_campaign=OrderDeliveryDateLitePlugin';

                    $message = wp_kses_post ( __( 'Create Delivery Settings by Shipping Zones & Shipping Classes using Order Delivery Date Pro for WooCommerce plugin. <br>Use discount code "ORDPRO20" and grab 20% discount on the purchase of the plugin. The discount code is valid only for the first 20 customers. <strong><a target="_blank" href= "'.$_link.'">Purchase now</a></strong>.', 'order-delivery-date' ) );

					$add_query_arguments = add_query_arg( self::$pro_plugin_prefix . '_fourth_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}
			}

            if ( get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore' ) &&
				 get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore' ) &&
				 get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore' ) &&
				 get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fourth_notice_ignore' ) &&
				 ! get_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fifth_notice_ignore' ) &&
				 ! is_plugin_active( 'post-purchase-experience/post-purchase-experience.php' ) ) {

				$ts_fourth_ignore_time = get_user_meta( get_current_user_id(), '_fourth_notice_ignore_time' );
				$ts_seven_days = strtotime( '+7 Days', $ts_fourth_ignore_time[0] );				

				if ( $current_time > $ts_seven_days ) {
                   	$_link = 'https://www.tychesoftwares.com/store/premium-plugins/post-delivery-product-reviews-addon-order-delivery-date-woocommerce/checkout?edd_action=add_to_cart&download_id=278278&utm_source=wpnotice&utm_medium=fifth&utm_campaign=OrderDeliveryDateLitePlugin';

                    $message = wp_kses_post ( __( 'Receive feedbacks for your products from verified owners by sending them post delivery emails using Post Delivery Product Reviews addon of Order Delivery Date plugin. <strong><a target="_blank" href= "'.$_link.'">Have it now!</a></strong>', 'order-delivery-date' ) );

					$add_query_arguments = add_query_arg( self::$pro_plugin_prefix . '_fifth_notice_ignore', '0' );
					
					$class = 'updated notice-info point-notice';
					$style = 'position:relative';
					$cancel_button = '<a href="'.$add_query_arguments.'" class="dashicons dashicons-dismiss dashicons-dismiss-icon" style="position: absolute; top: 8px; right: 8px; color: #222; opacity: 0.4; text-decoration: none !important;"></a>';
					printf( '<div class="%1$s" style="%2$s"><p>%3$s %4$s</p></div>', $class, $style, $message, $cancel_button );
				}
			} 
		}
	}

	/**
	 * Ignore pro notice
	 */
	public static function ts_ignore_pro_notices() {

		// If user clicks to ignore the notice, add that to their user meta
		if ( isset( $_GET[ self::$pro_plugin_prefix . '_first_notice_ignore' ] ) && '0' === $_GET[ self::$pro_plugin_prefix . '_first_notice_ignore' ] ) {
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore', 'true', true );
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_first_notice_ignore_time', current_time( 'timestamp' ), true );
			wp_safe_redirect( remove_query_arg( self::$pro_plugin_prefix . '_first_notice_ignore' ) );

		}

		if ( isset( $_GET[ self::$pro_plugin_prefix . '_second_notice_ignore'] ) && '0' === $_GET[ self::$pro_plugin_prefix . '_second_notice_ignore'] ) {
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore', 'true', true );
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_second_notice_ignore_time', current_time( 'timestamp' ), true );
			wp_safe_redirect( remove_query_arg( self::$pro_plugin_prefix . '_second_notice_ignore' )  );
		}

		if ( isset( $_GET[ self::$pro_plugin_prefix . '_third_notice_ignore'] ) && '0' === $_GET[ self::$pro_plugin_prefix . '_third_notice_ignore'] ) {
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore', 'true', true );
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_third_notice_ignore_time', current_time( 'timestamp' ), true );
			wp_safe_redirect( remove_query_arg( self::$pro_plugin_prefix . '_third_notice_ignore' ) );
		}

		if ( isset( $_GET[ self::$pro_plugin_prefix . '_fourth_notice_ignore' ] ) && '0' === $_GET[ self::$pro_plugin_prefix . '_fourth_notice_ignore' ] ) {
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fourth_notice_ignore', 'true', true );
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fourth_notice_ignore_time', current_time( 'timestamp' ), true );
			wp_safe_redirect( remove_query_arg( self::$pro_plugin_prefix . '_fourth_notice_ignore' ) );
		}

		if ( isset( $_GET[ self::$pro_plugin_prefix . '_fifth_notice_ignore' ] ) && '0' === $_GET[ self::$pro_plugin_prefix . '_fifth_notice_ignore' ] ) {
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fifth_notice_ignore', 'true', true );
			add_user_meta( get_current_user_id(), self::$pro_plugin_prefix . '_fifth_notice_ignore_time', current_time( 'timestamp' ), true );
			wp_safe_redirect( remove_query_arg( self::$pro_plugin_prefix . '_fifth_notice_ignore' ) );
		}
	}
}

