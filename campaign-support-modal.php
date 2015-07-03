<?php
$campaign 		= charitable_get_current_campaign();
$page_has_modal = wp_cache_get( sprintf( 'reach_page_has_campaign_%d_modal', $campaign->ID ) );

if ( false === $page_has_modal ) : ?>
	
	<div id="campaign-form-<?php echo $campaign->ID ?>" class="campaign-form modal content-block block">
		<a class="close-modal icon" data-icon="&#xf057;"></a>

		<?php //$campaign->get_donation_form()->render() ?>
		
	</div><!-- #campaign-form-<?php echo $campaign->ID ?> -->

	<?php wp_cache_set( sprintf( 'reach_page_has_campaign_%d_modal', $campaign->ID ), true );

endif;