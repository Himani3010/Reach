<?php 
/**
 * Social network links
 *
 * @package Reach
 */
?>
<ul class="social">
	<?php 

	foreach( array_keys( array_reverse( reach_get_social_sites() ) ) as $site ) : 
		if ( strlen ( get_theme_mod( $site ) ) ) :

		?>
		<li>
			<a class="<?php echo $site ?>" href="<?php echo get_theme_mod( $site ) ?>"><i class="icon-<?php echo $site ?>"></i></a>
		</li>
		<?php 

		endif;
	endforeach;

	?>
</ul><!-- .social -->