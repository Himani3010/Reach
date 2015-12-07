<?php 
/**
 * Content of link format post.
 * 
 * @package 	Reach
 */
?>
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>	
	<?php 

	get_template_part( 'partials/sticky' );
	
	get_template_part( 'partials/featured-image' );

	if ( is_single() ) :

		get_template_part( 'partials/meta', 'above' );

	endif;

	?>
	<div class="block entry-block">
		<?php 

		reach_post_header();

		$content = reach_link_format_the_content( null, false, false );

		if ( strlen( $content ) ) : 

		?>
			<div class="entry cf">
				<?php echo $content ?>
			</div><!-- .entry -->
		<?php 

		endif;

		if ( is_single() ) :
				
			get_template_part( 'partials/meta', 'taxonomy' );		

		else :				

			get_template_part( 'partials/meta', 'below' );

		endif ?>
	</div><!-- .entry-block -->
</article><!-- post-<?php the_ID() ?> -->