<?php
/**
 * The sidebar containing the campaigns widget area.
 *
 * @package Franklin
 */
if ( ! is_active_sidebar( 'sidebar_campaign' ) ) {
	return;
}
?>
<div id="secondary" class="widget-area sidebar sidebar-campaign" role="complementary">
	<?php dynamic_sidebar('sidebar_campaign') ?>
</div>