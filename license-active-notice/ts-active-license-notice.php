<?php
/**
 * Active License Notice class
 *
 * @class active_license_notice
 * @version	1.0
 */

class active_license_notice {
	
	/**
	 * @var string The name of the plugin
	 * @access public
	 */
	public $plugin_name = "Order Delivery Date Pro for WooCommerce";

	/**
	 * @var string The option name where the license key is stored
	 * @access public
	 */
	public $plugin_license_option = "edd_sample_license_status_odd_woo";

	/* Default Constructor
	 * 
	 * @access public
	 * @since  7.7
	 */
	public function __construct() {
		add_action( 'admin_init', array( &$this, 'ts_check_if_license_active' ) );

		
	}

	/* Check if the license key is active for the plugin. If not active a notice will be displayed
 	* 
 	* @access public
 	* @since 7.7
 	*/
	public function ts_check_if_license_active() {
	 	if ( ! $this->ts_check_active_license() ) {
            add_action( 'admin_notices', array( &$this, 'ts_license_active_notice' ) );
	    }
	}

	/* Returns the result of the license key
 	* 
 	* @access public
 	* @return bool
 	* @since  7.7
 	*/
	public function ts_check_active_license() {
		$status = get_option( $this->plugin_license_option );
	    if( false !== $status && 'valid' == $status ) {
	        return true;
	    } else {
	        return false;
	    }
	}
	
	/* Display the notice if the license key is not active
 	* 
 	* @access public
 	* @since 7.7
 	*/

	public function ts_license_active_notice() {
		$class = 'notice notice-error';
	    $message = __( 'We have noticed that the license for <b>' . $this->plugin_name . '</b> plugin is not active. To receive automatic updates & support, please activate the license <a><href="http://localhost/wordpress-fridays/wp-admin/admin.php?page=edd_sample_license_page">here</a>.', 'order-delivery-date' );
	    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}
}

$active_license_notice = new active_license_notice();