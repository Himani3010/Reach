<?php
/**
 * The template used to display the edit campaign link.
 *
 * @author  Studio 164a
 * @since   1.0.0
 * @version 1.0.0
 */

$campaign = charitable_get_current_campaign();
?>
<div class="charitable-ambassadors-campaign-creator-toolbar">
    <div class="layout-wrapper">
        <?php printf( '<span class="campaign-status">%s: <span class="status status-%s">%s</span></span>', __( 'Status', 'reach' ), $campaign->get_status(), ucwords( $campaign->get_status() ) ) ?>
        <?php printf( '<a href="%s" class="edit-link">%s</a>', charitable_get_permalink( 'campaign_editing_page' ), __( 'Edit campaign', 'reach' ) ) ?>
    </div><!-- .layout-wrapper -->
</div><!-- .charitable-ambassadors-campaign-creator-toolbar -->