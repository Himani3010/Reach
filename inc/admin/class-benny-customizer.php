<?php
/**
 * Sets up the Wordpress customizer
 *
 * @since 1.2
 */

class Benny_Customizer {

    /**
     * Instantiate the object, but only if this is the start phase. 
     *
     * @static
     * @param   Benny_Theme          $theme
     * @param   WP_Customize_Manager    $wp_customize 
     * @return  void
     */
    public static function start( Benny_Theme $theme, WP_Customize_Manager $wp_customize ) {
        if ( $theme->started() ) {
            return;
        }

        new Benny_Customizer();
    }

    /**
     * Instantiate the object. 
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {        
        add_action('after_setup_theme',                 array( $this, 'setup_callbacks' ) );        
    } 

    /**
     * Set up callbacks for the class.
     *
     * @return  void
     * @since   1.6.0
     */
    public function setup_callbacks() {
        add_action('customize_save_after',              array( $this, 'customize_save_after' ) );
        add_action('customize_register',                array( $this, 'customize_register' ) );     
        add_action('customize_controls_print_scripts',  array( $this, 'customize_controls_print_scripts' ), 100 );
        add_action('wp_head',                           array( $this, 'preview_styles' ) );
    }

    /**
     * After the customizer has finished saving each of the fields, delete the transient.
     *
     * @see     customize_save_after hook
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     * @access  public
     * @since   1.6.0
     */
    public function customize_save_after( WP_Customize_Manager $wp_customize ) {
        /** 
         * The saved styles may no longer be valid, so delete them. They 
         * will be re-created on the next page load.
         */
        delete_transient( Benny_Customizer_Styles::get_transient_key() );
    }

    /**
     * Theme customization. 
     *
     * @return  void
     */
    public function customize_register($wp_customize) {
        /** 
         * Title & tagline section
         */
        $wp_customize->add_setting( 'logo_url', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'retina_logo_url', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'hide_site_title', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_setting( 'hide_site_tagline', array( 'transport' => 'postMessage' ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_url',
            array(
                'settings'  => 'logo_url',
                'section'   => 'title_tagline',
                'label'     => __( 'Logo', 'benny' )
            ) )
        );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'retina_logo_url',
            array(
                'settings'  => 'retina_logo_url',
                'section'   => 'title_tagline',
                'label'     => __( 'Retina version of logo (2x)', 'benny' )
            ) )
        );        
        $wp_customize->add_control( 'hide_site_title', array(
            'settings'      => 'hide_site_title', 
            'label'         => __( 'Hide the site title', 'benny' ),
            'section'       => 'title_tagline', 
            'type'          => 'checkbox'            
        ) );
        $wp_customize->add_control( 'hide_site_tagline', array(
            'settings'      => 'hide_site_tagline', 
            'label'         => __( 'Hide the tagline', 'benny' ),
            'section'       => 'title_tagline', 
            'type'          => 'checkbox'            
        ) );
        
        /** 
         * Colors
         */
        $priority = 10;

        foreach ( Benny_Customizer_Styles::get_customizer_colours() as $key => $colour ) {          
            $wp_customize->add_setting( $key, array( 
                'default'   => $colour['default'], 
                'transport' => 'postMessage' 
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 
                'label'     => $colour['title'], 
                'section'   => 'colors', 
                'settings'  => $key, 
                'priority'  => $priority )
            ));

            $priority += 1;
        }

        /**
         * Textures
         */
        $wp_customize->add_section( 'textures', array( 
            'priority'      => $priority, 
            'title'         => __( 'Background Textures', 'benny' ), 
            'description'   => __( 'Upload background textures for the body and campaign section', 'benny' )
        ) );

        $priority += 1;

        $wp_customize->add_setting( 'body_texture_custom', array( 
            'default'       => '', 
            'transport'     => 'postMessage' 
        ) );
        $wp_customize->add_setting( 'campaign_texture_custom', array( 
            'default'       => '', 
            'transport'     => 'postMessage' 
        ) );
        $wp_customize->add_setting( 'blog_banner_texture_custom', array( 
            'default'       => '', 
            'transport'     => 'postMessage' 
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'body_texture_custom',
            array(
                'settings' => 'body_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Body', 'benny' )
            ) )
        );

        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'campaign_texture_custom',
            array(
                'settings' => 'campaign_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Featured Campaign Block', 'benny' )
            ) )
        );
        
        $priority += 1;

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'blog_banner_texture_custom',
            array(
                'settings' => 'blog_banner_texture_custom',
                'section'  => 'textures',
                'priority' => $priority,
                'label'    => __( 'Blog & Page Banners', 'benny' )
            ) )
        );
        
        $priority += 1;    

        /** 
         * Campaign
         */    
        // if ( get_benny_theme()->crowdfunding_enabled ) {

        //     $sharing_options = array(
        //         'campaign_share_twitter'    => __( 'Share on Twitter', 'benny' ), 
        //         'campaign_share_facebook'   => __( 'Share on Facebook', 'benny' ), 
        //         'campaign_share_googleplus' => __( 'Share on Google+', 'benny' ), 
        //         'campaign_share_linkedin'   => __( 'Share on LinkedIn', 'benny' ), 
        //         'campaign_share_pinterest'  => __( 'Share on Pinterest', 'benny' ),
        //         'campaign_share_widget'     => __( 'Share with embed code', 'benny' )
        //     );

        //     $wp_customize->add_section( 'campaign', array( 
        //         'priority' => $priority, 
        //         'title' => __( "Campaigns", 'benny' ), 
        //         'description' => __( 'Configure your campaign pages' )
        //     ) );

        //     $priority += 1; 

        //     foreach ( $sharing_options as $setting_key => $label ) {
        //         $wp_customize->add_setting( $setting_key, array( 'transport' => 'postMessage' ) );
        //         $wp_customize->add_control( $setting_key, array(
        //             'settings' => $setting_key, 
        //             'label' => $label,
        //             'section' => 'campaign', 
        //             'type' => 'checkbox', 
        //             'priority' => $priority
        //         ) );

        //         $priority += 1;
        //     }

        //     $wp_customize->add_setting( 'campaign_sharing_text', array( 'transport' => 'postMessage' ) );
        //     $wp_customize->add_control( new Sofa_Customize_Textarea_Control( $wp_customize, 'campaign_sharing_text', array(
        //         'settings' => 'campaign_sharing_text',
        //         'label' => __( 'Text to display on campaign sharing widget', 'benny' ), 
        //         'section' => 'campaign', 
        //         'type' => 'text', 
        //         'default' => __( 'Spread the word about this campaign by sharing this widget. Copy the snippet of HTML code below and paste it on your blog, website or anywhere else on the web.', 'benny' ),
        //         'priority' => $priority
        //     ) ) );

        //     $priority += 1;             
        // }

        /** 
         * Footer
         */
        $wp_customize->add_section( 'footer', array( 
            'title'     => __( 'Footer', 'benny' ), 
            'priority'  => $priority 
        ) );

        $priority += 1; 

        $wp_customize->add_setting( 'footer_notice', array( 
            'transport' => 'postMessage' 
        ) );
        $wp_customize->add_control( 'footer_notice', array( 
            'setting'   => 'footer_notice', 
            'label'     => __( 'Text for footer notice', 'benny' ), 
            'type'      => 'text', 
            'section'   => 'footer', 
            'priority'  => $priority
        ));

        $priority += 1; 

        /**
         * Social
         */ 
        $wp_customize->add_section( 'social', array( 
            'priority'      => 103, 
            'title'         => __( 'Social', 'benny' ),
            'description'   => __( 'Set up links to your online social presences', 'benny' )
        ) );

        // Loop over all the social sites the theme supports, creating settings and controls for each one
        foreach ( benny_get_social_sites() as $setting_key => $label ) {
            $wp_customize->add_setting( $setting_key, array( 
                'transport' => 'postMessage' 
            ) );
            $wp_customize->add_control( $setting_key, array( 
                'settings'  => $setting_key,
                'label'     => $label, 
                'section'   => 'social', 
                'type'      => 'text'
            ) );
        }
    }        

    /**
     * customize_controls_print_scripts
     * 
     * 
     */
     public function customize_controls_print_scripts() {
        ?>
        <script>
        ( function($){

            // Variables
            var $accent_colour, $accent_hover, $accent_text, $accent_text_secondary, $body_background, $body_text, $button_text, 
            $wrapper_background, $posts_background, $widget_background, $primary_border, $secondary_border, 
            $meta_colour, $footer_text, $footer_titles, $header_buttons, $header_buttons_hover, $palette,

            // Swaps a palette
            switchPalette = function() {                
                var colours = JSON.parse( $palette.find('input:checked').val() );
                
                // General link styling
                $accent_colour.wpColorPicker('color', colours.accent_colour);
                $accent_hover.wpColorPicker('color', colours.accent_hover);
                $accent_text.wpColorPicker('color', colours.accent_text);
                $accent_text_secondary.wpColorPicker('color', colours.accent_text_secondary);
                $body_background.wpColorPicker('color', colours.body_background);
                $body_text.wpColorPicker('color', colours.body_text);
                $button_text.wpColorPicker('color', colours.button_text);
                $wrapper_background.wpColorPicker('color', colours.wrapper_background);
                $posts_background.wpColorPicker('color', colours.posts_background);
                $widget_background.wpColorPicker('color', colours.widget_background);
                $primary_border.wpColorPicker('color', colours.primary_border);
                $secondary_border.wpColorPicker('color', colours.secondary_border);
                $meta_colour.wpColorPicker('color', colours.meta_colour);
                $footer_text.wpColorPicker('color', colours.footer_text);
                $footer_titles.wpColorPicker('color', colours.footer_titles);    
                $header_buttons.wpColorPicker('color', colours.header_buttons);    
                $header_buttons_hover.wpColorPicker('color', colours.header_buttons_hover);    
            };

            $(window).load(function() {             

                $accent_colour = $('.color-picker-hex', '#customize-control-accent_colour');
                $accent_hover = $('.color-picker-hex', '#customize-control-accent_hover');
                $accent_text = $('.color-picker-hex', '#customize-control-accent_text');
                $accent_text_secondary = $('.color-picker-hex', '#customize-control-accent_text_secondary');
                $body_background = $('.color-picker-hex', '#customize-control-body_background');
                $body_text = $('.color-picker-hex', '#customize-control-body_text');
                $button_text = $('.color-picker-hex', '#customize-control-button_text');
                $wrapper_background = $('.color-picker-hex', '#customize-control-wrapper_background');
                $posts_background = $('.color-picker-hex', '#customize-control-posts_background');
                $widget_background = $('.color-picker-hex', '#customize-control-widget_background');
                $primary_border = $('.color-picker-hex', '#customize-control-primary_border');
                $secondary_border = $('.color-picker-hex', '#customize-control-secondary_border');
                $meta_colour = $('.color-picker-hex', '#customize-control-meta_colour');
                $footer_text = $('.color-picker-hex', '#customize-control-footer_text');
                $footer_titles = $('.color-picker-hex', '#customize-control-footer_titles');
                $header_buttons = $('.color-picker-hex', '#customize-control-header_buttons');
                $header_buttons_hover = $('.color-picker-hex', '#customize-control-header_buttons_hover');
            
                $palette = $('#customize-control-palette'); 

                // When one of the preset palettes is selected, change the relevant colours
                $palette.on('change', function() {
                    switchPalette();
                });
            });
        })(jQuery);        
        </script>
        <?php
    }

    public function preview_styles() {
        ?>
        <style>
        .site-navigation .menu-site > li, 
        .menu-site li.hovering { 
            height: 1em; 
        }
        </style>
        <?php
    }
}