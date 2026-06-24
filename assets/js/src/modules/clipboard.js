/**
 * Handle clipboard copying of coupon codes.
 */
export function initClipboard() {
	document.addEventListener('click', function(event) {
		const btn = event.target.closest('.sc-copy-btn');
		if (!btn) {
			return;
		}

		const code = btn.getAttribute('data-code');
		if (!code) {
			return;
		}

		// Use modern clipboard API
		navigator.clipboard.writeText(code).then(() => {
			const originalHtml = btn.innerHTML;
			const copiedText = window.saleCouponData && window.saleCouponData.i18n ? window.saleCouponData.i18n.copied : 'Kopyalandı!';
			
			btn.innerHTML = `<span class="dashicons dashicons-yes" style="vertical-align:middle; font-size:16px;"></span> ${copiedText}`;
			btn.classList.add('copied');
			btn.setAttribute('disabled', 'disabled');

			setTimeout(() => {
				btn.innerHTML = originalHtml;
				btn.classList.remove('copied');
				btn.removeAttribute('disabled');
			}, 2000);
		}).catch(err => {
			console.error('Could not copy coupon code: ', err);
		});
	});
}
