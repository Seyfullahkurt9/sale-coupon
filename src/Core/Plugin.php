<?php
namespace SaleCoupon\Core;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin class orchestrator.
 */
class Plugin {

	/**
	 * Single instance of the class.
	 *
	 * @var Plugin
	 */
	private static $instance = null;

	/**
	 * Instances of registered modules.
	 *
	 * @var array
	 */
	private $modules = [];

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor. Initializes the plugin.
	 */
	private function __construct() {
		$this->init_modules();
	}

	/**
	 * Initialize all modules.
	 */
	private function init_modules() {
		// Define modules to instantiate and register hooks.
		$modules_to_load = [
			// Core
			'assets'            => 'SaleCoupon\Core\Assets',

			// Admin
			'settings_page'     => 'SaleCoupon\Admin\SettingsPage',
			'coupon_meta_box'   => 'SaleCoupon\Admin\CouponMetaBox',

			// Product Type
			'product_registrar' => 'SaleCoupon\ProductType\ProductTypeRegistrar',
			'product_panels'    => 'SaleCoupon\ProductType\ProductDataPanels',
			'add_to_cart'       => 'SaleCoupon\ProductType\AddToCartHandler',

			// Cart Rules
			'cart_validator'    => 'SaleCoupon\Cart\CartValidator',
			'cart_item_data'    => 'SaleCoupon\Cart\CartItemData',
			'price_override'    => 'SaleCoupon\Cart\PriceOverride',

			// Order Handler
			'order_handler'     => 'SaleCoupon\Order\OrderHandler',

			// Account
			'account_endpoint'  => 'SaleCoupon\Account\EndpointRegistrar',
			'account_menu'      => 'SaleCoupon\Account\MenuExtender',
			'account_list'      => 'SaleCoupon\Account\CouponListRenderer',

			// REST API
			'rest_controller'   => 'SaleCoupon\API\RestController',
		];

		// Instantiate modules
		foreach ( $modules_to_load as $key => $class ) {
			if ( class_exists( $class ) ) {
				$this->modules[ $key ] = new $class();
				
				// Call init or register if it exists on the class
				if ( method_exists( $this->modules[ $key ], 'register' ) ) {
					$this->modules[ $key ]->register();
				}
			}
		}

		// Register custom WooCommerce emails
		add_filter( 'woocommerce_email_classes', [ $this, 'register_emails' ] );
	}

	/**
	 * Register custom email class.
	 *
	 * @param array $email_classes Existing email classes.
	 * @return array
	 */
	public function register_emails( $email_classes ) {
		if ( ! isset( $email_classes['WC_Email_Coupon_Purchased'] ) ) {
			$email_classes['WC_Email_Coupon_Purchased'] = require_once SALE_COUPON_PATH . 'src/Email/CouponPurchasedEmail.php';
		}
		return $email_classes;
	}

	/**
	 * Get module instance.
	 *
	 * @param string $key Module key.
	 * @return mixed|null
	 */
	public function get_module( $key ) {
		return isset( $this->modules[ $key ] ) ? $this->modules[ $key ] : null;
	}
}
