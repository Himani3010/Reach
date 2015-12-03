<?php 
/**
 * Campaign widget template.
 *
 * @package Reach
 */

get_header('widget');

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        $campaign = new Charitable_Campaign( get_post() );

        ?>
        <div class="campaign-widget campaign block cf" style="width: 275px;">
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
        <?php 
        
    endwhile;
endif;

get_footer('widget');