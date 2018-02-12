<?php
include_once( 'class-ts-tracker.php' );

/** Adds the Tracking non-senstive data notice
*
* @since 6.8
*/
class TS_tracking {

	/**
	* @var string Plugin prefix
	* @access public 
	*/

	public $plugin_prefix = 'orddd';

	/**
	* @var string Plugin's URL
	* @access public
	*/
	public $plugin_url = ORDDD_PRO_PLUGIN_URL ;

	/** 
	* @var string Plugin Name
	* @access public
	*/

	public $plugin_name = "Order Delivery Date Pro for WooCommerce";

	/**
	* @var string Tracking data blog post link
	* @access public
	*/

	public $blog_post_link = 'https://www.tychesoftwares.com/order-delivery-date-usage-tracking/';

	/**
	* @var string Plugin context
	* @access public
	*/

	public $plugin_context = 'order-delivery-date';

	/** Default Constructor 
	*
	* @since 6.8
	*/
	public function __construct() {
		//Tracking Data
		add_action( 'admin_notices', array( &$this, 'ts_track_usage_data' ) );
		add_action( 'admin_footer',  array( __CLASS__, 'ts_admin_notices_scripts' ) );
		add_action( 'wp_ajax_orddd_admin_notices', array( __CLASS__, 'ts_admin_notices' ) );
	}

	public static function ts_admin_notices_scripts() {
        wp_enqueue_script(
            'dismiss-notice.js',
            plugins_url('/js/dismiss-notice.js', __FILE__),
            '',
            '',
            false
        );
    }

    public static function ts_admin_notices() {
        update_option( $this->plugin_prefix . '_allow_tracking', 'dismissed' );
        TS_Tracker::ts_send_tracking_data( false );
        die();
    }

	/**
	 * Actions on the final step.
	 */
	private function ts_tracking_actions() {
		if ( isset( $_GET[ $this->plugin_prefix . '_tracker_optin' ] ) && isset( $_GET[ $this->plugin_prefix . '_tracker_nonce' ] ) && wp_verify_nonce( $_GET[ $this->plugin_prefix . '_tracker_nonce' ], $this->plugin_prefix . '_tracker_optin' ) ) {
			update_option( $this->plugin_prefix . '_allow_tracking', 'yes' );
			TS_Tracker::ts_send_tracking_data( true );
			header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
		} elseif ( isset( $_GET[ $this->plugin_prefix . '_tracker_optout' ] ) && isset( $_GET[ $this->plugin_prefix . '_tracker_nonce' ] ) && wp_verify_nonce( $_GET[ $this->plugin_prefix . '_tracker_nonce' ], $this->plugin_prefix . '_tracker_optout' ) ) {
			update_option( $this->plugin_prefix . '_allow_tracking', 'no' );
			TS_Tracker::ts_send_tracking_data( false );
			header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
		}
	}

	/**
	* Data Usage tracking notice
	*/
	function ts_track_usage_data() {
		$admin_url = get_admin_url();
		echo '<input type="hidden" id="admin_url" value="' . $admin_url . '"/>';
		$this->ts_tracking_actions();
		if ( 'unknown' === get_option( $this->plugin_prefix . '_allow_tracking', 'unknown' ) ) : ?>
			<div class="<?php echo $this->plugin_prefix; ?>-message <?php echo $this->plugin_prefix; ?>-tracker notice notice-info is-dismissible" style="position: relative;">
				<div style="position: absolute;"><img class="site-logo" src="<?php echo $this->plugin_url; ?>/images/site-logo-new.jpg"></div>
				<p style="margin: 10px 0 10px 130px; font-size: medium;">
					<?php print( __( 'Want to help make ' . $this->plugin_name . ' even more awesome? Allow ' . $this->plugin_name . ' to collect non-sensitive diagnostic data and usage information and get 20% off on your next purchase. <a href="' . $this->blog_post_link . '">Find out more</a>.', $this->plugin_context ) ); ?></p>
				<p class="submit">
					<a class="button-primary button button-large" href="<?php echo esc_url( wp_nonce_url( add_query_arg( $this->plugin_prefix . '_tracker_optin', 'true' ), $this->plugin_prefix . '_tracker_optin', $this->plugin_prefix . '_tracker_nonce' ) ); ?>"><?php esc_html_e( 'Allow', $this->plugin_context ); ?></a>
					<a class="button-secondary button button-large skip"  href="<?php echo esc_url( wp_nonce_url( add_query_arg( $this->plugin_prefix . '_tracker_optout', 'true' ), $this->plugin_prefix . '_tracker_optout', $this->plugin_prefix . '_tracker_nonce' ) ); ?>"><?php esc_html_e( 'No thanks', $this->plugin_context ); ?></a>
				</p>
			</div>
		<?php endif;
	}
}
$TS_tracking = new TS_tracking();