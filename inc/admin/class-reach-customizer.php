<?php
/**
 * Sets up the Wordpress customizer
 *
 * @since 1.2
 */

class Reach_Customizer {

    /**
     * Instantiate the object, but only if this is the start phase. 
     *
     * @static
     * @param   Reach_Theme     $theme
     * @return  void
     */
    public static function start( Reach_Theme $theme ) {        
        if ( ! $theme->is_start() ) {
            return;
        }

        new Reach_Customizer();
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
        delete_transient( Reach_Customizer_Styles::get_transient_key() );
    }

    /**
     * Theme customization. 
     *
     * @param   WP_Customize_Manager $wp_customize
     * @return  void
     */
    public function customize_register($wp_customize) {

        /* Site Title & Tagline Section */
        $wp_customize->get_control( 'blogname' )->priority = 5;
        $wp_customize->get_control( 'blogdescription' )->priority = 10;
        $this->add_section_settings( 'title_tagline', $this->get_title_tagline_settings() );        
        
        /* Logo Section */
        $this->add_section( 'logo', $this->get_logo_section() );

        /* Layout Section */
        $this->add_section( 'layout', $this->get_layout_section() );
        
        /* Colour Section */
        $this->add_section( 'colour', $this->get_colour_section() );        

        /* Social Profiles Section */
        $this->add_section( 'social', $this->get_social_profiles_section() );

        /* Background Images Panel */
        $this->add_panel( 'background_images', $this->get_background_images_panel() );                

        /* Footer */
        // $wp_customize->add_section( 'footer', array( 
        //     'title'     => __( 'Footer', 'reach' ), 
        //     'priority'  => 60 
        // ) );

        // $wp_customize->add_setting( 'footer_tagline', array( 
        //     'transport' => 'postMessage' 
        // ) );

        // $wp_customize->add_control( 'footer_tagline', array( 
        //     'setting'   => 'footer_tagline', 
        //     'label'     => __( 'Footer Text', 'reach' ), 
        //     'type'      => 'text', 
        //     'section'   => 'footer', 
        //     'priority'  => 62
        // ));
    }        

    /**
     * Adds a panel. 
     *
     * @param   string  $panel_id
     * @param   array   $panel
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_panel( $panel_id, $panel ) {
        global $wp_customize;

        if ( empty( $panel ) ) {
            return;
        }
            
        $priority = $panel[ 'priority' ];

        $wp_customize->add_panel( 'background_images', array(
            'title' => $panel[ 'title' ],
            'priority' => $panel[ 'priority' ]  
        ) );

        $this->add_panel_sections( $panel_id, $panel[ 'sections' ] );
    }

    /**
     * Adds sections to a panel.
     *
     * @param   string  $panel_id
     * @param   array   $sections
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function add_panel_sections( $panel_id = false, $sections ) {
        global $wp_customize;

        if ( empty( $sections ) ) {
            return;
        }

        foreach ( $sections as $section_id => $section ) {
            $this->add_section( $section_id, $section, $panel_id );            
        }
    }

    /**
     * Adds section & settings
     *
     * @param   string  $section_id
     * @param   array   $section
     * @param   string  $panel
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function add_section( $section_id, $section, $panel = "" ) {
        global $wp_customize;

        if ( empty( $section ) ) {
            return;
        }

        $settings = $section[ 'settings' ];
        unset( $section[ 'settings' ] );

        if ( ! empty( $panel ) ) {
            $section[ 'panel' ] = $panel;
        } 

        $wp_customize->add_section( $section_id, $section );

        $this->add_section_settings( $section_id, $settings );
    }


    /**
     * Adds settings to a given section. 
     *
     * @param   string  $section_id
     * @param   array   $settings
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function add_section_settings( $section_id, $settings ) {
        global $wp_customize;

        if ( empty( $settings ) ) {
            return;
        }

        foreach ( $settings as $setting_id => $setting ) {                
            $wp_customize->add_setting( $setting_id, $setting[ 'setting' ] );        

            $setting_control = $setting[ 'control' ];
            $setting_control[ 'section' ] = $section_id;

            if ( isset( $setting_control[ 'control_type' ] ) ) {

                $setting_control_type = $setting_control[ 'control_type' ];

                unset( $setting_control[ 'control_type'] );

                $wp_customize->add_control( new $setting_control_type( $wp_customize, $setting_id, $setting_control ) );

            }
            else {

                $wp_customize->add_control( $setting_id, $setting_control );

            }
        }
    }

    /**
     * Returns the settings for the Site Title & Tagline section. 
     *
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_title_tagline_settings() {
        $title_tagline_settings = array(
            'hide_site_title'   => array(
                'setting'       => array(
                    'transport' => 'postMessage'
                ), 
                'control'       => array(
                    'label'     => __( 'Hide the title', 'reach' ),
                    'type'      => 'checkbox',
                    'priority'  => 6
                )
            ),
            'hide_site_tagline' => array(
                'setting'       => array(
                    'transport' => 'postMessage'
                ), 
                'control'       => array(
                    'label'     => __( 'Hide the tagline', 'reach' ),
                    'type'      => 'checkbox',
                    'priority'  => 11
                )
            )
        );
        
        return apply_filters( 'reach_customizer_title_tagline_section', $title_tagline_settings );
    }

    /**
     * Returns the logo section settings. 
     *
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_logo_section() {
        $logo_settings = array(
            'title'     => __( 'Logo', 'reach' ), 
            'priority'  => 30,
            'settings'  => array(
                'logo' => array(
                    'setting'   => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'esc_url_raw'
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'priority'      => 31
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_logo_section', $logo_settings );
    }

    /**
     * Returns the layout section settings. 
     *
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_layout_section() {
        $layout_settings = array(
            'title'     => __( 'Layout', 'reach' ), 
            'priority'  => 35,
            'settings'  => array(
                'layout' => array(
                    'setting'   => array(
                        'transport' => 'postMessage',
                        'default' => 'layout-wide'
                    ), 
                    'control'   => array(
                        'type'          => 'radio', 
                        'priority'      => 36,
                        'choices'       => array(
                            'layout-wide' => __( 'Wide Layout', 'reach' ),
                            'layout-boxed' => __( 'Boxed Layout', 'reach' )
                        )
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_layout_section', $layout_settings );
    }

    /**
     * Returns the colour section settings. 
     *
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_colour_section() {
        $colour_settings = array(
            'title'     => __( 'Colour', 'reach' ),
            'priority'  => 40,
            'settings'  => array(
                'accent_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage', 
                        'default'           => '#d95b43',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ),
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => 41,
                        'label'             => __( 'Accent Colour', 'reach' ),
                        'description'       => __( 'Used for: site title, links, banner background, feature section background', 'reach' )
                    )
                ),
                'background_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage', 
                        'default'           => '#aea198',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ),
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => 42,
                        'label'             => __( 'Body Background Colour', 'reach' ),
                        'description'       => __( 'Used for: site background', 'reach' )
                    )
                ),
                'text_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage', 
                        'default'           => '#7d6e63',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ),
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => 43,
                        'label'             => __( 'Text Colour', 'reach' ),
                        'description'       => __( 'Used for: text, buttons, site navigation', 'reach' )
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_colour_section', $colour_settings );
    }

    /**
     * Returns an array of social profiles settings. 
     *
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_social_profiles_section() {
        $priority = 60;

        $social_settings = array(
            'priority'      => $priority, 
            'title'         => __( 'Social Profiles', 'reach' ),
            'description'   => __( 'Enter the complete URL to your profile for each service below that you would like to share.', 'reach' ),
            'settings'      => array()
        );        

        foreach ( reach_get_social_sites() as $setting_key => $label ) {
            $social_settings[ 'settings' ][ $setting_key ] = array(
                'setting'   => array(
                    'transport' => 'postMessage' 
                ),
                'control'   => array(
                    'type'      => 'text',
                    'priority'  => $priority,
                    'label'     => $label
                )
            );

            $priority += 1;
        }

        return apply_filters( 'reach_customizer_social_section', $social_settings );
    }

    /**
     * Returns an array of background image settings.
     *
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_background_images_panel() {
        $background_images_settings = array(
            'title'         => __( 'Background Images', 'reach' ),
            'priority'      => 50, 
            'sections'      => array()
        );

        $background_images_settings[ 'sections' ][ 'background_images_body' ] = array(
            'title'         => __( 'Body', 'reach' ), 
            'priority'      => 51,
            'settings'      => array(
                'body_background' => array(
                    'setting'   => array(
                        'default'       => '', 
                        'transport'     => 'postMessage', 
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'priority'      => 52
                    )
                )
            )
        );

        $background_images_settings[ 'sections' ][ 'background_images_campaign' ] = array(
            'title'         => __( 'Featured Campaign Block', 'reach' ), 
            'priority'      => 53,
            'settings'      => array(
                'campaign_feature_background' => array(
                    'setting'   => array(
                        'default'       => '', 
                        'transport'     => 'postMessage', 
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'priority'      => 54
                    )
                )
            )
        );

        $background_images_settings[ 'sections' ][ 'background_images_blog' ] = array(
            'title'         => __( 'Blog & Page Banners', 'reach' ), 
            'priority'      => 55,
            'settings'      => array(
                'blog_banner_background' => array(
                    'setting'   => array(
                        'default'       => '', 
                        'transport'     => 'postMessage', 
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'priority'      => 56
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_background_images_panel', $background_images_settings );
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