<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Franklin_Customizer_Styles' ) ) : 

/**
 * Franklin Customizer Styles
 *
 * @class 		Franklin_Customizer_Styles
 * @author 		Studio 164a
 * @category 	Frontend
 * @package 	Franklin
 * @subpackage  Customizer
 * @since 		1.0.0
 */
class Franklin_Customizer_Styles {

	/**
	 * @var Franklin_Theme        $theme
	 */
	private $theme;

	/**
	 * Creates an instance of this class. 
	 * 
	 * This can only be run on the franklin_theme_start hook. You should
	 * never need to instantiate it again (if you do, I'd love to hear
	 * your use case).
	 *
	 * @static
	 * 
	 * @param 	Franklin_Theme 	$theme
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public static function start( Franklin_Theme $theme ) {
		if ( ! $theme->is_start() ) {
			return;
		}

		new Franklin_Customizer_Styles( $theme );
	}

	/**
	 * Object constructor. 
	 *
	 * @param 	Franklin_Theme 	$theme
	 * @return 	void
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct( Franklin_Theme $theme ) {
		$this->theme = $theme;

		add_action( 'wp_head', array( $this, 'output_styles' ) );

        do_action( 'franklin_customizer_styles', $this );
	}

    /**
     * Return the key used to store customizer styles as a transient.
     *
     * @static
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public static function get_transient_key() {
        return 'Franklin_customizer_styles';
    }

	/**
     * Insert output into end of <head></head> section.
     *
     * @hook    wp_head
     * @return 	void
     */
    public function output_styles() {  
        /**
         * Check for saved customizer styles. 
         */
        $styles = get_transient( self::get_transient_key() );      
            
        /**
         * If we're in debug mode, regenerate the styles on every page load. 
         */
        if ( defined( 'FRANKLIN_DEBUG' ) && true === FRANKLIN_DEBUG ) {
            $styles = false;
        }

        /**
         * 
         */
        if ( false === $styles ) {
            ob_start();
            ?>
<style media="all" type="text/css">   
</style>                    
            <?php 
            $styles = ob_get_clean();

            $styles = $this->compress_css( $styles );

            // Cache the styles
            set_transient( self::get_transient_key(), $styles );
        }

        // Print the styles
        echo $styles;
    }

    /**
     * A simple CSS compression method.
     *
     * Removes all comments, removes spaces after colons and strips out all the whitespace. 
     *
     * @link    http://manas.tungare.name/software/css-compression-in-php/
     * 
     * @param   string  $css    The block of CSS to be compressed.
     * @return  string          The compressed block of CSS.
     * @access  private
     * @since   1.0.0
     */
    private function compress_css( $css ) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

        // Remove space after colons
        $css = str_replace(': ', ':', $css);
         
        // Remove whitespace
        $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

        return $css;
    }
}

endif;