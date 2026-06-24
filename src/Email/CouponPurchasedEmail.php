<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom WooCommerce Email for Coupon Purchase.
 */
class WC_Email_Coupon_Purchased extends WC_Email {

	/**
	 * Order object.
	 *
	 * @var \WC_Order
	 */
	public $order;

	/**
	 * Coupon object.
	 *
	 * @var \WC_Coupon
	 */
	public $coupon;

	/**
	 * Coupon code.
	 *
	 * @var string
	 */
	public $coupon_code;

	/**
	 * Coupon amount.
	 *
	 * @var float
	 */
	public $coupon_amount;

	/**
	 * Expiry date.
	 *
	 * @var string
	 */
	public $expiry_date;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id             = 'coupon_purchased';
		$this->title          = __( 'Satın Alınan Kupon Bilgisi', 'sale-coupon' );
		$this->description    = __( 'Kupon satın alma işlemi tamamlandığında müşteriye kupon kodunu iletmek üzere gönderilir.', 'sale-coupon' );
		$this->template_html  = 'emails/coupon-purchased.php';
		$this->template_base  = SALE_COUPON_PATH . 'templates/';

		// Trigger settings.
		$this->heading        = __( 'Hediye Kuponunuz Hazır!', 'sale-coupon' );
		$this->subject        = __( 'Siparişiniz Tamamlandı - Hediye Kuponunuz Ektedir', 'sale-coupon' );

		// Call parent constructor.
		parent::__construct();
	}

	/**
	 * Trigger the sending of this email.
	 *
	 * @param \WC_Order  $order  Order object.
	 * @param \WC_Coupon $coupon Coupon object.
	 * @param float      $amount Coupon amount.
	 */
	public function trigger( $order, $coupon, $amount ) {
		$this->setup_locale();

		if ( is_a( $order, 'WC_Order' ) ) {
			$this->object        = $order;
			$this->order         = $order;
			$this->recipient     = $order->get_billing_email();
			$this->coupon        = $coupon;
			$this->coupon_code   = $coupon->get_code();
			$this->coupon_amount = floatval( $amount );

			$expiry = $coupon->get_date_expires();
			$this->expiry_date   = $expiry ? $expiry->date_i18n( get_option( 'date_format' ) ) : __( 'Sınırsız', 'sale-coupon' );
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );

		$this->restore_locale();
	}

	/**
	 * Get HTML content.
	 *
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html(
			$this->template_html,
			[
				'order'         => $this->order,
				'coupon'        => $this->coupon,
				'coupon_code'   => $this->coupon_code,
				'coupon_amount' => $this->coupon_amount,
				'expiry_date'   => $this->expiry_date,
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => false,
				'plain_text'    => false,
				'email'         => $this,
			],
			'',
			$this->template_base
		);
	}

	/**
	 * Initialise Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = [
			'enabled' => [
				'title'   => __( 'Etkinleştir/Devre Dışı Bırak', 'sale-coupon' ),
				'type'    => 'checkbox',
				'label'   => __( 'Bu e-posta bildirimini etkinleştir', 'sale-coupon' ),
				'default' => 'yes',
			],
			'subject' => [
				'title'       => __( 'Konu', 'sale-coupon' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( __( 'Varsayılan konu: %s', 'sale-coupon' ), $this->subject ),
				'placeholder' => $this->subject,
				'default'     => '',
			],
			'heading' => [
				'title'       => __( 'E-posta Başlığı', 'sale-coupon' ),
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => sprintf( __( 'Varsayılan başlık: %s', 'sale-coupon' ), $this->heading ),
				'placeholder' => $this->heading,
				'default'     => '',
			],
			'email_type' => [
				'title'       => __( 'E-posta tipi', 'sale-coupon' ),
				'type'        => 'select',
				'description' => __( 'Hangi e-posta tipinin gönderileceğini seçin.', 'sale-coupon' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => [
					'html' => __( 'HTML', 'sale-coupon' ),
				],
			],
		];
	}
}

return new WC_Email_Coupon_Purchased();
