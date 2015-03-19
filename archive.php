<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Benny
 */

get_header();

if ( have_posts() ) : ?>

	<main class="site-main content-area" role="main">
		<?php 
		the_archive_title( '<h1 class="archive-title">', '</h1>' );

		while ( have_posts() ) : 

			the_post();

			get_template_part( 'content', get_post_format() );

		endwhile;

		benny_paging_nav(); 
		?>		
	</main><!-- .site-main -->

<?php 
else :

	get_template_part( 'content', 'none' );

endif;

get_sidebar();

get_footer();
