<?php

/**
 * TS_Woo_Active Class
 *
 * @class TS_Woo_Active
 */

class TS_Woo_Active {

	public function __construct() {
		//Check for WooCommerce plugin
	    add_action( 'admin_init', array( &$this, 'ts_check_if_woocommerce_active' ) );

	    $this->plugin_name = "Order Delivery Date Pro for WooCommerce";
	    $this->plugin_file = "order-delivery-date/order_delivery_date.php";
	}

	/**
	* Checks if the WooCommerce plugin is active or not. If it is not active then it will display a notice.
	* 
	*/

	public function ts_check_if_woocommerce_active() {
		if ( ! $this->ts_check_woo_installed() ) {
		    if ( is_plugin_active(  $this->plugin_file ) ) {
		        deactivate_plugins(  $this->plugin_file );
		        add_action( 'admin_notices', array( &$this, 'ts_disabled_notice' ) );
		        if ( isset( $_GET[ 'activate' ] ) ) {
		            unset( $_GET[ 'activate' ] );
		        }
		    }
		}
	}

	/**
	 * Check if WooCommerce is active.
	 */
	public function ts_check_woo_installed() {
	    if ( class_exists( 'WooCommerce' ) ) {
	        return true;
	    } else {
	        return false;
	    }
	}

	/**
	* Display a notice in the admin plugins page if the plugin is activated while WooCommerce is deactivated.
	*/
	public function ts_disabled_notice() {
		$class = 'notice notice-error';
		$message = __( $this->plugin_name . ' plugin requires WooCommerce installed and activate.', 'ts_woo_active' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
}

$TS_Woo_Active = new TS_Woo_Active();