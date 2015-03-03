<?php
/**
 * A collection of handy functions that are used by the theme.
 *
 * @package 	Franklin
 * @category 	Functions
 */

/**
 * Return the home URL. Checks if WPML is installed and defers to the WPML function if it is. 
 * 
 * @return 	string
 * @since 	1.3.0
 */
function franklin_site_url() {
	return function_exists('wpml_get_home_url') ? wpml_get_home_url() : site_url();
}

/**
 * Returns the given URL minus the 
 *
 * @param 	string 		$url
 * @return 	string
 * @since 	1.5.0
 */
function franklin_condensed_url( $url ) {
	$parts = parse_url($url);
	$output = $parts['host'];
	if ( isset( $parts['path'] ) ) {
		$output .= $parts['path'];
	}
	return $output;
}

/**
 * A helper function to determine whether the current post should have the meta displayed.
 *
 * @param   WP_Post     $post       Optional. If a post is not passed, the current $post object will be used.
 * @return  boolean
 * @since   1.0.0
 */
function franklin_hide_post_meta( $post = '' ) {
    if ( ! strlen( $post ) ) {
        global $post;
    }

    if ( function_exists( 'hide_meta_start' ) ) {
        return get_post_meta( $post->ID, '_hide_meta', true );
    }
    else {
        return get_post_meta( $post->ID, '_franklin_hide_post_meta', true );
    } 
}