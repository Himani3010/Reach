<?php
/**
 * A collection of handy functions that are used by the theme.
 *
 * @package 	Franklin
 * @category 	Functions
 */

/**
 * Return the home URL. Checks if WPML is installed and defers to the WPML function if it is. 
 * 
 * @return 	string
 * @since 	1.3.0
 */
function franklin_site_url() {
	return function_exists('wpml_get_home_url') ? wpml_get_home_url() : site_url();
}

/**
 * Returns the given URL minus the 
 *
 * @param 	string 		$url
 * @return 	string
 * @since 	1.5.0
 */
function franklin_condensed_url( $url ) {
	$parts = parse_url($url);
	$output = $parts['host'];
	if ( isset( $parts['path'] ) ) {
		$output .= $parts['path'];
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
function franklin_hide_post_meta( $post = '' ) {
    if ( ! strlen( $post ) ) {
        global $post;
    }

    if ( function_exists( 'hide_meta_start' ) ) {
        return get_post_meta( $post->ID, '_hide_meta', true );
    }
    else {
        return get_post_meta( $post->ID, '_franklin_hide_post_meta', true );
    } 
}

/**
 * Returns the array of supported social media sites for profiles. 
 *
 * @return  array
 * @since   2.0.0
 */
function franklin_get_social_sites() {
    /* This is used for backwards compatibility. */
    $sites = apply_filters( 'sofa_social_sites', array() );

    $sites = array_merge( $sites, array(              
        'bitbucket'     => __( 'Bitbucket', 'franklin' ),                                               
        'dribbble'      => __( 'Dribbble', 'franklin' ), 
        'facebook'      => __( 'Facebook', 'franklin' ),                
        'flickr'        => __( 'Flickr', 'franklin' ), 
        'foursquare'    => __( 'Foursquare', 'franklin' ), 
        'github'        => __( 'Github', 'franklin' ), 
        'google-plus'   => __( 'Google+', 'franklin' ),                                     
        'gittip'        => __( 'Gittip', 'franklin' ),
        'instagram'     => __( 'Instagram', 'franklin' ),
        'linkedin'      => __( 'Linkedin', 'franklin'),
        'pinterest'     => __( 'Pinterest', 'franklin' ), 
        'renren'        => __( 'Renren', 'franklin' ), 
        'skype'         => __( 'Skype', 'franklin' ), 
        'trello'        => __( 'Trello', 'franklin' ), 
        'tumblr'        => __( 'Tumblr', 'franklin' ), 
        'twitter'       => __( 'Twitter', 'franklin' ), 
        'vk'            => __( 'VK', 'franklin' ), 
        'weibo'         => __( 'Weibo', 'franklin' ), 
        'windows'       => __( 'Windows', 'franklin' ), 
        'xing'          => __( 'Xing', 'franklin' ), 
        'youtube'       => __( 'YouTube', 'franklin' )
    ) );

    return apply_filters( 'franklin_social_sites', $sites );
}

/**
 * The currently viewed author on an author archive. 
 * 
 * @return  string
 * @since   1.0.0
 */
function franklin_get_current_author() {
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
function franklin_get_banner_title() {
    $title = "";

    /* Blog Home */
    if ( is_home() ) {
        if ( 'page' == get_option('show_on_front') ) {
            $title = get_the_title( get_option( 'page_for_posts' ) );
        }
        else {
            $title = apply_filters( 'franklin_banner_title_blog', '' );
        }            
    }
    /* 404 Page */
    elseif ( is_404() ) {
        $title = apply_filters( 'franklin_banner_title_404', '404' );
    }
    /* Author */
    elseif ( is_author() ) {
        $title = franklin_get_current_author()->nickname;
    }
    /* Search Results */
    elseif ( is_search() ) {
        $title = apply_filters( 'franklin_banner_title_search', __( 'Search Results', 'franklin' ) );
    }   
    /* Regular Page */
    elseif ( is_page() ) {
        $title = get_the_title();
    } 
    
    return apply_filters( 'franklin_banner_title', $title );   
}