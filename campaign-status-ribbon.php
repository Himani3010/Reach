<?php 
/**
 * Displays a ribbon to mark whether a campaign has been successful or not.
 *
 * @package Benny
 */

$campaign = charitable_get_current_campaign();

if ( $campaign->has_goal() && $campaign->has_achieved_goal() ) : ?>

    <span class="campaign-successful"><?php _e( 'Successful', 'benny' ) ?></span>

<?php 
elseif ( $campaign->has_ended() && false === $campaign->has_achieved_goal() ) : 
?>
    <span class="campaign-unsuccessful"><?php _e( 'Unsuccessful', 'benny' ) ?></span>
<?php 
endif;