<?php 
/**
 * Campaign category archive.
 *
 * @package     Reach
 */

get_header();

get_template_part( 'partials/banner' );        
?>  
<div class="layout-wrapper fullwidth">
    <main class="site-main content-area">        
        <div class="campaigns-grid-wrapper">                                
            <nav class="campaigns-navigation" role="navigation">
                <a class="menu-toggle menu-button toggle-button" aria-controls="menu" aria-expanded="false"></a>
                <?php reach_crowdfunding_campaign_nav() ?>              
            </nav>
            <?php charitable_template_campaign_loop( false, 3 ) ?>
        </div><!-- .campaigns-grid-wrapper -->
    </main><!-- .site-main -->
</div><!-- .layout-wrapper -->    
<?php 

get_footer();