<?php
/**
 * The template for displaying campaign content within loops.
 *
 * Override this template by copying it to yourtheme/charitable/campaign-loop/campaign.php
 *
 * @author  Studio 164a
 * @package Charitable/Templates/Campaign
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$campaign = charitable_get_current_campaign();

?>
<div id="campaign-<?php echo get_the_ID() ?>" class="campaign block entry-block cf">    
    <?php
    
    /**
     * @hook charitable_campaign_content_loop_before
     */
    do_action( 'charitable_campaign_content_loop_before', $campaign );

    /**
     * @hook charitable_campaign_content_loop_before_title
     */
    do_action( 'charitable_campaign_content_loop_before_title', $campaign );
    
    ?>
    <div class="title-wrapper">
        <h3 class="block-title">
            <a href="<?php the_permalink() ?>" title="<?php printf( __('Link to %s', 'reach'), get_the_title() ) ?>" target="_parent"><?php 
                the_title() 
            ?></a>
        </h3>
    </div>
    <?php

    /**
     * @hook charitable_campaign_content_loop_after_title
     */
    do_action( 'charitable_campaign_content_loop_after_title', $campaign );

    /**
     * @hook charitable_campaign_content_loop_after
     */
    do_action( 'charitable_campaign_content_loop_after', $campaign );

    ?>
</div>