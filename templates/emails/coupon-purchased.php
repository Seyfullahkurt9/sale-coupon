<?php
/**
 * Coupon purchased email template (HTML).
 *
 * @var \WC_Order  $order
 * @var \WC_Coupon $coupon
 * @var string     $coupon_code
 * @var float      $coupon_amount
 * @var string     $expiry_date
 * @var string     $email_heading
 * @var bool       $sent_to_admin
 * @var bool       $plain_text
 * @var \WC_Email  $email
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( 'Merhaba %s,', 'sale-coupon' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<p><?php esc_html_e( 'Kupon satın alma işleminiz başarıyla tamamlandı. Satın aldığınız kupon kodunu ve ayrıntılarını aşağıda bulabilirsiniz:', 'sale-coupon' ); ?></p>

<div style="background-color: #f7f7f7; border: 1px solid #e5e5e5; padding: 20px; text-align: center; margin: 30px 0; border-radius: 5px;">
	<h2 style="margin: 0 0 10px 0; font-size: 24px; color: #111; letter-spacing: 1px; font-family: monospace;">
		<?php echo esc_html( $coupon_code ); ?>
	</h2>
	<p style="margin: 0; font-size: 20px; font-weight: bold; color: #7f54b3;">
		<?php echo wp_kses_post( wc_price( $coupon_amount ) ); ?>
	</p>
</div>

<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee; margin-bottom: 20px; font-size: 14px;" border="1">
	<tbody>
		<tr>
			<th scope="row" style="text-align: left; border: 1px solid #eee; background-color: #fdfdfd;"><?php esc_html_e( 'Sipariş No:', 'sale-coupon' ); ?></th>
			<td style="text-align: left; border: 1px solid #eee;"><?php echo esc_html( $order->get_order_number() ); ?></td>
		</tr>

		<tr>
			<th scope="row" style="text-align: left; border: 1px solid #eee; background-color: #fdfdfd;"><?php esc_html_e( 'Kullanım Koşulu:', 'sale-coupon' ); ?></th>
			<td style="text-align: left; border: 1px solid #eee;"><?php esc_html_e( 'Tek kullanımlıktır ve başka kuponlarla birleştirilemez.', 'sale-coupon' ); ?></td>
		</tr>
	</tbody>
</table>

<p><?php esc_html_e( 'Kuponunuzu alışveriş yaparken ödeme sayfasında kupon kodu alanına girerek kullanabilirsiniz.', 'sale-coupon' ); ?></p>
<p><?php esc_html_e( 'Bizi tercih ettiğiniz için teşekkür ederiz!', 'sale-coupon' ); ?></p>

<?php
do_action( 'woocommerce_email_footer', $email );
