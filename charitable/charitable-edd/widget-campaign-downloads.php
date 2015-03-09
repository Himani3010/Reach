<?php
/**
 * Display the downloads that are connected to a campaign.
 *
 * @package	Franklin
 * @since 	2.0.0
 */
$widget_title 	= apply_filters( 'widget_title', $view_args['title'] );
$campaign_id 	= $view_args[ 'campaign_id' ] == 'current' ? get_the_ID() : $view_args[ 'campaign_id' ];
$campaign 		= new Charitable_EDD_Campaign( $campaign_id );
$downloads 		= $campaign->get_connected_downloads();

if ( false === $downloads ) {
	return;
}

echo $view_args['before_widget'];

if ( ! empty( $widget_title ) ) :
	echo $view_args['before_title'] . $widget_title . $view_args['after_title'];
endif;

while ( $downloads->have_posts() ) : 
	$downloads->the_post();

	$download_id 	= get_the_ID(); 
	?>

	<div id="campaign-pledge-levels-<?php echo $download_id ?>" class="campaign-pledge-levels accordion">

		<?php if ( false == edd_has_variable_prices( $download_id ) ) : ?>

			<p><a class="pledge-button button-alt button-large accent" data-price="<?php echo edd_get_download_price( $download_id ) ?>" href="#campaign-form-<?php echo $campaign_id ?>" data-trigger-modal><?php _e( 'Pledge', 'franklin' ) ?></a></p>

		<?php else : 

			foreach ( edd_get_variable_prices( $download_id ) as $price ) :			
				
				$has_limit 	= strlen( $price['purchase_limit'] ) > 0;
				$remaining 	= isset( $price['bought'] ) ? $price['purchase_limit'] - count($price['bought']) + 1 : $price['purchase_limit'];
				$class 		= ! $has_limit ? 'limitless' : ( $remaining == 0 ? 'not-available' : 'available' );
				?>

				<h3 class="pledge-title" data-icon="&#xf0d7;"><?php echo franklin_crowdfunding_get_pledge_amount_text( $price['amount'] ) ?></h3>
				<div class="pledge-level cf<?php if ($has_limit && $remaining == 0) echo ' not-available' ?>">

					<?php if ( $has_limit ) : ?>
						<span class="pledge-limit"><?php printf( __( '%d of %d remaining', 'franklin' ), $remaining, $price['limit'] ) ?></span>
					<?php else : ?>
						<span class="pledge-limit"><?php _e( 'Unlimited backers', 'franklin' ) ?></span>
					<?php endif ?>

					<p class="pledge-description"><?php echo $price['name'] ?></p>

					<?php if ( $campaign->is_active() && ( !$has_limit || $remaining > 0 ) ) : ?>
						<p class="pledge-button">
							<a class="button-alt button-small accent" data-price="<?php echo $price['amount'] ?>" href="#campaign-form-<?php echo $campaign_id ?>" data-trigger-modal><?php echo franklin_crowdfunding_get_pledge_amount_text( $price['amount'] ) ?></a>
						</p>
					<?php endif ?>
				</div>					

			<?php endforeach;

		endif ?>

	</div>

	<?php 
	get_template_part( 'campaign-support-modal' );
	
endwhile;

wp_reset_query();

echo $view_args['after_widget'];	