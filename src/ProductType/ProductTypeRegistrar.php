<?php
namespace SaleCoupon\ProductType;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the custom product type within WooCommerce.
 */
class ProductTypeRegistrar {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_filter( 'product_type_selector', [ $this, 'add_product_type' ] );
		add_filter( 'woocommerce_product_class', [ $this, 'set_product_class' ], 10, 2 );
		add_action( 'init', [ $this, 'register_product_type_taxonomy' ] );
	}

	/**
	 * Add custom product type to the WooCommerce admin dropdown selector.
	 *
	 * @param array $types Existing product types.
	 * @return array
	 */
	public function add_product_type( $types ) {
		$types['sale_coupon'] = __( 'Kupon Ürünü', 'sale-coupon' );
		return $types;
	}

	/**
	 * Map the product type string 'sale_coupon' to the custom PHP class.
	 *
	 * @param string $classname    Standard classname.
	 * @param string $product_type Product type key.
	 * @return string
	 */
	public function set_product_class( $classname, $product_type ) {
		if ( $product_type === 'sale_coupon' ) {
			return 'SaleCoupon\ProductType\CouponProductType';
		}
		return $classname;
	}

	/**
	 * Register 'sale_coupon' term under 'product_type' taxonomy.
	 * WooCommerce uses product_type taxonomy for product types.
	 */
	public function register_product_type_taxonomy() {
		if ( ! taxonomy_exists( 'product_type' ) ) {
			return;
		}

		if ( ! term_exists( 'sale_coupon', 'product_type' ) ) {
			wp_insert_term( 'sale_coupon', 'product_type' );
		}
	}
}
