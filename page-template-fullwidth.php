<?php
/**
 * Template name: Fullwidth
 *
 * @package Reach
 */

get_header();
    
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
                        
            get_template_part( 'banner' ); ?>

            <main class="site-main content-area" role="main">
                <?php get_template_part( 'content', 'page' );

                comments_template('', true); ?>
            </main><!-- .site-main -->
        <?php 
        endwhile;
    endif;

get_footer();