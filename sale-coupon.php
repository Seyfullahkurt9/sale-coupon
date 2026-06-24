<?php
/**
 * Plugin Name:       Sale Coupon
 * Plugin URI:        https://avdini.com/sale-coupon
 * Description:       A modular WooCommerce coupon purchasing plugin allowing customers to buy custom-amount single-use coupons.
 * Version:           1.0.0
 * Author:            Fikri
 * Author URI:        https://avdini.com
 * License:           GPL-2.0+
 * Text Domain:       sale-coupon
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'SALE_COUPON_VERSION', '1.0.0' );
define( 'SALE_COUPON_FILE', __FILE__ );
define( 'SALE_COUPON_PATH', plugin_dir_path( __FILE__ ) );
define( 'SALE_COUPON_URL', plugin_dir_url( __FILE__ ) );

// Load Composer Autoloader.
if ( file_exists( SALE_COUPON_PATH . 'vendor/autoload.php' ) ) {
	require_once SALE_COUPON_PATH . 'vendor/autoload.php';
}

/**
 * Register Activation and Deactivation hooks.
 */
register_activation_hook( __FILE__, [ 'SaleCoupon\Core\Activator', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'SaleCoupon\Core\Deactivator', 'deactivate' ] );

/**
 * Initialize the plugin after WooCommerce is loaded.
 */
add_action( 'woocommerce_loaded', function() {
	// Start the plugin.
	\SaleCoupon\Core\Plugin::instance();
} );

/**
 * WooCommerce dependency check notice.
 */
add_action( 'admin_init', function() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) && ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', function() {
			?>
			<div class="error notice is-dismissible">
				<p><?php esc_html_e( 'Sale Coupon requires WooCommerce to be installed and active.', 'sale-coupon' ); ?></p>
			</div>
			<?php
		} );

		// Deactivate itself if WooCommerce is not active.
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
} );
