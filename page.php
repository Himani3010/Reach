<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Franklin
 */

get_header(); 
	?>
	<div id="primary" class="content-area">

		<main class="site-main content" role="main">

		<?php if ( have_posts() ) :

			while ( have_posts() ) :

				the_post();
				
				get_template_part( 'banner', 'page' ); 
				
				get_template_part( 'content', 'page' );

				comments_template('', true);

			endwhile;

		endif;
		?>
		</main>
	</div>
	<?php 
get_sidebar();

get_footer();