<?php
namespace SaleCoupon\API;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles REST API endpoint logic for retrieving coupons.
 */
class CouponEndpoint {

	/**
	 * Retrieve coupons purchased by the current user.
	 *
	 * @param \WP_REST_Request $request REST request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function get_user_coupons( $request ) {
		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			return new \WP_Error( 'rest_forbidden', __( 'Bu işlem için giriş yapmalısınız.', 'sale-coupon' ), [ 'status' => 401 ] );
		}

		$page     = $request->get_param( 'page' ) ? intval( $request->get_param( 'page' ) ) : 1;
		$per_page = $request->get_param( 'per_page' ) ? intval( $request->get_param( 'per_page' ) ) : 10;

		// Query WC coupons using WooCommerce CRUD wc_get_coupons
		$query_args = [
			'limit'      => $per_page,
			'page'       => $page,
			'paginate'   => true,
			'meta_key'   => '_sc_purchased_by',
			'meta_value' => $user_id,
			'orderby'    => 'date_created',
			'order'      => 'DESC',
		];

		$results = wc_get_coupons( $query_args );
		$coupons = $results->coupons;
		$total   = $results->total;

		$formatted_coupons = [];

		foreach ( $coupons as $coupon ) {
			$id            = $coupon->get_id();
			$code          = $coupon->get_code();
			$amount        = $coupon->get_amount();
			$discount_type = $coupon->get_discount_type();
			$usage_count   = $coupon->get_usage_count();
			$usage_limit   = $coupon->get_usage_limit();
			$date_expires  = $coupon->get_date_expires();
			$date_created  = $coupon->get_date_created();
			$order_id      = $coupon->get_meta( '_sc_order_id' );

			// Determine status
			$status = 'active';
			if ( $usage_limit > 0 && $usage_count >= $usage_limit ) {
				$status = 'used';
			} elseif ( $date_expires && $date_expires->getTimestamp() < time() ) {
				$status = 'expired';
			}

			$formatted_coupons[] = [
				'id'            => $id,
				'code'          => $code,
				'amount'        => floatval( $amount ),
				'currency'      => get_woocommerce_currency(),
				'discount_type' => $discount_type,
				'status'        => $status,
				'date_expires'  => $date_expires ? $date_expires->date( 'c' ) : null,
				'date_created'  => $date_created ? $date_created->date( 'c' ) : null,
				'usage_count'   => $usage_count,
				'usage_limit'   => $usage_limit,
				'order_id'      => intval( $order_id ),
			];
		}

		return new \WP_REST_Response( [
			'coupons'  => $formatted_coupons,
			'total'    => intval( $total ),
			'page'     => intval( $page ),
			'per_page' => intval( $per_page ),
		], 200 );
	}
}
