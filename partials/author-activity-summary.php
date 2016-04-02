<?php 
/**
 * Partial template displaying the author's activity summary in the banner.
 * 
 * @package     Reach
 */

if ( ! reach_has_charitable() ) :
    return;
endif;

$donor = new Charitable_User( reach_get_current_author() );
$campaigns = Charitable_Campaigns::query( array( 'author' => $donor->ID ) );

?>
<div class="author-activity-summary">
    <?php printf( "<span class='number'>%d</span> %s <span class='separator'>/</span> <span class='number'>%d</span> %s", 
        $donor->count_campaigns_supported(), 
        __( 'Campaigns Backed', 'reach' ), 
        $campaigns->post_count, 
        __( 'Campaigns Created', 'reach' ) 
    ) ?>
</div><!-- .author-activity-summary -->