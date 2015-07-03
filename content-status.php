<?php 
/**
 * Content of status format post.
 * 
 * @package 	Reach
 */
?>		
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			
	<?php 
	
	get_template_part( 'featured_image' );

	if ( is_single() ) :

		get_template_part('meta', 'above');

		reach_post_header();

		get_template_part( 'meta', 'taxonomy' );

	else : 

		reach_post_header();
		
		get_template_part('meta', 'below');

	endif;
	
	?>
</article>