<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Franklin
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<div class="entry cf">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'franklin' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry -->
	
	<?php get_template_part( 'meta', 'below' ) ?>

</article><!-- #post-## -->
