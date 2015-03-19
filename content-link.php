		<article id="post-<?php the_ID() ?>" <?php post_class() ?>>	

			<?php get_template_part( 'featured_image' ) ?>

			<?php if ( is_single() ) : ?>

				<?php get_template_part('meta', 'above') ?>

			<?php endif ?>

			<?php benny_post_header() ?>			

			<?php $content = benny_link_format_the_content(null, false, false) ?>

			<?php if ( strlen($content) ) : ?>
				<div class="entry cf"><?php echo $content ?></div>
			<?php endif ?>

			<?php if ( is_single() ) : ?>
					
				<?php get_template_part( 'meta', 'taxonomy' ) ?>				

			<?php else : ?>				

				<?php get_template_part('meta', 'below') ?>

			<?php endif ?>	

		</article>