<?php
/**
 * A collection of handy functions that are used by the theme.
 *
 * @package 	Reach
 * @category 	Functions
 */

/**
 * Returns whether a given is enabled.  
 *
 * @return  boolean
 * @since   1.0.0
 */
function reach_has_module( $module ) {
    return in_array( 'charitable', reach_get_theme()->active_modules );
}

/**
 * Returns whether Charitable is enabled.  
 *
 * @return  boolean
 * @since   1.0.0
 */
function reach_has_charitable() {
    return reach_has_module( 'charitable' );
}

/**
 * Returns whether EDD is enabled.  
 *
 * @return  boolean
 * @since   1.0.0
 */
function reach_has_edd() {
    return reach_has_module( 'edd' );
}

/**
 * Return the home URL. Checks if WPML is installed and defers to the WPML function if it is. 
 * 
 * @return 	string
 * @since 	1.3.0
 */
function reach_site_url() {
	return function_exists('wpml_get_home_url') ? wpml_get_home_url() : site_url();
}

/**
 * Returns the given URL minus the 
 *
 * @param 	string 		$url
 * @return 	string
 * @since 	1.5.0
 */
function reach_condensed_url( $url ) {
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
function reach_hide_post_meta( $post = '' ) {
    if ( ! strlen( $post ) ) {
        global $post;
    }

    if ( function_exists( 'hide_meta_start' ) ) {
        return get_post_meta( $post->ID, '_hide_meta', true );
    }
    else {
        return get_post_meta( $post->ID, '_reach_hide_post_meta', true );
    } 
}

/**
 * Returns the array of supported social media sites for profiles. 
 *
 * @return  array
 * @since   2.0.0
 */
function reach_get_social_sites() {
    /* This is used for backwards compatibility. */
    $sites = apply_filters( 'sofa_social_sites', array() );

    $sites = array_merge( $sites, array(              
        'facebook'      => __( 'Facebook', 'reach' ),                
        'flickr'        => __( 'Flickr', 'reach' ), 
        'foursquare'    => __( 'Foursquare', 'reach' ), 
        'google-plus'   => __( 'Google+', 'reach' ),                                     
        'instagram'     => __( 'Instagram', 'reach' ),
        'linkedin'      => __( 'Linkedin', 'reach'),
        'pinterest'     => __( 'Pinterest', 'reach' ), 
        'renren'        => __( 'Renren', 'reach' ), 
        'skype'         => __( 'Skype', 'reach' ), 
        'tumblr'        => __( 'Tumblr', 'reach' ), 
        'twitter'       => __( 'Twitter', 'reach' ), 
        'vk'            => __( 'VK', 'reach' ), 
        'weibo'         => __( 'Weibo', 'reach' ), 
        'windows'       => __( 'Windows', 'reach' ), 
        'xing'          => __( 'Xing', 'reach' ), 
        'youtube'       => __( 'YouTube', 'reach' )
    ) );

    return apply_filters( 'reach_social_sites', $sites );
}

/**
 * The currently viewed author on an author archive. 
 * 
 * @return  string
 * @since   1.0.0
 */
function reach_get_current_author() {
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
function reach_get_banner_title() {
    $title = "";

    /* Homepage */
    if ( is_home() ) {
        if ( 'page' == get_option('show_on_front') ) {
            $title = get_the_title( get_option( 'page_for_posts' ) );
        }
        else {
            $title = apply_filters( 'reach_banner_title_blog', '' );
        }            
    }
    /* 404 Page */
    elseif ( is_404() ) {
        $title = apply_filters( 'reach_banner_title_404', '404' );
    }
    /* Author */
    elseif ( is_author() ) {
        $author = reach_get_current_author();
        $title = apply_filters( 'reach_banner_title_author', $author->display_name, $author );
    }
    /* Search Results */
    elseif ( is_search() ) {
        $title = apply_filters( 'reach_banner_title_search', __( 'Search Results', 'reach' ) );
    }   
    /* Post Type Archive */
    elseif ( is_post_type_archive() ) {
        $title = apply_filters( 'reach_banner_title_post_type_archive', post_type_archive_title( '', false ) );
    }
    /* General Archive */
    elseif ( is_archive() ) {
        $title = apply_filters( 'reach_banner_title_archive', single_term_title( '', false ) );
    }
    /* Fallback */
    else {
        $title = apply_filters( 'reach_banner_title_page', get_the_title() );
    } 
    
    return apply_filters( 'reach_banner_title', $title );
}

/**
 * Return the banner sub title for the current page. 
 *
 * @return  string
 * @since   2.0.0
 */
function reach_get_banner_subtitle() {
    $title = "";
    
    return apply_filters( 'reach_banner_subtitle', $title );   
}

/**
 * Return the media associated with the post.
 *
 * @param   array       $args
 * @return  string
 * @since   2.0.0
 */
function reach_get_media( $args = array() ) {
    $media = new Reach_Media_Grabber( $args );

    return $media->get_media();    
}

/**
 * Returns all anchors from the content.
 *
 * @param   string  $content
 * @return  array
 * @since   1.0.0
 */
function reach_get_first_anchor ($content ) {
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
function reach_strip_anchors( $content, $limit = 1 ) {
    return preg_replace( '/<a(.*)>(.*)<\/a>/', '', $content, $limit );
}

/**
 * Simple CSS compression. 
 *
 * Removes all comments, removes spaces after colons and strips out all the whitespace. 
 *
 * Based on http://manas.tungare.name/software/css-compression-in-php/
 *
 * @param string $css The block of CSS to be compressed. 
 * @return string The compressed CSS
 * @since 0.2
 */ 
if ( ! function_exists('reach_compress_css') ) : 

function reach_compress_css( $css ) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

    // Remove space after colons
    $css = str_replace(': ', ':', $css);
     
    // Remove whitespace
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);

    return $css;
}

endif; // End reach_compress_css

/**
 * This retrieves an image's post ID based on its URL.
 * Credit: http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 * @param string $image_url
 * @since 1.0.0
 */
function reach_get_image_id_from_url($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s;", $image_url ) ); 
    return $attachment[0]; 
}    


/**
 * Based on a single key, retrieve the data for a particular image, including whether it is retina and its dimensions.
 *
 * @param   string  $key
 * @return  false|array     False if not set, or array if set.
 * @since   1.0.0
 */
function reach_get_customizer_image_data( $key ) {
        $data = array();

        $key_value = reach_get_theme()->get_theme_setting( $key );

        if ( ! strlen( $key_value ) ) {
            return false;
        }

        $data['width'] = reach_get_theme()->get_theme_setting($key . '_width');
        $data['height'] = reach_get_theme()->get_theme_setting($key . '_height');

        if ( reach_get_theme()->get_theme_setting($key . '_is_retina') ) {

            /* Retrieve the post ID of the logo, then get the non-retina version */
            $data['id'] = get_theme_mod( $key . '_id' );
            $data['image'] = get_post_meta( $data['id'], '_non_retina', true );
            $data['retina_image'] = $key_value;
        }
        else {
            $data['image'] = $key_value;
        }

        return $data;
    } 