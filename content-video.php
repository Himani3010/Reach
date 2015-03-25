<?php 
/**
 * @package 	Benny
 */
?>
<article id="post-<?php the_ID() ?>" <?php post_class() ?>>	
	<?php 
	echo benny_get_media( array( 'split_media' => true, 'meta_key' => 'video' ) );
	// $video = get_post_meta( get_the_ID(), 'video', true ); 
	// $video = $video ? benny_fullwidth_video( $video ) : hybrid_media_grabber( array( 'split_media' => true ) );
	// echo $video;

	if ( is_single() ) :

		get_template_part('meta', 'above');

	endif;

	benny_post_header();

	?>
	<div class="entry cf">				
		<?php the_content() ?>			

		<?php wp_link_pages(array( 'before' => '<p class="entry_pages">' . __('Pages: ', 'benny') ) ) ?>
	</div>						
	<?php 

	if ( is_single() ) :
			
		get_template_part( 'meta', 'taxonomy' );

	else :

		get_template_part('meta', 'below');

	endif ?>

</article>