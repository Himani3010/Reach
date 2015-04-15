<?php 
/**
 * Account links
 *
 * @package Benny
 */
$crowdfunding   = benny_crowdfunding_enabled();
$profile_page   = charitable_get_permalink( 'profile_page' );
$submit_page    = charitable_get_permalink( 'campaign_submission_page' );
?>
<div class="account-links">
    <?php if ( $crowdfunding && $submit_page ) : ?>

        <a class="user-campaign button with-icon button-alt button-small" href="<?php echo $submit_page ?>" data-icon="&#xf055;"><?php _e( 'Create a campaign', 'benny' ) ?></a>

    <?php endif ?>

    <?php if ( is_user_logged_in() ) : ?>

        <?php if ( $crowdfunding && $profile_page ) : ?>
            <a class="user-account with-icon button button-alt button-small" href="<?php echo $profile_page ?>" data-icon="&#xf007;"><?php _e('Profile', 'benny') ?></a>
        <?php endif ?>

        <a class="logout with-icon" href="<?php echo wp_logout_url( get_permalink() ) ?>" data-icon="&#xf08b;"><?php _e('Log out', 'benny') ?></a>

    <?php elseif ( $crowdfunding && ! is_user_logged_in() ) : ?>

        <a class="user-login button with-icon button-alt button-small" href="#" data-reveal-id="login-form" data-icon="&#xf007;"><?php _e('Login / Register', 'benny') ?></a>

    <?php endif ?>
</div><!-- .account-links -->