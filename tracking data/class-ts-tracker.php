<?php
/**
 * The tracker class adds functionality to track usage of the plugin based on if the customer opted in.
 * No personal information is tracked, only general settings, order and user counts and admin email for 
 * discount code.
 *
 * @class 		TS_Tracker
 * @version		6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TS_Tracker {

	/**
	 * URL to the  Tracker API endpoint.
	 * @var string
	 */

	private static $api_url = 'http://tracking.tychesoftwares.com/v1/';

	/**
	* @var string Plugin prefix
	* @access public 
	*/

	public static $plugin_prefix = 'orddd';

	/**
	* @var string Plugin name
	* @access public 
	*/

	public static $plugin_name = 'Order Delivery Date Pro for WooCommerce';

	/**
	 * Hook into cron event.
	 */
	public function __construct() {
		add_action( 'ts_tracker_send_event',   array( __CLASS__, 'ts_send_tracking_data' ) );
		add_filter( 'ts_tracker_data',         array( __CLASS__, 'ts_add_plugin_tracking_data' ), 10, 1 );
		add_filter( 'ts_tracker_opt_out_data', array( __CLASS__, 'ts_get_data_for_opt_out' ), 10, 1 );
	}

	/**
	 * Decide whether to send tracking data or not.
	 *
	 * @param boolean $override
	 */
	public static function ts_send_tracking_data( $override = false ) {
		if ( ! apply_filters( 'ts_tracker_send_override', $override ) ) {
			// Send a maximum of once per week by default.
			$last_send = self::ts_get_last_send_time();
			if ( $last_send && $last_send > apply_filters( 'ts_tracker_last_send_interval', strtotime( '-1 week' ) ) ) {
				return;
			}
		} else {
			// Make sure there is at least a 1 hour delay between override sends, we don't want duplicate calls due to double clicking links.
			$last_send = self::ts_get_last_send_time();
			if ( $last_send && $last_send > strtotime( '-1 hours' ) ) {
				return;
			}
		}
        
		$allow_tracking =  get_option( self::$plugin_prefix . '_allow_tracking' );
		if ( 'yes' == $allow_tracking ) {
		    $override = true;
		}
		
		// Update time first before sending to ensure it is set
		update_option( 'ts_tracker_last_send', time() );

		if( $override == false ) {
			$params   = array();
			$params[ 'tracking_usage' ] = 'no';
			$params[ 'url' ]            = home_url();
			$params[ 'email' ]          = apply_filters( 'ts_tracker_admin_email', get_option( 'admin_email' ) );
			
			$params 					= apply_filters( 'ts_tracker_opt_out_data', $params );
		} else {
			$params   = self::ts_get_tracking_data();
		}
		
		wp_safe_remote_post( self::$api_url, array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => false,
				'headers'     => array( 'user-agent' => 'TSTracker/' . md5( esc_url( home_url( '/' ) ) ) . ';' ),
				'body'        => json_encode( $params ),
				'cookies'     => array(),
			)
		);	
	}

	/**
	 * Get the last time tracking data was sent.
	 * @return int|bool
	 */
	private static function ts_get_last_send_time() {
		return apply_filters( 'ts_tracker_last_send_time', get_option( 'ts_tracker_last_send', false ) );
	}

	/**
	 * Get all the tracking data.
	 * @return array
	 */
	private static function ts_get_tracking_data() {
		$data                        = array();

		// General site info
		$data[ 'url' ]               = home_url();
		$data[ 'email' ]             = apply_filters( 'ts_tracker_admin_email', get_option( 'admin_email' ) );

		// WordPress Info
		$data[ 'wp' ]                = self::ts_get_wordpress_info();

		$data[ 'theme_info' ]        = self::ts_get_theme_info();

		// Server Info
		$data[ 'server' ]            = self::ts_get_server_info();

		// Plugin info
		$all_plugins                 = self::ts_get_all_plugins();
		$data[ 'active_plugins' ]    = $all_plugins[ 'active_plugins' ];
		$data[ 'inactive_plugins' ]  = $all_plugins[ 'inactive_plugins' ];

		//WooCommerce version 
		$data[ 'wc_plugin_version' ] = self::ts_get_wc_plugin_version();
				
		return apply_filters( 'ts_tracker_data', $data );
	}
    
    /** 
    * Send the data when the user has opted out
    * @param array @params
    * @return array
    */
	public static function ts_get_data_for_opt_out( $params ) {
	    $plugin_data[ 'ts_meta_data_table_name']   = 'ts_tracking_meta_data';
	    $plugin_data[ 'ts_plugin_name' ]		   = self::$plugin_name;
	    
	    // Store count info
	    $plugin_data[ 'deliveries_count' ]         = self::ts_get_order_counts();
	    $params[ 'plugin_data' ]  				   = $plugin_data;
	    
	    return $params;
	}
	
	/** 
    * Send the plugin data when the user has opted in
    * @param array @data
    * @return array
    */
    public static function ts_add_plugin_tracking_data( $data ) {
    	if ( isset( $_GET[ self::$plugin_prefix . '_tracker_optin' ] ) && isset( $_GET[ self::$plugin_prefix . '_tracker_nonce' ] ) && wp_verify_nonce( $_GET[ self::$plugin_prefix . '_tracker_nonce' ], self::$plugin_prefix . '_tracker_optin' ) ) {

	        $plugin_data  = array();
	        $plugin_data[ 'ts_meta_data_table_name' ] = 'ts_tracking_meta_data';
	        $plugin_data[ 'ts_plugin_name' ]		  = self::$plugin_name;
	        
	        // Store count info
	        $plugin_data[ 'deliveries_count' ]        = self::ts_get_order_counts();
	        
	        // Get all plugin options info
	        $plugin_data[ 'deliveries_settings' ]     = self::ts_get_all_plugin_options_values();
	        $plugin_data[ 'orddd_plugin_version' ]    = self::ts_get_plugin_version();
	        $plugin_data[ 'license_key_info' ]        = self::ts_get_plugin_license_key();
	        $plugin_data[ self::$plugin_prefix . '_allow_tracking' ]    = get_option ( self::$plugin_prefix . '_allow_tracking' );
	        $data[ 'plugin_data' ]                    = $plugin_data;
	    }
        return $data;
    }

	/**
	 * Get WordPress related data.
	 * @return array
	 */
	private static function ts_get_wordpress_info() {
		$wp_data = array();

		$memory = wc_let_to_num( WP_MEMORY_LIMIT );

		if ( function_exists( 'memory_get_usage' ) ) {
			$system_memory = wc_let_to_num( @ini_get( 'memory_limit' ) );
			$memory        = max( $memory, $system_memory );
		}

		$wp_data[ 'memory_limit' ] = size_format( $memory );
		$wp_data[ 'debug_mode' ]   = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
		$wp_data[ 'locale' ]       = get_locale();
		$wp_data[ 'wp_version' ]      = get_bloginfo( 'version' );
		$wp_data[ 'multisite' ]    = is_multisite() ? 'Yes' : 'No';

		return $wp_data;
	}

	/**
	 * Get the current theme info, theme name and version.
	 * @return array
	 */
	public static function ts_get_theme_info() {
		$theme_data        = wp_get_theme();
		$theme_child_theme = is_child_theme() ? 'Yes' : 'No';

		return array( 'theme_name' => $theme_data->Name, 
					'theme_version' => $theme_data->Version, 
					'child_theme' => $theme_child_theme );
	}

	/**
	 * Get server related info.
	 * @return array
	 */
	private static function ts_get_server_info() {
		$server_data = array();

		if ( isset( $_SERVER[ 'SERVER_SOFTWARE' ] ) && ! empty( $_SERVER[ 'SERVER_SOFTWARE' ] ) ) {
			$server_data[ 'software' ] = $_SERVER[ 'SERVER_SOFTWARE' ];
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data[ 'php_version' ] = phpversion();
		}

		if ( function_exists( 'ini_get' ) ) {
			$server_data[ 'php_post_max_size' ] = size_format( wc_let_to_num( ini_get( 'post_max_size' ) ) );
			$server_data[ 'php_time_limt' ] = ini_get( 'max_execution_time' );
			$server_data[ 'php_max_input_vars' ] = ini_get( 'max_input_vars' );
			$server_data[ 'php_suhosin' ] = extension_loaded( 'suhosin' ) ? 'Yes' : 'No';
		}

		global $wpdb;
		$server_data[ 'mysql_version' ] = $wpdb->db_version();

		$server_data[ 'php_max_upload_size' ] = size_format( wp_max_upload_size() );
		$server_data[ 'php_default_timezone' ] = date_default_timezone_get();
		$server_data[ 'php_soap' ] = class_exists( 'SoapClient' ) ? 'Yes' : 'No';
		$server_data[ 'php_fsockopen' ] = function_exists( 'fsockopen' ) ? 'Yes' : 'No';
		$server_data[ 'php_curl' ] = function_exists( 'curl_init' ) ? 'Yes' : 'No';

		return $server_data;
	}

	/**
	 * Get all plugins grouped into activated or not.
	 * @return array
	 */
	private static function ts_get_all_plugins() {
		// Ensure get_plugins function is loaded
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins        	 = get_plugins();
		$active_plugins_keys = get_option( 'active_plugins', array() );
		$active_plugins 	 = array();

		foreach ( $plugins as $k => $v ) {
			// Take care of formatting the data how we want it.
			$formatted = array();
			$formatted[ 'name' ] = strip_tags( $v[ 'Name' ] );
			if ( isset( $v[ 'Version' ] ) ) {
				$formatted[ 'version' ] = strip_tags( $v[ 'Version' ] );
			}
			if ( isset( $v[ 'Author' ] ) ) {
				$formatted[ 'author' ] = strip_tags( $v[ 'Author' ] );
			}
			if ( isset( $v[ 'Network' ] ) ) {
				$formatted[ 'network' ] = strip_tags( $v[ 'Network' ] );
			}
			if ( isset( $v[ 'PluginURI' ] ) ) {
				$formatted[ 'plugin_uri' ] = strip_tags( $v[ 'PluginURI' ] );
			}
			if ( in_array( $k, $active_plugins_keys ) ) {
				// Remove active plugins from list so we can show active and inactive separately
				unset( $plugins[ $k ] );
				$active_plugins[ $k ] = $formatted;
			} else {
				$plugins[ $k ] = $formatted;
			}
		}

		return array( 'active_plugins' => $active_plugins, 'inactive_plugins' => $plugins );
	}

	/**
	 * Get order counts based on order status.
	 * @return array
	 */
	private static function ts_get_order_counts() {
		global $wpdb;
		$order_count = 0;
		$orddd_query = "SELECT count(ID) AS delivery_orders_count FROM `" . $wpdb->prefix . "posts` WHERE post_type = 'shop_order' AND post_status NOT IN ('wc-cancelled', 'wc-refunded', 'trash', 'wc-failed' ) AND ID IN ( SELECT post_id FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key IN ( %s, %s ) )";

		$results = $wpdb->get_results( $wpdb->prepare( $orddd_query, '_orddd_timestamp', get_option( 'orddd_delivery_date_field_label' ) ) );
		if( isset( $results[0] ) ) {
			$order_count = $results[0]->delivery_orders_count;	
		}
		
		return $order_count;
	}

	/**
	 * Get all options starting with woocommerce_ prefix.
	 * @return array
	 */
	private static function ts_get_all_plugin_options_values() {
		global $wpdb;
		$orddd_custom_count = 0;
		$shipping_based_settings_query = "SELECT COUNT(option_id) AS custom_settings_count FROM `" . $wpdb->prefix . "options` WHERE option_name LIKE 'orddd_shipping_based_settings_%' AND option_name != 'orddd_shipping_based_settings_option_key'";
		$results = $wpdb->get_results( $shipping_based_settings_query );
		
		if( isset( $results[0] ) ) {
			$orddd_custom_count = $results[0]->custom_settings_count;
		}

		return array(
			'enable_delivery'                       => get_option( 'orddd_enable_delivery_date' ),
			'delivery_options'                      => get_option( 'orddd_delivery_checkout_options' ),
			'weekday_wise_settings'                 => get_option( 'orddd_enable_day_wise_settings' ),
			'date_mandatory'                        => get_option( 'orddd_date_field_mandatory' ),
			'shipping_days'                         => get_option( 'orddd_enable_shipping_days' ),
			'specific_delivery_dates'               => get_option( 'orddd_enable_specific_delivery_dates' ),
			'delivery_time'                         => get_option( 'orddd_enable_delivery_time' ),
			'same_day_delivery'                     => get_option( 'orddd_enable_same_day_delivery' ),
			'next_day_delivery'                     => get_option( 'orddd_enable_next_day_delivery' ),
			'time_slot'                             => get_option( 'orddd_enable_time_slot' ),
			'time_slot_mandatory'                   => get_option( 'orddd_time_slot_mandatory' ),
			'populate_first_time_slot'              => get_option( 'orddd_auto_populate_first_available_time_slot' ),
			'populate_first_delivery_date'          => get_option( 'orddd_enable_autofill_of_delivery_date' ),
			'no_fields_for'                         => array( 'virtual_product'  => get_option( 'orddd_no_fields_for_virtual_product' ),         'featured_product' => get_option( 'orddd_no_fields_for_featured_product' ) ),
			'edit_date_for_customers'               => get_option( 'orddd_allow_customers_to_edit_date' ),
			'shipping_multiple_address'             => get_option( 'orddd_shipping_multiple_address_compatibility' ),
			'amazon_payments_advanced_gateway'      => get_option( 'orddd_amazon_payments_advanced_gateway_compatibility' ),
			'woocommerce_subscriptions'             => get_option( 'orddd_woocommerce_subscriptions_compatibility' ),
			'woocommerce_subscriptions_auto_update' => get_option( 'orddd_woocommerce_subscriptions_auto_update' ),
			'custom_delivery'                       => get_option( 'orddd_enable_shipping_based_delivery' ),
			'custom_delivery_count'                 => $orddd_custom_count,
			'calendar_sync'                         => get_option( 'orddd_calendar_sync_integration_mode' ) 
		 ); 
	}

	/**
	* Sends current WooCommerce version
	* @return string
	*/
	private static function ts_get_wc_plugin_version() {
		return WC()->version;
	}

	/**
	* Sends the license data of the plugin
	* @return array
	*/
	private static function ts_get_plugin_license_key() {
		return array( 'license_key' => get_option( 'edd_sample_license_key_odd_woo' ), 'active_status' => get_option( 'edd_sample_license_status_odd_woo' ) );
	}

	/**
	* Sends the current plugin version
	* @return string
	*/
	private static function ts_get_plugin_version() {
		global $orddd_version;
		return $orddd_version;
	}
}

$TS_Tracker = new TS_Tracker();