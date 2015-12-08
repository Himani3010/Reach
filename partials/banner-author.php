<?php
/**
 * The template for displaying the title banner at the top of a page.
 *
 * @package Reach
 */

global $donor, $campaigns;

$banner_title = reach_get_banner_title();

if ( ! empty( $banner_title ) ) : ?>

	<header class="banner">
		<div class="shadow-wrapper">
			<h1 class="banner-title"><?php echo $banner_title ?></h1>
            <div class="author-activity-summary">
                <?php printf( "<span class='number'>%d</span> %s <span class='separator'>/</span> <span class='number'>%d</span> %s", 
                    $donor->count_campaigns_supported(), 
                    __( 'Campaigns Backed', 'reach' ), 
                    $campaigns->post_count, 
                    __( 'Campaigns Created', 'reach' ) 
                ) ?>
            </div><!-- .author-activity-summary -->
		</div>
	</header><!-- .banner -->

<?php endif ?>