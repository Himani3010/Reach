<?php
/**
 * Template name: Stripped Narrow
 * 
 * A custom template for pages where you do want to minimise any 
 * distractions. Great for use with action-oriented pages, like
 * the checkout, login, campaign-creation, etc.
 *
 * This is like the Stripped template, except that it is narrower (max 600px). 
 * 
 * @package Reach
 */

get_header( 'stripped' );
	
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
						
			get_template_part( 'partials/banner' ); ?>

			<div class="layout-wrapper">
				<main class="site-main content-area" role="main">
					<?php get_template_part( 'partials/content', 'page' );

					comments_template('', true); ?>
				</main><!-- .site-main -->
			</div><!-- .layout-wrapper -->
		<?php 
		endwhile;
	endif;

get_footer( 'stripped' );