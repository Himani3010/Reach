<?php 
/**
 * Single campaign template.
 *
 * @package Franklin
 */
get_header();

	if ( have_posts() ) :
		while( have_posts() ) :
			the_post();
			?>
			<main class="site-main" role="main">
				
				<?php get_template_part('campaign', 'summary') ?>			
	
				<?php //echo franklin_campaign_video( $campaign ) ?>
				
				<div class="content-area">
	
					<!-- Campaign content -->					
					<?php get_template_part('content', 'campaign') ?>
					<!-- End campaign content -->

					<!-- "Campaign Below Content" sidebar -->
					<?php dynamic_sidebar('campaign_after_content') ?>
					<!-- End "Campaign Below Content" sidebar -->

					<?php //comments_template('/comments-campaign.php', true) ?>

				</div>

				<?php get_sidebar( 'campaign' ) ?>
			
			</main>
		<?php 
		endwhile;
	endif;
	//get_template_part('campaign', 'modals');

get_footer();