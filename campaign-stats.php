<?php 
/**
 * Campaign stats.
 *
 * @package Franklin
 */

$campaign 			= charitable_get_current_campaign();
$currency_helper 	= charitable()->get_currency_helper();
?>
<ul class="campaign-stats">
	<li class="campaign-raised">
		<span><?php echo $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ) ?></span>
		<?php _e( 'Pledged', 'franklin' ) ?>		
	</li>
	<li class="campaign-goal">
		<span><?php echo $currency_helper->get_monetary_amount( $campaign->get_goal() ) ?></span>
		<?php _e( 'Goal', 'franklin' ) ?>				
	</li>
	<li class="campaign-backers">
		<span><?php echo $campaign->get_donor_count() ?></span>
		<?php _e( 'Backers', 'franklin' ) ?>
	</li>				
</ul>