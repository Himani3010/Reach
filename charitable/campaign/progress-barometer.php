<?php
/**
 * The template for displaying the campaign's progress barometer.
 *
 * Override this template by copying it to your-child-theme/charitable/campaign/progress-barometer.php
 *
 * @author  Studio 164a
 * @package Reach
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$campaign = $view_args[ 'campaign' ];

?>
<div class="barometer" 
    data-progress="<?php echo $campaign->get_percent_donated_raw() ?>" 
    data-width="148" 
    data-height="148" 
    data-strokewidth="11" 
    data-stroke="#fff" 
    data-progress-stroke="<?php echo get_theme_mod( 'text_colour', '#7D6E63' ) ?>"
    >
    <span>
        <?php printf( _x( '%s Funded', 'x percent funded', 'reach' ), '<span>' . number_format( $campaign->get_percent_donated_raw(), 0 ) . '<sup>%</sup></span>' ) ?>
    </span>
</div>