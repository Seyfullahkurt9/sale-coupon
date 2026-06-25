<?php
/**
 * Plugin Name:       Sale Coupon
 * Plugin URI:        https://avdini.com/sale-coupon
 * Description:       A modular WooCommerce coupon purchasing plugin allowing customers to buy custom-amount single-use coupons.
 * Version:           1.4.3
 * Author:            Seyfullah Kurt
 * Author URI:        https://github.com/Seyfullahkurt9
 * License:           AGPL-3.0-or-later
 * Text Domain:       sale-coupon
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register a global fatal error and exception logger to catch any checkout crashes
register_shutdown_function( function() {
	$error = error_get_last();
	if ( $error && in_array( $error['type'], [ E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR ] ) ) {
		$log_dir = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
		$log_file = $log_dir . '/uploads/sc-fatal-errors.log';
		$timestamp = date( 'Y-m-d H:i:s' );
		$line = "[{$timestamp}] PHP Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}" . PHP_EOL;
		if ( ! file_exists( $log_dir . '/uploads' ) ) {
			@mkdir( $log_dir . '/uploads', 0755, true );
		}
		@file_put_contents( $log_file, $line, FILE_APPEND | LOCK_EX );
	}
} );

set_exception_handler( function( $exception ) {
	try {
		$log_dir = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
		$log_file = $log_dir . '/uploads/sc-fatal-errors.log';
		$timestamp = date( 'Y-m-d H:i:s' );
		$line = "[{$timestamp}] Uncaught Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
		$line .= "Stack trace:" . PHP_EOL . $exception->getTraceAsString() . PHP_EOL;
		if ( ! @file_exists( $log_dir . '/uploads' ) ) {
			@mkdir( $log_dir . '/uploads', 0755, true );
		}
		@file_put_contents( $log_file, $line, FILE_APPEND | LOCK_EX );
	} catch ( \Throwable $t ) {
		// Ignore errors inside the handler to prevent recursion
	}
	
	restore_exception_handler();
	throw $exception;
} );

// Define plugin constants safely.
if ( ! defined( 'SALE_COUPON_VERSION' ) ) {
	define( 'SALE_COUPON_VERSION', '1.4.3' );
}
if ( ! defined( 'SALE_COUPON_FILE' ) ) {
	define( 'SALE_COUPON_FILE', __FILE__ );
}
if ( ! defined( 'SALE_COUPON_PATH' ) ) {
	define( 'SALE_COUPON_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'SALE_COUPON_URL' ) ) {
	define( 'SALE_COUPON_URL', plugin_dir_url( __FILE__ ) );
}

// Load Composer Autoloader.
if ( file_exists( SALE_COUPON_PATH . 'vendor/autoload.php' ) ) {
	require_once SALE_COUPON_PATH . 'vendor/autoload.php';
} else {
	add_action( 'admin_notices', function() {
		?>
		<div class="error notice">
			<p><?php esc_html_e( 'Sale Coupon eklentisinin bağımlılıkları yüklenmemiş. Lütfen eklenti klasöründe "composer install" komutunu çalıştırın veya doğru yayın paketini (sale-coupon.zip) indirdiğinizden emin olun.', 'sale-coupon' ); ?></p>
		</div>
		<?php
	} );
	return;
}

// Setup GitHub Auto Updater.
if ( class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
	$update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/Seyfullahkurt9/sale-coupon/',
		__FILE__,
		'sale-coupon'
	);
	
	// Force download compiled release asset (sale-coupon.zip) instead of source code zip.
	if ( method_exists( $update_checker, 'getVcsApi' ) ) {
		$update_checker->getVcsApi()->enableReleaseAssets();
	}
}

/**
 * Load plugin textdomain for translation files.
 */
add_action( 'init', function() {
	load_plugin_textdomain( 'sale-coupon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

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
 * Temporary Frontend Product Debugger
 */
add_action( 'wp_head', function() {
	if ( is_product() ) {
		global $post;
		if ( $post ) {
			$product = wc_get_product( $post->ID );
			if ( $product ) {
				echo "\n<!-- SALE COUPON DEBUG: \n";
				echo "Class: " . get_class( $product ) . "\n";
				echo "Type: " . $product->get_type() . "\n";
				echo "Purchasable: " . ( $product->is_purchasable() ? 'YES' : 'NO' ) . "\n";
				echo "In Stock: " . ( $product->is_in_stock() ? 'YES' : 'NO' ) . "\n";
				echo "Price: " . $product->get_price() . "\n";
				echo "-->\n";
			}
		}
	}
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
