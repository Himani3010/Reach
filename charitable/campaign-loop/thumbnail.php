<?php
/**
 * The template for displaying the campaign thumbnail within a loop of campaigns.
 *
 * Override this template by copying it to your-child-theme/charitable/campaign-loop/campaign.php
 *
 * @author  Studio 164a
 * @package Charitable/Templates/Campaign
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var Charitable_Campaign
 */
$campaign = $view_args[ 'campaign' ];

?>
<div class="campaign-image">    
    <?php echo charitable_template_campaign_status_tag( $campaign ); ?>
    <a href="<?php the_permalink() ?>" title="<?php printf( __( 'Go to %s', 'reach' ), get_the_title() ) ?>" target="_parent">
        <?php echo get_the_post_thumbnail( get_the_ID(), 'campaign-thumbnail-medium' ) ?>
    </a>
</div>