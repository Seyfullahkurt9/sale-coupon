<?php
namespace SaleCoupon\Admin;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce settings tab for Sale Coupon.
 */
class SettingsPage {

	/**
	 * Settings page ID.
	 *
	 * @var string
	 */
	protected $id = 'sale_coupon';

	/**
	 * Register hooks.
	 */
	public function register() {
		add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_settings_tab' ], 50 );
		add_action( 'woocommerce_settings_tabs_' . $this->id, [ $this, 'settings_tab_content' ] );
		add_action( 'woocommerce_update_options_' . $this->id, [ $this, 'update_settings' ] );
	}

	/**
	 * Add the tab to WooCommerce settings.
	 *
	 * @param array $settings_tabs Array of settings tabs.
	 * @return array
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs[ $this->id ] = __( 'Sale Coupon', 'sale-coupon' );
		return $settings_tabs;
	}

	/**
	 * Output settings page content.
	 */
	public function settings_tab_content() {
		woocommerce_admin_fields( $this->get_settings() );
	}

	/**
	 * Save/update settings.
	 */
	public function update_settings() {
		woocommerce_update_options( $this->get_settings() );
	}

	/**
	 * Define plugin settings fields.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = [
			[
				'title' => __( 'Kupon Satış Eklentisi Ayarları', 'sale-coupon' ),
				'type'  => 'title',
				'desc'  => __( 'Müşterilere satılacak olan kuponların varsayılan yapılandırma ayarlarını buradan yapabilirsiniz.', 'sale-coupon' ),
				'id'    => 'sc_settings_title',
			],
			[
				'title'             => __( 'Kupon Kod Ön Eki (Prefix)', 'sale-coupon' ),
				'desc'              => __( 'Oluşturulacak kuponların başına eklenecek varsayılan ön ek (örn: GIFT-). Ürün bazlı ayarlanmazsa bu kullanılır.', 'sale-coupon' ),
				'id'                => 'sc_coupon_prefix',
				'type'              => 'text',
				'default'           => 'GIFT-',
				'css'               => 'min-width: 150px;',
				'custom_attributes' => [ 'required' => 'required' ],
			],
			[
				'title'             => __( 'Rastgele Karakter Uzunluğu', 'sale-coupon' ),
				'desc'              => __( 'Kupon kodundaki rastgele karakter sayısı (Güvenlik için minimum 8 olmalıdır).', 'sale-coupon' ),
				'id'                => 'sc_random_length',
				'type'              => 'number',
				'default'           => 10,
				'css'               => 'width: 80px;',
				'custom_attributes' => [
					'min'  => 8,
					'step' => 1,
				],
			],
			[
				'title'             => __( 'Minimum Kupon Tutarı', 'sale-coupon' ),
				'desc'              => __( 'Müşterinin satın alabileceği minimum kupon tutarı (Mağaza para biriminde).', 'sale-coupon' ),
				'id'                => 'sc_min_amount',
				'type'              => 'number',
				'default'           => 10,
				'css'               => 'width: 100px;',
				'custom_attributes' => [
					'min'  => 1,
					'step' => 'any',
				],
			],
			[
				'title'             => __( 'Maksimum Kupon Tutarı', 'sale-coupon' ),
				'desc'              => __( 'Müşterinin satın alabileceği maksimum kupon tutarı (Mağaza para biriminde).', 'sale-coupon' ),
				'id'                => 'sc_max_amount',
				'type'              => 'number',
				'default'           => 1000,
				'css'               => 'width: 100px;',
				'custom_attributes' => [
					'min'  => 1,
					'step' => 'any',
				],
			],
			[
				'title'   => __( 'İndirim Türü', 'sale-coupon' ),
				'desc'    => __( 'Oluşturulacak kuponların varsayılan indirim türü.', 'sale-coupon' ),
				'id'      => 'sc_discount_type',
				'type'    => 'select',
				'default' => 'fixed_cart',
				'options' => [
					'fixed_cart'    => __( 'Sabit Sepet İndirimi (Önerilen)', 'sale-coupon' ),
					'fixed_product' => __( 'Sabit Ürün İndirimi', 'sale-coupon' ),
				],
				'class'   => 'wc-enhanced-select',
			],

			[
				'title'   => __( 'E-posta Bildirimi', 'sale-coupon' ),
				'desc'    => __( 'Kupon başarıyla oluşturulduğunda müşteriye kupon kodunu içeren e-posta gönderilsin.', 'sale-coupon' ),
				'id'      => 'sc_email_enabled',
				'type'    => 'checkbox',
				'default' => 'yes',
			],
			[
				'type' => 'sectionend',
				'id'   => 'sc_settings_section_end',
			],
		];

		return apply_filters( 'woocommerce_sale_coupon_settings', $settings );
	}
}
