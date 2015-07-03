<?php 
/**
 * @package 	Reach
 */
?>
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>			
	<?php 
	
	get_template_part( 'featured_image' );

	if ( is_single() ) :

		get_template_part('meta', 'above');

	endif;

	reach_post_header();

	?>			
	<div class="entry cf">				
		<?php 
		echo reach_get_media( array( 'split_media' => true, 'meta_key' => '_format_audio_embed', 'type' => 'audio' ) );
		
		the_content();

		wp_link_pages(array( 'before' => '<p class="entry_pages">' . __('Pages: ', 'reach') ) ); 
		?>
	</div>						
	<?php 

	if ( is_single() ) :
			
		get_template_part( 'meta', 'taxonomy' );		

	else :				

		get_template_part('meta', 'below');

	endif ?>			
</article>