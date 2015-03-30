<?php //if ( benny_using_crowdfunding() === false ) return;

$campaign = charitable_get_current_campaign();

if ( $campaign === false ) return;

?>
<div class="campaign block entry-block cf">

	<div class="campaign-image">
		<?php 
		if ( $campaign->has_goal() && $campaign->has_achieved_goal() ) : ?>

			<span class="campaign-successful"><?php _e( 'Successful', 'benny' ) ?></span>

		<?php elseif ( $campaign->has_ended() && false === $campaign->has_achieved_goal() ) :  ?>

			<span class="campaign-unsuccessful"><?php _e( 'Unsuccessful', 'benny' ) ?></span>

		<?php endif ?>

		<a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'benny' ), get_the_title() ) ?>" target="_parent">
			<?php echo get_the_post_thumbnail( $campaign->ID, 'campaign-thumbnail-medium' ) ?>
		</a>
	</div>
	<div class="title-wrapper">
		<h3 class="block-title">
			<a href="<?php the_permalink() ?>" title="<?php printf( __('Link to %s', 'benny'), get_the_title() ) ?>" target="_parent"><?php 
				the_title() 
			?></a>
		</h3>
	</div>
	<div class="campaign-description campaign-excerpt">
		<?php echo apply_filters( 'the_content', $campaign->campaign_description ) ?>			
	</div>

	<?php 
	get_template_part( 'campaign', 'stats-small' ); 

	get_template_part( 'meta', 'campaign' );
	?>
</div>