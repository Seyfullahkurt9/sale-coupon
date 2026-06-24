<?php
namespace SaleCoupon\Core;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Run code during plugin deactivation.
 */
class Deactivator {

	/**
	 * Deactivate hook handler.
	 */
	public static function deactivate() {
		// Flush rewrite rules to clean up the custom My Account endpoint.
		flush_rewrite_rules();
	}
}
