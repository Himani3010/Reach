<?php 
/**
 * Campaign category archive.
 *
 * @package     Reach
 */

get_header();

get_template_part( 'banner' );        
?>  
<div class="layout-wrapper fullwidth">
    <main class="site-main content-area" role="main">        
        <div class="campaigns-grid-wrapper">                                
            <nav class="campaigns-navigation" role="navigation">
                <a class="menu-toggle menu-button toggle-button" aria-controls="menu" aria-expanded="false"></a>
                <?php reach_crowdfunding_campaign_nav() ?>              
            </nav>
            <div class="campaigns-grid masonry-grid">                           
            <?php 
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();

                    get_template_part( 'campaign' );
                endwhile;                                
            endif;
            ?>                      
            </div>
        </div><!-- .campaigns-grid-wrapper -->
    </main><!-- .site-main -->
</div><!-- .layout-wrapper -->    
<?php 

get_footer();