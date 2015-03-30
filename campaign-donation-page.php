<?php 
/**
 * Donation page template.
 *
 * @package Benny
 */

get_header( 'stripped' );
	
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			$campaign = charitable_get_current_campaign();
						
			get_template_part( 'banner' ); ?>

			<main class="site-main content-area" role="main">
				
				<aside class="campaign-benefiting">
					<?php 
					if ( $campaign && has_post_thumbnail( $campaign->ID ) ) : 

						echo get_the_post_thumbnail( $campaign->ID, 'campaign-thumbnail-small' );

					endif ?>

					<h4 class="campaign-title"><?php echo get_the_title( $campaign->ID ) ?></h4>
				</aside>

				<?php $campaign->get_donation_form()->render() ?>

			</main><!-- .site-main -->
		<?php 

		endwhile;
	endif;

get_footer( 'stripped' );