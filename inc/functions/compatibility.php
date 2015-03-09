<?php
/**
 * Various backwards compatibility functions for people migrating from Franklin 1.* to 2.*
 *
 * @package 	Franklin
 * @category 	Functions
 */

function get_franklin_theme() {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_get_theme' );
	return franklin_get_theme();
}

function sofa_site_url() {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_site_url' );
	return franklin_site_url();
}

function sofa_condensed_url( $url ) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_condensed_url' );
	return franklin_condensed_url( $url );
}

function sofa_link_format_title( $echo = true ) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_link_format_title' );
	return franklin_link_format_title( $echo );
}

function sofa_site_title( $echo = true ) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_site_title' );
	return franklin_site_title( $echo );
}

function sofa_site_tagline($echo = true) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_site_tagline' );
	return franklin_site_tagline( $echo );
}

function sofa_post_header( $echo = true ) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_post_header' );
	return franklin_post_header( $echo );
}

function sofa_video_format_the_content($more_link_text = null, $stripteaser = false) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_video_format_the_content' );
	return franklin_video_format_the_content( $more_link_text, $stripteaser );
}

function sofa_link_format_the_content($more_link_text = null, $stripteaser = false, $echo = true) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_link_format_the_content' );
	return franklin_link_format_the_content( $more_link_text, $stripteaser, $echo);
}





function sofa_crowdfunding_get_pledge_text() {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_crowdfunding_get_pledge_text' );
	return franklin_crowdfunding_get_pledge_text();
}

function sofa_crowdfunding_get_pledge_amount_text( $amount ) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'franklin_crowdfunding_get_pledge_amount_text' );
	return franklin_crowdfunding_get_pledge_amount_text( $amount );
}

function atcf_get_campaign( $id ) {
	_deprecated_function( __FUNCTION__, '2.0.0', 'new Charitable_Campaign( $id )' );
	return new Charitable_Campaign( $id );
}