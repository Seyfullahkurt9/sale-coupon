<?php
namespace SaleCoupon\ProductType;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom WooCommerce Product Type for Sale Coupon.
 */
class CouponProductType extends \WC_Product_Simple {

	/**
	 * Get product type.
	 *
	 * @return string
	 */
	public function get_type() {
		return 'sale_coupon';
	}

	/**
	 * Coupon products are always virtual.
	 *
	 * @return bool
	 */
	public function is_virtual() {
		return true;
	}

	/**
	 * Coupon products do not need shipping.
	 *
	 * @return bool
	 */
	public function needs_shipping() {
		return false;
	}

	/**
	 * Coupon products are not downloadable.
	 *
	 * @return bool
	 */
	public function is_downloadable() {
		return false;
	}

	/**
	 * Coupon products are sold individually (only 1 in cart at a time).
	 *
	 * @return bool
	 */
	public function is_sold_individually() {
		return true;
	}

	/**
	 * Coupon products can be purchased.
	 *
	 * @return bool
	 */
	public function is_purchasable() {
		return true;
	}

	/**
	 * Return the dynamic price of the product (default to 0).
	 * Fiyat, sepete eklenirken müşterinin girdiği miktar olacaktır.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	/**
	 * Return the price of the product.
	 * If no price is set, return 0.0 to avoid PHP 8 type errors in calculations.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string|float
	 */
	public function get_price( $context = 'view' ) {
		$price = parent::get_price( $context );
		return $price === '' ? 0.0 : $price;
	}

	/**
	 * Return the regular price of the product.
	 * If no price is set, return 0.0 to avoid PHP 8 type errors in calculations.
	 *
	 * @param string $context What the value is for.
	 * @return string|float
	 */
	public function get_regular_price( $context = 'view' ) {
		$price = parent::get_regular_price( $context );
		return $price === '' ? 0.0 : $price;
	}

	/**
	 * Return the sale price of the product.
	 * If no price is set, return 0.0 to avoid PHP 8 type errors in calculations.
	 *
	 * @param string $context What the value is for.
	 * @return string|float
	 */
	public function get_sale_price( $context = 'view' ) {
		$price = parent::get_sale_price( $context );
		return $price === '' ? 0.0 : $price;
	}

	/**
	 * Get the price HTML showing min and max range.
	 *
	 * @return string
	 */
	public function get_price_html() {
		$min_amount = get_post_meta( $this->get_id(), '_sc_product_min_amount', true );
		$max_amount = get_post_meta( $this->get_id(), '_sc_product_max_amount', true );

		if ( empty( $min_amount ) ) {
			$min_amount = get_option( 'sc_min_amount', 10 );
		}
		if ( empty( $max_amount ) ) {
			$max_amount = get_option( 'sc_max_amount', 1000 );
		}

		$min_amount = floatval( $min_amount );
		$max_amount = floatval( $max_amount );

		if ( $min_amount === $max_amount ) {
			$price = wc_price( $min_amount );
		} else {
			$price = wc_format_price_range( $min_amount, $max_amount );
		}

		return apply_filters( 'woocommerce_get_price_html', $price, $this );
	}
}
