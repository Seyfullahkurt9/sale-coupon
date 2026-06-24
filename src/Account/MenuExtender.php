<?php
namespace SaleCoupon\Account;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Injects the custom "Kuponlarım" menu tab into the WooCommerce My Account sidebar menu.
 */
class MenuExtender {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_filter( 'woocommerce_account_menu_items', [ $this, 'add_menu_item' ] );
	}

	/**
	 * Add "Kuponlarım" link into the WooCommerce account sidebar navigation links array.
	 * Places the item right before the logout button.
	 *
	 * @param array $items Existing account navigation items.
	 * @return array
	 */
	public function add_menu_item( $items ) {
		$new_items = [];

		foreach ( $items as $key => $value ) {
			if ( $key === 'customer-logout' ) {
				$new_items['my-coupons'] = __( 'Kuponlarım', 'sale-coupon' );
			}
			$new_items[ $key ] = $value;
		}

		// Fallback check if logout isn't found
		if ( ! isset( $new_items['my-coupons'] ) ) {
			$new_items['my-coupons'] = __( 'Kuponlarım', 'sale-coupon' );
		}

		return $new_items;
	}
}
