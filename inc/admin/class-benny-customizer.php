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
     * @param   Benny_Theme     $theme
     * @return  void
     */
    public static function start( Benny_Theme $theme ) {        
        if ( ! $theme->is_start() ) {
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
        add_action('after_setup_theme', array( $this, 'setup_callbacks' ) );        
    } 

    /**
     * Set up callbacks for the class.
     *
     * @return  void
     * @since   1.6.0
     */
    public function setup_callbacks() {
        add_action('customize_save_after', array( $this, 'customize_save_after' ) );
        add_action('customize_register', array( $this, 'customize_register' ) );     
        add_action('customize_controls_print_scripts', array( $this, 'customize_controls_print_scripts' ), 100 );
        add_action('wp_head', array( $this, 'preview_styles' ) );
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
        delete_transient( Benny_Customizer_Styles::get_transient_key() );
    }

    /**
     * Theme customization. 
     *
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     */
    public function customize_register($wp_customize) {
        
        /* Logo section */

        $wp_customize->add_section( 'logo', array(
            'title'     => __( 'Logo', 'benny' ), 
            'priority'  => 30
        ) );

        $wp_customize->add_setting( 'logo', array( 
            'transport' => 'postMessage',
            'sanitize_callback' => 'esc_url_raw'
        ) );

        $wp_customize->add_control( new Sofa_Customizer_Retina_Image_Control( 
            $wp_customize, 
            'logo',
            array(
                'settings' => 'logo',
                'section'  => 'logo',
                'label'    => __( 'Logo', 'benny' ), 
                'priority' => 32
            )             
        ) );

        /* Colour section */

        $wp_customize->add_section( 'colour', array(
            'title'     => __( 'Colour', 'benny' ),
            'priority'  => 40
        ) );

        $wp_customize->add_setting( 'accent_colour', array( 
            'transport' => 'postMessage', 
            'default'   => '#d95b43',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );        

        $wp_customize->add_control( new WP_Customize_Color_Control( 
            $wp_customize, 
            'accent_colour', 
            array( 
                'label' => __( 'Accent Colour', 'benny' ), 
                'section' => 'colour', 
                'settings' => 'accent_colour',
                'priority' => 42
            )            
        ) );

        $wp_customize->add_setting( 'background_colour', array( 
            'transport' => 'postMessage', 
            'default'   => '#aea198',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( 
            $wp_customize, 
            'background_colour', 
            array( 
                'label' => __( 'Background Colour', 'benny' ), 
                'section' => 'colour', 
                'settings' => 'background_colour',
                'priority' => 44
            )
        ) );

        $wp_customize->add_setting( 'text_colour', array( 
            'transport' => 'postMessage', 
            'default'   => '#7d6e63',
            'sanitize_callback' => 'sanitize_hex_color'
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( 
            $wp_customize, 
            'text_colour', 
            array( 
                'label' => __( 'Text Colour', 'benny' ), 
                'section' => 'colour', 
                'settings' => 'text_colour',
                'priority' => 46
            )
        ) );

        /* Title & tagline section */        
        // $wp_customize->add_setting( 'hide_site_title', array( 'transport' => 'postMessage' ) );
        // $wp_customize->add_setting( 'hide_site_tagline', array( 'transport' => 'postMessage' ) );        
        // $wp_customize->add_control( 'hide_site_title', array(
        //     'settings'      => 'hide_site_title', 
        //     'label'         => __( 'Hide the site title', 'benny' ),
        //     'section'       => 'title_tagline', 
        //     'type'          => 'checkbox'            
        // ) );
        // $wp_customize->add_control( 'hide_site_tagline', array(
        //     'settings'      => 'hide_site_tagline', 
        //     'label'         => __( 'Hide the tagline', 'benny' ),
        //     'section'       => 'title_tagline', 
        //     'type'          => 'checkbox'            
        // ) );
    
        /* Colors */
        // $priority = 10;
        // foreach ( Benny_Customizer_Styles::get_customizer_colours() as $key => $colour ) {          
        //     $wp_customize->add_setting( $key, array( 
        //         'default'   => $colour['default'], 
        //         'transport' => 'postMessage' 
        //     ) );
        //     $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, array( 
        //         'label'     => $colour['title'], 
        //         'section'   => 'colors', 
        //         'settings'  => $key, 
        //         'priority'  => $priority )
        //     ));
        //     $priority += 1;
        // }

        /* Backgrounds */

        $wp_customize->add_panel( 'background_images_panel', array(
            'title' => __( 'Background Images', 'benny' ),
            'priority' => 50
        ) );

        $wp_customize->add_section( 'background_images', array( 
            'title'         => __( 'Background Images', 'benny' ), 
            'priority'      => 51, 
            'panel'         => 'background_images_panel'
        ) );

        $wp_customize->add_setting( 'body_background_image', array( 
            'default'       => '', 
            'transport'     => 'postMessage' 
        ) );        

        $wp_customize->add_control( new Sofa_Customizer_Retina_Image_Control( 
            $wp_customize, 
            'body_background_image',
            array(
                'settings' => 'body_background_image',
                'section'  => 'background_images',                
                'label'    => __( 'Body', 'benny' ),
                'priority' => 52
            )
        ) );

        $wp_customize->add_control( new Benny_Customizer_Misc_Control(
            $wp_customize,
            'body_background_control_group',
            array(
                'section'   => 'background_images',
                'priority'  => 53,
                'type'      => 'line',
            )
        ) );

        $wp_customize->add_setting( 'campaign_feature_background', array( 
            'default'       => '', 
            'transport'     => 'postMessage' 
        ) );

        $wp_customize->add_control( new Sofa_Customizer_Retina_Image_Control( 
            $wp_customize, 
            'campaign_feature_background',
            array(
                'settings' => 'campaign_feature_background',
                'section'  => 'background_images',                
                'label'    => __( 'Featured Campaign Block', 'benny' ),
                'priority' => 54
            )
        ) );

        $wp_customize->add_setting( 'blog_banner_background', array( 
            'default'       => '', 
            'transport'     => 'postMessage'            
        ) );

        $wp_customize->add_control( new Sofa_Customizer_Retina_Image_Control( 
            $wp_customize, 
            'blog_banner_background',
            array(
                'settings' => 'blog_banner_background',
                'section'  => 'background_images',                
                'label'    => __( 'Blog & Page Banners', 'benny' ),
                'priority' => 56
            )
        ) );        

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
    //          
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

        /* Footer */
        $wp_customize->add_section( 'footer', array( 
            'title'     => __( 'Footer', 'benny' ), 
            'priority'  => 60 
        ) );

        $wp_customize->add_setting( 'footer_tagline', array( 
            'transport' => 'postMessage' 
        ) );

        $wp_customize->add_control( 'footer_tagline', array( 
            'setting'   => 'footer_tagline', 
            'label'     => __( 'Footer Text', 'benny' ), 
            'type'      => 'text', 
            'section'   => 'footer', 
            'priority'  => 62
        ));

        /* Social Profiles */ 

        $wp_customize->add_section( 'social', array( 
            'priority'      => 70, 
            'title'         => __( 'Social Profiles', 'benny' ),
            'description'   => __( 'Set up links to your online social presences', 'benny' )
        ) );

        $priority = 72;

        foreach ( benny_get_social_sites() as $setting_key => $label ) {                    

            $wp_customize->add_setting( $setting_key, array( 
                'transport' => 'postMessage' 
            ) );

            $wp_customize->add_control( $setting_key, array( 
                'settings'  => $setting_key,
                'label'     => $label, 
                'section'   => 'social', 
                'type'      => 'text',
                'priority'  => $priority
            ) );

            $priority += 2;
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