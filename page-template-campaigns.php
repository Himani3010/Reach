<?php 
/**
 * Template name: Campaigns Homepage
 * 
 * This is one of the homepage templates.
 *
 * @package 	Reach
 */

get_header();
	
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>	
			<main class="site-main content-area" role="main">
			
			<?php 
			get_template_part( 'partials/campaign', 'featured' );

			if ( strlen( get_the_content() ) ) : ?>

					<div <?php post_class('home-content') ?>>
						<?php the_content() ?>
					</div>

			<?php endif;

			get_template_part('campaign', 'grid');
			?>
			
			</main>

		<?php 
		endwhile;
	endif;

get_footer();