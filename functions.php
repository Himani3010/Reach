<?php
/**
 * Core functions and definitions for Benny Theme.
 *
 * @package 	Benny
 */

require_once( 'inc/class-benny-theme.php' );

/**
 * Start the theme. 
 */
benny_get_theme();

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

/**
 * Define whether we're in debug mode. 
 *
 * This is set to false by default. If set to true, 
 * scripts and stylesheets are NOT cached or minified 
 * to make debugging easier. 
 */
if ( ! defined( 'BENNY_DEBUG' ) ) {
	define( 'BENNY_DEBUG', false );
}

/**
 * Return the one true instance of the Benny_Theme.
 * 
 * @return 	Benny_Theme
 * @since 	1.0.0
 */
function benny_get_theme() {
	return Benny_Theme::get_instance();
}