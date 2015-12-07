<?php 
/**
 * Checkout page template. 
 *
 * @package Reach
 */

if ( ! reach_has_edd() ) {
    return;
}

$cart = new Charitable_EDD_Cart( edd_get_cart_contents(), edd_get_cart_fees( 'item' ) );
$campaigns = $cart->get_benefits_by_campaign();

get_header( 'stripped' );
    
?>    
<main id="main" class="site-main site-content cf" role="main">  
    <div class="layout-wrapper">    
        <div id="primary" class="content-area <?php if ( empty( $campaigns ) ) : ?>no-sidebar<?php endif ?>">      
        <?php

        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                
                get_template_part( 'partials/content', 'page' );

            endwhile;
        endif;

        ?>
        </div><!-- #primary -->
        <?php if ( ! empty( $campaigns ) ) : ?>
            <aside class="campaign-benefiting" role="complementary">

                <p class="header"><?php echo _n( 'Thank you for supporting this campaign', 'Thank you for supporting these campaigns', count( $campaigns ), 'reach' ) ?></p>
                
                <?php foreach ( $campaigns as $campaign_id => $benefits ) :

                    if ( has_post_thumbnail( $campaign_id ) ) : 

                        echo get_the_post_thumbnail( $campaign_id, 'campaign-thumbnail-small' );

                    endif ?>

                    <div class="campaign-title"><a href="<?php echo get_permalink( $campaign_id ) ?>"><?php echo get_the_title( $campaign_id ) ?></a></div>

                <?php endforeach ?>

            </aside>
        <?php endif ?>
    </div><!-- .layout-wrapper -->
</main><!-- #main -->
<?php 

get_footer( 'stripped' );