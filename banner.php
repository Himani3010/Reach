<?php
/**
 * The template for displaying the title banner at the top of a page.
 *
 * @package Franklin
 */
$banner_title = franklin_get_banner_title();

if ( ! empty( $banner_title ) ) : ?>

	<div class="banner">
		<div class="shadow-wrapper">
			<h1 class="banner-title"><?php echo $banner_title ?></h1>
		</div>
	</div>

<?php endif ?>