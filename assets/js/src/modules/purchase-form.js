/**
 * Frontend product purchase form interactions.
 * Handles preset button selections, input synchronization, and basic client-side checks.
 */
export function initPurchaseForm() {
	const container = document.querySelector('.sc-purchase-container');
	if ( ! container ) {
		return;
	}

	const presetBtns = container.querySelectorAll('.sc-preset-btn');
	const amountInput = container.querySelector('#sc_coupon_amount');
	const form = container.closest('form.cart');

	if ( ! amountInput ) {
		return;
	}

	// Preset button click events
	presetBtns.forEach(btn => {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			const val = parseFloat(this.getAttribute('data-value'));
			if ( ! isNaN(val) ) {
				amountInput.value = val;
				
				// Toggle active class state
				presetBtns.forEach(b => b.classList.remove('active'));
				this.classList.add('active');
			}
		});
	});

	// De-select preset button if user types in the input manually
	amountInput.addEventListener('input', function() {
		presetBtns.forEach(b => b.classList.remove('active'));
	});

	// Client-side validation on form submit
	if ( form ) {
		form.addEventListener('submit', function(e) {
			const val = parseFloat(amountInput.value);
			const min = parseFloat(amountInput.getAttribute('min'));
			const max = parseFloat(amountInput.getAttribute('max'));
			const i18n = window.saleCouponData ? window.saleCouponData.i18n : {};

			if ( isNaN(val) ) {
				e.preventDefault();
				alert(i18n.enterAmount || 'Lütfen geçerli bir tutar girin.');
				amountInput.focus();
				return false;
			}

			if ( ! isNaN(min) && val < min ) {
				e.preventDefault();
				alert(i18n.minError || 'Girdiğiniz tutar minimum limitin altındadır.');
				amountInput.focus();
				return false;
			}

			if ( ! isNaN(max) && val > max ) {
				e.preventDefault();
				alert(i18n.maxError || 'Girdiğiniz tutar maksimum limiti aşmaktadır.');
				amountInput.focus();
				return false;
			}
		});
	}
}
