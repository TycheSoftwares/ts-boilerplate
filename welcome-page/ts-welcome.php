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
	* @var string Prefix of the plugin
	* @access public 
	*/

	public $plugin_prefix = 'orddd';

	/**
	* @var Plugin Context
	* @access public
	**/

	public $plugin_context = 'order-delivery-date';

	/**
	 * @var string The current version of the plugin
	 * @access public
	 */
	public $plugin_version = ORDDD_PRO_VERSION;

	/**
	 * @var string The url of the plugin
	 * @access public
	 */
	public $plugin_url = ORDDD_PRO_PLUGIN_URL;

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
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		if ( !isset( $_GET[ 'page' ] ) || 
		( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] != $this->plugin_prefix . '-pro-about' ) ) {
			add_action( 'admin_init', array( $this, $this->plugin_prefix . '_pro_welcome' ) );
		}

		$this->template_html    = 'welcome/welcome-page.php';
		$this->template_plain   = 'welcome/plain/welcome-page.php';
		$this->template_base    = ORDDD_TEMPLATE_PATH;
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
		
		//echo $this->template_base;exit;	
		ob_start();
        wc_get_template( $this->template_html, array(
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
	 * @since 3.3
	 */
	public function get_welcome_header() {
		// Badge for welcome page
		$badge_url = $this->plugin_url . 'images/icon-256x256.png';
		?>
        <h1 class="welcome-h1"><?php echo get_admin_page_title(); ?></h1>
		<?php $this->social_media_elements(); ?>

	<?php }

	/**
	 * Social Media Like Buttons
	 *
	 * Various social media elements to Tyche Softwares
	 */
	public function social_media_elements() { ?>

        <div class="social-items-wrap">

            <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Ftychesoftwares&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=220596284639969"
                    scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;"
                    allowTransparency="true"></iframe>

            <a href="https://twitter.com/tychesoftwares" class="twitter-follow-button" data-show-count="false"><?php
				printf(
					esc_html_e( 'Follow %s', 'tychesoftwares' ),
					'@tychesoftwares'
				);
				?></a>
            <script>!function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = p + '://platform.twitter.com/widgets.js';
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, 'script', 'twitter-wjs');
            </script>

        </div>
        <!--/.social-items-wrap -->

		<?php
	}


	/**
	 * Sends user to the Welcome page on first activation of the plugin as well as each
	 * time the plugin is updated is upgraded to a new version
	 *
	 * @access public
	 * @since  3.3
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
}

new TS_Welcome();