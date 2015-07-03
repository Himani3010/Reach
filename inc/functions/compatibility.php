<?php
/**
 * Various backwards compatibility functions for people migrating from Reach 1.* to 2.*
 *
 * @package 	Reach
 * @category 	Functions
 */

if ( ! function_exists( 'get_reach_theme' ) ) {
	function get_reach_theme() {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_get_theme' );
		return reach_get_theme();
	}
}

if ( ! function_exists( 'sofa_site_url' ) ) {
	function sofa_site_url() {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_site_url' );
		return reach_site_url();
	}
}

if ( ! function_exists( 'sofa_condensed_url' ) ) {
	function sofa_condensed_url( $url ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_condensed_url' );
		return reach_condensed_url( $url );
	}
}

if ( ! function_exists( 'sofa_link_format_title' ) ) {
	function sofa_link_format_title( $echo = true ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_link_format_title' );
		return reach_link_format_title( $echo );
	}
}

if ( ! function_exists( 'sofa_site_title' ) ) {
	function sofa_site_title( $echo = true ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_site_title' );
		return reach_site_title( $echo );
	}
}

if ( ! function_exists( 'sofa_site_tagline' ) ) {
	function sofa_site_tagline($echo = true) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_site_tagline' );
		return reach_site_tagline( $echo );
	}
}

if ( ! function_exists( 'sofa_post_header' ) ) {
	function sofa_post_header( $echo = true ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_post_header' );
		return reach_post_header( $echo );
	}
}

if ( ! function_exists( 'sofa_video_format_the_content' ) ) {
	function sofa_video_format_the_content($more_link_text = null, $stripteaser = false) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_video_format_the_content' );
		return reach_video_format_the_content( $more_link_text, $stripteaser );
	}
}

if ( ! function_exists( 'sofa_link_format_the_content' ) ) {
	function sofa_link_format_the_content($more_link_text = null, $stripteaser = false, $echo = true) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_link_format_the_content' );
		return reach_link_format_the_content( $more_link_text, $stripteaser, $echo);
	}
}

if ( ! function_exists( 'sofa_crowdfunding_get_pledge_text' ) ) {
	function sofa_crowdfunding_get_pledge_text() {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_crowdfunding_get_pledge_text' );
		return reach_crowdfunding_get_pledge_text();
	}
}

if ( ! function_exists( 'sofa_crowdfunding_get_pledge_amount_text' ) ) {
	function sofa_crowdfunding_get_pledge_amount_text( $amount ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_crowdfunding_get_pledge_amount_text' );
		return reach_crowdfunding_get_pledge_amount_text( $amount );
	}
}

if ( ! function_exists( 'atcf_get_campaign' ) ) {
	function atcf_get_campaign( $id ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'new Charitable_Campaign( $id )' );
		return new Charitable_Campaign( $id );
	}
}

if ( ! function_exists( 'sofa_strip_anchors' ) ) {
	function sofa_strip_anchors( $content, $limit = 1 ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_strip_anchors' );
		return reach_strip_anchors( $content, $limit );
	}
}

if ( ! function_exists( 'sofa_get_first_anchor' ) ) {
	function sofa_get_first_anchor( $content ) {
		_deprecated_function( __FUNCTION__, '1.0.0', 'reach_get_first_anchor' );
		return reach_get_first_anchor( $content );
	}
}