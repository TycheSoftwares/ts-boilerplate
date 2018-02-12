<?php 

/**
 * Welcome Page Class
 *
 * Displays on plugin activation or updation
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TS_Welcome Class
 *
 * A general class for About page.
 *
 * @since 7.7
 */

class TS_Welcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';
	
	/**
	 * @var string The name of the plugin
	 * @access public
	 */
	public $plugin_name = "Order Delivery Date Pro for WooCommerce";

	/**
	 * @var string Unique prefix of the plugin
	 * @access public 
	 */

	public $plugin_prefix = 'orddd';

	/**
	 * @var Plugin Context
	 * @access public
	 */

	public $plugin_context = 'order-delivery-date';

	/**
	 * @var string Folder of the plugin
	 * @access public
	 */
	public $plugin_folder = 'order-delivery-date/';

	/**
	 * Get things started
	 *
	 * @since 7.7
	 */
	public function __construct() {
		//Update plugin
		add_action( 'admin_init', array( &$this, 'ts_update_db_check' ) );

		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		if ( !isset( $_GET[ 'page' ] ) || 
		( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] != $this->plugin_prefix . '-pro-about' ) ) {
			add_action( 'admin_init', array( $this, $this->plugin_prefix . '_pro_welcome' ) );
		}

		$this->plugin_version = $this->ts_get_version();
		$this->previous_plugin_version = get_option( 'orddd_db_version' );
		$this->plugin_url     = $this->ts_get_plugin_url();
		$this->template_base  = $this->ts_get_template_path();
	}

	/**
     * This function returns the plugin version number.
     *
     * @access public 
     * @since 7.7
     * @return $plugin_version
     */
    public function ts_get_version() {
        $plugin_version = '';
        $plugin_dir =  dirname ( dirname (__FILE__) );
        $plugin_dir .= '/order-delivery-date/order_delivery_date.php';
        
        $plugin_data = get_file_data( $plugin_dir, array( 'Version' => 'Version' ) );
        if ( ! empty( $plugin_data['Version'] ) ) {
            $plugin_version = $plugin_data[ 'Version' ];
        }
        return $plugin_version;
    }

    /**
     * This function returns the plugin url 
     *
     * @access public 
     * @since 7.7
     * @return string
     */
    public function ts_get_plugin_url() {
        return plugins_url() . '/order-delivery-date/';
    }

    /**
    * This function returns the template directory path
    *
    * @access public 
    * @since 7.7
    * @return string
    */
    public function ts_get_template_path() {
    	return untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
    } 

	/**
	 * Register the Dashboard Page which is later hidden but this pages
	 * is used to render the Welcome page.
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function admin_menus() {
		$display_version = $this->plugin_version;

		// About Page
		add_dashboard_page(
			sprintf( esc_html__( 'Welcome to %s %s', $this->plugin_context ), $this->plugin_name, $display_version ),
			esc_html__( 'Welcome to ' . $this->plugin_name, $this->plugin_context ),
			$this->minimum_capability,
			$this->plugin_prefix . '-pro-about',
			array( $this, 'about_screen' )
		);

	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', $this->plugin_prefix . '-pro-about' );
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function about_screen() {
		$display_version = $this->plugin_version;
		// Badge for welcome page
		$badge_url = $this->plugin_url . 'images/icon-256x256.png';		
		
		ob_start();
        wc_get_template( 'welcome/welcome-page.php', array(
        	'plugin_name'     => $this->plugin_name,
        	'plugin_url'      => $this->plugin_url, 
            'display_version' => $display_version,
            'badge_url'       => $badge_url,
            'get_welcome_header' => $this->get_welcome_header()
        ), $this->plugin_folder, $this->template_base );
        echo ob_get_clean();

		update_option( $this->plugin_prefix . '_pro_welcome_page_shown', 'yes' );
		update_option( $this->plugin_prefix . '_pro_welcome_page_shown_time', current_time( 'timestamp' ) );
	}


	/**
	 * The header section for the welcome screen.
	 *
	 * @since 7.7
	 */
	public function get_welcome_header() {
		// Badge for welcome page
		$badge_url = $this->plugin_url . 'images/icon-256x256.png';
		?>
        <h1 class="welcome-h1"><?php echo get_admin_page_title(); ?></h1>
		<?php $this->social_media_elements();
	}

	/**
	 * Social Media Like Buttons
	 *
	 * Various social media elements to Tyche Softwares
	 */
	public function social_media_elements() { 
		ob_start();
        wc_get_template( 'welcome/social-media-elements.php', array(), $this->plugin_folder, $this->template_base );
        echo ob_get_clean();
	}


	/**
	 * Sends user to the Welcome page on first activation of the plugin as well as each
	 * time the plugin is updated is upgraded to a new version
	 *
	 * @access public
	 * @since  7.7
	 *
	 * @return void
	 */
	public function orddd_pro_welcome() {

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET[ 'activate-multi' ] ) ) {
			return;
		}

		if( !get_option( $this->plugin_prefix . '_pro_welcome_page_shown' ) ) {
			wp_safe_redirect( admin_url( 'index.php?page=' . $this->plugin_prefix . '-pro-about' ) );
			exit;
		}
	}

	


	/**
	 *  Executed when the plugin is updated using the Automatic Updater. 
	 */
	public function ts_update_db_check() {
		if ( $this->plugin_version != $this->previous_plugin_version ) {
			delete_option( $plugin_prefix . '_pro_welcome_page_shown' );
			delete_option( $plugin_prefix . '_pro_welcome_page_shown_time' );
		}
	}
}

new TS_Welcome();