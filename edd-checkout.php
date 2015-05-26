<?php 
/**
 * Checkout page template. 
 *
 * @package Benny
 */

if ( ! benny_has_edd() ) {
    return;
}

$cart = new Charitable_EDD_Cart( edd_get_cart_contents(), edd_get_cart_fees( 'item' ) );
$campaigns = $cart->get_benefits_by_campaign();

get_header( 'stripped' );
    
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();

            get_template_part( 'banner' ); ?>

            <main class="site-main content-area" role="main">
                
                <?php if ( ! empty( $campaigns ) ) : ?>

                    <aside class="campaign-benefiting">

                        <p class="header"><?php echo _n( 'Thank you for supporting this campaign', 'Thank you for supporting these campaigns', count( $campaigns ), 'benny' ) ?></p>
                        
                        <?php foreach ( $campaigns as $campaign_id => $benefits ) :

                            if ( has_post_thumbnail( $campaign_id ) ) : 

                                echo get_the_post_thumbnail( $campaign_id, 'campaign-thumbnail-small' );

                            endif ?>

                            <h6 class="campaign-title"><a href="<?php echo get_permalink( $campaign_id ) ?>"><?php echo get_the_title( $campaign_id ) ?></a></h6>

                        <?php endforeach ?>

                    </aside>

                <?php endif ?>

                <?php get_template_part( 'content', 'page' ) ?>

            </main><!-- .site-main -->
        <?php 

        endwhile;
    endif;

get_footer( 'stripped' );