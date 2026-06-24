<?php
namespace SaleCoupon\Admin;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle custom columns and badges for WooCommerce Coupons in the admin panel.
 */
class CouponMetaBox {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_filter( 'manage_edit-shop_coupon_columns', [ $this, 'add_custom_columns' ] );
		add_action( 'manage_shop_coupon_posts_custom_column', [ $this, 'render_custom_columns' ], 10, 2 );
	}

	/**
	 * Add custom columns to the shop_coupon list table.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_custom_columns( $columns ) {
		// Insert "Satış Bilgisi" before the expiry date column if possible, or just append it.
		$new_columns = [];
		foreach ( $columns as $key => $column ) {
			if ( $key === 'expiry_date' ) {
				$new_columns['sc_sale_info'] = __( 'Satış Bilgisi', 'sale-coupon' );
			}
			$new_columns[ $key ] = $column;
		}

		if ( ! isset( $new_columns['sc_sale_info'] ) ) {
			$new_columns['sc_sale_info'] = __( 'Satış Bilgisi', 'sale-coupon' );
		}

		return $new_columns;
	}

	/**
	 * Render custom column content.
	 *
	 * @param string $column_name Column key.
	 * @param int    $post_id     Post/Coupon ID.
	 */
	public function render_custom_columns( $column_name, $post_id ) {
		if ( $column_name !== 'sc_sale_info' ) {
			return;
		}

		$coupon       = new \WC_Coupon( $post_id );
		$purchased_by = $coupon->get_meta( '_sc_purchased_by' );
		$order_id     = $coupon->get_meta( '_sc_order_id' );

		if ( ! $purchased_by && ! $order_id ) {
			echo '<span class="na">&ndash;</span>';
			return;
		}

		echo '<div class="sc-sale-info-badge" style="display:inline-block; padding: 2px 6px; background:#e5e5e5; color:#3c434a; border-radius:3px; font-weight:600; font-size:11px; margin-bottom: 5px;">' . esc_html__( 'Satılan Kupon', 'sale-coupon' ) . '</div>';

		if ( $purchased_by ) {
			$user = get_userdata( $purchased_by );
			if ( $user ) {
				$user_link = admin_url( 'user-edit.php?user_id=' . $purchased_by );
				echo '<div class="sc-purchaser-info" style="font-size:12px;">';
				echo '<strong>' . esc_html__( 'Alıcı:', 'sale-coupon' ) . '</strong> ';
				echo '<a href="' . esc_url( $user_link ) . '">' . esc_html( $user->display_name ) . '</a>';
				echo '</div>';
			}
		}

		if ( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				echo '<div class="sc-order-info" style="font-size:12px; margin-top:2px;">';
				echo '<strong>' . esc_html__( 'Sipariş:', 'sale-coupon' ) . '</strong> ';
				echo '<a href="' . esc_url( $order->get_edit_order_url() ) . '">#' . esc_html( $order->get_order_number() ) . '</a>';
				echo '</div>';
			}
		}
	}
}
