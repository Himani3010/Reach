<?php
/**
 * Helper functions for the crowdfunding functionality.
 *
 * @package 	Reach/Crowdfunding
 * @category 	Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the text used for making a pledge / supporting a campaign. 
 *
 * @global 	array 		$edd_options
 * @return 	string
 * @since 	1.0.0
 */
function reach_crowdfunding_get_pledge_text() {
	global $edd_options;
	return ! empty( $edd_options['add_to_cart_text'] ) ? $edd_options['add_to_cart_text'] : __( 'Pledge', 'reach' );
}

/**
 * Returns the text used when displaying a statement like "Pledge $10.00". i.e. Pledge amount
 *
 * @param   amount
 * @return  string
 * @since   1.0.0
 */
function reach_crowdfunding_get_pledge_amount_text( $amount ) {
    return sprintf( '%s %s', 
        reach_crowdfunding_get_pledge_text(),
        '<strong>'.edd_currency_filter( edd_format_amount( $amount ) ).'</strong>' 
    );
} 

/**
 * Gets the timezone offset. 
 * 
 * @return  string
 * @since   1.0.0
 */
function reach_get_timezone_offset() {        
    $timezone = charitable_get_timezone_id();
    $date_timezone = new DateTimeZone($timezone);
    $date_time = new DateTime('now', $date_timezone);
    $offset_secs = $date_time->format('Z');
    $offset = $date_time->format('P');
    $offset = str_replace( ':', '.', $offset );

    if ( $offset_secs >= 0 ) {
        return $offset;
    }
    return str_replace( '+', '-', $offset );
} 

/**
 * Return the image size to use for campaign featured images.
 * 
 * @return  string
 * @since   1.0.0
 */
function reach_get_campaign_featured_image_size() {
    if ( 'featured_image_in_summary' == reach_get_theme()->get_theme_setting( 'campaign_media_placement', 'featured_image_in_summary' ) ) {
        $size = 'campaign-thumbnail-summary';
    }
    else {
        $size = 'post-thumbnail';
    }

    return apply_filters( 'reach_campaign_featured_image_size', $size );
}




