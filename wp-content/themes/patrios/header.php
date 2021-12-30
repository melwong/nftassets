<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $favicon_icon = get_theme_mod( 'favicon_logo', get_template_directory_uri().'/images/logo.png' ); ?>
    <?php if ($favicon_icon): ?>
    <link rel="icon" href="<?php echo $favicon_icon; ?>">
    <?php endif ?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>


<?php $preloader = get_theme_mod( 'header_preloader', true ); ?>

<?php if ($preloader): ?>
    <!-- preloader area start -->
    <div id="preloader">
        <div class="preloader_inner">
            <img src="<?php echo $favicon_icon; ?>" alt="preloader image">
            <div class="preloader_dots">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>
    <!-- preloader area end -->
<?php endif ?>


<?php
  $layout = get_theme_mod( 'boxfull_en', 'fullwidth' );
  $headerlayout = get_theme_mod( 'head_style', 'solid' );
?>
<div id="page" class="hfeed site <?php echo esc_attr($layout); ?>">
    <header id="masthead" class="site-header header header-<?php echo esc_attr($headerlayout);?>">
        <?php echo ( !class_exists('wp_megamenu_initial_setup') ) ? '<div class="patrios-menu-wrap">' : '<div class="megamenu-main">'; ?>
        <div class="site-header-wrap container">
            <div class="row">

                <?php if( !class_exists('wp_megamenu_initial_setup') ) { ?>
                    <!-- Logo Section -->
                    <div class="col-xs-6 col-sm-3 col-lg-2 col-lg-auto">
                    <div class="themeum-navbar-header">
                        <div class="logo-wrapper">
                        <a class="themeum-navbar-brand" href="<?php echo esc_url(site_url()); ?>">
                            <?php
                                $logoimg = get_theme_mod( 'logo', get_parent_theme_file_uri().'/images/logo.png' );
                                $logotext = get_theme_mod( 'logo_text', 'patrios' );
                                $logotype = get_theme_mod( 'logo_style', 'logoimg' );
                                switch ($logotype) {
                                case 'logoimg':
                                    if( !empty($logoimg) ) {?>
                                        <img class="enter-logo img-responsive" src="<?php echo esc_url( $logoimg ); ?>" alt="<?php  esc_html_e( 'Logo', 'patrios' ); ?>" title="<?php  esc_html_e( 'Logo', 'patrios' ); ?>">
                                    <?php }else{?>
                                        <h1> <?php  echo esc_html(get_bloginfo('name'));?> </h1>
                                    <?php }
                                    break;

                                    case 'logotext':
                                    if( $logotext ) { ?>
                                        <h1> <?php echo esc_html( $logotext ); ?> </h1>
                                    <?php }
                                    else
                                    {?>
                                        <h1><?php  echo esc_html(get_bloginfo('name'));?> </h1>
                                    <?php }
                                    break;

                                default:
                                    if( $logotext ) { ?>
                                        <h1> <?php echo esc_html( $logotext ); ?> </h1>
                                    <?php }
                                    else
                                    {?>
                                    <h1><?php  echo esc_html(get_bloginfo('name'));?> </h1>
                                    <?php }
                                    break;
                                } ?>
                            </a>
                        </div>
                    </div><!--/#themeum-navbar-header-->
                    </div><!-- Logo End -->
                <?php } ?>

                    <!-- Menu Setup - Default menu or Megamenu -->
                    <?php if( !class_exists('wp_megamenu_initial_setup') ) { ?>
                        <div class="clearfix col-xs-6 col-sm-5 col-lg-5 col-lg-auto">
                    <?php } else { ?>
                        <div class="col-md-12 col-lg-12 common-menu common-main-menu">
                    <?php } ?>
                        <?php if ( has_nav_menu( 'primary' ) ) { ?>
                            <div id="main-menu" class="common-menu-wrap d-none d-lg-block">
                                <?php
                                    wp_nav_menu(  array(
                                        'theme_location' => 'primary',
                                        'container'      => '',
                                        'menu_class'     => 'nav',
                                        'fallback_cb'    => 'wp_page_menu',
                                        'depth'          => 4,
                                        'walker'         => new Megamenu_Walker()
                                        )
                                    );
                                ?>
                            </div><!--/#main-menu-->
                        <?php } ?>
                    </div>
                    <!-- End Menu -->

                    <!-- Login Registration section -->
                    <div class="d-block col-xs-6 col-sm-2 col-lg-5 register">
                    <?php if( get_theme_mod( 'header_campaign', false ) ): ?>
                        <div class="patrios-login-register">
                            <ul>
                                <?php 
                                $campaign_text  = get_theme_mod( 'header_campaign_text', 'Start a Campaign' );
                                $campaign_url   = get_theme_mod( 'header_campaign_url'); 
                                ?>
                                <?php if( get_theme_mod( 'header_search', false ) ): ?>
                                    <li class="patrios-search-wrap">
                                        <div class="d-none d-lg-block">
                                            <a href="#" class="patrios-search search-open-icon"><i class="back-magnifying-glass-2"></i></a>
                                        </div> 
                                    </li>
                                <?php endif; ?>
                                <!-- Login Section -->
                                <?php if( get_theme_mod( 'header_login', false ) ): ?>
                                    <?php if ( !is_user_logged_in() ): ?>
                                        <li><div class="d-none d-lg-block"><a data-toggle="modal" data-target="#myModal" href="#"><?php _e( 'Login/Sign Up','patrios' ); ?></a></div></li>
                                    <?php else: ?>
                                    <?php $dashboard_id = get_option( 'wpneo_crowdfunding_dashboard_page_id','' ); ?>
                                    <?php if($dashboard_id){ ?>
                                        <li><div class="d-none d-lg-block"><a href="<?php the_permalink( $dashboard_id ); ?>"> <i class="back-profile"></i><?php echo get_the_title( $dashboard_id ); ?></a></div></li>
                                    <?php } else { ?>
                                        <i class="back-profile"></i><a href="<?php echo wp_logout_url( esc_url( home_url('/') ) ); ?>"><?php _e('Logout','patrios'); ?></a>
                                    <?php } ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- End Login section -->
                                <li><a href="<?php echo esc_url($campaign_url); ?>" class="patrios-login patrios-dashboard"><?php echo wp_kses_post($campaign_text); ?></a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    </div><!--End register-->

                    <!-- Mobile menu -->
                    <?php if( ! class_exists('wp_megamenu_initial_setup')) { ?>
                    <div class="col-7 col-sm-6 d-lg-none">
                        <button type="button" class="navbar-toggle float-right" data-toggle="collapse" data-target=".navbar-collapse">
                            <i class="fa fa-navicon"></i>
                        </button>
                        <?php if( get_theme_mod( 'header_login', false ) || get_theme_mod( 'header_search', false ) ): ?>
                            <div class="patrios-login-register float-right">
                                <?php if( get_theme_mod( 'header_search', false ) ): ?>
                                    <div class="patrios-search-wrap">
                                        <a href="#" class="patrios-search search-open-icon"><i class="back-magnifying-glass-2"></i></a>
                                    </div>
                                <?php endif; ?>
                                <ul>
                                    <!-- Login Section -->
                                    <?php if( get_theme_mod( 'header_login', false ) ): ?>
                                        <?php if ( !is_user_logged_in() ): ?>
                                            <li><a data-toggle="modal" data-target="#myModal" href="#"> <i class="back-profile"></i></a></li>
                                        <?php else: ?>
                                        <?php $dashboard_id = get_option( 'wpneo_crowdfunding_dashboard_page_id','' ); ?>
                                            <li><a href="<?php the_permalink( $dashboard_id ); ?>"> <i class="back-profile"></i></a></li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <!-- End Login section -->
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 d-lg-none">
                        <div id="mobile-menu" class="">
                            <div class="collapse navbar-collapse">
                                <?php
                                if ( has_nav_menu( 'primary' ) ) {
                                    wp_nav_menu( array(
                                        'theme_location'      => 'primary',
                                        'container'           => false,
                                        'menu_class'          => 'nav navbar-nav',
                                        'fallback_cb'         => 'wp_page_menu',
                                        'depth'               => 3,
                                        'walker'              => new wp_bootstrap_mobile_navwalker()
                                        )
                                    );
                                }
                                ?>
                            </div>
                        </div><!--/.#mobile-menu-->
                    </div>
                    <?php } ?>
                    <!-- End Mobile Menu -->

                    <div class="thm-fullscreen-search d-flex flex-wrap justify-content-center align-items-center">
                        <div class="search-overlay"></div>
                        <form action="<?php echo esc_url(home_url( '/' )); ?>" method="get">
                            <input class="main-font" type="text" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php esc_html_e('Search here...','patrios'); ?>" autocomplete="off" />
                            <input type="submit" value="submit" class="d-none" id="thm-search-submit">
                            <label for="thm-search-submit"><i class="back-magnifying-glass-2"></i></label>
                        </form>
                    </div> <!--/ .main-menu-wrap -->
                </div><!--Row-->
            </div><!--/.container-->
        </div><!--Empty Div-->
    </header><!--/.header-->


<?php if ( !is_user_logged_in() ): ?>
    <!-- Login -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php esc_html_e( 'Sign In','patrios' ); ?></h4>
                    <p class="modal-text"><?php esc_html_e( 'Don’t worry, we won’t spam you or sell your information.','patrios' ); ?></p>
                </div>
                <div class="modal-body">
                    <form id="login" action="login" method="post">
                        <div class="login-error alert alert-danger" role="alert"></div>
                        <input type="text"  id="usernamelogin" name="username" class="form-control" placeholder="Username">
                        <input type="password" id="passwordlogin" name="password" class="form-control" placeholder="Password">
                        <input type="checkbox" id="rememberlogin" name="remember" ><label><?php esc_html_e( 'Remember me','patrios' ); ?></label>
                        <input type="submit" class="btn btn-primary submit_button"  value="Log In" name="submit">
                        <?php wp_nonce_field( 'ajax-login-nonce', 'securitylogin' ); ?>
                    </form>
                </div>
                <div class="modal-footer clearfix d-block text-left">
                    <div class="d-inline-block">
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e( 'Forgot password?','patrios' ); ?></a>
                    </div>
                    <div class="d-inline-block float-right">
                        <a data-toggle="modal" data-target="#registerlog" href="#" data-dismiss="modal" ><?php esc_html_e( 'Sign Up','patrios' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerlog" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php esc_html_e( 'Sign Up','patrios' ); ?></h4>
                    <p class="modal-text"><?php esc_html_e( 'By signing up you agree to all the Terms and conditions.','patrios' ); ?></p>
                </div>
                <div class="modal-body">
                    <form id="register" action="login" method="post">
                        <div class="login-error alert alert-danger" role="alert"></div>
                        <input type="text" id="username" name="username" class="form-control" placeholder="<?php esc_html_e( 'Username','patrios' ); ?>">
                        <input type="text" id="email" name="email" class="form-control" placeholder="<?php esc_html_e( 'Email','patrios' ); ?>">
                        <input type="password" id="password" name="password" class="form-control" placeholder="<?php esc_html_e( 'Password','patrios' ); ?>">
                        <input type="submit" class="btn btn-primary submit_button"  value="Register" name="submit">
                        <?php wp_nonce_field( 'ajax-register-nonce', 'security' ); ?>
                    </form>
                </div>
                <div class="modal-footer clearfix d-block text-left">
                    <div class="d-inline-block">
                        <a data-toggle="modal" data-target="#myModal" href="#" data-dismiss="modal" ><?php esc_html_e( 'Sign In','patrios' ); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
