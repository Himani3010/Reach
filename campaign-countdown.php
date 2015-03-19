<?php 
/**
 * Campaign featured image template.
 *
 * @package Benny
 */
$campaign = charitable_get_current_campaign();

if ( ! $campaign->is_endless() ) : ?>
	<div class="campaign-countdown">
		<span class="countdown" data-enddate='<?php echo $campaign->get_end_date( 'j F Y H:i:s' ) ?>'></span>
		<span><?php _e( 'Time left to donate', 'benny' ) ?></span>
	</div>
<?php endif ?>