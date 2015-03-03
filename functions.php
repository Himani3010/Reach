<?php
/**
 * Core functions and definitions for Franklin Theme.
 *
 * @package 	Franklin
 */

require_once( 'inc/class-franklin-theme.php' );

/**
 * Start the theme. 
 */
franklin_get_theme();

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
if ( ! defined( 'FRANKLIN_DEBUG' ) ) {
	define( 'FRANKLIN_DEBUG', false );
}

/**
 * Return the one true instance of the Franklin_Theme.
 * 
 * @return 	Franklin_Theme
 * @since 	1.0.0
 */
function franklin_get_theme() {
	return Franklin_Theme::get_instance();
}