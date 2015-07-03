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
			<h1 class="campaign-title"><?php echo $campaign->post_title ?></h1>
			<div class="campaign-description campaign-excerpt">				
				<?php echo $campaign->get( 'description' ) ?>
			</div>				
			
			<?php get_template_part( 'campaign', 'featured-image' ) ?>

			<div class="campaign-details cf">		
				<?php if ( $campaign->has_ended() ) :

					get_template_part( 'campaign', 'ended' );
			
				else :

					$campaign->donate_button_template();
					
				endif;

				get_template_part( 'campaign', 'barometer' );

				get_template_part( 'campaign', 'stats' );

				get_template_part( 'campaign', 'countdown' );
				?>				
			</div>				

			<?php get_template_part( 'campaign', 'sharing' ) ?>

		</div>
	</section>
<?php 
endif;