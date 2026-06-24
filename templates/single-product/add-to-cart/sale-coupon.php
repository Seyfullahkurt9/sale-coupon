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

		<div class="sc-purchase-container" style="margin-bottom: 20px;">
			
			<?php if ( ! empty( $presets ) ) : ?>
				<label class="sc-label" style="font-weight: 600; display: block; margin-bottom: 10px;">
					<?php esc_html_e( 'Kupon Tutarını Seçin', 'sale-coupon' ); ?>
				</label>
				<div class="sc-presets-wrapper" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
					<?php foreach ( $presets as $preset ) : ?>
						<button type="button" class="button sc-preset-btn" data-value="<?php echo esc_attr( $preset ); ?>" style="padding: 10px 20px;">
							<?php echo esc_html( wc_price( $preset ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<div class="sc-divider" style="margin: 15px 0; border-bottom: 1px dashed #ddd; position: relative; text-align: center;">
					<span style="background: #fff; padding: 0 10px; color: #777; font-size: 12px; position: relative; top: -10px;">
						<?php esc_html_e( 'veya özel tutar girin', 'sale-coupon' ); ?>
					</span>
				</div>
			<?php endif; ?>

			<div class="sc-custom-amount-wrapper" style="margin-top: 20px;">
				<label for="sc_coupon_amount" class="sc-label" style="font-weight: 600; display: block; margin-bottom: 5px;">
					<?php esc_html_e( 'Kupon Tutarı', 'sale-coupon' ); ?>
				</label>
				<div class="sc-input-wrapper" style="position: relative; display: inline-block;">
					<input 
						type="number" 
						id="sc_coupon_amount" 
						name="sc_coupon_amount" 
						value="" 
						min="<?php echo esc_attr( $min_amount ); ?>" 
						max="<?php echo esc_attr( $max_amount ); ?>" 
						step="any" 
						required 
						style="padding-right: 30px; width: 150px;"
						placeholder="<?php echo esc_attr( $min_amount ); ?>"
					/>
					<span class="sc-currency-symbol" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-weight: bold; color: #777;">
						<?php echo esc_html( get_woocommerce_currency_symbol() ); ?>
					</span>
				</div>
				<small class="sc-limits-desc" style="display: block; color: #777; margin-top: 5px;">
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
