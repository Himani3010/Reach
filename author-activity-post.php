<?php 
/**
 * Partial template displaying posts in the activity feed shown on user profiles.
 * 
 * @package 	Benny
 */

global $donor;

?>
<li class="activity-type-post cf">
	<p class="activity-summary">
		<?php printf( '<span class="display-name">%s</span> %s <a href="%s" title="%s">%s</a>.', 
			$donor->display_name, 
			_x( 'published', 'user published post', 'benny' ), 
			get_permalink(),
			sprintf( __('Link to %s', 'benny'), get_the_title()  ),
			get_the_title() 
		) ?><br />
		<span class="time-ago"><?php printf( '%s %s', human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ), _x( 'ago', 'time ago', 'benny' ) ) ?></span>
	</p>
	<?php if ( has_post_thumbnail() ) :

		the_post_thumbnail( 'thumbnail' );

	endif ?>
</li><!-- .activity-type-post -->