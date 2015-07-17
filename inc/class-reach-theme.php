<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Reach_Theme' ) ) : 

/**
 * Core theme class. Everything starts here.
 *
 * The purpose of this class is to encapsulate all the core theme definitions
 * inside a single class, to avoid namespace collisions.
 *
 * @package 	Reach
 * @subpackage 	Core
 * @author 		Studio 164a
 * @since 		1.0.0
 */
class Reach_Theme {

	/**
	 * The one and only class instance. 
	 *
	 * @var 	Reach_Theme
	 * @static
	 * @access  private
	 */
	private static $instance = null;

	/**
	 * The theme version. 
	 */
	const VERSION = '1.0.0-20150717';

	/**
	 * Database version number. 
	 *
	 * This is different to the theme version since it is used only to 
	 * manage theme updates that require some sort of upgrade process. 
	 *
	 * It is in the following format: YYYYMMDD
	 */
	const DATABASE_VERSION = '20150303';

	/**
	 * Whether crowdfunding is enabled. 
	 *
	 * @var 	string[]
	 * @access  public
	 */
	public $active_modules = array();

	/**
	 * Retrieve the class instance. If one hasn't been created yet, create it first. 
	 *
	 * @return 	Reach_Theme
	 * @static
	 * @access  public
	 * @since 	1.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Reach_Theme();
		}

		return self::$instance;
	}

	/**
	 * Class constructor. 
	 *
	 * This is only called once, since the only way to instantiate
	 * the theme is with the get_instance() method above.
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function __construct() {		
		
		$this->load_dependencies();

		$this->maybe_upgrade();

		$this->maybe_start_charitable();

        $this->maybe_start_edd();        

        $this->maybe_start_jetpack();

        $this->maybe_start_tribe_events();

        $this->maybe_start_easy_google_fonts();   

        $this->maybe_start_customizer();

		$this->attach_hooks_and_filters();

		/**
		 * If you want to do anything during the start of the 
		 * theme, use this hook. You can also use this hook
		 * to remove any of the hooks or filters called during 
		 * this phase.
		 */
		do_action( 'reach_theme_start', $this );
	}

	/**
	 * Returns the version number of the theme. 
	 *
	 * @return 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public function get_theme_version() {
		if ( defined( 'REACH_DEBUG' ) && REACH_DEBUG ) {
            return time();
        }

        return self::VERSION;	
	}

	/**
	 * Checks whether the theme's start hook has already run.  
	 *
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function started() {
		return did_action( 'reach_theme_start' );
	}

	/**
	 * Checks whether we are currently on the `reach_theme_start` hook.
	 *	
	 * @return 	boolean
	 * @access  public
	 * @since 	1.0.0
	 */
	public function is_start() {
		return 'reach_theme_start' == current_filter();
	}

    /**
     * Returns the theme's user-defined settings. 
     *
     * @param boolean $use_cached Set this to false if you want to re-fetch the settings.
     * @return array
     * @access public
     * @since 1.0
     */
    public function get_theme_settings($use_cached = true) {
        if ( ! isset( $this->settings ) || $use_cached === false ) {
            $this->settings = get_theme_mods();
        }
        return $this->settings; 
    }

    /**
     * Retuns the value for a given setting. 
     *
     * @param string $key The key of the setting you want to get. 
     * @param boolean $use_cached Set this to false if you want to re-fetch the settings.
     * @return mixed
     * @access public
     * @since 1.0
     */
    public function get_theme_setting($key, $use_cached = true) {
        $settings = $this->get_theme_settings($use_cached);
        return isset( $settings[$key] ) ? $settings[$key] : false;
    }

	/**
	 * Load required files. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function load_dependencies() {
		require get_template_directory() . '/inc/vendors/hybrid-media-grabber.php';
		require get_template_directory() . '/inc/class-reach-media-grabber.php';
		require get_template_directory() . '/inc/class-reach-customizer-styles.php';
		require get_template_directory() . '/inc/functions/template-tags.php';
		require get_template_directory() . '/inc/functions/helper-functions.php';
		require get_template_directory() . '/inc/functions/comments.php';
		require get_template_directory() . '/inc/functions/compatibility.php';
	}

	/**
	 * Check whether the theme has been updated and needs an upgrade.  
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function maybe_upgrade() {
		$db_version = get_option( 'reach_version' );

		if ( self::DATABASE_VERSION !== $db_version ) {

			require_once( get_template_directory() . '/inc/class-reach-upgrade.php' );

			Reach_Upgrade::upgrade_from( $db_version, self::DATABASE_VERSION );
		}
	}

	/**
	 * Load up the Customizer helper class if we're using the Customizer. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function maybe_start_customizer() {
		global $wp_customize;

        if ( $wp_customize ) {

            require_once( get_template_directory() . '/inc/admin/class-reach-customizer.php');
            require_once( get_template_directory() . '/inc/admin/customizer-controls/class-reach-customizer-misc-control.php');
            require_once( get_template_directory() . '/inc/admin/customizer-controls/class-reach-customizer-retina-image-control.php');

            add_action( 'reach_theme_start', array( 'Reach_Customizer', 'start' ) );
        } 
	}

	/**
     * Set up Jetpack support if it's enabled.
     *
     * @return 	void
     * @access 	private
     * @since 	1.0
     */
    private function maybe_start_jetpack() {
        if ( defined( 'JETPACK__VERSION' ) ) {

            require_once( get_template_directory() . '/inc/jetpack/class-reach-jetpack.php');

            add_action( 'reach_theme_start', array( 'Reach_Jetpack', 'start' ) );

            $this->active_modules[] = 'jetpack';
        }        
    }

    /**
     * Set up Tribe Events support if it's enabled. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_start_tribe_events() {
        if ( class_exists( 'TribeEvents' ) ) {

            require_once( get_template_directory() . '/inc/tribe-events/class-reach-tribe-events.php' );

            add_action( 'reach_theme_start', array( 'Reach_Tribe_Events', 'start' ) );

            $this->active_modules[] = 'tribe-events';
        }
    }

    /**
     * Set up Easy Google Fonts support if it is enabled. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_start_easy_google_fonts() {
        if ( class_exists( 'Easy_Google_Fonts' ) ) {

            require_once( get_template_directory() . '/inc/easy-google-fonts/class-reach-easy-google-fonts.php' );

            add_action( 'reach_theme_start', array( 'Reach_Easy_Google_Fonts', 'start' ) );

            $this->active_modules[] = 'easy-google-fonts';
        }
    }

    /**
     * Set up Charitable support if it is enabled.
     *
     * @return 	void
     * @access  private
     * @since 	1.0.0
     */
    private function maybe_start_charitable() {    	
    	if ( class_exists( 'Charitable' ) ) {

    		require_once( get_template_directory() . '/inc/charitable/class-reach-charitable.php' );

    		add_action( 'reach_theme_start', array( 'Reach_Charitable', 'start' ) );

    		$this->active_modules[] = 'charitable';
    	}
    }

    /**
     * Set up EDD support if it is enabled. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_start_edd() {    
        if ( class_exists( 'Easy_Digital_Downloads' )  && class_exists( 'Charitable_EDD' ) ) {

            require_once( get_template_directory() . '/inc/easy-digital-downloads/class-reach-edd.php' );

            add_action( 'reach_theme_start', array( 'Reach_EDD', 'start' ) );

            $this->active_modules[] = 'edd';
        }
    }

	/**
	 * Set up callback methods for various core WordPress hooks and filters. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function attach_hooks_and_filters() {
		/**
		 * Core theme classes hooked in on the `reach_theme_start` hook. 
		 */
		add_action( 'reach_theme_start',		array( 'Reach_Customizer_Styles', 'start' ) );
        
		/**
		 * Methods within this class that are hooked into core WordPress action hooks. 
		 */
		add_action( 'after_setup_theme', 		array( $this, 'setup_theme' ) );
		add_action( 'widgets_init', 			array( $this, 'setup_sidebars' ) );
		add_action( 'wp_enqueue_scripts', 		array( $this, 'setup_scripts' ) );		
		add_action( 'wp', 						array( $this, 'setup_author' ) );
		add_action( 'wp_footer', 				array( $this, 'setup_fonts' ) );
		
		/**
		 * Methods within this class that are hooked into core WordPress filter hooks.
		 */
		add_filter( 'wp_title', 				array( $this, 'wp_title' ), 10, 2 );
		add_filter( 'wp_page_menu_args', 		array( $this, 'page_menu_args' ) );
        add_filter( 'body_class',               array( $this, 'body_classes' ) );
		add_filter( 'post_class',      			array( $this, 'post_classes' ) );
		add_filter(	'the_content_more_link', 	array( $this, 'the_content_more_link_filter' ), 10, 2);
        add_filter(	'next_posts_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
        add_filter(	'previous_posts_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
        add_filter(	'next_comments_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );
        add_filter(	'previous_comments_link_attributes', array( $this, 'posts_navigation_link_attributes' ) );                
        add_filter(	'oembed_dataparse', 		array( $this, 'wrap_fullwidth_videos' ), 10, 3 );
        add_filter( 'hybrid_media_grabber_valid_shortcodes', array( $this, 'add_valid_media_grabber_shortcodes' ) );
        add_filter(	'video_embed_html', 		'reach_fullwidth_video' );        
	}

	/**
	 * Set up main theme supports and definitions. 
	 *
	 * @hook 	after_setup_theme
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function setup_theme() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'reach', get_template_directory() . '/languages' );

		/** 
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link 	http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 786, 0, false );
        add_image_size( 'campaign-thumbnail-summary', 640, 427, true );
        add_image_size( 'campaign-thumbnail-medium', 527, 351, true );
        add_image_size( 'campaign-thumbnail-small', 351, 234, true );
        add_image_size( 'widget-thumbnail', 294, 882, false );

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary_navigation' => __( 'Primary Menu', 'reach' ),
		) );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		) );

		/**
		 * Enable support for Post Formats.
		 * 
		 * @link 	http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'quote', 'link',
		) );

        /**
         * Enable support for Hide Meta plugin.
         */
        add_theme_support( 'hide-meta' );
	}

	/**
	 * Set up sidebars.  
	 *
	 * @hook 	widgets_init
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function setup_sidebars() {
		register_sidebar( array(
            'id'            => 'default',            
            'name'          => __( 'Default sidebar', 'reach' ),
            'description'   => __( 'The default sidebar.', 'reach' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));  

        register_sidebar( array(
            'id'            => 'sidebar_campaign',            
            'name'          => __( 'Campaign sidebar', 'reach' ),
            'description'   => __( 'The campaign sidebar.', 'reach' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));  

        register_sidebar( array(
            'id'            => 'campaign_after_content',            
            'name'          => __( 'Campaign below content', 'reach' ),
            'description'   => __( 'Displayed below the campaign\'s content, but above the comment section.', 'reach' ),
            'before_widget' => '<aside id="%1$s" class="widget block content-block cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h3 class="widget-title">',
            'after_title'   => '</h3></div>'
        ));        

        register_sidebar( array(
            'id'            => 'footer_left',            
            'name'          => __( 'Footer left', 'reach' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        )   );

        register_sidebar( array(
            'id'            => 'footer_right',            
            'name'          => __( 'Footer right', 'reach' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));
	}

	/**
	 * Register and enqueue scripts and stylesheets.  
	 *
	 * @hook 	wp_enqueue_scripts
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function setup_scripts() {
        $theme_dir = untrailingslashit( get_template_directory_uri() );
        
        wp_register_style( 'reach-base', $theme_dir . '/css/base.css', array(), $this->get_theme_version() );
		wp_register_style( 'reach-style', $theme_dir . '/css/main.css', array(), $this->get_theme_version() );
        wp_enqueue_style( 'reach-style' );

		$ext = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.js' : '.min.js'; 
		
		// Allow other scripts to add their scripts to the dependencies.
        $reach_script_dependencies = apply_filters( 'reach_script_dependencies', array( 
            'jquery-ui-accordion', 
            'audio-js', 
            'rrssb',
            'hoverIntent',             
            'fitvids',
            'jquery' 
        ) );

        if ( ! wp_script_is( 'lean-modal', 'registered' ) ) {
            wp_register_script( 'lean-modal', $theme_dir . '/js/vendors/leanmodal/jquery.leanModal' . $ext, array( 'jquery' ), $this->get_theme_version(), true );            
        }

        wp_register_script( 'audio-js', $theme_dir . '/js/vendors/audiojs/audio.min.js', array(), $this->get_theme_version(), true);
        wp_register_script( 'rrssb', $theme_dir . '/js/vendors/rrssb/rrssb.min.js', array('jquery'), $this->get_theme_version(), true );
        wp_register_script( 'fitvids', $theme_dir . '/js/vendors/fitvids/jquery.fitvids.min.js', array('jquery'), '1.0', true );
		wp_register_script( 'reach-lib', $theme_dir . '/js/reach-lib' . $ext, $reach_script_dependencies, $this->get_theme_version(), true );
        wp_register_script( 'reach', $theme_dir . '/js/reach.js', array( 'reach-lib' ), $this->get_theme_version(), true );
        wp_enqueue_script( 'reach' ); 

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( reach_has_charitable() ) {
			wp_localize_script('reach', 'REACH_CROWDFUNDING', array(
	            'need_minimum_pledge'   => __( 'Your pledge must be at least the minimum pledge amount.', 'reach' ), 
	            'years'                 => __( 'Years', 'reach' ), 
	            'months'                => __( 'Months', 'reach' ), 
	            'weeks'                 => __( 'Weeks', 'reach' ), 
	            'days'                  => __( 'Days', 'reach' ), 
	            'hours'                 => __( 'Hours', 'reach' ), 
	            'minutes'               => __( 'Minutes', 'reach' ), 
	            'seconds'               => __( 'Seconds', 'reach' ), 
	            'year'                  => __( 'Year', 'reach' ), 
	            'month'                 => __( 'Month', 'reach' ), 
	            'day'                   => __( 'Day', 'reach' ), 
	            'hour'                  => __( 'Hour', 'reach' ), 
	            'minute'                => __( 'Minute', 'reach' ), 
	            'second'                => __( 'Second', 'reach' ), 
	            'timezone_offset'       => reach_get_timezone_offset()
	        ) ); 
		}		
	}

	/**
	 * Sets the authordata global when viewing an author archive.
	 *
	 * This provides backwards compatibility with
	 * http://core.trac.wordpress.org/changeset/25574
	 *
	 * It removes the need to call the_post() and rewind_posts() in an author
	 * template to print information about the author.
	 *
	 * @hook 	wp
	 * @global 	WP_Query 	$wp_query 		WordPress Query object.
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function setup_author() {
		global $wp_query;

		if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
			$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
		}
	}

	/**
	 * Set up custom fonts. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function setup_fonts() {
		echo apply_filters( 'reach_font_link', "<link href='//fonts.googleapis.com/css?family=Merriweather:400,400italic,700italic,700,300italic,300|Oswald:400,300' rel='stylesheet' type='text/css'>" );
	}

	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @hook 	wp_title
	 * @param 	string 	$title 		Default title text for current view.
	 * @param 	string 	$sep 		Optional separator.
	 * @return 	string 				The filtered title.
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'reach' ), max( $paged, $page ) );
		}

		return $title;
	}

	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * @hook 	page_menu_args
	 * @param 	array 	$args 		Configuration arguments.
	 * @return 	array
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}

    /**
     * Adds the layout class to the body. 
     *
     * @param   string[] $classes
     * @return  string[]
     * @access  public
     * @since   1.0.0
     */
    public function body_classes( $classes ) {
        $layout = $this->get_theme_setting( 'layout', true );
        $classes[] = $layout ? $layout : 'layout-wide';

        if ( is_page_template( 'page-template-user-dashboard.php' ) ) {
            $classes[] = 'user-dashboard';
        } 

        return $classes;
    }

	/**
     * Filters the post class.
     * 
     * @param   string[] $classes
     * @return  string[]
     * @since   1.0.0
     */
    public function post_classes( $classes ) {
        if ( is_page_template( 'page-template-home-slider.php' ) ) {
            return array_merge( $classes, array( 'feature-block', 'center', 'block' ) );
        }

        return array_merge( $classes, array( 'block', 'entry-block' ) );
    }

    /**
     * Filters the "more" link on post archives.
     *
     * @return  string
     * @since   1.0.0
     */
    public function the_content_more_link_filter($more_link, $more_link_text = null) {
        $post = get_post();
        return sprintf( '<span class="aligncenter"><a href="%s" class="more-link button-alt" title="%s &#8220;%s&#8221;">%s</a></span>', 
            get_permalink(), 
            _x( 'Keep reading', 'keep reading post', 'reach' ), 
            get_the_title(), 
            __( 'Continue Reading', 'reach' ) 
        );
    }

    /**
     * Filters the next & previous posts links.
     * 
     * @return  string
     * @since   1.0.0
     */
    public function posts_navigation_link_attributes() {
        return 'class="button-alt button-small"';
    }   

	/**
     * Wrap videos inside fit_video class.
     * 
     * @param 	string 		$html 
     * @param 	WP_oEmbed 	$data
     * @param 	string 		$url
     * @return 	string
     * @since 	1.0.0
     */
    public function wrap_fullwidth_videos( $html, $data, $url ) {
    	if ( $data->type == 'video'  ) {
			return reach_fullwidth_video( $html );
        }
        return $html;
    } 

    /**
     * Add some custom shortcodes as valid shortcodes for the Reach_Media_Grabber class. 
     *
     * @param   string[] $shortcodes
     * @return  string[]
     * @access  public
     * @since   1.0.0
     */
    public function add_valid_media_grabber_shortcodes( $shortcodes ) {
        $shortcodes[] = 'layerslider';
        $shortcodes[] = 'rev_slider';
        $shortcodes[] = 'soliloquy';
        return $shortcodes;
    }
}

endif; // End class_exists