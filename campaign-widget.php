<?php 
/**
 * Campaign widget template.
 *
 * @package Benny
 */

get_header('widget');

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        $campaign = new Charitable_Campaign( get_post() );
        ?>
        <div class="campaign block entry-block cf" style="width: 275px;">
            <div class="campaign-image">
                <?php get_template_part( 'campaign', 'status-ribbon' ) ?>

                <a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'benny' ), get_the_title() ) ?>" target="_parent">
                    <?php echo get_the_post_thumbnail( $campaign->ID, 'campaign-thumbnail-medium' ) ?>
                </a>
            </div>
            <div class="title-wrapper">
                <h3 class="block-title">
                    <a href="<?php the_permalink() ?>" title="<?php printf( __('Link to %s', 'benny'), get_the_title() ) ?>" target="_parent"><?php 
                        the_title() 
                    ?></a>
                </h3>
            </div>
            <?php 
            get_template_part( 'campaign', 'stats-small' ); 

            get_template_part( 'meta', 'campaign' );
            ?>
        </div>
        <?php 
    endwhile;
endif;

get_footer('widget');