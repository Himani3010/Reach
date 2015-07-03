<?php
/**
 * Custom template tags used when crowdfunding is enabled.
 *
 * @package     Reach
 * @category    Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'reach_crowdfunding_campaign_nav' ) ) :

    /**
     * The callback function for the campaigns navigation
     * 
     * @param   bool    $echo
     * @return  string
     * @since   1.0.0
     */
    function reach_crowdfunding_campaign_nav( $echo = true ) {  
        $categories = get_categories( array( 'taxonomy' => 'download_category', 'orderby' => 'name', 'order' => 'ASC' ) );

        if ( empty( $categories ) )
            return;

        $html = '<ul class="menu menu-site"><li class="download_category with-icon" data-icon="&#xf02c;">'.__('Categories', 'reach');
        $html .= '<ul><li><a href="'.get_post_type_archive_link('download').'">'.__('All', 'reach').'</a></li>';

        foreach ( $categories as $category ) {
            $html .= '<li><a href="'.esc_url( get_term_link($category) ).'">'.$category->name.'</a></li>';
        }

        $html .= '</li></ul>';

        if ( $echo === false ) 
            return $html;
        
        echo $html;
    }

endif;

if ( ! function_exists( 'reach_campaign_ended_text' ) ) : 

    /**
     * Return the text to display when a campaign has finished. 
     *
     * @return  string
     * @since   1.0.0
     */
    function reach_campaign_ended_text() {
        return sprintf( '<span>%s</span> %s', __( 'Campaign', 'reach' ), __( 'has ended', 'reach' ) );
    }

endif;