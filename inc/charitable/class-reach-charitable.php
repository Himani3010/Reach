<?php 
/**
 * Handles how Charitable features are integrated into the theme.
 * 
 * @package 	Reach/Classes/Reach_Charitable
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Reach_Charitable' ) ) : 

/**
 * Reach_Charitable
 *
 * @since 		2.0.0
 */
class Reach_Charitable {

	/**
	 * This creates an instance of this class. 
	 *
	 * If the reach_theme_start hook has already run, this will not do anything.
	 * 
	 * @param 	Reach_Theme 	$theme
	 * @static
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function start( Reach_Theme $theme ) {
		if ( ! $theme->is_start() ) {
			return;
		}

		new Reach_Charitable();	
	}

	/** 
	 * Create object instance.
	 *
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct() {
		$this->attach_hooks_and_filters();
		$this->load_dependencies();
	}

	/**
	 * Include required files. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	2.0.0
	 */
	private function load_dependencies() {
		require_once( 'functions/helper-functions.php' );
		require_once( 'functions/template-hooks.php' );
		require_once( 'functions/template-tags.php' );
	}

	/**
	 * Set up hooks and filters. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	2.0.0
	 */
	private function attach_hooks_and_filters() {
		remove_filter( 'the_content', array( charitable_get_helper( 'templates' ), 'campaign_content' ), 2 );
		add_filter( 'reach_script_dependencies', array( $this, 'setup_script_dependencies' ) );
		add_filter( 'reach_banner_title', array( $this, 'set_banner_title' ) );
		add_filter( 'charitable_campaign_ended', 'reach_campaign_ended_text' );		
		add_filter( 'charitable_force_user_dashboard_template', '__return_true' );
		add_filter( 'charitable_campaign_submission_campaign_fields', array( $this, 'campaign_submission_fields' ) );
		add_filter( 'charitable_fes_my_campaign_thumbnail_size', array( $this, 'my_campaign_thumbnail_size' ) );
		add_filter( 'charitable_use_campaign_template', '__return_false' );
		add_filter( 'charitable_modal_window_class', array( $this, 'modal_window_class' ) );
		add_filter( 'charitable_campaign_video_embed_args', array( $this, 'video_embed_args' ), 5 );
	}	

	/**
	 * Register scripts required for crowdfunding functionality. 
	 *
	 * @param 	array 		$dependencies
	 * @return 	array
	 * @access  public
	 * @since 	2.0.0
	 */
	public function setup_script_dependencies( $dependencies ) {
		$dependencies[] = 'raphael';
		$dependencies[] = 'jquery-masonry';		
		
		wp_register_script( 'raphael', get_template_directory_uri() . '/js/vendors/raphael/raphael-min.js', array( 'jquery' ), reach_get_theme()->get_theme_version(), true );

		if ( 'campaign' == get_post_type() ) {
			wp_register_script( 'countdown-plugin', get_template_directory_uri() . '/js/vendors/jquery-countdown/jquery.plugin.min.js', array( 'jquery' ), reach_get_theme()->get_theme_version(), true );
            wp_register_script( 'countdown', get_template_directory_uri() . '/js/vendors/jquery-countdown/jquery.countdown.min.js', array( 'countdown-plugin' ), reach_get_theme()->get_theme_version(), true );

            $dependencies[] = 'countdown';
        }

		return $dependencies;
	}

	/**
	 * Set banner title for campaign donation page. 
	 *
	 * @global 	WP_Query 	$wp_query
	 * @param 	string 		$title	
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function set_banner_title( $title ) {
		global $wp_query;

		if ( isset ( $wp_query->query_vars[ 'donate' ] ) && is_singular( 'campaign' ) ) {

			$title = get_the_title();

		}

		return $title; 
	}

	/**
	 * Apply custom styles to the WP editor. 
	 *
	 * @return 	array
	 * @access  public
	 * @since 	1.0.0
	 */
	public function campaign_submission_fields( $fields ) {
		if ( ! isset( $fields[ 'content' ] ) ) {
			return $fields;
		}

		$fields[ 'content' ][ 'editor' ] = array(
			'tinymce' 			=> array(
				'content_css' 	=> get_template_directory_uri() . '/css/editor-style.css' 
			)
		);

		return $fields;
	}

	/**
	 * Set the thumbnail size for campaign images displayed on the "My Campaigns" page. 
	 *
	 * @param 	string 	$size
	 * @return  string
	 * @access  public
	 * @since   1.0.0
	 */
	public function my_campaign_thumbnail_size( $size ) {
		return 'campaign-thumbnail-medium';
	}

	/**
	 * Set the modal window class. 
	 *
	 * @param 	string 	$class
	 * @return  string
	 * @access  public
	 * @since   1.0.0
	 */
	public function modal_window_class( $class ) {
		return 'modal';
	}

	/**
	 * Video embed width argument set to 1098px (fullwidth). 
	 *
	 * @param 	array 	$args
	 * @return  array
	 * @access  public
	 * @since   1.0.0
	 */
	public function video_embed_args( $args ) {
		$args[ 'width' ] = 1098;
		return $args;
	}
}

endif;