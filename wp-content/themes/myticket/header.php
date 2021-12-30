<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package myticket
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<meta name="author" content="Kenzap">
<?php wp_site_icon(); ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php $colored = get_theme_mod('myticket_header_scheme', 'white');
$meta = get_post_meta( get_the_ID() );
$enable_transparent = false;
$enable_header_dark = false;
$enable_transparent_top = false;
if ( isset( $meta['_transparent_header'][0] ) ) {
    $enable_transparent = true;
} 
if ( isset( $meta['_dark_header'][0] ) ) {
    $enable_header_dark = true;
} 
if ( isset( $meta['_transparent_top_header'][0] ) ) {
    $enable_transparent_top = true;
} 

wp_head();
?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-137344205-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-137344205-3');
</script>

</head>
<body <?php body_class(); ?> >

    <?php if ( class_exists( 'Kenzap_Plugin' ) ){ Kenzap_Plugin::get_demo_body(); } ?>

    <!-- main wrapper of the site starts -->
    <header id="masthead" class="<?php if( is_admin_bar_showing() ) { echo " logged_ofset "; } echo esc_attr( $colored ); ?> noscroll <?php if ( $enable_transparent ) { echo 'transparent'; }else{ echo 'solid'; } ?> site-header <?php if ( $enable_transparent ) echo 'fix-header header-1'; ?>" >
        <?php if ( 1 == get_theme_mod( 'myticket_top_header' ) ) : ?>
            <div class="top-header <?php if ( !$enable_transparent_top ) echo 'top-header-bg'; ?>">
                <div class="container">
                    <div class="row">
                        <div class="top-left">
                            <?php if ( get_theme_mod( 'myticket_header_left_html' ) ) : ?>

                                <?php echo wp_kses( get_theme_mod( 'myticket_header_left_html' ), array( 
                                    'a' => array(
                                        'href' => array(),
                                        'title' => array()
                                    ),
                                    'br' => array(),
                                    'b' => array(),
                                    'tr' => array(),
                                    'th' => array(),
                                    'td' => array(),
                                    'em' => array(),
                                    'span' => array(
                                        'id' => array(),
                                        'class' => array(),),
                                    'i' => array( 
                                        'id' => array(),
                                        'class' => array(),),
                                    'strong' => array(),
                                    'span' => array(
                                        'href' => array(),
                                        'class' => array(),
                                    ),
                                    'div' => array(
                                        'id' => array(),
                                        'class' => array(),
                                    ),
                                    ) ); ?>

                            <?php else: ?>

                                <ul>

                                    <?php if ( get_theme_mod( 'myticket_phone' ) ) : ?>
                                        <li>
                                            <a href="tel:<?php echo preg_replace("/[^+0-9]/","",esc_attr( get_theme_mod( 'myticket_phone' ) ) ); ?>">
                                                <i class="fa fa-phone txcolors"></i>
                                                <?php echo esc_attr( get_theme_mod( 'myticket_phone' ) ); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( get_theme_mod( 'myticket_phone2' ) ) : ?>
                                        <li>
                                            <a href="tel:<?php echo preg_replace("/[^+0-9]/","",esc_attr( get_theme_mod( 'myticket_phone2' ) ) ); ?>">
                                                <i class="fa fa-phone"></i>
                                                <?php echo esc_attr( get_theme_mod( 'myticket_phone2' ) ); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ( get_theme_mod( 'myticket_email' ) ) : ?>
                                        <li>
                                            <a href="mailto:<?php echo esc_attr( get_theme_mod( 'myticket_email' ) ); ?>">
                                                <i class="fa fa-envelope-o txcolors"></i>
                                                <?php echo esc_attr( get_theme_mod( 'myticket_email' ) ); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                </ul>

                            <?php endif;?>
                        </div>
                        <div class="top-right">
                            <?php if ( get_theme_mod( 'myticket_header_right_html' ) ) : ?>

                                <?php echo wp_kses( get_theme_mod( 'myticket_header_right_html' ), array( 
                                    'a' => array(
                                        'href' => array(),
                                        'title' => array()
                                    ),
                                    'br' => array(),
                                    'b' => array(),
                                    'tr' => array(),
                                    'th' => array(),
                                    'td' => array(),
                                    'em' => array(),
                                    'span' => array(
                                        'id' => array(),
                                        'class' => array(),),
                                    'i' => array( 
                                        'id' => array(),
                                        'class' => array(),),
                                    'strong' => array(),
                                    'span' => array(
                                        'href' => array(),
                                        'class' => array(),
                                    ),
                                    'div' => array(
                                        'id' => array(),
                                        'class' => array(),
                                    ),
                                    ) ); ?>

                            <?php else: ?>
                                <?php if ( 1 == get_theme_mod( 'myticket_sign_cont' ) ) : ?>
                                    <ul>
                                        <?php if( !is_user_logged_in() ) : ?>

                                            <li class="login"> 
                                                <?php $myticket_login_link = get_theme_mod( 'myticket_login_link', '/login/' );
                                                if ( empty($myticket_login_link) || $myticket_login_link == '' ) {

                                                    $myticket_login_link = wp_login_url( get_permalink() );
                                                } ?>
                                                <a href="<?php echo esc_url( $myticket_login_link ); ?>" title="<?php esc_html_e( 'Sign In', 'myticket' ); ?>"><?php esc_html_e( 'Sign in', 'myticket' ); ?></a>
                                            </li>

                                            <li class="register"> 
                                                <?php $myticket_register_link = get_theme_mod( 'myticket_register_link', '/register/' );
                                                if ( empty($myticket_register_link) || $myticket_register_link == '' ) {

                                                    $myticket_register_link = wp_registration_url( get_permalink() );
                                                } ?>
                                                <a href="<?php echo esc_url( $myticket_register_link ); ?>" title="<?php esc_html_e( 'Sign up', 'myticket' ); ?>"><?php esc_html_e( 'Sign up', 'myticket' ); ?></a>
                                            </li>

                                        <?php else: ?>
										
											<!--Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7-->
											<li class="logout"> 
												<?php if ( is_user_logged_in() ) : ?>
													<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'My account', 'myticket' ); ?></a>
												<?php endif; ?>
                                            </li>
											<!--Mel: End-->

                                            <li class="logout"> 
                                                <?php $myticket_logout_link = get_theme_mod( 'myticket_logout_link', '/logout/' );
                                                if ( empty($myticket_logout_link) || $myticket_logout_link == '' ) {

                                                    $myticket_logout_link = wp_logout_url( get_permalink() );
                                                } ?>
                                                <a href="<?php echo esc_url( $myticket_logout_link ); ?>" title="<?php esc_html_e( 'Sign out', 'myticket' ); ?>"><?php esc_html_e( 'Sign out', 'myticket' ); ?></a>
                                            </li>

                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endif; ?>
      
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="header-empty"></div>
        <div class="main-header <?php if ( $enable_header_dark ) { echo 'main-header-dark'; } if ( !$enable_transparent ) echo 'main-header-bg'; ?>">
            <div class="container">
                <div class="row">
                    <div class="site-branding col-md-3 logo-block">
                        <?php 

                            if ( $enable_header_dark ) {
                                $imgurl = (get_theme_mod( 'myticket_logo_dark', '' ));
                                if(empty($imgurl) || '' == $imgurl){

                                    $imgurl = get_template_directory_uri() . '/images/logo-dark.png';
                                }
                            }else{   
                                $imgurl = (get_theme_mod( 'myticket_logo', '' ));
                                if(empty($imgurl) || '' == $imgurl){

                                    $imgurl = get_template_directory_uri() . '/images/logo.svg';
                                }
                            }
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="myticket" rel="home"><img src='<?php echo esc_url( $imgurl ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a></h1>
                    </div>

                    <div class="col-md-9">
                        <nav id="site-navigation" class="navbar">
                            <div class="navbar-header">
                                <?php if ( 1 == get_theme_mod( 'myticket_cart', 1 ) && class_exists( 'WooCommerce' ) ) :
                                    global $woocommerce; ?>
                                    <div class="mobile-cart" ><a href="<?php echo esc_url( ((wc_get_cart_url()==get_site_url())?get_site_url()."/cart/":wc_get_cart_url()) ); ?>"><?php echo WC()->cart->get_cart_contents_count(); ?></a></div>
                                <?php endif; ?>
                                <button type="button" class="navbar-toggle offcanvas-toggle pull-right" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">
                                    <span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'myticket' ); ?></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="navbar-offcanvas navbar-offcanvas-touch navbar-offcanvas-right" id="js-bootstrap-offcanvas">
                                <button type="button" class="offcanvas-toggle closecanvas" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">
                                   <i class="fa fa-times fa-2x" aria-hidden="true"></i>
                                </button>

                                    <?php

                                    if ( has_nav_menu( 'primary' ) ) {
                                        wp_nav_menu(array(
                                              'theme_location'  => 'primary',
                                              'menu_class'      => 'nav navbar-nav navbar-right navbar-primary sf-menu',
                                              'container'       => 'div',
                                              'container_id'    => 'tyuio',
                                              'container'       => false,
                                              'depth'           => 3,
                                              'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s',
                                              ));
                                    }
                                    if ( 1 == get_theme_mod( 'myticket_cart', 1 ) && class_exists( 'WooCommerce' ) ) :

                                        global $woocommerce; ?>
                                        <li class="cart"><a class="cart-count" href="<?php echo esc_url( ((wc_get_cart_url()==get_site_url())?get_site_url()."/cart/":wc_get_cart_url()) ); ?>"><?php echo WC()->cart->get_cart_contents_count(); ?></a></li>

                                    <?php endif; ?>
                                    </ul>
                                    <?php 

                                    if ( has_nav_menu( 'primary_mobile' ) ) {
                                        wp_nav_menu(array(
                                              'theme_location'  => 'primary_mobile',
                                              'menu_class'      => 'nav navbar-nav navbar-right navbar-primary_mobile',
                                              'container'       => 'div',
                                              'container_id'    => 'tyuio',
                                              'container'       => false,
                                              'depth'           => 2,
                                              'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s',
                                              ));
                                    }
                                    if ( 1 == get_theme_mod( 'myticket_cart' ) && class_exists( 'WooCommerce' ) ) :

                                        global $woocommerce; ?>
                                        <li class="cart"><a class="cart-count" href="<?php echo esc_url( ((wc_get_cart_url()==get_site_url())?get_site_url()."/cart/":wc_get_cart_url()) ); ?>"><?php echo WC()->cart->get_cart_contents_count(); ?></a></li>

                                    <?php endif; ?>
                                    </ul>

                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
