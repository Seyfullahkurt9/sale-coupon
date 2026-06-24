<?php
namespace SaleCoupon\Core;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle script and style loading.
 */
class Assets {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Enqueue frontend CSS and JS.
	 */
	public function enqueue_frontend_assets() {
		$should_enqueue = false;

		// Enqueue on single product page of type sale_coupon.
		if ( is_product() ) {
			global $post;
			if ( $post ) {
				$product = wc_get_product( $post->ID );
				if ( $product && $product->get_type() === 'sale_coupon' ) {
					$should_enqueue = true;
				}
			}
		}

		// Enqueue on WooCommerce account page.
		if ( is_account_page() ) {
			$should_enqueue = true;
		}

		if ( ! $should_enqueue ) {
			return;
		}

		// Enqueue styles.
		wp_enqueue_style(
			'sale-coupon-frontend',
			SALE_COUPON_URL . 'assets/css/frontend.css',
			[],
			SALE_COUPON_VERSION
		);

		// Enqueue scripts.
		// WooCommerce uses @wordpress/api-fetch which is bundled as wp-api-fetch dependency.
		wp_enqueue_script(
			'sale-coupon-frontend',
			SALE_COUPON_URL . 'assets/js/build/frontend.js',
			[ 'jquery', 'wp-api-fetch' ],
			SALE_COUPON_VERSION,
			true
		);

		// Pass data to JS.
		wp_localize_script( 'sale-coupon-frontend', 'saleCouponData', [
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'restUrl'      => esc_url_raw( get_rest_url( null, 'sale-coupon/v1' ) ),
			'restNonce'    => wp_create_nonce( 'wp_rest' ),
			'currency'     => get_woocommerce_currency_symbol(),
			'i18n'         => [
				'copied'      => __( 'Kupon kodu kopyalandı!', 'sale-coupon' ),
				'copy'        => __( 'Kopyala', 'sale-coupon' ),
				'validating'  => __( 'Doğrulanıyor...', 'sale-coupon' ),
				'enterAmount' => __( 'Lütfen geçerli bir tutar girin.', 'sale-coupon' ),
				'minError'    => __( 'Girdiğiniz tutar minimum limitin altındadır.', 'sale-coupon' ),
				'maxError'    => __( 'Girdiğiniz tutar maksimum limiti aşmaktadır.', 'sale-coupon' ),
			]
		] );
	}

	/**
	 * Enqueue admin CSS and JS.
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on edit product pages.
		if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
			return;
		}

		global $post;
		if ( ! $post || $post->post_type !== 'product' ) {
			return;
		}

		wp_enqueue_style(
			'sale-coupon-admin',
			SALE_COUPON_URL . 'assets/css/admin.css',
			[],
			SALE_COUPON_VERSION
		);

		wp_enqueue_script(
			'sale-coupon-admin',
			SALE_COUPON_URL . 'assets/js/build/admin.js',
			[ 'jquery' ],
			SALE_COUPON_VERSION,
			true
		);
	}
}
