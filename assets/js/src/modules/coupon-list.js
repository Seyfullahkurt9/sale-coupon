import apiClient from './api-client';

/**
 * Handles rendering the coupon list in WooCommerce My Account "Kuponlarım" section.
 */
class CouponList {
	constructor() {
		this.container = document.querySelector('#sc-coupon-app');
		this.currentPage = 1;
		this.perPage = 10;
	}

	init() {
		if ( ! this.container ) {
			return;
		}

		this.loadCoupons();
	}

	/**
	 * Load coupons via REST API.
	 */
	loadCoupons() {
		apiClient.getUserCoupons(this.currentPage, this.perPage)
			.then(response => {
				this.render(response);
			})
			.catch(err => {
				console.error('Error fetching coupons:', err);
				this.renderError();
			});
	}

	/**
	 * Render the list or notice.
	 *
	 * @param {object} data REST API response containing coupons, total, page, per_page.
	 */
	render(data) {
		const { coupons, total } = data;

		if ( ! coupons || coupons.length === 0 ) {
			this.container.innerHTML = `
				<div class="sc-no-coupons" style="padding: 30px; text-align: center; background: #fafafa; border: 1px solid #eee; border-radius: 5px; color: #666;">
					<span class="dashicons dashicons-tickets-alt" style="font-size: 40px; width: 40px; height: 40px; display: block; margin: 0 auto 10px auto;"></span>
					<p style="margin: 0; font-weight: 600;">${window.saleCouponData && window.saleCouponData.i18n ? 'Kuponunuz bulunmamaktadır.' : 'Kuponunuz bulunmamaktadır.'}</p>
				</div>
			`;
			return;
		}

		let html = '<div class="sc-coupons-grid">';

		coupons.forEach(coupon => {
			const formattedAmount = this.formatPrice(coupon.amount, coupon.currency);
			const formattedExpiry = coupon.date_expires 
				? new Date(coupon.date_expires).toLocaleDateString()
				: 'Sınırsız';
			
			const formattedCreated = coupon.date_created
				? new Date(coupon.date_created).toLocaleDateString()
				: '';

			let statusLabel = '';
			let statusClass = '';

			switch (coupon.status) {
				case 'active':
					statusLabel = 'Aktif';
					statusClass = 'sc-status-active';
					break;
				case 'used':
					statusLabel = 'Kullanıldı';
					statusClass = 'sc-status-used';
					break;
				case 'expired':
					statusLabel = 'Süresi Doldu';
					statusClass = 'sc-status-expired';
					break;
			}

			// Copy button text.
			const copyText = window.saleCouponData && window.saleCouponData.i18n ? window.saleCouponData.i18n.copy : 'Kopyala';

			html += `
				<div class="sc-coupon-card ${statusClass}">
					<div class="sc-coupon-header">
						<span class="sc-coupon-code">${coupon.code}</span>
						<button type="button" class="sc-copy-btn button" data-code="${coupon.code}">
							<span class="dashicons dashicons-admin-page" style="vertical-align:middle; font-size: 14px;"></span> ${copyText}
						</button>
					</div>
					<div class="sc-coupon-body">
						<div class="sc-coupon-amount">${formattedAmount}</div>
						<div class="sc-coupon-status-badge">${statusLabel}</div>
					</div>
					<div class="sc-coupon-footer">
						<div class="sc-meta-item">
							<strong>Son Kullanma:</strong> <span>${formattedExpiry}</span>
						</div>
						${formattedCreated ? `
						<div class="sc-meta-item">
							<strong>Satın Alma:</strong> <span>${formattedCreated}</span>
						</div>` : ''}
						${coupon.order_id ? `
						<div class="sc-meta-item">
							<strong>Sipariş:</strong> <span>#${coupon.order_id}</span>
						</div>` : ''}
					</div>
				</div>
			`;
		});

		html += '</div>';

		// Render Pagination if total > perPage
		const totalPages = Math.ceil(total / this.perPage);
		if ( totalPages > 1 ) {
			html += this.renderPagination(totalPages);
		}

		this.container.innerHTML = html;

		// Bind pagination clicks
		this.bindPaginationEvents();
	}

	/**
	 * Format price using WooCommerce symbol.
	 *
	 * @param {number} amount Amount.
	 * @param {string} currency Currency code.
	 * @returns {string}
	 */
	formatPrice(amount, currency) {
		const symbol = window.saleCouponData ? window.saleCouponData.currency : '';
		// standard format.
		return `${amount.toFixed(2)} ${symbol}`;
	}

	/**
	 * Render error notice.
	 */
	renderError() {
		this.container.innerHTML = `
			<div class="woocommerce-error" style="margin: 0;">
				${window.saleCouponData && window.saleCouponData.i18n ? 'Kuponlar yüklenirken bir hata oluştu. Lütfen sayfayı yenileyin.' : 'Kuponlar yüklenirken bir hata oluştu. Lütfen sayfayı yenileyin.'}
			</div>
		`;
	}

	/**
	 * Generate pagination HTML.
	 *
	 * @param {number} totalPages Total pages count.
	 * @returns {string}
	 */
	renderPagination(totalPages) {
		let html = '<div class="sc-pagination" style="margin-top: 25px; text-align: center; display:flex; gap: 5px; justify-content: center;">';
		
		for ( let i = 1; i <= totalPages; i++ ) {
			const activeClass = i === this.currentPage ? 'current button primary' : 'button';
			html += `<button type="button" class="sc-page-btn ${activeClass}" data-page="${i}">${i}</button>`;
		}

		html += '</div>';
		return html;
	}

	/**
	 * Bind click listeners to pagination buttons.
	 */
	bindPaginationEvents() {
		const buttons = this.container.querySelectorAll('.sc-page-btn');
		buttons.forEach(btn => {
			btn.addEventListener('click', (e) => {
				e.preventDefault();
				const targetPage = parseInt(btn.getAttribute('data-page'));
				if ( targetPage !== this.currentPage ) {
					this.currentPage = targetPage;
					this.container.innerHTML = `
						<div class="sc-loading-spinner" style="padding: 20px 0; text-align: center; color: #666;">
							<div class="sc-spinner" style="display:inline-block; width: 30px; height: 30px; border: 3px solid #ccc; border-top-color: #7f54b3; border-radius: 50%; animation: sc-spin 1s linear infinite; margin-right: 10px; vertical-align: middle;"></div>
							<span style="vertical-align: middle; font-weight: 600;">Yükleniyor...</span>
						</div>
					`;
					this.loadCoupons();
				}
			});
		});
	}
}

export default new CouponList();
