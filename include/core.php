<?php
/**
 * @package AI Related Products
 * @category Core
 * @author Shark Themes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ST_Woo_AI_Rel_Products_Core {

	public function __construct()
	{
		$this->st_woo_ai_rel_products_register();
		add_action( 'admin_enqueue_scripts', array( $this, 'st_woo_ai_rel_products_admin_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'st_woo_ai_rel_products_enqueue' ) );
		add_filter( 'plugin_action_links_' .  ST_WOO_AI_REL_PRODUCTS_PLUGIN_BASE_PATH, array( $this, 'st_woo_ai_rel_products_setting_links' ) );
		add_action( 'activated_plugin', array( $this, 'st_woo_ai_rel_products_redirect' ) );
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'st_woo_ai_rel_products_elementor_widget' ) );
		}
		
	}

	/**
	 * redirect on activation
	 */
	public function st_woo_ai_rel_products_redirect( $plugin ) {
		if ( $plugin == ST_WOO_AI_REL_PRODUCTS_PLUGIN_BASE_PATH ) {
			exit( wp_redirect( admin_url( 'admin.php?page=ai-related-products' ) ) );
		}
	}

	/**
	 * register functions
	 */
    public function st_woo_ai_rel_products_register()
    {
		include_once ST_WOO_AI_REL_PRODUCTS_BASE_PATH . '/include/setting.php';
		include_once ST_WOO_AI_REL_PRODUCTS_BASE_PATH . '/include/structure.php';
		include_once ST_WOO_AI_REL_PRODUCTS_BASE_PATH . '/include/functions.php';
		include_once ST_WOO_AI_REL_PRODUCTS_BASE_PATH . '/include/shortcode.php';
    }

	/**
	 * elementor widget register
	 */
	public function st_woo_ai_rel_products_elementor_widget() {
		include_once ST_WOO_AI_REL_PRODUCTS_BASE_PATH . '/include/elementor.php';

		// Register the custom Elementor widget.
    	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new ST_Woo_AI_Rel_Products_Elementor_Widget() );
	}
	
	/*
	 * Enqueue scripts
	 */
    public function st_woo_ai_rel_products_enqueue()
	{
        // Load style
        wp_enqueue_style( 'st-woo-ai-rel-products', ST_WOO_AI_REL_PRODUCTS_URL_PATH . 'assets/css/style.css' );

	}

	/*
	 * admin Enqueue scripts
	 */
	public function st_woo_ai_rel_products_admin_enqueue( $hook ) {
		if ( in_array( $hook, array( 'toplevel_page_ai-related-products', 'ai-related-products_page_ai-related-products-shortcode' ) ) ) :
			wp_enqueue_style( 'st-woo-ai-rel-products-admin', ST_WOO_AI_REL_PRODUCTS_URL_PATH . 'assets/css/admin.css' );
		endif;
	
	}

	/*
	* Add Support link to plugin action
	*/
	public function st_woo_ai_rel_products_setting_links( $links ) {
		$setting_page_link = array(
			'<a href="' . admin_url( 'admin.php?page=ai-related-products' ) . '">' . esc_html__( 'Settings', 'ai-related-products' ) . '</a>',
		);
		return array_merge( $links, $setting_page_link );
	}

}

new ST_Woo_AI_Rel_Products_Core();