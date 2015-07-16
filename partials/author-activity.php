<?php 
/**
 * Partial template displaying the activity feed shown on user profiles.
 * 
 * @package 	Reach
 */

global $donor;

$activity = $donor->get_activity( array(
	'post_type' => array( 'donation', 'campaign', 'post' )
)) ?>

<h3><?php _e( 'Activity Feed', 'reach' ) ?></h3>

<?php if ( $activity->have_posts() ) :  ?>
	
	<ul class="author-activity-feed">
	
	<?php while( $activity->have_posts() ) : 

		$activity->the_post();

		get_template_part( 'partials/author', 'activity-' . get_post_field( 'post_type', get_the_ID() ) );
		
	endwhile ?>
	
	</ul>

<?php else : ?>				

	<p><?php printf( '%s %s', $donor->display_name, _x( 'has no activity to show yet.', 'user has no activity', 'reach' ) ) ?></p>

<?php 

endif;