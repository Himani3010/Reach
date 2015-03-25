<?php
/**
 * A custom header used by the Stripped page template.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Benny
 */
?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes() ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed site-container">		
		<div class="body-wrapper">
			<header id="header" class="cf wrapper site-header" role="banner">
				<div class="site-branding site-identity">
					<a class="home-link" href="<?php echo benny_site_url() ?>"></a>
					<?php 
					benny_site_title();
					benny_site_tagline(); 
					?>								
				</div><!-- .site-branding -->				
			</header><!-- #header -->
			<div id="main" class="site-content fullwidth cf">