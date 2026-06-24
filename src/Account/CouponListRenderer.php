<?php
namespace SaleCoupon\Account;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles rendering the custom tab content container in My Account section.
 */
class CouponListRenderer {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'woocommerce_account_my-coupons_endpoint', [ $this, 'render_endpoint_content' ] );
	}

	/**
	 * Render the endpoint template.
	 */
	public function render_endpoint_content() {
		wc_get_template(
			'myaccount/my-coupons.php',
			[],
			'',
			SALE_COUPON_PATH . 'templates/'
		);
	}
}
