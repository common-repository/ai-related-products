<?php
/**
 * AI Related Products Structure
 *
 * @package AI Related Products
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// shortcode structure open
add_action( 'st_woo_ai_rel_products_shortcode_open_action', 'st_woo_ai_rel_products_shortcode_main_wrapper_open', 10 );
add_action( 'st_woo_ai_rel_products_shortcode_open_action', 'st_woo_ai_rel_products_shortcode_ul_open', 20, 1 );

// shortcode structure close
add_action( 'st_woo_ai_rel_products_shortcode_close_action', 'st_woo_ai_rel_products_shortcode_ul_close', 10 );
add_action( 'st_woo_ai_rel_products_shortcode_close_action', 'st_woo_ai_rel_products_shortcode_main_wrapper_close', 20 );

// shortcode main div wrapper start
function st_woo_ai_rel_products_shortcode_main_wrapper_open() { ?>
    <div class="st-woo-ai-rel-products">
        <div class="woocommerce">
<?php }

// shortcode products ul start
function st_woo_ai_rel_products_shortcode_ul_open( $column = 3 ) { ?>
    <ul class="products column-<?php echo absint( $column ); ?>">
<?php }

// shortcode products ul close
function st_woo_ai_rel_products_shortcode_ul_close() { ?>
    </ul>
<?php }

// shortcode main div wrapper close
function st_woo_ai_rel_products_shortcode_main_wrapper_close() { ?>
        </div><!-- .woocommerce -->
    </div><!-- .st-woo-ai-rel-products -->
<?php }
