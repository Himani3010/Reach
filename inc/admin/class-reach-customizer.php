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
        add_action('customize_preview_init', array( $this, 'load_theme_customizer_script' ) );
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
        $wp_customize->get_section( 'title_tagline' )->priority = 0;
        $wp_customize->get_control( 'blogname' )->priority = 1;       
        $wp_customize->get_control( 'blogdescription' )->priority = 2;

        $this->add_section( 'logo', $this->get_logo_section( 20 ) );
        $this->add_section( 'layout', $this->get_layout_section( 40 ) );        
        $this->add_section( 'colour', $this->get_colour_section( 60 ) );
        $this->add_section( 'header', $this->get_header_section( 80 ) );
        $this->add_section( 'footer', $this->get_footer_section( 100 ) );
        $this->add_section( 'campaigns', $this->get_campaign_section( 120 ) );
        $this->add_section( 'social', $this->get_social_profiles_section( 140 ) );

        /* Background Images Panel */
        $this->add_panel( 'background_images', $this->get_background_images_panel() );
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

        $wp_customize->add_panel( $panel_id, array(
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
     * Returns the logo section settings. 
     *
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_logo_section( $priority ) {
        $logo_settings = array(
            'title'     => __( 'Logo', 'reach' ), 
            'priority'  => $priority,
            'settings'  => array(
                'logo' => array(
                    'setting'   => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'esc_url_raw'
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'priority'      => $priority + 1
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_logo_section', $logo_settings );
    }

    /**
     * Returns the layout section settings. 
     *
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_layout_section( $priority ) {
        $layout_settings = array(
            'title'     => __( 'Layout', 'reach' ), 
            'priority'  => $priority,
            'settings'  => array(
                'layout' => array(
                    'setting'   => array(
                        'transport' => 'postMessage',
                        'default' => 'layout-wide', 
                        'sanitize_callback' => array( $this, 'sanitize_layout' )
                    ), 
                    'control'   => array(
                        'type'          => 'radio', 
                        'priority'      => $priority + 1,
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
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_colour_section( $priority ) {
        $colour_settings = array(
            'title'     => __( 'Colour', 'reach' ),
            'priority'  => $priority,
            'settings'  => array(
                'accent_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage', 
                        'default'           => '#7bb4e0',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ),
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => $priority + 1,
                        'label'             => __( 'Accent Colour', 'reach' ),
                        'description'       => __( 'Used for: site title, links, banner background, feature section background', 'reach' )
                    )
                ),
                'background_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage', 
                        'default'           => '#8e989e',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ),
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => $priority + 2,
                        'label'             => __( 'Body Background Colour', 'reach' ),
                        'description'       => __( 'Used for: site background', 'reach' )
                    )
                ),
                'text_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage', 
                        'default'           => '#70777c',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ),
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => $priority + 3,
                        'label'             => __( 'Text Colour', 'reach' ),
                        'description'       => __( 'Used for: text, buttons, site navigation', 'reach' )
                    )
                ), 
                'header_text_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage',
                        'default'           => '#ffffff',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ), 
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => $priority + 4,
                        'label'             => __( 'Header Text', 'reach' ),
                        'description'       => __( 'Used for social profile icons & button text', 'reach' )
                    )
                ),
                'footer_text_colour' => array(
                    'setting'   => array(
                        'transport'         => 'postMessage',
                        'default'           => '#ffffff',
                        'sanitize_callback' => 'sanitize_hex_color'
                    ), 
                    'control'   => array(
                        'control_type'      => 'WP_Customize_Color_Control',
                        'priority'          => $priority + 5,
                        'label'             => __( 'Footer Text', 'reach' )
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_colour_section', $colour_settings );
    }

    /**
     * Returns the header section settings. 
     *
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_header_section( $priority ) {
        $header_settings = array(
            'priority'  => $priority,
            'title'     => __( 'Header', 'reach' ),
            'settings'  => array(                 
                'hide_site_title'   => array(
                    'setting'       => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ), 
                    'control'       => array(
                        'label'     => __( 'Hide the title', 'reach' ),
                        'type'      => 'checkbox',
                        'priority'  => $priority + 2
                    )
                ),
                'hide_site_tagline' => array(
                    'setting'       => array(
                        'transport' => 'postMessage',
                        'sanitize_callback' => 'absint'
                    ), 
                    'control'       => array(
                        'label'     => __( 'Hide the tagline', 'reach' ),
                        'type'      => 'checkbox',
                        'priority'  => $priority + 3
                    )
                )          
            )
        );

        return apply_filters( 'reach_customizer_header_section', $header_settings );
    }

    /**
     * Returns the footer section settings. 
     *
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_footer_section( $priority ) {
        $footer_settings = array(
            'priority'  => $priority,
            'title'     => __( 'Footer', 'reach' ),
            'settings'  => array(                
                'footer_tagline' => array(
                    'setting'   => array(
                        'transport' => 'postMessage', 
                        'default'   => sprintf( 
                            '<a href="%s">%s</a>', 
                            esc_url( 'http://wordpress.org/' ), 
                            __( 'Proudly powered by WordPress', 'reach' ) 
                        ), 
                        'sanitize_callback' => 'esc_html'
                    ), 
                    'control'   => array(
                        'label'     => __( 'Tagline', 'reach' ), 
                        'type'      => 'text', 
                        'priority'  => $priority + 2,
                    )
                )                
            )
        );

        return apply_filters( 'reach_customizer_footer_section', $footer_settings );
    }

    /**
     * Returns the campaign section settings. 
     *
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_campaign_section( $priority ) {
        $campaign_setings = array(
            'priority'  => $priority,
            'title'     => __( 'Campaigns', 'reach' ),
            'active_callback' => 'charitable_is_campaign_page',
            'settings'  => array(
                'campaign_feature_background' => array(
                    'setting'   => array(
                        'default'       => '', 
                        'transport'     => 'postMessage', 
                        'sanitize_callback' => 'esc_url_raw'
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'label'         => __( 'Background image for campaign summary block', 'reach' ),
                        'priority'      => $priority + 1
                    )
                ), 
                'campaign_section_break_1' => array(
                    'setting' => array(
                        'sanitize_callback' => '__return_true'
                    ),
                    'control' => array(
                        'control_type'  => 'Reach_Customizer_Misc_Control',
                        'type'          => 'line',
                        'priority'      => $priority + 2
                    )
                ), 
                'campaign_media_placement' => array(
                    'setting' => array(
                        'default'       => 'featured_image_in_summary',
                        'transport'     => 'refresh', 
                        'sanitize_callback' => array( $this, 'sanitize_campaign_media_placement' )
                    ),
                    'control' => array(
                        'type'          => 'radio',
                        'label'         => __( 'Where would you like the campaign video and featured image to be displayed?', 'reach' ),
                        'priority'      => $priority + 3,
                        'choices'       => array(
                            'featured_image_in_summary' => __( 'Featured image in summary, video before content.', 'reach' ),
                            'video_in_summary' => __( 'Video in summary, featured image before content.', 'reach' )
                        )
                    )
                )
            )
        );

        return apply_filters( 'reach_customizer_campaign_section', $campaign_setings );
    }

    /**
     * Returns an array of social profiles settings. 
     *
     * @param   int     $priority
     * @return  array[]
     * @access  private
     * @since   1.0.0
     */
    private function get_social_profiles_section( $priority ) {
        $social_settings = array(
            'priority'      => $priority, 
            'title'         => __( 'Social Profiles', 'reach' ),
            'description'   => __( 'Enter the complete URL to your profile for each service below that you would like to share.', 'reach' ),
            'settings'      => array()
        );        

        foreach ( reach_get_social_sites() as $setting_key => $label ) {
            $priority += 1;

            $social_settings[ 'settings' ][ $setting_key ] = array(
                'setting'   => array(
                    'transport' => 'postMessage',
                    'sanitize_callback' => 'esc_url_raw' 
                ),
                'control'   => array(
                    'type'      => 'text',
                    'priority'  => $priority,
                    'label'     => $label
                )
            );            
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
                        'sanitize_callback' => 'esc_url_raw' 
                    ), 
                    'control'   => array(
                        'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
                        'priority'      => 52
                    )
                )
            )
        );

        // $background_images_settings[ 'sections' ][ 'background_images_campaign' ] = array(
        //     'title'         => __( 'Featured Campaign Block', 'reach' ), 
        //     'priority'      => 53,
        //     'settings'      => array(
        //         'campaign_feature_background' => array(
        //             'setting'   => array(
        //                 'default'       => '', 
        //                 'transport'     => 'postMessage', 
        //             ), 
        //             'control'   => array(
        //                 'control_type'  => 'Reach_Customizer_Retina_Image_Control', 
        //                 'priority'      => 54
        //             )
        //         )
        //     )
        // );

        $background_images_settings[ 'sections' ][ 'background_images_blog' ] = array(
            'title'         => __( 'Blog & Page Banners', 'reach' ), 
            'priority'      => 55,
            'settings'      => array(
                'blog_banner_background' => array(
                    'setting'   => array(
                        'default'       => '', 
                        'transport'     => 'postMessage',
                        'sanitize_callback' => 'esc_url_raw'
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
        // ( function($){

        //     // Variables
        //     var $accent_colour, $accent_hover, $accent_text, $accent_text_secondary, $body_background, $body_text, $button_text, 
        //     $wrapper_background, $posts_background, $widget_background, $primary_border, $secondary_border, 
        //     $meta_colour, $footer_text, $footer_titles, $header_buttons, $header_buttons_hover, $palette,

        //     // Swaps a palette
        //     switchPalette = function() {                
        //         var colours = JSON.parse( $palette.find('input:checked').val() );
                
        //         // General link styling
        //         $accent_colour.wpColorPicker('color', colours.accent_colour);
        //         $accent_hover.wpColorPicker('color', colours.accent_hover);
        //         $accent_text.wpColorPicker('color', colours.accent_text);
        //         $accent_text_secondary.wpColorPicker('color', colours.accent_text_secondary);
        //         $body_background.wpColorPicker('color', colours.body_background);
        //         $body_text.wpColorPicker('color', colours.body_text);
        //         $button_text.wpColorPicker('color', colours.button_text);
        //         $wrapper_background.wpColorPicker('color', colours.wrapper_background);
        //         $posts_background.wpColorPicker('color', colours.posts_background);
        //         $widget_background.wpColorPicker('color', colours.widget_background);
        //         $primary_border.wpColorPicker('color', colours.primary_border);
        //         $secondary_border.wpColorPicker('color', colours.secondary_border);
        //         $meta_colour.wpColorPicker('color', colours.meta_colour);
        //         $footer_text.wpColorPicker('color', colours.footer_text);
        //         $footer_titles.wpColorPicker('color', colours.footer_titles);    
        //         $header_buttons.wpColorPicker('color', colours.header_buttons);    
        //         $header_buttons_hover.wpColorPicker('color', colours.header_buttons_hover);    
        //     };

        //     $(window).load(function() {             

        //         $accent_colour = $('.color-picker-hex', '#customize-control-accent_colour');
        //         $accent_hover = $('.color-picker-hex', '#customize-control-accent_hover');
        //         $accent_text = $('.color-picker-hex', '#customize-control-accent_text');
        //         $accent_text_secondary = $('.color-picker-hex', '#customize-control-accent_text_secondary');
        //         $body_background = $('.color-picker-hex', '#customize-control-body_background');
        //         $body_text = $('.color-picker-hex', '#customize-control-body_text');
        //         $button_text = $('.color-picker-hex', '#customize-control-button_text');
        //         $wrapper_background = $('.color-picker-hex', '#customize-control-wrapper_background');
        //         $posts_background = $('.color-picker-hex', '#customize-control-posts_background');
        //         $widget_background = $('.color-picker-hex', '#customize-control-widget_background');
        //         $primary_border = $('.color-picker-hex', '#customize-control-primary_border');
        //         $secondary_border = $('.color-picker-hex', '#customize-control-secondary_border');
        //         $meta_colour = $('.color-picker-hex', '#customize-control-meta_colour');
        //         $footer_text = $('.color-picker-hex', '#customize-control-footer_text');
        //         $footer_titles = $('.color-picker-hex', '#customize-control-footer_titles');
        //         $header_buttons = $('.color-picker-hex', '#customize-control-header_buttons');
        //         $header_buttons_hover = $('.color-picker-hex', '#customize-control-header_buttons_hover');
        //     });
        // })(jQuery);        
        </script>
        <?php
    }

    /**
     * Load the theme-customizer.js file. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function load_theme_customizer_script() {
        wp_register_script( 'theme-customizer', get_template_directory_uri().'/js/admin/theme-customizer.js', array( 'jquery', 'customize-preview' ), '1.0.0-20150710i', true );
        wp_enqueue_script( 'theme-customizer' );        
    }

    /**
     * Sanitize the layout setting. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function sanitize_layout( $value ) {
        if ( ! in_array( $value, array( 'layout-wide', 'layout-boxed' ) ) ) {
            $value = 'layout-wide';
        }

        return $value;
    }

    /**
     * Sanitize the value for the campaign media placement setting. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function sanitize_campaign_media_placement( $value ) {
        if ( ! in_array( $value, array( 'featured_image_in_summary', 'video_in_summary' ) ) ) {
            $value = 'featured_image_in_summary';
        }

        return $value;
    }    
}