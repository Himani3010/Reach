<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package 	Reach
 * @category 	Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'reach_paging_nav' ) ) :

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @param 	string $older_posts 	Text to display for older posts button. 	
	 * @param 	string $newer_posts		Text to display for newer posts button.
	 * @return 	void
	 * @since 	1.0.0
	 */
	function reach_paging_nav( $older_posts = '', $newer_posts = '' ) {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}

		if ( empty( $older_posts ) ) {
			$older_posts = __( 'Older Posts', 'reach' );
		}

		if ( empty( $newer_posts ) ) {
			$newer_posts = __( 'Newer Posts', 'reach' );
		}

		?>
		<nav class="navigation paging-navigation pagination" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'reach' ); ?></h1>
			<ul>
				<?php if ( get_next_posts_link() ) : ?>
				<li class="nav-previous"><?php next_posts_link( $older_posts ) ?></li>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<li class="nav-next"><?php previous_posts_link( $newer_posts ) ?></li>
				<?php endif; ?>
			</ul>
		</nav><!-- .navigation -->
		<?php
	}

endif;

if ( ! function_exists( 'reach_post_nav' ) ) :

	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @return 	void
	 * @since 	1.0.0
	 */
	function reach_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'reach' ); ?></h1>
			<div class="nav-links">
				<?php
					previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'reach' ) );
					next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'reach' ) );
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}

endif;

if ( ! function_exists( 'reach_posted_on' ) ) :
	
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @return 	void
	 * @since 	1.0.0
	 */
	function reach_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			_x( 'Posted on %s', 'post date', 'reach' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			_x( 'by %s', 'post author', 'reach' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

	}

endif;

if ( ! function_exists( 'the_archive_description' ) ) :

	/**
	 * Shim for `the_archive_description()`.
	 *
	 * Display category, tag, or term description.
	 *
	 * @todo 	Remove this function when WordPress 4.3 is released.
	 *
	 * @param 	string 	$before 	Optional. Content to prepend to the description. Default empty.
	 * @param 	string 	$after  	Optional. Content to append to the description. Default empty.
	 * @return 	void 
	 * @since 	1.0.0
	 */
	function the_archive_description( $before = '', $after = '' ) {
		$description = apply_filters( 'get_the_archive_description', term_description() );

		if ( ! empty( $description ) ) {
			/**
			 * Filter the archive description.
			 *
			 * @see term_description()
			 *
			 * @param string $description Archive description to be displayed.
			 */
			echo $before . $description . $after;
		}
	}

endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return 	bool
 * @since 	1.0.0
 */
function reach_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'reach_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'reach_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so reach_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so reach_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in reach_categorized_blog.
 *
 * @return 	void
 * @since 	1.0.0
 */
function reach_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'reach_categories' );
}

add_action( 'edit_category', 'reach_category_transient_flusher' );
add_action( 'save_post',     'reach_category_transient_flusher' );

if ( ! function_exists( 'reach_site_identity' ) ) : 

/**
 * Displays the site identity section. 
 *
 * This may include the logo, site title and/or site tagline.
 *
 * @param 	boolean 	$echo
 * @return 	string
 * @since 	1.0.0
 */
function reach_site_identity( $echo = true ) {
	$output = "";	

	$site_url = site_url();
	$site_title = get_bloginfo( 'title' );
	$hide_title = get_theme_mod( 'hide_site_title' );
	$hide_tagline = get_theme_mod( 'hide_site_tagline' );
	$logo = reach_get_customizer_image_data( 'logo' );
	
	if ( $logo ) {
		$output .= apply_filters( 'reach_site_logo', sprintf( '<a href="%s" title="%s"><img src="%s" alt="%s" width="%s" height="%s" %s/></a>',  
			$site_url,
		__( 'Go to homepage', 'reach' ),
			$logo[ 'image' ], 
			$site_title, 
			$logo[ 'width' ],
			$logo[ 'height' ],
			isset( $logo[ 'retina_image' ] ) ? "srcset={$logo[ 'retina_image' ]}" : ''
		) );
	}

	$tag = is_front_page() ? 'h1' : 'div';
	$output .= apply_filters( 'reach_site_title', sprintf( '<%s class="site-title %s"><a href="%s" title="%s">%s</a></%s>', 
		$tag,
		$hide_title ? 'hidden' : '',
		$site_url,
		__( 'Go to homepage', 'reach' ),
		$site_title,
		$tag
	) );

	$output .= apply_filters( 'reach_site_tagline', sprintf( '<h3 class="site-tagline %s">%s</h3>', 
		$hide_tagline ? 'hidden' : '',
		get_bloginfo( 'description' )
	) );

	if ( ! $echo ) {
		return $output;
	}

	echo $output;
}

endif;

if ( ! function_exists( 'reach_post_header' ) ) :

/**
 * Displays the post title. 
 * 
 * @param 	bool 		$echo
 * @return 	string|void
 * @since 	2.0.0
 */
function reach_post_header( $echo = true ) {
	global $post;

	$post_format = get_post_format();

	if ( ! strlen( get_the_title() ) )
		return '';

	// Set up the wrapper
	if ( is_single() ) {
		$wrapper_start = '<h1 class="post-title">';
		$wrapper_end = '</h1>';
	}
	else {
		$wrapper_start = '<h2 class="post-title">';
		$wrapper_end = '</h2>';
	}

	// Link posts have a different title setup
	if ( 'link' == $post_format ) {
		$title = reach_link_format_title(false);
	}
	elseif ( 'status' == $post_format ) {
		$title = get_the_content();
	}
	else {
		$title = sprintf( '<a href="%s" title="%s">%s</a>', 
			get_permalink(),
			the_title_attribute( array( 'before' => __('Link to', 'reach'), 'echo' => false ) ),
			get_the_title() 
		);	
	}	

	$output = $wrapper_start . $title . $wrapper_end;

	if ( $echo === false )
		return $output;

	echo $output;
}

endif;

if ( ! function_exists( 'reach_video_format_the_content' ) ) :

/**
 * Prints the content for a video post.
 * 
 * @return 	void
 * @since 	1.0.0
 */ 
function reach_video_format_the_content($more_link_text = null, $stripteaser = false) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = reach_strip_embed_shortcode( $content, 1 );
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);		
	echo $content;
}

endif;

if ( ! function_exists( 'reach_link_format_the_content' ) ) :

/**
 * Prints the content for a link post.
 * 
 * @param 	string 		$more_link_text
 * @param 	string 		$stripteaser
 * @param 	bool 		$echo
 * @return 	void|string
 * @since 	1.0.0
 */
function reach_link_format_the_content($more_link_text = null, $stripteaser = false, $echo = true) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = reach_strip_anchors( $content, 1 );
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);		

	if ($echo === false) 
		return $content;

	echo $content;
}

endif;

if ( ! function_exists( 'reach_link_format_title' ) ) :

/**
 * Returns or prints the title for a link post.
 * 
 * @uses 	reach_link_format_title
 * @param 	bool 		$echo
 * @return 	string
 * @since 	1.0.0
 */
function reach_link_format_title($echo = true) {
	global $post;
	$anchors = reach_get_first_anchor( $post->post_content );

	// If there are no anchors, just return the normal title.
	if ( empty( $anchors ) ) 
		return sprintf( '<a href="%s" title="%s">%s</a>', get_permalink(), $post->post_title, $post->post_title );

	$anchor = apply_filters( 'reach_link_format_title', $anchors[0] );

	if ( $echo === false )
		return $anchor;

	echo $anchor;
}

endif;

if ( ! function_exists( 'reach_fullwidth_video' ) ) : 

/**
 * Wraps the video in the fit-video class to ensure it is displayed at fullwidth.
 *
 * @param 	string 		$video
 * @return 	string
 * @since 	1.0.0
 */
function reach_fullwidth_video( $video ) {
	return sprintf( '<div class="fit-video">%s</div>', $video );
}

endif;