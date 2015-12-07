<?php 
/**
 * Campaign post type archive.
 *
 * @package     Reach
 */

get_header();

?>  
<main id="main" class="site-main site-content cf" role="main">  
    <div class="layout-wrapper">
        <div id="primary" class="content-area">
            <?php

            get_template_part( 'partials/banner' );

            ?>
            <div class="campaigns-grid-wrapper">                                
                <nav class="campaigns-navigation" role="navigation">
                    <a class="menu-toggle menu-button toggle-button" aria-controls="menu" aria-expanded="false"></a>
                    <?php reach_crowdfunding_campaign_nav() ?>              
                </nav>
                <?php 

                charitable_template_campaign_loop( false, 3 );

                reach_paging_nav( __( 'Older Campaigns', 'reach' ), __( 'Newer Campaigns', 'reach' ) );

                ?>
            </div><!-- .campaigns-grid-wrapper -->
        </div><!-- #primary -->            
    </div><!-- .layout-wrapper -->
</main><!-- #main -->   
<?php 

get_footer();