<?php 
/**
 * Campaign barometer.
 *
 * @package Reach
 */

$campaign = charitable_get_current_campaign();

?>
<div class="barometer" 
	data-progress="<?php echo $campaign->get_percent_donated_raw() ?>" 
	data-width="148" 
	data-height="148" 
	data-strokewidth="11" 
	data-stroke="<?php echo get_theme_mod( 'accent_text', '#fff' ) ?>" 
	data-progress-stroke="<?php echo get_theme_mod( 'body_text', '#7D6E63' ) ?>"
	>
	<span>
		<?php printf( _x( "%s Funded", 'x percent funded', 'reach' ), '<span>' . number_format( $campaign->get_percent_donated_raw(), 0 ) . '<sup>%</sup></span>' ) ?>
	</span>
</div>		