<?php
namespace SaleCoupon\Cart;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles overriding cart item product prices dynamically with the user-selected amount.
 */
class PriceOverride {

	/**
	 * Register hooks.
	 */
	public function register() {
		// Hook into price calculations.
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'override_coupon_product_prices' ], 10, 1 );
	}

	/**
	 * Dynamically sets the coupon product price to the selected coupon amount in the cart.
	 *
	 * @param \WC_Cart $cart Cart object.
	 */
	public function override_coupon_product_prices( $cart ) {
		// Avoid infinite loops and run only once per calculation.
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			// Running multiple times is normal in WC, but let's make sure we check we don't cause loops.
		}

		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'];
			if ( $product && $product->get_type() === 'sale_coupon' ) {
				if ( isset( $cart_item['sc_coupon_amount'] ) ) {
					$amount = floatval( $cart_item['sc_coupon_amount'] );

					// Server-side bounds double validation for extra security.
					$product_id = $product->get_id();
					$min_amount = get_post_meta( $product_id, '_sc_product_min_amount', true );
					$max_amount = get_post_meta( $product_id, '_sc_product_max_amount', true );

					if ( empty( $min_amount ) ) {
						$min_amount = get_option( 'sc_min_amount', 10 );
					}
					if ( empty( $max_amount ) ) {
						$max_amount = get_option( 'sc_max_amount', 1000 );
					}

					$min_amount = floatval( $min_amount );
					$max_amount = floatval( $max_amount );

					// Clamp amount to bounds to prevent hacking/manipulation.
					$amount = max( $min_amount, min( $max_amount, $amount ) );

					// Set product prices in the cart.
					$product->set_price( $amount );
				}
			}
		}
	}
}
