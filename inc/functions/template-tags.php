<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package 	Franklin
 * @category 	Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'franklin_paging_nav' ) ) :

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @return 	void
	 * @since 	1.0.0
	 */
	function franklin_paging_nav() {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'franklin' ); ?></h1>
			<div class="nav-links">

				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'franklin' ) ); ?></div>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'franklin' ) ); ?></div>
				<?php endif; ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}

endif;

if ( ! function_exists( 'franklin_post_nav' ) ) :

	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @return 	void
	 * @since 	1.0.0
	 */
	function franklin_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'franklin' ); ?></h1>
			<div class="nav-links">
				<?php
					previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span>&nbsp;%title', 'Previous post link', 'franklin' ) );
					next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title&nbsp;<span class="meta-nav">&rarr;</span>', 'Next post link',     'franklin' ) );
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}

endif;

if ( ! function_exists( 'franklin_posted_on' ) ) :
	
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @return 	void
	 * @since 	1.0.0
	 */
	function franklin_posted_on() {
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
			_x( 'Posted on %s', 'post date', 'franklin' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			_x( 'by %s', 'post author', 'franklin' ),
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
function franklin_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'franklin_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'franklin_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so franklin_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so franklin_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in franklin_categorized_blog.
 *
 * @return 	void
 * @since 	1.0.0
 */
function franklin_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'franklin_categories' );
}

add_action( 'edit_category', 'franklin_category_transient_flusher' );
add_action( 'save_post',     'franklin_category_transient_flusher' );


if ( !function_exists( 'franklin_site_title' ) ) : 
	
	/**
	 * Displays the site title class. 
	 * 
	 * @param 	bool 		$echo
	 * @return 	string|void
	 * @since 	1.0.0
	 */
	function franklin_site_title( $echo = true ) {

		$classes = get_theme_mod('hide_site_title') ? 'site-title hidden' : 'site-title';
		$classes = apply_filters( 'franklin_site_title_class', $classes );

		// Set up HTML 
		$html = is_front_page() ? '<h1 ' : '<div ';
		$html .= 'class="'.$classes.'">';
		$html .= '<a href="'.site_url().'" title="'.__( 'Go to homepage', 'franklin' ).'">';
		$html .= get_bloginfo('title');
		$html .= '</a>';
		$html .= is_front_page() ? '</h1>' : '</div>';

		// Allow child themes to filter this
		$html = apply_filters( 'franklin_site_title', $html );

		if ( $echo == false )
			return $html;

		echo $html;
	}

endif;

if ( !function_exists( 'franklin_site_tagline' ) ) :

	/**
	 * Displays the site tagline, if there is one and it's not set to be hidden.
	 * 
	 * @param 	bool 		$echo
	 * @return 	void
	 * @since 	1.0.0
	 */
	function franklin_site_tagline($echo = true) {
		$classes = get_theme_mod('hide_site_tagline') ? 'site-tagline hidden' : 'site-tagline';

		$html = '<h3 class="'.$classes.'">'.get_bloginfo('description').'</h3>';
		$html = apply_filters( 'franklin_site_tagline', $html );

		if ( $echo == false )
			return $html;
		
		echo $html;
	}

endif;

if ( !function_exists( 'franklin_post_header' ) ) :

	/**
	 * Displays the post title. 
	 * 
	 * @param 	bool 		$echo
	 * @return 	string|void
	 * @since 	2.0.0
	 */
	function franklin_post_header( $echo = true ) {
		global $post;

		$post_format = get_post_format();

		// Default title
		$post_title = get_the_title();
		
		if ( strlen($post_title) == 0 )
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
		if ( $post_format == 'link' ) {
			$title = franklin_link_format_title(false);
		}
		elseif ( $post_format == 'status' ) {
			$title = get_the_content();
		}
		else {
			$title = sprintf( '<a href="%s" title="%s">%s</a>', 
				get_permalink(),
				sprintf( __('Link to %s', 'franklin'), $post_title ),
				$post_title );	
		}	

		$output = $wrapper_start . $title . $wrapper_end;

		if ( $echo === false )
			return $output;

		echo $output;
	}

endif;

if ( !function_exists( 'franklin_video_format_the_content' ) ) :

	/**
	 * Prints the content for a video post.
	 * 
	 * @return 	void
	 * @since 	1.0.0
	 */ 
	function franklin_video_format_the_content($more_link_text = null, $stripteaser = false) {
		$content = get_the_content($more_link_text, $stripteaser);
		$content = franklin_strip_embed_shortcode( $content, 1 );
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);		
		echo $content;
	}

endif;

if ( !function_exists( 'franklin_link_format_the_content' ) ) :
	/**
	 * Prints the content for a link post.
	 * 
	 * @param 	string 		$more_link_text
	 * @param 	string 		$stripteaser
	 * @param 	bool 		$echo
	 * @return 	void|string
	 * @since 	1.0.0
	 */
	function franklin_link_format_the_content($more_link_text = null, $stripteaser = false, $echo = true) {
		$content = get_the_content($more_link_text, $stripteaser);
		$content = franklin_strip_anchors( $content, 1 );
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);		

		if ($echo === false) 
			return $content;

		echo $content;
	}
endif;

if ( !function_exists( 'franklin_link_format_title' ) ) :
	/**
	 * Returns or prints the title for a link post.
	 * 
	 * @uses 	franklin_link_format_title
	 * @param 	bool 		$echo
	 * @return 	string
	 * @since 	1.0.0
	 */
	function franklin_link_format_title($echo = true) {
		global $post;
		$anchors = franklin_get_first_anchor($post->post_content);

		// If there are no anchors, just return the normal title.
		if ( empty($anchors) ) 
			return '<a href="'.get_permalink().'" title="Go to '.$post->post_title.'">'.$post->post_title.'</a>';

		$anchor = apply_filters( 'franklin_link_format_title', $anchors[0] );

		if ( $echo === false )
			return $anchor;

		echo $anchor;
	}

endif;