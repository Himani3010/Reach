<?php 
/**
 * Partial template displaying donations in the activity feed shown on user profiles.
 * 
 * @package 	Benny
 */

global $donor;

$donation 		= new Charitable_Donation( get_the_ID() );
$campaign_id 	= current( $donation->get_campaign_donations() )->campaign_id;
?>
<li class="activity-type-donation cf">
	<p class="activity-summary">
		<?php printf( '<span class="display-name">%s</span> %s %s.', 
			$donor->display_name, 
			_x( 'backed', 'user backed campaign', 'benny' ), 
			$donation->get_campaigns_donated_to( true ) 
		) ?><br />
		<span class="time-ago"><?php printf( '%s %s', human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ), _x( 'ago', 'time ago', 'benny' ) ) ?></span>
	</p>
	<?php if ( has_post_thumbnail( $campaign_id ) ) :

		echo get_the_post_thumbnail( $campaign_id, 'thumbnail' );

	endif ?>
</li><!-- .activity-type-donation -->