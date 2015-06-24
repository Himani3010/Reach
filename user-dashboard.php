<?php 
/**
 * Displays the user dashboard.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

get_header();
	
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
						
			get_template_part( 'banner' ); 

			if ( function_exists( 'charitable_user_dashboard' ) ) :

				charitable_user_dashboard()->nav( array(
					'container_class' 	=> 'user-dashboard-menu'
				) );

			endif;
			?>

			<main class="site-main content-area" role="main">
				<?php get_template_part( 'content', 'page' ) ?>
			</main><!-- .site-main -->
		<?php 
		endwhile;
	endif;

get_footer();