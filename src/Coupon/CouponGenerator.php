<?php
namespace SaleCoupon\Coupon;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles WC_Coupon object creation programmatically.
 */
class CouponGenerator {

	/**
	 * Generate a unique coupon and insert it into WooCommerce.
	 *
	 * @param float $amount The coupon monetary value.
	 * @param array $args   Config arguments (prefix, expiry_days, discount_type, purchaser_id, order_id).
	 * @return \WC_Coupon|false The generated coupon object or false on failure.
	 */
	public static function generate( $amount, $args = [] ) {
		$amount = floatval( $amount );
		if ( $amount <= 0 ) {
			return false;
		}

		// Defaults from settings
		$default_prefix        = get_option( 'sc_coupon_prefix', 'GIFT-' );
		$default_length        = get_option( 'sc_random_length', 10 );
		$default_expiry_days   = get_option( 'sc_expiry_days', 365 );
		$default_discount_type = get_option( 'sc_discount_type', 'fixed_cart' );

		// Parse args
		$prefix        = isset( $args['prefix'] ) && ! empty( $args['prefix'] ) ? $args['prefix'] : $default_prefix;
		$length        = isset( $args['length'] ) && ! empty( $args['length'] ) ? intval( $args['length'] ) : $default_length;
		$expiry_days   = isset( $args['expiry_days'] ) && $args['expiry_days'] !== '' ? intval( $args['expiry_days'] ) : $default_expiry_days;
		$discount_type = isset( $args['discount_type'] ) && ! empty( $args['discount_type'] ) ? $args['discount_type'] : $default_discount_type;
		$purchaser_id  = isset( $args['purchaser_id'] ) ? intval( $args['purchaser_id'] ) : 0;
		$order_id      = isset( $args['order_id'] ) ? intval( $args['order_id'] ) : 0;

		// Generate a unique code
		$code = '';
		$attempts = 0;
		$max_attempts = 5;

		while ( $attempts < $max_attempts ) {
			$temp_code = CodeGenerator::generate_code( $prefix, $length );
			if ( ! CouponValidator::exists( $temp_code ) ) {
				$code = $temp_code;
				break;
			}
			$attempts++;
		}

		// If we couldn't generate a unique code after 5 attempts, append extra timestamp characters to make it unique.
		if ( empty( $code ) ) {
			$code = CodeGenerator::generate_code( $prefix, 8 ) . strtoupper( substr( uniqid(), -4 ) );
		}

		// Calculate expiry date
		$expiry_date = null;
		if ( $expiry_days > 0 ) {
			$expiry_date = new \WC_DateTime();
			$expiry_date->add( new \DateInterval( 'P' . $expiry_days . 'D' ) );
		}

		try {
			// Initialize WC_Coupon
			$coupon = new \WC_Coupon();
			$coupon->set_code( $code );
			$coupon->set_amount( $amount );
			$coupon->set_discount_type( $discount_type );
			
			// Constraints
			$coupon->set_usage_limit( 1 );                  // Single-use only
			$coupon->set_individual_use( true );            // Cannot combine with other coupons
			$coupon->set_apply_before_tax( true );
			
			if ( $expiry_date ) {
				$coupon->set_date_expires( $expiry_date );
			}

			// Add descriptions and meta
			$coupon->set_description( sprintf( __( 'Satın alınan hediye kuponu. Sipariş #%d', 'sale-coupon' ), $order_id ) );
			
			// Custom tracking meta
			$coupon->update_meta_data( '_sc_purchased_by', $purchaser_id );
			$coupon->update_meta_data( '_sc_order_id', $order_id );

			// Save to database
			$coupon->save();

			return $coupon;

		} catch ( \Exception $e ) {
			// Log error if WooCommerce logging is available
			if ( function_exists( 'wc_get_logger' ) ) {
				wc_get_logger()->error( 
					sprintf( 'Sale Coupon creation failed: %s', $e->getMessage() ), 
					[ 'source' => 'sale-coupon' ] 
				);
			}
			return false;
		}
	}
}
