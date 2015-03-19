<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Benny
 */
?>
		</div><!-- #main -->
		<footer id="site-footer" class="wrapper" role="contentinfo">
			<div class="footer-left">
				<?php 
				dynamic_sidebar( 'footer_left' ) 
				?>
			</div>
			<div class="footer-right">
				<?php 
				dynamic_sidebar( 'footer_right' ) 
				?>
			</div>
			<div id="colophon">
				<?php 
				if ( function_exists('wpml_languages_list') ) :
					echo wpml_languages_list(0, 'language-list');
				endif;
				?>
				<p class="footer-notice aligncenter">
					<?php 
					if ( get_theme_mod( 'footer_notice', false ) ) : 
						echo get_theme_mod( 'footer_notice' );
					else: 
					?>
						<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'benny' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'benny' ), 'WordPress' ) ?></a>
					<?php
					endif  
					?>
				</p>			
			</div><!-- #rockbottom -->	
		</footer><!-- #site-footer -->
	</div><!-- .body-wrapper -->
</div><!-- #page -->

<?php wp_footer() ?>

</body>
</html>