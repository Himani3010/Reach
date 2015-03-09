<?php
$campaign_id 	= charitable_get_current_campaign_id();
$edd_campaign 	= new Charitable_EDD_Campaign( $campaign_id );
$downloads 		= $edd_campaign->get_connected_downloads();

$page_has_modal = wp_cache_get( sprintf( 'franklin_page_has_campaign_%d_modal', $campaign_id ) );

if ( false === $page_has_modal ) : 

	if ( $downloads->have_posts() ) : ?>

		<div id="campaign-form-<?php echo $campaign_id ?>" class="campaign-form modal content-block block">
			<a class="close-modal icon" data-icon="&#xf057;"></a>

		<?php	
		while ( $downloads->have_posts() ) : 
			$downloads->the_post();
		
			echo edd_get_purchase_link( array( 'download_id' => get_the_ID() ) );

		endwhile ?>

		</div><!-- #campaign-form-<?php echo $campaign_id ?> -->

	<?php 
	endif;

	wp_cache_set( sprintf( 'franklin_page_has_campaign_%d_modal', $campaign_id ), true );

endif;