<?php
namespace SaleCoupon\Account;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the custom WooCommerce My Account endpoint rewrite rules and query vars.
 */
class EndpointRegistrar {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'init', [ $this, 'add_endpoints' ] );
		add_filter( 'query_vars', [ $this, 'add_query_vars' ], 0 );
	}

	/**
	 * Register the rewrite endpoint for "my-account/my-coupons".
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( 'my-coupons', EP_ROOT | EP_PAGES );
	}

	/**
	 * Register query variable for the custom page endpoint.
	 *
	 * @param array $vars Existing query vars.
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = 'my-coupons';
		return $vars;
	}
}
