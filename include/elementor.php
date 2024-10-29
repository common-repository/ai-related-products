<?php
/**
 * AI Related Products Elementor Widget
 *
 * @package AI Related Products
 * @since 1.0.0
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ST_Woo_AI_Rel_Products_Elementor_Widget extends Widget_Base {

    /**
     * Summary of get_name
     * @return string
     */
    public function get_name() {
        return 'ai-rel-products-elementor-widget';
    }

    /**
     * Summary of get_title
     * @return mixed
     */
    public function get_title() {
        return __( 'AI Related Products', 'ai-related-products' );
    }

    /**
     * Summary of get_icon
     * @return string
     */
    public function get_icon() {
        return 'eicon-filter';
    }

    /**
     * Summary of get_categories
     * @return array
     */
    public function get_categories() {
        return [ 'general' ];
    }

    /**
     * Summary of _register_controls
     * @return void
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'AI Related Products Settings', 'ai-related-products' ),
            ]
        );

        // Add control for the number of products to display.
        $this->add_control(
            'no_of_products',
            [
                'label' => __( 'Number of Products', 'ai-related-products' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 6, // Default number of products.
                'max' => 8,
            ]
        );

        // Add control for the number of columns (1 to 4).
        $this->add_control(
            'column',
            [
                'label' => __( 'Column Layout', 'ai-related-products' ),
                'type' => Controls_Manager::SELECT,
                'default' => 3,
                'options' => [
                    1 => __( 'One Column', 'ai-related-products' ),
                    2 => __( 'Two Columns', 'ai-related-products' ),
                    3 => __( 'Three Columns', 'ai-related-products' ),
                    4 => __( 'Four Columns', 'ai-related-products' ),
                ],
            ]
        );

        // Add control for sort.
        $this->add_control(
            'sort',
            [
                'label' => __( 'Sort By', 'ai-related-products' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'rand',
                'options' => [
                    'rand' => __( 'Random', 'ai-related-products' ),
                    'date' => __( 'Date', 'ai-related-products' ),
                ],
            ]
        );

        // Add control to enable/disable cart ref.
        $this->add_control(
            'cart_ref',
            [
                'label' => __( 'Include Cart Items for Reference', 'ai-related-products' ),
                'description' => __( 'Allow filtering of products based on products that have been added to cart by user or visitor.', 'ai-related-products' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'ai-related-products' ),
                'label_off' => __( 'No', 'ai-related-products' ),
                'default' => 'no', // Default is enabled.
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Summary of render
     * @return void
     */
    protected function render() {
        $settings = $this->get_settings();

        $cart_ref = $settings['cart_ref'] && ( 'yes' == strtolower( $settings['cart_ref'] ) ) ? true : false;

        // no of columns
		$column = $settings['column'] ? absint($settings['column']) : 3;
		$column = ( 4 < $column ) ? 4 : $column;
        
		// no of products
		$original_no_of_products = $settings['no_of_products'] ? absint( $settings['no_of_products'] ) : 6;
        $original_no_of_products = ( 8 < $original_no_of_products ) ? 8 : $original_no_of_products;
        $products_to_fetch = $original_no_of_products + 2;

        // sort
        $sort = sanitize_key( $settings['sort'] );
		$sort = ( 'date' == $settings['sort'] ) ? 'date' : 'rand';
        
        // retrive ordered and cart products
		$product_ids = ST_Woo_AI_Rel_Products_Control::st_woo_rel_products( $cart_ref, 1 );

        // query args
		$args = array(
			'post_type' => 'product',
			'ignore_sticky_posts' => true,
			'posts_per_page' => absint( $products_to_fetch ),
			'orderby' => $sort,
		);
        
		$product_cats = array();
		$parent_cats = array();

		if ( ! empty( $product_ids ) ) {
			foreach ( $product_ids as $product ) {
				$product_cat = wc_get_product_term_ids( $product, 'product_cat' );
				if ( ! empty( $product_cat ) ) {
					$product_cat = $product_cat[0];
					$product_cats[] = $product_cat;
					$parent_cat = get_ancestors($product_cat, 'product_cat');
					if ( ! empty( $parent_cat ) ) {
						$parent_cats[] = $parent_cat[0];
					}
				}
			}
		
			$product_cats = array_merge( $product_cats, $parent_cats );
			$product_cats = array_unique( $product_cats );
		}

		// filter if categories are set or else show ramdom products
		if ( ! empty( $product_cats ) ) :
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'terms' => ( array ) $product_cats,
				)
			);
		endif;

		$query = new WP_Query( $args );

        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
            echo '<div style="position:absolute; width:80%; top:50%; left:50%; transform:translate(-50%,-50%); z-index:999; padding: 0 25px; background: rgba(255,255,255,0.8)">';
            echo '<h3 style="text-align:center; color:#ccc;">' . __( 'AI Related Products', 'ai-related-products' ) . '</h3>';
            echo '<h6 style="text-align:center; color:#ccc;">' . __( 'This message is only visible in editor', 'ai-related-products' ) . '</h6>';
            echo '</div>';
        }

        /**
         * st_woo_ai_rel_products_shortcode_wrapper_open_action hook
         *
         * @hooked st_woo_ai_rel_products_shortcode_main_wrapper_open -  10
         */
        do_action( 'st_woo_ai_rel_products_shortcode_wrapper_open_action' );

        /** 
         * st_woo_ai_rel_products_shortcode_open_action hook
         * @hooked st_woo_ai_rel_products_shortcode_ul_open -  10
         *
         */
		do_action( 'st_woo_ai_rel_products_shortcode_open_action', absint( $column ) );

		if ( $query->have_posts() ):
			$i = 1;
			while ( $query->have_posts() ) : $query->the_post();
				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );

				if ( $original_no_of_products == $i ) {
                    break;
                }

                $i++;
			endwhile;
		endif;

		/**
		 * st_woo_ai_rel_products_shortcode_close_action hook
		 *
		 * @hooked st_woo_ai_rel_products_shortcode_ul_close -  10
		 *
		 */
		do_action( 'st_woo_ai_rel_products_shortcode_close_action' );

		/**
		 * st_woo_ai_rel_products_shortcode_close_action hook
		 *
		 * @hooked st_woo_ai_rel_products_shortcode_main_wrapper_close -  10
		 *
		 */
		do_action( 'st_woo_ai_rel_products_shortcode_wrapper_close_action' );

		wp_reset_postdata();

    }
}


