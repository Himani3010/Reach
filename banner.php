<?php
/**
 * The template for displaying the title banner at the top of a page.
 *
 * @package Benny
 */

$banner_title = benny_get_banner_title();
$banner_subtitle = benny_get_banner_subtitle();

if ( ! empty( $banner_title ) ) : ?>

	<div class="banner">
		<div class="shadow-wrapper">
			<h1 class="banner-title"><?php echo $banner_title ?></h1>
            <?php if ( $banner_subtitle ) : ?>
                <h3 class="banner-subtitle"><?php echo $banner_subtitle ?></h3>
            <?php endif ?>
		</div>
	</div>

<?php endif ?>