/**
 * WooCommerce Admin Panel interactions for Sale Coupon product type.
 */
jQuery( function( $ ) {
	// Add class show_if_sale_coupon to general tab so it stays visible.
	$( '.general_options_tab' ).addClass( 'show_if_sale_coupon' );

	/**
	 * Toggle WooCommerce product metabox sections based on selection.
	 *
	 * @param {string} type Selected product type.
	 */
	function handleProductTypeChange( type ) {
		if ( type === 'sale_coupon' ) {
			// Hide regular and sale pricing input containers.
			$( '.pricing' ).hide();
			$( '.pricing_options' ).hide();

			// Hide downloadable/virtual top-bar checkboxes on general simple options.
			$( '.show_if_simple' ).find( 'input[type="checkbox"]' ).closest( 'label' ).hide();

			// Hide inventory/shipping tabs as they are not needed.
			$( '.inventory_options_tab' ).hide();
			$( '.shipping_options_tab' ).hide();
			$( '.linked_product_options_tab' ).hide();
			$( '.attribute_options_tab' ).hide();
			$( '.advanced_options_tab' ).hide();
		} else {
			// Restore default settings.
			$( '.pricing' ).show();
			$( '.pricing_options' ).show();
			$( '.show_if_simple' ).find( 'input[type="checkbox"]' ).closest( 'label' ).show();

			$( '.inventory_options_tab' ).show();
			$( '.shipping_options_tab' ).show();
			$( '.linked_product_options_tab' ).show();
			$( '.attribute_options_tab' ).show();
			$( '.advanced_options_tab' ).show();
		}
	}

	// Hook into WooCommerce change trigger.
	$( 'body' ).on( 'woocommerce-product-type-change', function( event, select_val ) {
		handleProductTypeChange( select_val );
	} );

	// Trigger initial check on document load.
	$( document ).ready( function() {
		const currentType = $( '#product-type' ).val();
		handleProductTypeChange( currentType );
	} );
} );
