import apiFetch from '@wordpress/api-fetch';

/**
 * REST API client wrapper for Sale Coupon plugin.
 */
class ApiClient {
	constructor() {
		// Base REST API url set via wp_localize_script
		this.baseUrl = window.saleCouponData ? window.saleCouponData.restUrl : '/wp-json/sale-coupon/v1';
		this.nonce = window.saleCouponData ? window.saleCouponData.restNonce : '';

		// Configure apiFetch middleware to handle nonce automatically if needed.
		// WordPress apiFetch automatically handles the standard wp_rest nonce if it's set on wpApiSettings.
		// To be safe, we can pass headers.
	}

	/**
	 * Fetch user coupons list.
	 *
	 * @param {number} page Page number.
	 * @param {number} perPage Page limit.
	 * @returns {Promise}
	 */
	getUserCoupons(page = 1, perPage = 10) {
		return apiFetch({
			url: `${this.baseUrl}/coupons?page=${page}&per_page=${perPage}`,
			method: 'GET',
			headers: {
				'X-WP-Nonce': this.nonce
			}
		});
	}

	/**
	 * Fetch public settings configuration.
	 *
	 * @returns {Promise}
	 */
	getPublicSettings() {
		return apiFetch({
			url: `${this.baseUrl}/settings/public`,
			method: 'GET'
		});
	}
}

export default new ApiClient();
