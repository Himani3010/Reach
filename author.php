<?php 
/**
 * The template for displaying public user profiles.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 * 
 * @package 	Benny
 */

$donor 		= new Charitable_User( benny_get_current_author() );
$campaigns 	= new Charitable_Campaigns( array( 'author' => $donor->ID ) );
$avatar 	= $donor->get_avatar( 140 );

get_header();

	get_template_part( 'banner' );

	?>
	<main class="site-main content-area" role="main">		
		<div class="author-description">
			<div class="author-avatar">
				<?php echo $avatar ?>
			</div><!-- .author-avatar -->
			<div class="author-facts">
				<h3><?php echo $donor->display_name ?></h3>
				<p><?php printf( __( 'Joined %s', 'benny' ), date('F Y', strtotime( $donor->user_registered ) ) ) ?></p>
				<?php if ( get_current_user_id() == $donor->ID && get_theme_mod( 'edit_profile_page', false ) ) : ?>
					<a href="<?php echo get_theme_mod( 'edit_profile_page' ) ?>" class="button"><?php _e( 'Edit Profile', 'benny' ) ?></a>
				<?php endif ?>
			</div><!-- .author-facts -->			
			<ul class="author-links">
				<?php if ( $donor->user_url ) : ?>
					<li class="with-icon" data-icon="&#xf0c1;">
						<a target="_blank" href="<?php echo $donor->user_url ?>" title="<?php printf( __("Visit %s's website", 'benny'), $donor->display_name ) ?>"><?php echo benny_condensed_url( $donor->user_url ) ?></a>
					</li>
				<?php endif ?>

				<?php if ( $donor->twitter ) : ?>
					<li class="with-icon" data-icon="&#xf099;">
						<a target="_blank" href="<?php echo $twitter ?>" title="<?php printf( __("Visit %s's Twitter profile", 'benny'), $donor->display_name ) ?>"><?php echo benny_condensed_url( $donor->twitter ) ?></a>
					</li>
				<?php endif ?>

				<?php if ( $donor->facebook ) : ?>
					<li class="with-icon" data-icon="&#xf09a;">
						<a target="_blank" href="<?php echo $facebook ?>" title="<?php printf( __("Visit %s's Facebook profile", 'benny'), $donor->display_name ) ?>"><?php echo benny_condensed_url( $donor->facebook ) ?></a>
					</li>
				<?php endif ?>
			</ul><!-- .author-links -->
			<div class="author-bio">				
				<h5><?php _e( 'About', 'benny' ) ?></h5>				
				<?php echo apply_filters( 'the_content', $donor->description ) ?>
			</div><!-- .author-bio -->
		</div><!-- .author-description -->		
		<div class="author-activity">
			<div class="author-activity-summary">
				<?php printf( "<span class='number'>%d</span> %s <span class='separator'>/</span> <span class='number'>%d</span> %s", 
					$donor->get_donation_count(), 
					__( 'Campaigns Backed', 'benny' ), 
					$campaigns->post_count, 
					__( 'Campaigns Created', 'benny' ) 
				) ?>
			</div><!-- .author-activity-summary -->
			<?php 

			get_template_part( 'author', 'activity' );

			?>
		</div><!-- .author-activity -->
	</main><!-- .site-main -->
	<?php 

get_footer();