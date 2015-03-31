<?php 
/**
 * Account links
 *
 * @package Benny
 */
$crowdfunding_enabled 	= true;
$profile_page		 	= '#';
?>
<div class="account-links">
	<?php 

	if ( is_user_logged_in() ) : 
		if ( $crowdfunding_enabled && $profile_page ) : 

		?>
			<a class="user-account with-icon button button-alt button-small" href="<?php echo $profile_page ?>" data-icon="&#xf007;"><?php _e('Profile', 'benny') ?></a>
		<?php 
		endif ?>

		<a class="logout with-icon" href="<?php echo wp_logout_url( get_permalink() ) ?>" data-icon="&#xf08b;"><?php _e('Log out', 'benny') ?></a>

	<?php endif ?>
</div><!-- .account-links -->