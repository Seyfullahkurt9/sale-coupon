<?php
/**
 * Sale Coupon product add to cart template.
 *
 * @var \WC_Product $product
 * @var float       $min_amount
 * @var float       $max_amount
 * @var array       $presets
 */

defined( 'ABSPATH' ) || exit;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="sc-purchase-container">
			
			<?php if ( ! empty( $presets ) ) : ?>
				<label class="sc-label">
					<?php esc_html_e( 'Kupon Tutarını Seçin', 'sale-coupon' ); ?>
				</label>
				<div class="sc-presets-wrapper">
					<?php foreach ( $presets as $preset ) : ?>
						<button type="button" class="button sc-preset-btn" data-value="<?php echo esc_attr( $preset ); ?>">
							<?php echo wp_kses_post( wc_price( $preset ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<div class="sc-divider">
					<span>
						<?php esc_html_e( 'veya özel tutar girin', 'sale-coupon' ); ?>
					</span>
				</div>
			<?php endif; ?>

			<div class="sc-custom-amount-wrapper">
				<label for="sc_coupon_amount" class="sc-label">
					<?php esc_html_e( 'Kupon Tutarı', 'sale-coupon' ); ?>
				</label>
				<div class="sc-input-wrapper">
					<input 
						type="number" 
						id="sc_coupon_amount" 
						name="sc_coupon_amount" 
						value="" 
						min="<?php echo esc_attr( $min_amount ); ?>" 
						max="<?php echo esc_attr( $max_amount ); ?>" 
						step="any" 
						required 
						placeholder="<?php echo esc_attr( $min_amount ); ?>"
					/>
					<span class="sc-currency-symbol">
						<?php echo esc_html( get_woocommerce_currency_symbol() ); ?>
					</span>
				</div>
				<small class="sc-limits-desc">
					<?php 
					printf( 
						esc_html__( 'Min: %1$s — Max: %2$s', 'sale-coupon' ), 
						wp_kses_post( wc_price( $min_amount ) ), 
						wp_kses_post( wc_price( $max_amount ) ) 
					); 
					?>
				</small>
			</div>
		</div>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />

		<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; 
