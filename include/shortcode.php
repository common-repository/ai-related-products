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

class ST_Woo_AI_Rel_Products_Shortcode extends ST_Woo_AI_Rel_Products_Control {	

    /*
	 * SHORTCODES
	 *
	 * Default shortcode:
	 * [ST_WOO_AI_REL_PRODUCTS]
	 *
	 * Shortcode with atts:
	 * [ST_WOO_AI_REL_PRODUCTS column="3" cart_ref="true" no_of_products="6" orderby="date"]
	 */

	public function __construct() 
	{
		add_action( 'init', array( $this, 'st_woo_ai_rel_products_create_shortcode' ), 100 );
	}

	/**
	 * Shortcode construction
	 *
	 * @package AI Related Products
	 * @since 1.0.0
	 */
	public function st_woo_ai_rel_products_shortcode_function( $atts ) 
	{
		$input = shortcode_atts( array(
			'column' => 3, //no of columns layout
			'cart_ref' => false, // filter by products selected in cart
			'no_of_products' => 6, // no of products
			'sort' => 'rand', // no of products
		), $atts );

		$cart_ref = $input['cart_ref'] && ( 'yes' == strtolower( $input['cart_ref'] ) ) ? true : false;

		// no of columns
		$column = $input['column'] ? absint($input['column']) : 3;
		$column = ( 4 < $column ) ? 4 : $column;

		// no of products
		$original_no_of_products = $input['no_of_products'] ? absint( $input['no_of_products'] ) : 6;
		$original_no_of_products = ( 8 < $original_no_of_products ) ? 8 : $original_no_of_products;
        $products_to_fetch = $original_no_of_products + 2;

		// sort
		$sort = ( 'date' == $input['sort'] ) ? 'date' : 'rand';
		
		// retrive ordered and cart products
		$product_ids = $this::st_woo_rel_products( $cart_ref, 1 );


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

		ob_start();

		/**
		 * st_woo_ai_rel_products_shortcode_open_action hook
		 *
		 * @hooked st_woo_ai_rel_products_shortcode_main_wrapper_open -  10
		 * @hooked st_woo_ai_rel_products_shortcode_ul_open -  20
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
		 * @hooked st_woo_ai_rel_products_shortcode_main_wrapper_close -  20
		 *
		 */
		do_action( 'st_woo_ai_rel_products_shortcode_close_action' );

		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * Create Shortcode
	 *
	 * @package AI Related Products
	 * @since 1.0.0
	 */
	public function st_woo_ai_rel_products_create_shortcode() 
	{
		add_shortcode( 'ST_WOO_AI_REL_PRODUCTS', array( $this, 'st_woo_ai_rel_products_shortcode_function' ) );
	}

}

new ST_Woo_AI_Rel_Products_Shortcode();