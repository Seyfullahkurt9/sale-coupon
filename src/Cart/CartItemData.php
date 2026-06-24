<?php
namespace SaleCoupon\Cart;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles adding and persisting custom data (coupon amount) on cart items.
 */
class CartItemData {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 3 );
		add_filter( 'woocommerce_get_cart_item_from_session', [ $this, 'get_cart_item_from_session' ], 10, 2 );
		add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'checkout_create_order_line_item' ], 10, 4 );
	}

	/**
	 * Add custom amount data to the cart item array.
	 *
	 * @param array $cart_item_data Cart item meta data.
	 * @param int   $product_id     Product ID.
	 * @param int   $variation_id   Variation ID.
	 * @return array
	 */
	public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
		if ( isset( $_POST['sc_coupon_amount'] ) ) {
			$amount = floatval( $_POST['sc_coupon_amount'] );
			if ( $amount > 0 ) {
				$cart_item_data['sc_coupon_amount'] = $amount;
				// Add unique key to prevent merging separate items if needed, but sold individually covers this.
				$cart_item_data['unique_key'] = md5( microtime() . $product_id );
			}
		}
		return $cart_item_data;
	}

	/**
	 * Retrieve custom amount data from session and load it into the cart object.
	 *
	 * @param array $cart_item Cart item array.
	 * @param array $values    Session values.
	 * @return array
	 */
	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( isset( $values['sc_coupon_amount'] ) ) {
			$cart_item['sc_coupon_amount'] = floatval( $values['sc_coupon_amount'] );
		}
		return $cart_item;
	}

	/**
	 * Display custom amount metadata in Cart and Checkout templates.
	 *
	 * @param array $item_data Existing item metadata.
	 * @param array $cart_item Cart item array.
	 * @return array
	 */
	public function get_item_data( $item_data, $cart_item ) {
		if ( isset( $cart_item['sc_coupon_amount'] ) ) {
			$item_data[] = [
				'key'     => __( 'Kupon Tutarı', 'sale-coupon' ),
				'value'   => wc_price( $cart_item['sc_coupon_amount'] ),
				'display' => '',
			];
		}
		return $item_data;
	}

	/**
	 * Persist custom cart item data to order line items during checkout.
	 *
	 * @param \WC_Order_Item_Product $item           Order item object.
	 * @param string                 $cart_item_key  Cart item key.
	 * @param array                  $values         Cart item values.
	 * @param \WC_Order              $order          Order object.
	 */
	public function checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['sc_coupon_amount'] ) ) {
			$item->update_meta_data( '_sc_coupon_amount', floatval( $values['sc_coupon_amount'] ) );
		}
	}
}
