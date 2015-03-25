<?php 
/**
 * Content of status format post.
 * 
 * @package 	Benny
 */
?>		
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			
	<?php 
	
	get_template_part( 'featured_image' );

	if ( is_single() ) :

		get_template_part('meta', 'above');

		benny_post_header();

		get_template_part( 'meta', 'taxonomy' );

	else : 

		benny_post_header();
		
		get_template_part('meta', 'below');

	endif;
	
	?>
</article>