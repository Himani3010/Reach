<?php 
/**
 * Displays the user dashboard.
 *
 * @author 	Studio 164a
 * @since 	1.0.0
 */

get_header();

?>
<main id="main" class="site-main site-content cf" role="main">  
	<div class="layout-wrapper">
        <div id="primary" class="content-area">  	
		<?php 

		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
			
				get_template_part( 'partials/content', 'user-dashboard' );

			endwhile;
		endif;
				
		?>
        </div><!-- #primary -->
    </div><!-- .layout-wrapper -->
</main><!-- #main -->
<?php 

get_footer();