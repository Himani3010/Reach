<?php
/**
 * A collection of handy functions that are used by the theme.
 *
 * @package 	Benny
 * @category 	Functions
 */

/**
 * Returns whether a given is enabled.  
 *
 * @return  boolean
 * @since   1.0.0
 */
function benny_has_module( $module ) {
    return in_array( 'charitable', benny_get_theme()->active_modules );
}

/**
 * Returns whether Charitable is enabled.  
 *
 * @return  boolean
 * @since   1.0.0
 */
function benny_has_charitable() {
    return benny_has_module( 'charitable' );
}

/**
 * Returns whether EDD is enabled.  
 *
 * @return  boolean
 * @since   1.0.0
 */
function benny_has_edd() {
    return benny_has_module( 'edd' );
}

/**
 * Return the home URL. Checks if WPML is installed and defers to the WPML function if it is. 
 * 
 * @return 	string
 * @since 	1.3.0
 */
function benny_site_url() {
	return function_exists('wpml_get_home_url') ? wpml_get_home_url() : site_url();
}

/**
 * Returns the given URL minus the 
 *
 * @param 	string 		$url
 * @return 	string
 * @since 	1.5.0
 */
function benny_condensed_url( $url ) {
	$parts = parse_url($url);

    if ( isset( $parts[ 'hosts' ] ) ) {

        $output = $parts[ 'host' ];

        if ( isset( $parts[ 'path' ] ) ) {
            $output .= $parts[ 'path' ];
        }

    }
    else {
        $output = $url;
    }
	
	return $output;
}

/**
 * A helper function to determine whether the current post should have the meta displayed.
 *
 * @param   WP_Post     $post       Optional. If a post is not passed, the current $post object will be used.
 * @return  boolean
 * @since   1.0.0
 */
function benny_hide_post_meta( $post = '' ) {
    if ( ! strlen( $post ) ) {
        global $post;
    }

    if ( function_exists( 'hide_meta_start' ) ) {
        return get_post_meta( $post->ID, '_hide_meta', true );
    }
    else {
        return get_post_meta( $post->ID, '_benny_hide_post_meta', true );
    } 
}

/**
 * Returns the array of supported social media sites for profiles. 
 *
 * @return  array
 * @since   2.0.0
 */
function benny_get_social_sites() {
    /* This is used for backwards compatibility. */
    $sites = apply_filters( 'sofa_social_sites', array() );

    $sites = array_merge( $sites, array(              
        'bitbucket'     => __( 'Bitbucket', 'benny' ),                                               
        'dribbble'      => __( 'Dribbble', 'benny' ), 
        'facebook'      => __( 'Facebook', 'benny' ),                
        'flickr'        => __( 'Flickr', 'benny' ), 
        'foursquare'    => __( 'Foursquare', 'benny' ), 
        'github'        => __( 'Github', 'benny' ), 
        'google-plus'   => __( 'Google+', 'benny' ),                                     
        'gittip'        => __( 'Gittip', 'benny' ),
        'instagram'     => __( 'Instagram', 'benny' ),
        'linkedin'      => __( 'Linkedin', 'benny'),
        'pinterest'     => __( 'Pinterest', 'benny' ), 
        'renren'        => __( 'Renren', 'benny' ), 
        'skype'         => __( 'Skype', 'benny' ), 
        'trello'        => __( 'Trello', 'benny' ), 
        'tumblr'        => __( 'Tumblr', 'benny' ), 
        'twitter'       => __( 'Twitter', 'benny' ), 
        'vk'            => __( 'VK', 'benny' ), 
        'weibo'         => __( 'Weibo', 'benny' ), 
        'windows'       => __( 'Windows', 'benny' ), 
        'xing'          => __( 'Xing', 'benny' ), 
        'youtube'       => __( 'YouTube', 'benny' )
    ) );

    return apply_filters( 'benny_social_sites', $sites );
}

/**
 * The currently viewed author on an author archive. 
 * 
 * @return  string
 * @since   1.0.0
 */
function benny_get_current_author() {
    return get_query_var('author_name')
        ? get_user_by( 'slug', get_query_var( 'author_name' ) ) 
        : get_userdata( get_query_var( 'author' ) );
}

/**
 * Return the banner title for the current page. 
 *
 * @return  string
 * @since   2.0.0
 */
function benny_get_banner_title() {
    $title = "";

    /* User Dashboard */
    if ( function_exists( 'charitable_user_dashboard' ) && charitable_user_dashboard()->in_nav() ) {
        $title = apply_filters( 'benny_banner_title_dashboard', __( 'Dashboard', 'benny' ) );
    }
    /* Blog Home */
    elseif ( is_home() ) {
        if ( 'page' == get_option('show_on_front') ) {
            $title = get_the_title( get_option( 'page_for_posts' ) );
        }
        else {
            $title = apply_filters( 'benny_banner_title_blog', '' );
        }            
    }
    /* 404 Page */
    elseif ( is_404() ) {
        $title = apply_filters( 'benny_banner_title_404', '404' );
    }
    /* Author */
    elseif ( is_author() ) {
        $author = benny_get_current_author();
        $title = apply_filters( 'benny_banner_title_author', $author->display_name, $author );
    }
    /* Search Results */
    elseif ( is_search() ) {
        $title = apply_filters( 'benny_banner_title_search', __( 'Search Results', 'benny' ) );
    }   
    /* Regular Page */
    elseif ( is_page() ) {
        $title = get_the_title();
    } 
    
    return apply_filters( 'benny_banner_title', $title );   
}

/**
 * Return the banner sub title for the current page. 
 *
 * @return  string
 * @since   2.0.0
 */
function benny_get_banner_subtitle() {
    $title = "";
    
    return apply_filters( 'benny_banner_subtitle', $title );   
}

/**
 * Return the media associated with the post.
 *
 * @param   array       $args
 * @return  string
 * @since   2.0.0
 */
function benny_get_media( $args = array() ) {
    $media = new Benny_Media_Grabber( $args );

    return $media->get_media();    
}

/**
 * Returns all anchors from the content.
 *
 * @param   string  $content
 * @return  array
 * @since   1.0.0
 */
function benny_get_first_anchor ($content ) {
    preg_match( '/<a(.*)>(.*)<\/a>/', $content, $matches );
    return $matches;
}

/**
 * Strips anchors from the content, up to the set limit (defaults to 1).
 * 
 * @param   string  $content
 * @param   int     $limit
 * @return  string     
 * @since   1.0.0
 */
function benny_strip_anchors( $content, $limit = 1 ) {
    return preg_replace( '/<a(.*)>(.*)<\/a>/', '', $content, $limit );
}

