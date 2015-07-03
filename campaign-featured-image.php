<?php 
/**
 * Campaign featured image template.
 *
 * @package Reach
 */

$campaign = charitable_get_current_campaign();

if ( $campaign && has_post_thumbnail( $campaign->ID ) ) : ?>

	<div class="campaign-image">
		<?php 
		get_template_part( 'campaign', 'status-ribbon' );
				
		echo get_the_post_thumbnail( $campaign->ID, 'campaign-thumbnail' );
		?>
	</div>

<?php endif;