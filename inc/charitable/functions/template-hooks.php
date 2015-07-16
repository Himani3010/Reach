<?php 
/**
 * Custom Reach template funtions for Charitable hooks. 
 *
 * @package     Reach
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Campaigns loop, before title.
 * 
 * @see reach_template_campaign_loop_stats
 * @see reach_template_campaign_loop_creator
 */
add_action( 'charitable_campaign_content_loop_after', 'reach_template_campaign_loop_stats', 10 );
add_action( 'charitable_campaign_content_loop_after', 'reach_template_campaign_loop_creator', 15 );

remove_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_progress_bar', 10 );
remove_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_loop_donation_stats', 15 );
remove_action( 'charitable_campaign_content_loop_after', 'charitable_template_campaign_donate_link', 20 );