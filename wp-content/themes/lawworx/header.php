<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>
  
  <?php do_action('lawworx_before_site'); ?>
  	<?php 
	if ( function_exists( 'lawworx_contact_info' ) ) {
		lawworx_contact_info(); 
	}?>
	
    <header id="masthead"  class="site-header  float-header" role="banner">
		<div class="head-wrap banner-background">
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-9">
						<?php if ( get_theme_mod('custom_logo') ) : 
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$logo_src = wp_get_attachment_image_src( $custom_logo_id , 'full' );?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr ( bloginfo('name') ); ?>"><img class="site-logo" src="<?php echo esc_url( $logo_src[0] ); ?>" /></a>
						<?php else : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php esc_html( bloginfo( 'name' ) ); ?></a></h1>
							<h5 class="site-description"><?php esc_html( bloginfo( 'description' ) ); ?></h5>	        
						<?php endif; ?>
					</div>
					<div class="col-xs-12">
						<div class="btn-menu"></div>
						<nav id="site-navigation" class="site-navigation" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
						</nav><!-- #site-navigation -->
					</div>
				</div>
			</div>
		</div>
    </header>
	
	
	<?php 
		
	if ( function_exists( 'lawworx_mobile_header' ) ) {
		//lawworx_mobile_header(); 
	}?>
	<?php

		$banner_type = get_theme_mod('banner_type', 'image');
		if ( $banner_type != 'bgnone' || is_blog()){
	?>
	<div class="lawworx-banner-area">
		<?php if( is_front_page() ) : ?>
			<?php
				if ( $banner_type == 'video' && is_front_page() ) :
					lawworx_banner_video();
				elseif ( $banner_type == 'carousel' && is_front_page() ) :
					lawworx_banner_slider();
				else :
					lawworx_banner_background();
				endif;
			?>
		<?php else : ?>
			<div class="">
				<div class="header-content other">
					<?php if(function_exists('is_shop')) : ?>
						<h3 class="title-post entry-title"><?php wp_title(''); ?></h3>
					<?php else : ?>
						<h3 class="title-post entry-title"><?php wp_title(''); ?></h3>
					<?php endif; ?>
					<hr class="divider-separator"/>
					<?php if( get_theme_mod('show_breadcrumb', 1) != 0 ): ?>
					<div class = "breadcrumb" ><?php if (class_exists('WooCommerce')){
							( (is_woocommerce())? woocommerce_breadcrumb() :lawworx_get_breadcrumb() );
						}else{
							lawworx_get_breadcrumb();
						}?></div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<?php } ?>
	
	<div id="content" class="page-wrap">
		<div class="content-wrapper">
			<div class="container">
