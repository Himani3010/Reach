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
				
				<?php //$campaign->get_donation_form()->render() ?>

			</main><!-- .site-main -->
		<?php 

		endwhile;
	endif;

get_footer( 'stripped' );