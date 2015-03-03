<?php
/**
 * @package Franklin
 */
?>
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			

	<?php get_template_part( 'featured-image' ) ?>

	<?php if ( is_single() ) : ?>

		<?php get_template_part('meta', 'above') ?>

	<?php endif ?>

	<?php franklin_post_header() ?>			

	<div class="entry cf">				
		<?php 
		the_content(); 			

		wp_link_pages( array( 
			'before' => '<p class="entry_pages">' . __('Pages: ', 'franklin') 
		) ); 
		?>
	</div>						

	<?php if ( is_single() ) : ?>
			
		<?php get_template_part( 'meta', 'taxonomy' ) ?>				

	<?php else : ?>				

		<?php get_template_part('meta', 'below') ?>

	<?php endif ?>			

</article>