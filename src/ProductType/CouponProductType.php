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
	public function get_price( $context = 'view' ) {
		return parent::get_price( $context );
	}
}
