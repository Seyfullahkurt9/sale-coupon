<?php
namespace SaleCoupon\ProductType;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles outputting the custom Add to Cart form for sale_coupon products.
 */
class AddToCartHandler {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'woocommerce_sale_coupon_add_to_cart', [ $this, 'output_add_to_cart_form' ] );
	}

	/**
	 * Render the custom add to cart template.
	 */
	public function output_add_to_cart_form() {
		global $product;

		if ( ! $product || $product->get_type() !== 'sale_coupon' ) {
			return;
		}

		// Retrieve settings for the product (overrides)
		$product_id = $product->get_id();
		$min_amount = get_post_meta( $product_id, '_sc_product_min_amount', true );
		$max_amount = get_post_meta( $product_id, '_sc_product_max_amount', true );
		$presets    = get_post_meta( $product_id, '_sc_product_presets', true );

		// Fallback to global settings if product overrides are empty
		if ( empty( $min_amount ) ) {
			$min_amount = get_option( 'sc_min_amount', 10 );
		}
		if ( empty( $max_amount ) ) {
			$max_amount = get_option( 'sc_max_amount', 1000 );
		}

		// Parse presets
		$preset_array = [];
		if ( ! empty( $presets ) ) {
			// Clean whitespace and split by comma
			$preset_array = array_filter( array_map( 'floatval', explode( ',', $presets ) ) );
			// Sort ascending
			sort( $preset_array );
		}

		// Load template and pass variables
		wc_get_template(
			'single-product/add-to-cart/sale-coupon.php',
			[
				'product'    => $product,
				'min_amount' => floatval( $min_amount ),
				'max_amount' => floatval( $max_amount ),
				'presets'    => $preset_array,
			],
			'',
			SALE_COUPON_PATH . 'templates/'
		);
	}
}
