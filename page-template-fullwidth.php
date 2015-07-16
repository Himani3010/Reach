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
                        
            get_template_part( 'partials/banner' ); ?>
            <div class="layout-wrapper">
                <main class="site-main content-area" role="main">
                    <?php get_template_part( 'partials/content', 'page' );

                    comments_template('', true); ?>
                </main><!-- .site-main -->
            </div><!-- .layout-wrapper -->
        <?php 
        endwhile;
    endif;

get_footer();