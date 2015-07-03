<?php 
/**
 * Partial template displaying campaigns in the activity feed shown on user profiles.
 * 
 * @package 	Reach
 */

global $donor;


?>
<li class="activity-type-campaign cf">
	<p class="activity-summary">
		<?php printf( '<span class="display-name">%s</span> %s <a href="%s" title="%s">%s</a>.', 
			$donor->display_name, 
			_x( 'created', 'user created campaign', 'reach' ), 
			get_permalink(),
			sprintf( __('Link to %s', 'reach'), get_the_title()  ),
			get_the_title() 
		) ?><br />
		<span class="time-ago"><?php printf( '%s %s', human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ), _x( 'ago', 'time ago', 'reach' ) ) ?></span>
	</p>
	<?php if ( has_post_thumbnail() ) :

		the_post_thumbnail( 'thumbnail' );

	endif ?>
</li><!-- .activity-type-campaign -->