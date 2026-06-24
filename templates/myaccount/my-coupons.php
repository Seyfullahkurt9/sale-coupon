<?php
/**
 * My Account My Coupons template page.
 */

defined( 'ABSPATH' ) || exit;
?>

<h3><?php esc_html_e( 'Satın Aldığım Kuponlar', 'sale-coupon' ); ?></h3>
<p><?php esc_html_e( 'Burada daha önce satın aldığınız tek kullanımlık hediye kuponlarını bulabilir ve kodlarını kopyalayarak alışverişlerinizde kullanabilirsiniz.', 'sale-coupon' ); ?></p>

<div id="sc-coupon-app">
	<div class="sc-loading-spinner" style="padding: 20px 0; text-align: center; color: #666;">
		<div class="sc-spinner" style="display:inline-block; width: 30px; height: 30px; border: 3px solid #ccc; border-top-color: #7f54b3; border-radius: 50%; animation: sc-spin 1s linear infinite; margin-right: 10px; vertical-align: middle;"></div>
		<span style="vertical-align: middle; font-weight: 600;"><?php esc_html_e( 'Kuponlar yükleniyor...', 'sale-coupon' ); ?></span>
	</div>
</div>

<style>
@keyframes sc-spin {
	to { transform: rotate(360deg); }
}
</style>
