<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options.
$options = [
	'sc_coupon_prefix',
	'sc_random_length',
	'sc_min_amount',
	'sc_max_amount',
	'sc_discount_type',
	'sc_expiry_days',
	'sc_email_enabled',
];

foreach ( $options as $option ) {
	delete_option( $option );
}
