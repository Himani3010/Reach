<?php 
/**
 * Donation page template.
 *
 * @package Reach
 */

get_header( 'stripped' );
	
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			$campaign = charitable_get_current_campaign();
						
			get_template_part( 'banner' ); ?>

			<main class="site-main content-area" role="main">
				
				<aside class="campaign-benefiting">

					<p class="header"><?php _e( 'Thank you for supporting this campaign', 'reach' ) ?></p>
					
					<?php 
					if ( $campaign && has_post_thumbnail( $campaign->ID ) ) : 

						echo get_the_post_thumbnail( $campaign->ID, 'campaign-thumbnail-small' );

					endif ?>

					<h6 class="campaign-title"><a href="<?php echo get_permalink( $campaign->ID ) ?>"><?php echo get_the_title( $campaign->ID ) ?></a></h6>
				</aside>

				<?php $campaign->get_donation_form()->render() ?>

			</main><!-- .site-main -->
		<?php 

		endwhile;
	endif;

get_footer( 'stripped' );