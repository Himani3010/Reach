<?php 
/**
 * Handles how Crowdfunding features are integrated into the theme.
 * 
 * @package 	Benny/Crowdfunding
 * @category	Classes
 * @author 		Studio 164a
 * @version 	2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Benny_Jetpack' ) ) : 

/**
 * Benny_Crowdfunding
 *
 * @since 		2.0.0
 */
class Benny_Crowdfunding {

	/**
	 * This creates an instance of this class. 
	 *
	 * If the benny_theme_start hook has already run, this will not do anything.
	 * 
	 * @param 	Benny_Theme 	$theme
	 * @static
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function start( Benny_Theme $theme ) {
		if ( ! $theme->is_start() ) {
			return;
		}

		new Benny_Crowdfunding();	
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
		remove_action( 'edd_purchase_link_top', 	'edd_purchase_variable_pricing', 10, 2 );
		remove_action( 'edd_purchase_link_top', 	'edd_pl_override_variable_pricing', 10 ); // Purchase limits
		add_action( 'edd_purchase_link_top', 		'benny_edd_variable_pricing', 10, 2 );

		remove_filter( 'the_content', 				array( charitable_get_helper( 'templates' ), 'campaign_content' ), 2 );
		add_filter( 'benny_script_dependencies',	array( $this, 'setup_script_dependencies' ) );
		add_filter( 'charitable_campaign_ended', 	'benny_campaign_ended_text' );
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
		
		wp_register_script( 'raphael', get_template_directory_uri() . '/js/vendors/raphael/raphael-min.js', array( 'jquery' ), benny_get_theme()->get_theme_version(), true );

		if ( get_post_type() == 'campaign' ) {
			wp_register_script( 'countdown-plugin', get_template_directory_uri() . '/js/vendors/jquery-countdown/jquery.plugin.min.js', array( 'jquery' ), benny_get_theme()->get_theme_version(), true );
            wp_register_script( 'countdown', get_template_directory_uri() . '/js/vendors/jquery-countdown/jquery.countdown.min.js', array( 'countdown-plugin' ), benny_get_theme()->get_theme_version(), true );

            $dependencies[] = 'countdown';
        }

		return $dependencies;
	}
}

endif;