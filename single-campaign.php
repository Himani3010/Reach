<?php 
/**
 * Single campaign template.
 *
 * @package Benny
 */
get_header();

	if ( have_posts() ) :
		while( have_posts() ) :
			the_post();
			?>
			<main class="site-main" role="main">
				
				<?php get_template_part('campaign', 'summary') ?>			
	
				<?php get_template_part('campaign', 'video') ?>

				<div class="content-area">
	
					<!-- Campaign content -->					
					<?php get_template_part( 'content', 'campaign' ) ?>
					<!-- End campaign content -->

					<!-- "Campaign Below Content" sidebar -->
					<?php get_sidebar( 'campaign-after' ) ?>
					<!-- End "Campaign Below Content" sidebar -->

					<?php comments_template( '/comments-campaign.php', true ) ?>

				</div>

				<?php get_sidebar( 'campaign' ) ?>
			
			</main>
		<?php 
		endwhile;
	endif;
	
	get_template_part( 'campaign', 'share-modal' );

	//get_template_part( 'campaign', 'support-modal' );

get_footer();