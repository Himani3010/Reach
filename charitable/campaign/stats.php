<?php
/**
 * The template for displaying the campaign's stats.
 *
 * Override this template by copying it to your-child-theme/charitable/campaign/stats.php
 *
 * @author  Studio 164a
 * @package Reach
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$campaign = $view_args[ 'campaign' ];
$currency_helper = charitable()->get_currency_helper();

?>
<ul class="campaign-stats">
    <li class="campaign-raised">
        <?php printf( '<span>%s</span>%s', 
            $currency_helper->get_monetary_amount( $campaign->get_donated_amount() ), 
            __( 'Donated', 'reach' )
        ) ?>
    </li>
    <li class="campaign-goal">
        <?php printf( '<span>%s</span>%s', 
            $currency_helper->get_monetary_amount( $campaign->get_goal() ),
            __( 'Goal', 'reach' )
        ) ?>
    </li>
    <li class="campaign-backers">
        <?php printf( '<span>%d</span>%s',
            $campaign->get_donor_count(),
            __( 'Donors', 'reach' )
        ) ?>
    </li>               
</ul>