<?php 
/**
 * Campaign creator.
 *
 * Override this template by copying it to yourtheme/charitable/campaign-loop/creator.php
 *
 * @package Reach
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$campaign = $view_args[ 'campaign' ];
$campaign_author = new WP_User( $campaign->get_campaign_creator() );

?>
<div class="meta meta-below">   
    <p class="center"><?php printf( '%s <a href="%s">%s</a>', 
        _x( 'By', 'by author', 'reach' ),
        get_author_posts_url( get_the_author_meta( 'ID' ) ),
        $campaign_author->display_name
    ) ?></p>
</div>