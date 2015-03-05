<?php 
/**
 * Handles how Crowdfunding features are integrated into the theme.
 * 
 * @package 	Franklin/Crowdfunding
 * @category	Classes
 * @author 		Studio 164a
 * @version 	2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Franklin_Jetpack' ) ) : 

/**
 * Franklin_Crowdfunding
 *
 * @since 		2.0.0
 */
class Franklin_Crowdfunding {

	/**
	 * This creates an instance of this class. 
	 *
	 * If the franklin_theme_start hook has already run, this will not do anything.
	 * 
	 * @param 	Franklin_Theme 	$theme
	 * @static
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function start( Franklin_Theme $theme ) {
		if ( ! $theme->is_start() ) {
			return;
		}
		
		new Franklin_Crowdfunding();	
	}

	/** 
	 * Create object instance.
	 *
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct() {
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
	}
}

endif;