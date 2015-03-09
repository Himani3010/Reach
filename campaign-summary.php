<?php 
/**
 * Campaign summary template. Shows the campaign short description and stats, as well as sharing buttons.
 *
 * @package Franklin
 */
$campaign = charitable_get_current_campaign();

if ( $campaign ) : 
?>
	<section class="campaign-summary current-campaign feature-block cf <?php if ( $campaign->has_ended() ) : ?>ended<?php endif ?>">
		<div class="shadow-wrapper">
			<div class="campaign-description campaign-excerpt">
				<?php echo $campaign->campaign_description ?>			
			</div>				
			
			<?php get_template_part( 'campaign', 'featured-image' ) ?>

			<div class="campaign-details cf">		
				<?php if ( $campaign->has_ended() ) :

					get_template_part( 'campaign', 'ended' );
			
				else : ?>
					<p class="campaign-support">
						<a class="button button-large" data-reveal-id="campaign-form-<?php echo $campaign->ID ?>" href="#"><?php echo franklin_crowdfunding_get_pledge_text() ?></a>
					</p>				
				<?php endif;

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