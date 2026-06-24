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

		// Query WC coupons using WP_Query as shop_coupon post type
		$query_args = [
			'post_type'      => 'shop_coupon',
			'post_status'    => 'any',
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'meta_query'     => [
				[
					'key'     => '_sc_purchased_by',
					'value'   => $user_id,
					'compare' => '=',
				],
			],
			'orderby'        => 'date',
			'order'          => 'DESC',
		];

		$query        = new \WP_Query( $query_args );
		$coupon_posts = $query->posts;
		$total        = $query->found_posts;

		$formatted_coupons = [];

		foreach ( $coupon_posts as $post ) {
			$coupon        = new \WC_Coupon( $post->ID );
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
