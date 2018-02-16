<?php

/**
 * 
 * @since 1.0.0
 */
class TS_Faq_Support {
	/**
	* @var string Plugin name
	* @access public 
	*/

	public static $plugin_name = 'Order Delivery Date Pro for WooCommerce';

	/**
	 * Initialization of hooks where we prepare the functionality to ask use for survey
	 */
	public function __construct() {
		
		//Add a sub menu in the main menu of the plugin if added.
		add_action( 'orddd_add_submenu', array( &$this, 'ts_add_submenu' ) );

		//Add a tab for FAQ & Support along with other plugin settings tab.
		add_action( 'orddd_add_new_settings_tab', array( &$this, 'ts_add_new_settings_tab' ) );
		add_action( 'orddd_add_tab_content', array( &$this, 'ts_add_tab_content' ) );

		$this->plugin_folder  = 'order-delivery-date/'; 		
		$this->plugin_url     = $this->ts_get_plugin_url();
		$this->template_base  = $this->ts_get_template_path();
		
	}

	/**
	* Adds a subment to the main menu of the plugin
	* 
	* @since 7.7 
	*/

	public function ts_add_submenu() {
		$page = add_submenu_page( 'order_delivery_date', 'FAQ & Support', 'FAQ & Support', 'manage_woocommerce', 'ts_faq_support_page', array( &$this, 'ts_faq_support_page' ) );

	}

	/** 
	* Add a new tab on the settings page.
	*
	* @since 7.7
	*/
	public function ts_add_new_settings_tab() {
		$faq_support_page = '';
		if( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'faq_support_page' ) {
		    $faq_support_page = "nav-tab-active";
		}

		?>
		<a href="admin.php?page=order_delivery_date&action=faq_support_page" class="nav-tab <?php echo $faq_support_page; ?>"> <?php _e( 'FAQ & Support', 'order-delivery-date' ); ?> </a>
		<?php

		
	}

	/**
	* Add content to the new tab added.
	*
	* @since 7.7
	*/

	public function ts_add_tab_content() {
		if( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'faq_support_page' ) {
			$this->ts_faq_support_page();
		}
	}

	/**
	* Adds a page to display the FAQ's and support information
	*
	* @since 7.7
	*/
	public function ts_faq_support_page() {
		ob_start();
        wc_get_template( 'faq-page/faq-page.php', array(), $this->plugin_folder, $this->template_base );
        echo ob_get_clean();
	}

	/**
     * This function returns the plugin url 
     *
     * @access public 
     * @since 7.7
     * @return string
     */
    public function ts_get_plugin_url() {
        return plugins_url() . '/' . $this->plugin_folder;
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
}

//intialization
$TS_Faq_Support = new TS_Faq_Support();