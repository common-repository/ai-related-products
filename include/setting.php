<?php
/**
 * AI Related Products Shortcode
 *
 * @package AI Related Products
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ST_Woo_AI_Rel_Products_Setting {

    private $page_slug = 'ai-related-products';

    private $shortcode_page_slug = 'ai-related-products-shortcode';

    private $option_group = 'st_woo_ai_rel_products_settings';

    private $setting_id = 'st_woo_ai_rel_products_single_Page';

    private $shortcode_option_group = 'st_woo_ai_rel_products_shortcode_settings';

    private $shortcode_setting_id = 'st_woo_ai_rel_products_shortcode';

    public function __construct() {
        $this->st_woo_ai_rel_products_register_hook();
    }
    
    public function st_woo_ai_rel_products_register_hook() {
        add_action( 'admin_menu', array( $this, 'st_woo_ai_rel_products_add_menu' ), 9 );   
        add_action( 'admin_init',  array( $this, 'st_woo_ai_rel_products_settings_fields' ) );
    }

    public function st_woo_ai_rel_products_add_menu() {
        add_menu_page(
            __( 'AI Related Products', 'ai-related-products' ),
            __( 'AI Related Products', 'ai-related-products' ),
            'manage_options',
            $this->page_slug,
            array( $this, 'st_woo_ai_rel_products_page_cb' ),
            'dashicons-filter',
            59
        );

        add_submenu_page(
            $this->page_slug,
            __( 'Single Product Page Related Products', 'ai-related-products' ),
            __( 'Single Product Page', 'ai-related-products' ),
            'manage_options',
            $this->page_slug,
            array( $this, 'st_woo_ai_rel_products_page_cb' )
        );

        add_submenu_page(
            $this->page_slug,
            __( 'Products Recommendation Details', 'ai-related-products' ),
            __( 'Component', 'ai-related-products' ),
            'manage_options',
            $this->shortcode_page_slug,
            array( $this, 'st_woo_ai_rel_products_shortcode_page_cb' )
        );
    }

    public function st_woo_ai_rel_products_page_cb() {
        $page_title = get_admin_page_title(); ?>
        <div class="wrap">
			<h1><?php echo esc_html( $page_title ); ?></h1>
			<form method="post" action="options.php">
				<?php
					do_settings_sections( $this->page_slug ); // just a page slug
					settings_fields( $this->option_group ); // settings group name
					submit_button(); // "Save Changes" button
				?>
			</form>
		</div>
    <?php
    }

    public function st_woo_ai_rel_products_shortcode_page_cb() {
        $page_title = get_admin_page_title(); ?>
        <div class="wrap">
			<h1><?php echo esc_html( $page_title ); ?></h1>
            <form>
				<?php
					do_settings_sections( $this->shortcode_page_slug ); // just a page slug
					settings_fields( $this->shortcode_option_group ); // settings group name
				?>
            </form>
		</div>
    <?php
    }

    public function st_woo_ai_rel_products_settings_fields() {

        /*
         * Register Section
         */
        // single product page section
        add_settings_section(
            $this->setting_id,
            __( 'WooCommerce Single Product Page\'s Related Products Settings ', 'ai-related-products' ) . '<a rel="nofollow" target="_blank" class="button button-primary" href="https://www.sharkthemes.com/docs/ai-related-products-documentation/">' . __( 'Documentation', 'ai-related-products' ) . '</a> <a rel="nofollow" target="_blank" class="button button-primary" href="https://www.sharkthemes.com/downloads/ai-related-products-pro/">' . __( 'Upgrade To Pro', 'ai-related-products' ) . '</a>',
            '',
            $this->page_slug
        );

        // shortcode section
        add_settings_section(
            $this->shortcode_setting_id,
            __( 'Gutenberg Block, Elementor Widget and Shortcode Information ', 'ai-related-products' ) . '<a rel="nofollow" target="_blank" class="button button-primary" href="https://www.sharkthemes.com/docs/ai-related-products-documentation/">' . __( 'Documentation', 'ai-related-products' ) . '</a> <a rel="nofollow" target="_blank" class="button button-primary" href="https://www.sharkthemes.com/downloads/ai-related-products-pro/">' . __( 'Upgrade To Pro', 'ai-related-products' ) . '</a>',
            '',
            $this->shortcode_page_slug,
        );
    
        /*
         * Register Single Product Page Related Products Settings
         */

        // description
        register_setting( 
            $this->option_group, 
            'st_woo_ai_rel_products_description_single_rel_products' 
        );

        // enable replace related products
        register_setting( 
            $this->option_group, 
            'st_woo_ai_rel_products_replace_single_rel_products', 
            array( $this, 'st_woo_ai_rel_products_sanitize_checkbox' ) 
        );

        // enable cart reference for related products
        register_setting( 
            $this->option_group, 
            'st_woo_ai_rel_products_cart_ref_single_rel_products', 
            array( $this, 'st_woo_ai_rel_products_sanitize_checkbox' ) 
        );

        // no of ordered products for reference in related products
        register_setting( 
            $this->option_group, 
            'st_woo_ai_rel_products_ordered_ref_single_rel_products', 
            'absint' 
        );
        
        // no of related products
        register_setting( 
            $this->option_group, 
            'st_woo_ai_rel_products_number_single_rel_products', 
            'absint' 
        );

        // no of columns for related products
        register_setting( 
            $this->option_group, 
            'st_woo_ai_rel_products_column_single_rel_products', 
            'absint' 
        );

        /*
         * Register Shortcode Settings
         */

        // shortcode description
        register_setting( 
            $this->shortcode_option_group, 
            'st_woo_ai_rel_products_description_shortcode' 
        );
    
        /*
         * Register Single Product Page Fields
         */

        // description
        add_settings_field(
            'st_woo_ai_rel_products_description_single_rel_products',
            '',
            array( $this, 'st_woo_ai_rel_products_description_control' ),
            $this->page_slug,
            $this->setting_id,
            array (
                'description' => __( 'Your settings will be saved when you click the Save Changes button.', 'ai-related-products' )
            )
        );

        // enable replace related products
        add_settings_field(
            'st_woo_ai_rel_products_replace_single_rel_products',
            __( 'Replace Default Related Products With Plugin\'s Related Products', 'ai-related-products' ),
            array( $this, 'st_woo_ai_rel_products_checkbox_control' ),
            $this->page_slug,
            $this->setting_id,
            array (
                'name' => 'st_woo_ai_rel_products_replace_single_rel_products',
                'default' => true
            )
        );
        
        // enable cart reference for related products
        add_settings_field(
            'st_woo_ai_rel_products_cart_ref_single_rel_products',
            __( 'Include Cart Products as a Reference to Filter Related Products', 'ai-related-products' ),
            array( $this, 'st_woo_ai_rel_products_checkbox_control' ),
            $this->page_slug,
            $this->setting_id,
            array (
                'name' => 'st_woo_ai_rel_products_cart_ref_single_rel_products',
                'default' => false
            )
        );

        // no of ordered products for reference in related products
        add_settings_field(
            'st_woo_ai_rel_products_ordered_ref_single_rel_products',
            __( 'Number of Recently Ordered/Purchased Products as a Reference', 'ai-related-products' ),
            array( $this, 'st_woo_ai_rel_products_description_control' ),
            $this->page_slug,
            $this->setting_id,
            array (
                'description' => __( 'Only one recently ordered/purchased product is set as a filter reference in free version. Upgrade to pro version to get input option to set multiple number value.', 'ai-related-products' )
            )
        );

        // no of related products
        add_settings_field(
            'st_woo_ai_rel_products_number_single_rel_products',
            __( 'Number of Related Products ( Min = 1, Max = 6 )', 'ai-related-products' ),
            array( $this, 'st_woo_ai_rel_products_number_control' ),
            $this->page_slug,
            $this->setting_id,
            array(
                'name' => 'st_woo_ai_rel_products_number_single_rel_products',
                'default' => 6,
                'min' => 1,
                'max' => 6
            )
        );

        // no of columns for related products
        add_settings_field(
            'st_woo_ai_rel_products_column_single_rel_products',
            __( 'Number of Layout Columns ( Min = 1, Max = 3 )', 'ai-related-products' ),
            array( $this, 'st_woo_ai_rel_products_number_control' ),
            $this->page_slug,
            $this->setting_id,
            array(
                'name' => 'st_woo_ai_rel_products_column_single_rel_products',
                'default' => 3,
                'min' => 1,
                'max' => 3
            )
        );

        /*
         * Register Shortcode Fields
         */

        // shortcode description
        $output = '';
        $output .= '<b>' . __( 'Gutenberg Block (Only in Pro Version)', 'ai-related-products' ) . '</b><br><hr>';
        $output .= __( 'Add AI Related Products anywhere in your site through block. Since WordPress has moved to Full Site Editing and supports blocks since version 5.0. We have added the AI Related Products Block for your convenience. Since you are familiar with blocks, you will be able to add this block in any page and widget area. You can customize the settings and attributes to filter the product list.', 'ai-related-products' );
        $output .= '<br><br>';

        $output .= '<b>' . __( 'Elementor Widget (Limited in Free Version)', 'ai-related-products' ) . '</b><br><hr>';
        $output .= __( 'If you are using Elementor, add AI Related Products anywhere in your site through Elementor Widget. Since Elementor is the most popular page builder plugin in WordPress. We have added the AI Related Products widget for your convenience. As you are familiar with Elementor, you will be able to add this widget in any page. You can customize the settings and attributes to filter the product list.', 'ai-related-products' );
        $output .= '<br><br>';

        $output .= '<b>' . __( 'Shortcode (Limited in Free Version)', 'ai-related-products' ) . '</b><br><hr>';
        $output .= __( 'This plugin supports shorcode to add AI Related Products anywhere in your site. Shortcode is an easy way to add a dynamic content to your page. You can use it in blocks, widgets and classical editor. You can customize the content with the attributes that are supported in shortcode.', 'ai-related-products' );
        $output .= '<br><br>' . __( 'Default Shortcode with default attributes:', 'ai-related-products' );
        $output .= '<br><b>[ST_WOO_AI_REL_PRODUCTS]</b>';
        $output .= '<br><br><span>' . __( 'List of attributes that are supported in shortcode.', 'ai-related-products' ) . '</span>';
        $output .= '<ol>';
        $output .= '<li>' . __( 'Column Layout ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS column="4"]</b>';
        $output .= '<br>' . __( 'Column possibilities are 1, 2, 3, 4, 5 and 6 (the default is "3," so choose one of these).', 'ai-related-products' );
        $output .= '<br><b>' . __( 'Note: In free version, you can only have 1, 2, 3 and 4 columns.', 'ai-related-products' ) . '</b>';
        $output .= '</li><li>' . __( 'Products Sort ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS sort="rand"]</b>';
        $output .= '<br>' . __( 'Sorting possibilities are rand(random) and date(starting from latest) (the default is "rand," so choose one of these).', 'ai-related-products' );
        $output .= '</li><li>' . __( 'Enable Slider ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS slide="yes"]</b>';
        $output .= '<br>' . __( 'carousel instead of grid. Options : yes / no (the default is "no")', 'ai-related-products' );
        $output .= '<br><b>' . __( 'Note: This attribute is only available in Pro Version.', 'ai-related-products' ) . '</b>';
        $output .= '</li><li>' . __( 'Include Cart Products For Reference ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS cart_ref="yes"]</b>';
        $output .= '<br>' . __( 'Include items from the shopping cart to filter the related products. Options : yes / no (the default is "no")', 'ai-related-products' );
        $output .= '</li><li>' . __( 'Include Watched Products For Reference ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS cookie_ref="yes"]</b>';
        $output .= '<br>' . __( 'Include items that were visited by the visitor to filter the related products. Options : yes / no (the default is "no")', 'ai-related-products' );
        $output .= '<br><b>' . __( 'Note: This attribute is only available in Pro Version.', 'ai-related-products' ) . '</b>';
        $output .= '</li><li>' . __( 'Number of Products ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS no_of_products="8"]</b>';
        $output .= '<br>' . __( 'Set the number of products to display. Options: As you prefer (the default is "6")', 'ai-related-products' );
        $output .= '<br><b>' . __( 'Note: In free version, you can only have upto 8 products.', 'ai-related-products' ) . '</b>';
        $output .= '</li><li>' . __( 'Number of Recently Ordered Products ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS order_ref="1"]</b>';
        $output .= '<br>' . __( 'Set 1 as a reference to filter products by most recently ordered/purchased item, 2 for two most recently ordered/purchased items, and so on. For all ordered/purchased items, leave it empty. (the default is empty)', 'ai-related-products' );
        $output .= '<br><b>' . __( 'Note: This attribute is only available in Pro Version. In free version, 1 is set in built.', 'ai-related-products' ) . '</b>';
        $output .= '</li><li>' . __( 'Additional Meta Filters ( Optional ) :', 'ai-related-products' ) . ' <b>[ST_WOO_AI_REL_PRODUCTS meta_filter="best-selling"]</b>';
        $output .= '<br>' . __( 'Further, filter the products by choosing only those that are on sale, featured, or are the best-selling. Options: sale / featured / best-selling. (the default is empty, so choose one of these)', 'ai-related-products' );
        $output .= '<br><b>' . __( 'Note: This attribute is only available in Pro Version.', 'ai-related-products' ) . '</b>';
        $output .= '</li><li>' . __( 'Shortcode with all attributes.', 'ai-related-products' );
        $output .= '<br><b>[ST_WOO_AI_REL_PRODUCTS column="4" sort="date" cart_ref="yes" no_of_products="8"]</b>';
        $output .= '</li><br>' . __( 'Note: If you want some attributes to have default value, then you don\'t have to add it in the shortcode. You can use multiple shortcode with multiple settings anywhere in your site.', 'ai-related-products' );
        $output .= '</ol>';
        
        add_settings_field(
            'st_woo_ai_rel_products_description_shortcode',
            '',
            array( $this, 'st_woo_ai_rel_products_description_control' ),
            $this->shortcode_page_slug,
            $this->shortcode_setting_id,
            array (
                'description' => $output
            )
        );
    }
                
    // custom callback function to print checkbox field HTML
    public function st_woo_ai_rel_products_checkbox_control( $args ) {
        $value = get_option( $args['name'] ); 
        $value = isset( $value ) && $value ? $value : $args['default']; 
        ?>
        <input id="<?php echo esc_attr( $args['name'] ); ?>" class='st-checkbox' type="checkbox" name="<?php echo esc_attr( $args['name'] ); ?>" <?php checked( $value, true ); ?> />
    <?php
    }

    // custom callback function to print number field HTML
    public function st_woo_ai_rel_products_number_control( $args ) {
        $value = get_option( $args['name'] );
        $default = $args['default'];
        $min = $args['min'];
        $max = $args['max']; 
        ?>
        <label class="st-number-label <?php echo esc_attr( $args['name'] ); ?>">
            <input id="<?php echo esc_attr( $args['name'] ); ?>" class='st-number' type="number" min="<?php echo absint( $min ); ?>" max="<?php echo absint( $max ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" value="<?php echo $value ? absint( $value ) : $default; ?>" />
        </label>
    <?php
    }

    // custom callback function to print description
    public function st_woo_ai_rel_products_description_control( $args ) { ?>
        <p class="st-description"><?php echo $this->st_woo_ai_rel_products_allow_tags( $args['description'] ); ?></p>
    <?php
    }

    // custom sanitization function for a checkbox field
    public function st_woo_ai_rel_products_sanitize_checkbox( $value ) {
        return 'on' === $value ? true : false;
    }

    public function st_woo_ai_rel_products_allow_tags( $input ) {
        $input = wp_kses( $input, array(
            'span' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
            'b' => array(),
            'br' => array(),
            'hr' => array(),
            ) );

        return $input;
    }
     
}

new ST_Woo_AI_Rel_Products_Setting();