<?php 
/**
 * Template name: Homepage
 * 
 * This is a homepage template with a content block at the top and campaigns grid below.
 *
 * @package 	Reach 
 */

get_header();
	
	while ( have_posts() ) :
		the_post();
		?>	
		<main class="site-main content-area" role="main">
			<article <?php post_class() ?>>
				<div class="shadow-wrapper">
					<div class="layout-wrapper">						
						<?php echo reach_get_media( array( 'split_media' => true ) ) ?>
						<h1 class="page-title"><?php the_title() ?></h1>
						<div class="entry">
							<?php the_content() ?>
						</div>
					</div>
				</div>
			</article>
			<div class="layout-wrapper">			
				<?php get_template_part('partials/campaign', 'grid');

				// if ( get_post_meta( get_the_ID(), '_franklin_homepage_2_show_categories', true ) ) :

					// get_template_part('campaign', 'categories');

				// endif;
				?>
			</div>
		</main>
	<?php 

	endwhile;

get_footer();