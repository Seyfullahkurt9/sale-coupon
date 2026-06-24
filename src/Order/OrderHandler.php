<?php
namespace SaleCoupon\Order;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SaleCoupon\Coupon\CouponGenerator;

/**
 * Handles generating coupon codes when an order with a sale_coupon product is completed.
 */
class OrderHandler {

	/**
	 * Register hooks.
	 */
	public function register() {
		// Run when order is marked completed.
		add_action( 'woocommerce_order_status_completed', [ $this, 'process_order_coupons' ], 10, 1 );
	}

	/**
	 * Scans order for sale_coupon products, generates coupons, and sends notification.
	 *
	 * @param int $order_id WC Order ID.
	 */
	public function process_order_coupons( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}

		$purchaser_id = $order->get_customer_id();

		// Loop through order items.
		foreach ( $order->get_items() as $item_id => $item ) {
			$product = $item->get_product();
			if ( ! $product || $product->get_type() !== 'sale_coupon' ) {
				continue;
			}

			// Idempotency check: verify if coupon has already been generated.
			$existing_coupon_code = $item->get_meta( '_sc_generated_coupon_code' );
			if ( ! empty( $existing_coupon_code ) ) {
				continue;
			}

			// Get the purchased coupon amount.
			$amount = $item->get_meta( '_sc_coupon_amount' );
			if ( empty( $amount ) ) {
				// Fallback to line item subtotal if meta is missing.
				$amount = $item->get_subtotal();
			}

			$amount = floatval( $amount );
			if ( $amount <= 0 ) {
				continue;
			}

			// Get product level configuration overrides.
			$product_id = $product->get_id();
			$prefix        = get_post_meta( $product_id, '_sc_product_prefix', true );
			$expiry_days   = get_post_meta( $product_id, '_sc_product_expiry_days', true );
			$discount_type = get_post_meta( $product_id, '_sc_product_discount_type', true );

			// Call generator with config overrides.
			$coupon = CouponGenerator::generate( $amount, [
				'prefix'        => $prefix,
				'expiry_days'   => $expiry_days,
				'discount_type' => $discount_type,
				'purchaser_id'  => $purchaser_id,
				'order_id'      => $order_id,
			] );

			if ( $coupon ) {
				$coupon_code = $coupon->get_code();
				$coupon_id   = $coupon->get_id();

				// Save generated coupon details to the order item.
				$item->update_meta_data( '_sc_generated_coupon_code', $coupon_code );
				$item->update_meta_data( '_sc_generated_coupon_id', $coupon_id );
				$item->save();

				// Add order note for administrator audit trail.
				$order->add_order_note( 
					sprintf( 
						__( 'Kupon başarıyla oluşturuldu. Kod: %1$s, Tutar: %2$s', 'sale-coupon' ), 
						$coupon_code, 
						wc_price( $amount ) 
					) 
				);

				// Send the email notification if enabled.
				if ( get_option( 'sc_email_enabled', 'yes' ) === 'yes' ) {
					$this->send_coupon_email( $order, $coupon, $amount );
				}
			}
		}
	}

	/**
	 * Triggers the custom WooCommerce email for the purchased coupon.
	 *
	 * @param \WC_Order  $order  WC Order.
	 * @param \WC_Coupon $coupon WC Coupon.
	 * @param float      $amount Coupon amount.
	 */
	protected function send_coupon_email( $order, $coupon, $amount ) {
		$emails = WC()->mailer()->get_emails();
		if ( isset( $emails['WC_Email_Coupon_Purchased'] ) ) {
			$emails['WC_Email_Coupon_Purchased']->trigger( $order, $coupon, $amount );
		}
	}
}
