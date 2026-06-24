<?php
namespace SaleCoupon\Cart;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validates cart rules: blocking coupon application, amount validation, and single-item limits.
 */
class CartValidator {

	/**
	 * Register hooks.
	 */
	public function register() {
		// Prevent coupon codes from being applied when a sale_coupon product is in the cart.
		add_filter( 'woocommerce_coupon_is_valid', [ $this, 'disallow_coupon_usage_with_coupon_products' ], 10, 3 );

		// Validate custom amount and sold-individually rule when adding to cart.
		add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'validate_add_to_cart' ], 10, 3 );
	}

	/**
	 * Disallow coupon application if a sale_coupon product is in the cart.
	 *
	 * @param bool        $is_valid Existing validity.
	 * @param \WC_Coupon  $coupon   The coupon object.
	 * @param \WC_Discounts $discount Optional discount object.
	 * @return bool
	 * @throws \Exception When coupon is invalid.
	 */
	public function disallow_coupon_usage_with_coupon_products( $is_valid, $coupon, $discount = null ) {
		if ( ! $is_valid || ! WC()->cart ) {
			return $is_valid;
		}

		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product = $cart_item['data'];
			if ( $product && $product->get_type() === 'sale_coupon' ) {
				throw new \Exception( __( 'Kupon ürünü içeren sepetlere indirim kuponu uygulanamaz.', 'sale-coupon' ) );
			}
		}

		return $is_valid;
	}

	/**
	 * Validate product adding to cart.
	 *
	 * @param bool $passed     Whether validation passed.
	 * @param int  $product_id Product ID.
	 * @param int  $quantity   Quantity added.
	 * @return bool
	 */
	public function validate_add_to_cart( $passed, $product_id, $quantity ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return $passed;
		}

		// 1. If product being added is sale_coupon.
		if ( $product->get_type() === 'sale_coupon' ) {
			// Check if cart already has a sale_coupon product.
			foreach ( WC()->cart->get_cart() as $cart_key => $cart_item ) {
				$cart_product = $cart_item['data'];
				if ( $cart_product && $cart_product->get_type() === 'sale_coupon' ) {
					wc_add_notice( __( 'Sepetinizde zaten bir kupon ürünü bulunmaktadır. Aynı anda yalnızca bir adet kupon satın alabilirsiniz.', 'sale-coupon' ), 'error' );
					return false;
				}
			}

			// Validate amount input
			if ( ! isset( $_POST['sc_coupon_amount'] ) || empty( $_POST['sc_coupon_amount'] ) ) {
				wc_add_notice( __( 'Lütfen kupon tutarı girin.', 'sale-coupon' ), 'error' );
				return false;
			}

			$amount = floatval( $_POST['sc_coupon_amount'] );

			// Get min/max
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

			if ( $amount < $min_amount ) {
				wc_add_notice( 
					sprintf( 
						__( 'Kupon tutarı minimum limitin altındadır (%s).', 'sale-coupon' ), 
						wc_price( $min_amount ) 
					), 
					'error' 
				);
				return false;
			}

			if ( $amount > $max_amount ) {
				wc_add_notice( 
					sprintf( 
						__( 'Kupon tutarı maksimum limiti aşmaktadır (%s).', 'sale-coupon' ), 
						wc_price( $max_amount ) 
					), 
					'error' 
				);
				return false;
			}
		}

		// 2. If a non-coupon product is being added, but cart already has a coupon product, we allow it.
		// However, if the cart has a coupon product, we should make sure we don't apply coupons. That is covered in disallow_coupon_usage_with_coupon_products.

		return $passed;
	}
}
