<?php 
/**
 * Checkout page template. 
 *
 * @package Benny
 */

$cart = new Charitable_EDD_Cart( edd_get_cart_contents(), edd_get_cart_fees( 'item' ) );
echo '<pre>';
var_dump( $cart );
var_dump( $cart->get_benefits_by_campaign() );
echo '</pre>';

get_header( 'stripped' );
    
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();

            get_template_part( 'banner' ); ?>

            <main class="site-main content-area" role="main">
                
                <?php if ( ! empty( $cart->get_benefits_by_campaign() ) ) : ?>

                    <aside class="campaign-benefiting">
                        
                        <?php foreach ( $cart->get_benefits_by_campaign() as $campaign_id => $benefits ) :

                            if ( has_post_thumbnail( $campaign_id ) ) : 

                                echo get_the_post_thumbnail( $campaign_id, 'campaign-thumbnail-small' );

                            endif ?>

                            <h4 class="campaign-title"><?php echo get_the_title( $campaign_id ) ?></h4>

                        <?php endforeach ?>

                    </aside>

                <?php endif ?>

                <?php get_template_part( 'content', 'page' ) ?>

            </main><!-- .site-main -->
        <?php 

        endwhile;
    endif;

get_footer( 'stripped' );