import { initPurchaseForm } from './modules/purchase-form';
import { initClipboard } from './modules/clipboard';
import couponList from './modules/coupon-list';

/**
 * Initialize frontend scripts.
 */
document.addEventListener( 'DOMContentLoaded', () => {
	// Initialize single product page purchase form controls.
	initPurchaseForm();

	// Initialize copy to clipboard listeners.
	initClipboard();

	// Load and display purchased coupons list in My Account.
	couponList.init();
} );
