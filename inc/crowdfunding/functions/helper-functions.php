<?php
/**
 * Helper functions for the crowdfunding functionality.
 *
 * @package 	Franklin/Crowdfunding
 * @category 	Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the text used for making a pledge / supporting a campaign. 
 *
 * @global 	array 		$edd_options
 * @return 	string
 * @since 	1.5.12
 */
function franklin_crowdfunding_get_pledge_text() {
	global $edd_options;
	return ! empty( $edd_options['add_to_cart_text'] ) ? $edd_options['add_to_cart_text'] : __( 'Pledge', 'franklin' );
}