<?php
namespace SaleCoupon\ProductType;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds product settings panel and fields for the 'sale_coupon' product type.
 */
class ProductDataPanels {

	/**
	 * Register hooks.
	 */
	public function register() {
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'add_settings_tab' ] );
		add_action( 'woocommerce_product_data_panels', [ $this, 'render_settings_panel' ] );
		add_action( 'woocommerce_process_product_meta_sale_coupon', [ $this, 'save_product_settings' ] );
	}

	/**
	 * Add a custom tab in the Product Data metabox.
	 *
	 * @param array $tabs Existing product data tabs.
	 * @return array
	 */
	public function add_settings_tab( $tabs ) {
		$tabs['sale_coupon'] = [
			'label'    => __( 'Kupon Ayarları', 'sale-coupon' ),
			'target'   => 'sale_coupon_options_panel',
			'class'    => [ 'show_if_sale_coupon' ],
			'priority' => 50,
		];
		return $tabs;
	}

	/**
	 * Render the custom option fields in the Product Data metabox.
	 */
	public function render_settings_panel() {
		global $post;
		?>
		<div id="sale_coupon_options_panel" class="panel woocommerce_options_panel show_if_sale_coupon">
			<div class="options_group">
				<?php
				// Coupon Prefix Override
				woocommerce_wp_text_input( [
					'id'          => '_sc_product_prefix',
					'label'       => __( 'Kupon Ön Eki (Prefix)', 'sale-coupon' ),
					'placeholder' => get_option( 'sc_coupon_prefix', 'GIFT-' ),
					'desc_tip'    => true,
					'description' => __( 'Boş bırakılırsa genel ayarlardaki prefix kullanılır.', 'sale-coupon' ),
				] );



				// Discount Type Override
				woocommerce_wp_select( [
					'id'          => '_sc_product_discount_type',
					'label'       => __( 'İndirim Türü', 'sale-coupon' ),
					'default'     => '',
					'options'     => [
						''              => __( 'Varsayılan (Genel Ayarlar)', 'sale-coupon' ),
						'fixed_cart'    => __( 'Sabit Sepet İndirimi', 'sale-coupon' ),
						'fixed_product' => __( 'Sabit Ürün İndirimi', 'sale-coupon' ),
					],
					'desc_tip'    => true,
					'description' => __( 'Boş bırakılırsa genel ayarlardaki indirim türü kullanılır.', 'sale-coupon' ),
				] );
				?>
			</div>

			<div class="options_group">
				<?php
				// Min Amount Override
				woocommerce_wp_text_input( [
					'id'          => '_sc_product_min_amount',
					'label'       => sprintf( __( 'Min Tutar (%s)', 'sale-coupon' ), get_woocommerce_currency_symbol() ),
					'type'        => 'number',
					'placeholder' => get_option( 'sc_min_amount', '10' ),
					'desc_tip'    => true,
					'description' => __( 'Müşterinin satın alabileceği minimum limit.', 'sale-coupon' ),
					'custom_attributes' => [
						'min'  => 1,
						'step' => 'any',
					],
				] );

				// Max Amount Override
				woocommerce_wp_text_input( [
					'id'          => '_sc_product_max_amount',
					'label'       => sprintf( __( 'Max Tutar (%s)', 'sale-coupon' ), get_woocommerce_currency_symbol() ),
					'type'        => 'number',
					'placeholder' => get_option( 'sc_max_amount', '1000' ),
					'desc_tip'    => true,
					'description' => __( 'Müşterinin satın alabileceği maksimum limit.', 'sale-coupon' ),
					'custom_attributes' => [
						'min'  => 1,
						'step' => 'any',
					],
				] );

				// Preset amounts
				woocommerce_wp_text_input( [
					'id'          => '_sc_product_presets',
					'label'       => __( 'Hazır Tutarlar', 'sale-coupon' ),
					'placeholder' => '25, 50, 100, 250',
					'desc_tip'    => true,
					'description' => __( 'Müşteriye sunulacak hazır tutar butonları. Virgülle ayırarak girin (örn: 25,50,100,250). Boş bırakırsanız sadece serbest giriş alanı gösterilir.', 'sale-coupon' ),
				] );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Save product custom settings.
	 *
	 * @param int $post_id Product/Post ID.
	 */
	public function save_product_settings( $post_id ) {
		$fields = [
			'_sc_product_prefix',
			'_sc_product_discount_type',
			'_sc_product_min_amount',
			'_sc_product_max_amount',
			'_sc_product_presets',
		];

		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				$val = sanitize_text_field( wp_unslash( $_POST[ $field ] ) );
				update_post_meta( $post_id, $field, $val );
			}
		}
	}
}
