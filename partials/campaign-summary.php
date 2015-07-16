<?php 
/**
 * Campaign summary template. Shows the campaign short description and stats, as well as sharing buttons.
 *
 * @package Reach
 */

$campaign = charitable_get_current_campaign();

if ( $campaign ) : 
?>
	<section class="campaign-summary current-campaign feature-block cf <?php if ( $campaign->has_ended() ) : ?>ended<?php endif ?>">
		<div class="shadow-wrapper">
			<div class="layout-wrapper">
				<h1 class="campaign-title"><?php echo $campaign->post_title ?></h1>
				<div class="campaign-description campaign-excerpt">				
					<?php echo $campaign->get( 'description' ) ?>
				</div><!-- .campaign-description -->			
				
				<?php get_template_part( 'partials/campaign', 'featured-image' ) ?>

				<div class="campaign-details cf">		
					<?php if ( $campaign->has_ended() ) :

						get_template_part( 'partials/campaign', 'ended' );
				
					else :

						$campaign->donate_button_template();
						
					endif;

					get_template_part( 'partials/campaign', 'barometer' );

					get_template_part( 'partials/campaign', 'stats' );

					get_template_part( 'partials/campaign', 'countdown' );
					?>				
				</div><!-- .campaign-details -->

				<?php get_template_part( 'partials/campaign', 'sharing' ) ?>
			</div><!-- .layout-wrapper -->
		</div><!-- .shadow-wrapper -->
	</section><!-- .campaign-summary -->
<?php 
endif;