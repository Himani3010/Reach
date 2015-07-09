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

get_template_part( 'banner' ) ?>

<div class="layout-wrapper">
	<main class="site-main content-area" role="main">					
		<?php 
		if ( have_posts() ) : 
			while ( have_posts() ) : 
				the_post(); 

				get_template_part( 'content', get_post_format() );

			endwhile; 

			reach_paging_nav();

		else :

			get_template_part( 'content', 'none' );

		endif;		
		?>	
	</main><!-- .site-main -->

	<?php get_sidebar() ?>
	
</div><!-- .layout-wrapper -->
<?php 

get_footer();
