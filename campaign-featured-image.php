<?php 
/**
 * Campaign featured image template.
 *
 * @package Franklin
 */
$campaign = charitable_get_current_campaign();

if ( $campaign && has_post_thumbnail( $campaign->ID ) ) : ?>

	<div class="campaign-image">
		<?php 
		if ( $campaign->has_goal() && $campaign->has_achieved_goal() ) : ?>

			<span class="campaign-successful"><?php _e( 'Successful', 'franklin' ) ?></span>

		<?php 
		elseif ( $campaign->has_ended() && false === $campaign->has_achieved_goal() ) : 
		?>
			<span class="campaign-unsuccessful"><?php _e( 'Unsuccessful', 'franklin' ) ?></span>
		<?php 
		endif;
		
		echo get_the_post_thumbnail( $campaign->ID, 'campaign-thumbnail' );
		?>
	</div>

<?php endif;