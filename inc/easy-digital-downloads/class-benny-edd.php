<?php
/**
 * Responsible for setting up theme-specific customisation of Easy Digital Downloads.
 *
 * @package     Benny/Classes/Benny_EDD
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Benny_EDD' ) ) : 

/**
 * Benny_EDD
 *
 * @since       1.0.0
 */
class Benny_EDD {

    /**
     * This creates an instance of this class. 
     *
     * If the benny_theme_start hook has already run, this will not do anything.
     * 
     * @param   Benny_Theme     $theme
     * @static
     * @access  public
     * @since   1.0.0
     */
    public static function start( Benny_Theme $theme ) {
        if ( ! $theme->is_start() ) {
            return;
        }

        new Benny_EDD();   
    }

    /** 
     * Create object instance.
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {
        $this->attach_hooks_and_filters();
        $this->load_dependencies();
    }

    /**
     * Include required files. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function load_dependencies() {
        require_once( 'functions/helper-functions.php' );
        require_once( 'functions/template-tags.php' );
    }

    /**
     * Set up hooks and filters. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function attach_hooks_and_filters() {   
        add_action( 'edd_purchase_link_top', 'benny_edd_show_price', 8, 3 );    

        add_filter( 'template_include', array( $this, 'edd_checkout_template' ) );
        add_filter( 'edd_purchase_form_quantity_input', 'benny_edd_purchase_form_quantity_input' );
        add_filter( 'edd_purchase_link_args', 'benny_edd_purchase_link_text' );        
        add_filter( 'charitable_edd_donation_form_show_thumbnail', '__return_false' );        
    }

    /**
     * Use our custom EDD checkout template.  
     *
     * @param   string  $template
     * @return  string  
     * @access  public
     * @since   1.0.0
     */
    public function edd_checkout_template( $template ) {
        if ( ! edd_is_checkout() ) { 
            return $template;
        }
        
        if ( ! apply_filters( 'benny_use_custom_checkout_template', true ) ) {
            return $template;
        }   

        $t = locate_template( 'edd-checkout.php' );

        if ( file_exists( $t ) ) {
            $template = $t;
        }

        /* Add a body class for easier styling */
        add_filter( 'body_class', array( $this, 'edd_checkout_body_class' ) );
        
        return $template;
    }

    /**
     * Add custom body class to indicate we are using the custom checkout template. 
     *
     * @param   string[] $class
     * @return  string[] 
     * @access  public
     * @since   1.0.0
     */
    public function edd_checkout_body_class( $class ) {
        $class[] = 'edd-checkout-template';
        return $class;
    }
}

endif; // End class_exists check