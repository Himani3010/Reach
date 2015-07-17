<?php 
/**
 * Single campaign template.
 *
 * @package Reach
 */
get_header();

if ( have_posts() ) :
	while( have_posts() ) :
		the_post();

		$campaign = charitable_get_current_campaign();
		?>
		<main class="site-main" role="main">				
			<?php 
			/**
			 * @hook charitable_single_campaign_before
			 */
			do_action( 'charitable_single_campaign_before', $campaign );

			?>				
			<div class="layout-wrapper">
				<div class="content-area">
					<?php
					/**
					 * @hook charitable_campaign_content_before
					 */
					do_action( 'charitable_campaign_content_before', $campaign ); 
					
					get_template_part( 'partials/content', 'campaign' );
					
					/**
			         * @hook charitable_campaign_content_after
			         */
    				do_action( 'charitable_campaign_content_after', $campaign ); 
 
					?>
				</div><!-- .content-area -->

				<?php get_sidebar( 'campaign' ) ?>					

			</div><!-- .layout-wrapper -->
            <?php 
            /**
             * @hook charitable_single_campaign_after
             */
            do_action( 'charitable_single_campaign_after' );

            ?>
		</main>
	<?php 
	endwhile;
endif;

get_template_part( 'partials/campaign', 'share-modal' );
	
get_footer();