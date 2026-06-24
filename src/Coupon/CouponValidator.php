<?php
namespace SaleCoupon\Coupon;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles checking validity and uniqueness of coupon codes.
 */
class CouponValidator {

	/**
	 * Checks if a coupon code already exists in the database.
	 *
	 * @param string $code The coupon code to check.
	 * @return bool True if code exists, false otherwise.
	 */
	public static function exists( $code ) {
		if ( empty( $code ) ) {
			return true;
		}

		$coupon_id = wc_get_coupon_id_by_code( $code );
		return $coupon_id > 0;
	}
}
