<?php
namespace SaleCoupon\Core;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run code during plugin activation.
 */
class Activator {

	/**
	 * Activate hook handler.
	 */
	public static function activate() {
		// Initialize default options.
		self::init_default_options();

		// Flush rewrite rules for the WooCommerce "my-account" endpoint.
		// Register the rewrite rules before flushing.
		add_rewrite_endpoint( 'my-coupons', EP_ROOT | EP_PAGES );
		flush_rewrite_rules();
	}

	/**
	 * Initialize default configuration options.
	 */
	private static function init_default_options() {
		$defaults = [
			'sc_coupon_prefix'     => 'GIFT-',
			'sc_random_length'     => 10,
			'sc_min_amount'        => 10,
			'sc_max_amount'        => 1000,
			'sc_discount_type'     => 'fixed_cart',
			'sc_expiry_days'       => 365,
			'sc_email_enabled'     => 'yes',
		];

		foreach ( $defaults as $option => $value ) {
			if ( false === get_option( $option ) ) {
				update_option( $option, $value );
			}
		}
	}
}
