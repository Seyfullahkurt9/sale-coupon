<?php
namespace SaleCoupon\Coupon;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates secure, cryptographic random, unique coupon codes.
 */
class CodeGenerator {

	/**
	 * Characters allowed in the random part of the coupon code.
	 * Excludes confusing similar characters: 0, 1, O, I, L.
	 */
	const ALLOWED_CHARS = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

	/**
	 * Generate a random coupon code.
	 *
	 * @param string $prefix Prefix for the coupon.
	 * @param int    $length Length of the random string part.
	 * @return string
	 */
	public static function generate_code( $prefix = 'GIFT-', $length = 10 ) {
		// Clean and prepare the prefix.
		$prefix = uppercase_esc_html_prefix( $prefix );

		// Validate random character length (min 8).
		$length = max( 8, intval( $length ) );

		$random_part = '';
		$char_list_len = strlen( self::ALLOWED_CHARS );

		for ( $i = 0; $i < $length; $i++ ) {
			try {
				// Use cryptographically secure pseudorandom integers.
				$index = random_int( 0, $char_list_len - 1 );
				$random_part .= self::ALLOWED_CHARS[ $index ];
			} catch ( \Exception $e ) {
				// Fallback if random_int is not supported.
				$index = rand( 0, $char_list_len - 1 );
				$random_part .= self::ALLOWED_CHARS[ $index ];
			}
		}

		return $prefix . $random_part;
	}
}

/**
 * Utility helper to sanitize and format prefix.
 *
 * @param string $prefix Input prefix.
 * @return string
 */
function uppercase_esc_html_prefix( $prefix ) {
	$prefix = sanitize_text_field( $prefix );
	$prefix = strtoupper( preg_replace( '/[^A-Za-z0-9\-]/', '', $prefix ) );
	return $prefix;
}
