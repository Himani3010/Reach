<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reach
 */

get_header();

if ( have_posts() ) : 

	get_template_part( 'banner' ); ?>

	<main class="site-main content-area" role="main">
		
		<?php 
		while ( have_posts() ) : 
			the_post(); 

			get_template_part( 'content', get_post_format() );

		endwhile; 

		reach_paging_nav();
		?>		

	</main><!-- .site-main -->

<?php 
else :

	get_template_part( 'content', 'none' );

endif;

get_sidebar();

get_footer();
