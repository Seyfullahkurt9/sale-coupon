<?php
namespace SaleCoupon\API;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers WordPress REST API routes for the plugin.
 */
class RestController {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register REST routes.
	 */
	public function register_routes() {
		$namespace = 'sale-coupon/v1';

		// Get user's purchased coupons
		register_rest_route( $namespace, '/coupons', [
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ 'SaleCoupon\API\CouponEndpoint', 'get_user_coupons' ],
			'permission_callback' => [ $this, 'permissions_check_logged_in' ],
		] );

		// Public settings (e.g. min, max, currency)
		register_rest_route( $namespace, '/settings/public', [
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ $this, 'get_public_settings' ],
			'permission_callback' => '__return_true',
		] );
	}

	/**
	 * Check if the user is logged in.
	 *
	 * @return bool
	 */
	public function permissions_check_logged_in() {
		return is_user_logged_in();
	}

	/**
	 * Get public configuration settings.
	 *
	 * @return \WP_REST_Response
	 */
	public function get_public_settings() {
		return new \WP_REST_Response( [
			'min_amount' => floatval( get_option( 'sc_min_amount', 10 ) ),
			'max_amount' => floatval( get_option( 'sc_max_amount', 1000 ) ),
			'currency'   => get_woocommerce_currency(),
			'symbol'     => get_woocommerce_currency_symbol(),
		], 200 );
	}
}
