<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Franklin_Theme' ) ) : 

/**
 * Core theme class. Everything starts here.
 *
 * The purpose of this class is to encapsulate all the core theme definitions
 * inside a single class, to avoid namespace collisions.
 *
 * @package 	Franklin
 * @subpackage 	Core
 * @author 		Studio 164a
 * @since 		2.0.0
 */
class Franklin_Theme {

	/**
	 * The one and only class instance. 
	 *
	 * @var 	Franklin_Theme
	 * @static
	 * @access  private
	 */
	private static $instance = null;

	/**
	 * The theme version. 
	 */
	const VERSION = '2.0.0';

	/**
	 * Database version number. 
	 *
	 * This is different to the theme version since it is used only to 
	 * manage theme updates that require some sort of upgrade process. 
	 *
	 * It is in the following format: YYYYMMDD
	 */
	const DATABASE_VERSION = '20141127';

	/**
	 * Retrieve the class instance. If one hasn't been created yet, create it first. 
	 *
	 * @return 	Franklin_Theme
	 * @static
	 * @access  public
	 * @since 	2.0.0
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Franklin_Theme();
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
	 * @since 	2.0.0
	 */
	private function __construct() {		
		
		$this->load_dependencies();

		$this->maybe_upgrade();

        $this->maybe_start_customizer();

        $this->maybe_start_jetpack();

		$this->attach_hooks_and_filters();

		/**
		 * If you want to do anything during the start of the 
		 * theme, use this hook. You can also use this hook
		 * to remove any of the hooks or filters called during 
		 * this phase.
		 */
		do_action( 'franklin_theme_start', $this );
	}

	/**
	 * Returns the version number of the theme. 
	 *
	 * @return 	string
	 * @access  public
	 * @since 	2.0.0
	 */
	public function get_theme_version() {
		if ( defined( 'FRANKLIN_DEBUG' ) && FRANKLIN_DEBUG ) {
            return time();
        }

        return self::VERSION;	
	}

	/**
	 * Checks whether the theme's start hook has already run.  
	 *
	 * @return 	boolean
	 * @access  public
	 * @since 	2.0.0
	 */
	public function started() {
		return did_action( 'franklin_theme_start' );
	}

	/**
	 * Checks whether we are currently on the `franklin_theme_start` hook.
	 *	
	 * @return 	boolean
	 * @access  public
	 * @since 	2.0.0
	 */
	public function is_start() {
		return 'franklin_theme_start' == current_filter();
	}

	/**
	 * Load required files. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	2.0.0
	 */
	private function load_dependencies() {
		require get_template_directory() . '/inc/class-franklin-customizer-styles.php';
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
	 * @since 	2.0.0
	 */
	private function maybe_upgrade() {
		$db_version = get_option( 'franklin_version' );

		if ( self::DATABASE_VERSION !== $db_version ) {

			require_once( get_template_directory() . '/inc/class-franklin-upgrade.php' );

			Franklin_Upgrade::upgrade_from( $db_version, self::DATABASE_VERSION );
		}
	}

	/**
	 * Load up the Customizer helper class if we're using the Customizer. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	2.0.0
	 */
	public function maybe_start_customizer() {
		global $wp_customize;

        if ( $wp_customize ) {

            require_once( get_template_directory() . '/inc/admin/class-franklin-customizer.php');

            add_action( 'franklin_theme_start', array( 'Franklin_Customizer', 'start' ) );
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

            require_once( get_template_directory() . '/inc/jetpack/class-franklin-jetpack.php');

            add_action( 'wpcharitable_theme_start', array( 'Franklin_Jetpack', 'start' ) );
        }        
    }

	/**
	 * Set up callback methods for various core WordPress hooks and filters. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	2.0.0
	 */
	private function attach_hooks_and_filters() {
		/**
		 * Core theme classes hooked in on the `franklin_theme_start` hook. 
		 */
		add_action( 'franklin_theme_start',		array( 'Franklin_Customizer_Styles', 'start' ) );

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
		add_filter( 'post_class',      			array( $this, 'post_classes' ) );
	}

	/**
	 * Set up main theme supports and definitions. 
	 *
	 * @hook 	after_setup_theme
	 * @return 	void
	 * @access  public
	 * @since 	2.0.0
	 */
	public function setup_theme() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'franklin', get_template_directory() . '/languages' );

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

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary_navigation' => __( 'Primary Menu', 'franklin' ),
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
	}

	/**
	 * Set up sidebars.  
	 *
	 * @hook 	widgets_init
	 * @return 	void
	 * @access  public
	 * @since 	2.0.0
	 */
	public function setup_sidebars() {
		register_sidebar( array(
            'id'            => 'default',            
            'name'          => __( 'Default sidebar', 'franklin' ),
            'description'   => __( 'The default sidebar.', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));  

        register_sidebar( array(
            'id'            => 'sidebar_campaign',            
            'name'          => __( 'Campaign sidebar', 'franklin' ),
            'description'   => __( 'The campaign sidebar.', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        ));  

        register_sidebar( array(
            'id'            => 'campaign_after_content',            
            'name'          => __( 'Campaign below content', 'franklin' ),
            'description'   => __( 'Displayed below the campaign\'s content, but above the comment section.', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget block content-block cf %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h2 class="block-title">',
            'after_title'   => '</h2></div>'
        ));        

        register_sidebar( array(
            'id'            => 'footer_left',            
            'name'          => __( 'Footer left', 'franklin' ),
            'before_widget' => '<aside id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<div class="title-wrapper"><h4 class="widget-title">',
            'after_title'   => '</h4></div>'
        )   );

        register_sidebar( array(
            'id'            => 'footer_right',            
            'name'          => __( 'Footer right', 'franklin' ),
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
	 * @since 	2.0.0
	 */
	public function setup_scripts() {
		wp_enqueue_style( 'franklin-style', get_stylesheet_uri() );

		wp_enqueue_script( 'franklin-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

		wp_enqueue_script( 'franklin-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
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
	 * @since 	2.0.0
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
	 * @since 	2.0.0
	 */
	public function setup_fonts() {
		echo apply_filters( 'franklin_font_link', "<link href='//fonts.googleapis.com/css?family=Merriweather:400,400italic,700italic,700,300italic,300|Oswald:400,300' rel='stylesheet' type='text/css'>" );
	}

	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @hook 	wp_title
	 * @param 	string 	$title 		Default title text for current view.
	 * @param 	string 	$sep 		Optional separator.
	 * @return 	string 				The filtered title.
	 * @access 	public
	 * @since 	2.0.0
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
			$title .= " $sep " . sprintf( __( 'Page %s', 'franklin' ), max( $paged, $page ) );
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
	 * @since 	2.0.0
	 */
	public function page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}

	/**
     * Filters the post class.
     * 
     * @param   array   $classes
     * @return  array
     * @since   1.0.0
     */
    public function post_classes($classes) {
        return array_merge( $classes, array('block', 'entry-block') );
    }
}

endif; // End class_exists