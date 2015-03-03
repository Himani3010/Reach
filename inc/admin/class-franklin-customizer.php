<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Franklin_Customizer' ) ) : 

/**
 * Franklin Customizer
 *
 * @class       Franklin_Customizer
 * @author      Studio 164a
 * @category    Admin
 * @package     Franklin
 * @subpackage 	Customizer
 * @since       1.0.0
 */
class Franklin_Customizer {

    /**
     * @var     Franklin_Theme   $theme
     */
    private $theme;

    /**
     * Instantiate the object, but only if this is the start phase. 
     *
     * @static
     * @param   Franklin_Theme           $theme
     * @return  void
     */
    public static function start( Franklin_Theme $theme ) {
        if ( ! $theme->is_start() ) {
            return;
        }

        new Franklin_Customizer( $theme );
    }

    /**
     * Instantiate the object. 
     * 
     * @param   Franklin_Theme           $theme
     */
    private function __construct( Franklin_Theme $theme ) {
        $this->theme = $theme;
            
        add_action('after_setup_theme', array( $this, 'setup_callbacks' ) );
    } 

    /**
     * Set up callback methods for the Customizer.
     *
     * @return 	void
     * @access 	public
     * @since 	1.0.0
     */
    public function setup_callbacks() {        
        add_action('customize_register',        array( $this, 'customize_register' ) );
        add_action('customize_preview_init',    array( $this, 'customize_preview_init' ) ); 
        add_action('customize_save_after',      array( $this, 'customize_save_after' ) );
    }

    /**
     * Theme customization settings.
     * 
     * @hook 	customize_register
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     * @access  public
     */
    public function customize_register($wp_customize) {    
        $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    }   

    /**
     * Enqueue the customizer.js script, which is used
     * 
     * @hook 	customize_preview_init
     * @return 	void
     * @access 	public
     * @since 	1.0.0
     */
    public function customize_preview_init() {        
        wp_enqueue_script( 'franklin_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), $this->theme->get_theme_version(), true );
    }     

    /**
     * After the customizer has finished saving each of the fields, check whether we're using a retina logo. 
     *
     * @hook 	customize_save_after
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function customize_save_after(WP_Customize_Manager $wp_customize) {
        /** 
         * The saved styles may no longer be valid, so delete them. They 
         * will be re-created on the next page load.
         */
        delete_transient( Franklin_Customizer_Styles::get_transient_key() );
    }    
}

endif; // End class_exists check