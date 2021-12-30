<?php

if ( ! function_exists( 'myticket_setup' ) ) :
function myticket_setup() {

  /* add title tag support */
  add_theme_support( 'title-tag' );

  /* Add excerpt to pages */
  add_post_type_support( 'page', 'excerpt' );

  // wp-content/themes/myticket-child-theme/languages/nb_NO.mo
  load_theme_textdomain( 'myticket', get_stylesheet_directory() . '/languages' );

  /* load theme languages */
  load_theme_textdomain( 'myticket', get_template_directory() . '/languages' );
  
  /* Add default posts and comments RSS feed links to head */
  add_theme_support( 'automatic-feed-links' );

  /* Add support for post thumbnails */
  add_theme_support( 'post-thumbnails' );

  /* Add support for HTML5 */
  add_theme_support( 'html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'caption',
    'widgets',
  ) );
  
  /*  Enable support for Post Formats */
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

  add_theme_support( 'gutenberg', array( 'wide-images' => true ) );
}
endif;
    
add_theme_support( 'post-formats', array( 'aside', 'video', 'quote', 'link', 'image', 'gallery' ) );

// myticket_setup
add_action( 'after_setup_theme', 'myticket_setup' );

add_image_size( 'myticket-minified', 262, 179, true );
add_image_size( 'myticket-blog', 750, 405, true );
add_image_size( 'myticket-story-large', 1536, 637, true );
add_image_size( 'myticket-story', 568, 291, true );
add_image_size( 'myticket-schedule', 836, 261, true );
add_image_size( 'myticket-event', 390, 280, true );
add_image_size( 'myticket-mini', 70, 70, true );
add_image_size( 'myticket-thumb', 192, 132, true ); 
add_image_size( 'myticket-gallery', 270, 187, true );
add_image_size( 'myticket-aboutus-small', 321, 257, true );
add_image_size( 'myticket-aboutus-large', 471, 543, true );

/*  Registrer menus. */
register_nav_menus( array(
      'primary' => esc_html__( 'Main Menu', 'myticket' ),
      'primary_mobile' => esc_html__( 'Main Menu - Mobile', 'myticket' ),
      'footer' => esc_html__( 'Footer Menu', 'myticket' ),
      'footer_right' => esc_html__( 'Footer Menu Right', 'myticket' ),
      ) );

function myticket_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'myticket_mime_types');

/**
 * Deactivate default widgets
 */
function myticket_widgets_init() {

  register_sidebar( array(
    'name'          => esc_html__( 'Sidebar', 'myticket' ),
    'id'            => 'sidebar-main',
    'description'   => esc_html__( 'Blog extension sidebar for core functionality', 'myticket' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3><div class="tx-div small"></div>',
  ) );
}

add_action( 'widgets_init', 'myticket_widgets_init' );

function myticket_fonts_url() {

  $fonts_url = '';
  $pt_serif = _x( 'off', 'Open Sans: on or off', 'myticket' );
  $elsie = _x( 'off', 'Elsie font: on or off', 'myticket' );
  $montserrat = _x( 'on', 'Montserrat font: on or off', 'myticket' );
   
  if ( 'off' !== $pt_serif || 'off' !== $elsie  || 'off' !== $montserrat ) {
    $font_families = array();
     
    if ( 'off' !== $pt_serif ) {
      $font_families[] = 'Open Sans:300,400,600,700';
    }

    if ( 'off' !== $elsie ) {
      $font_families[] = 'Elsie:400,900';
    }
     
    if ( 'off' !== $montserrat ) {
      $font_families[] = 'Montserrat:100,200,300,400,700';
    }

    $query_args = array(
      'family' => urlencode( implode( '|', $font_families ) ),
      'subset' => urlencode( 'latin,latin-ext' ),
    );
     
    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
  }
  return esc_url_raw( $fonts_url );
}

/* Setup myticket Scripts and CSS */
function myticket_scripts() {

  $theme = wp_get_theme('myticket');
  $version = $theme['Version'];

  /* Ajax urls */
  $ajaxurl = '';
  if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
      $ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
  } else{
      $ajaxurl .= admin_url( 'admin-ajax.php');
  }

  /* Styles */
  if( 1 != get_theme_mod( 'myticket_minified' ) ){
      wp_enqueue_style( 'bootstrap', get_template_directory_uri() .'/css/bootstrap.min.css', array(), $version, 'all' ); 
      wp_enqueue_style( 'bootstrap-select', get_template_directory_uri() .'/css/bootstrap-select.min.css', array(), $version, 'all' ); 
      wp_enqueue_style( 'jquery-scrolling-tabs', get_template_directory_uri() .'/css/jquery.scrolling-tabs.min.css', array(), $version, 'all' );
      wp_enqueue_style( 'bootstrap-checkbox', get_template_directory_uri() .'/css/bootstrap-checkbox.css', array(), $version, 'all' );
      //wp_enqueue_style( 'bootstrap-slider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/css/bootstrap-slider.min.css', array(), $version, 'all' );
      //wp_enqueue_style( 'bootstrap-slider', get_template_directory_uri() .'/css/bootstrap-slider.min.css', array(), $version, 'all' );
      wp_enqueue_style( 'flexslider', get_template_directory_uri() .'/css/flexslider.css', array(), $version, 'all' );
      wp_enqueue_style( 'featherlight', get_template_directory_uri() .'/css/featherlight.min.css', array(), $version, 'all' );
      wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/font-awesome.min.css', array(), $version, 'all' );
      wp_enqueue_style( 'bootstrap-offcanvas', get_template_directory_uri() .'/css/bootstrap.offcanvas.min.css', array(), $version, 'all' );
      wp_enqueue_style( 'superfish', get_template_directory_uri() .'/css/superfish.css', array(), $version, 'all' );
      wp_enqueue_style( 'myticket-fonts', myticket::load_fonts(), array(), null );

  } else {
      //wp_enqueue_style( 'myticket-fonts', myticket_fonts_url(), array(), null );
      wp_enqueue_style( 'myticket-fonts', myticket::load_fonts(), array(), null );
      wp_enqueue_style( 'myticket-minified', get_template_directory_uri() .'/css/myticket.min.css', array(), $version, 'all' );
  }

  /* Load Custom styles CSS */
  wp_enqueue_style( 'myticket-style', get_stylesheet_uri(), array(), $version, 'all');
  myticket_custom_css();

  /* JS libaries */
  if( 1 != get_theme_mod( 'myticket_minified' ) ){   

      //wp_enqueue_script( 'bootstrap-slider', get_template_directory_uri() .'/js/bootstrap-slider.min.js', array( 'jquery' ), $version, true );
      //wp_enqueue_script( 'bootstrap-slider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/bootstrap-slider.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'bootstrap-select', get_template_directory_uri() .'/js/bootstrap-select.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'jquery-scrolling-tabs', get_template_directory_uri() .'/js/jquery.scrolling-tabs.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'jquery-countdown', get_template_directory_uri() .'/js/jquery.countdown.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'jquery-flexslider', get_template_directory_uri() .'/js/jquery.flexslider-min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'jquery-imagemapster', get_template_directory_uri() .'/js/jquery.imagemapster.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'bootstrap', get_template_directory_uri() .'/js/bootstrap.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'featherlight', get_template_directory_uri() .'/js/featherlight.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'featherlight-gallery', get_template_directory_uri() .'/js/featherlight.gallery.min.js', array( 'jquery' ), $version, true );
      wp_enqueue_script( 'bootstrap-offcanvas', get_template_directory_uri() .'/js/bootstrap.offcanvas.min.js', array( 'jquery' ), $version, true );

  } else {
      
      wp_enqueue_script( 'myticket-minified', get_template_directory_uri() .'/js/myticket.min.js', array( 'jquery' ), $version, true );
  }

  $googleapis = get_theme_mod( 'myticket_maps_api', '' );
  if ( !empty($googleapis) && '' != $googleapis ){
    wp_enqueue_script( 'maps.googleapis', 'https://maps.googleapis.com/maps/api/js?key='.esc_attr( $googleapis ), array('jquery'), $version, true );//callback=initMap&
    wp_enqueue_script( 'myticket-script', get_template_directory_uri() .'/js/main.js', array( 'jquery', 'maps.googleapis' ), $version, true );
  }else{
    wp_enqueue_script( 'myticket-script', get_template_directory_uri() .'/js/main.js', array( 'jquery' ), $version, true );
  }
 
  /* add JS variables to scripts */
  wp_localize_script( 'myticket-script', 'screenReaderText', array(
      'expand'   => esc_html__( 'expand child menu', 'myticket' ),
      'prev'  => esc_html__('Prev', 'myticket'),
      'next'  => esc_html__('Next', 'myticket'),
      'collapse' => esc_html__( 'collapse child menu', 'myticket' ),
      'ajaxurl'  => $ajaxurl,
      'noposts'  => esc_html__('No records found', 'myticket'),
      'loadmore' => esc_html__('Load more', 'myticket')
  ) );
  wp_localize_script( 'myticket-theme-js', 'ajaxURL',  array( 'ajaxurl'    => admin_url( 'admin-ajax.php' ) ) );
  wp_localize_script( 'myticket-theme-js-minified', 'ajaxURL',  array( 'ajaxurl'    => admin_url( 'admin-ajax.php' ) ) );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'myticket_scripts' );

    
function myticket_image_dimensions() {
  global $pagenow;
 
  if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
    return;
  }
}    
add_action( 'after_switch_theme', 'myticket_image_dimensions', 1 );


function myticket_body_class( $classes ) {

    if ( '0' == get_theme_mod( 'sidebar_sprod', '1' ) ){
      $classes[] = 'rem-sidebar';
    }
    
    if ( 'left' == get_theme_mod( 'sidebar_location' ) ){
      $classes[] = 'left-sidebar';
    }else if ( 'right' == get_theme_mod( 'sidebar_location' ) ){
      $classes[] = 'right-sidebar';
    }

    if ( 1 == get_theme_mod( 'myticket_sticky' ) )
      $classes[] = ' add-sticky-header';

    //sidebar position
    return $classes;  
} 
add_filter( 'body_class', 'myticket_body_class' );


//footer menu nav walker
class myticket_footer_walker_nav_menu extends Walker_Nav_Menu {
    
    // add classes to ul sub-menus
    public $myticket_depth_couter = 0;
    function __construct(){
        $this->myticket_depth_couter = 0;
  
    }

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
                         'sub-menu',
                         ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
                         ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
                         'menu-depth-' . $display_depth
                         );
        $class_names = implode( ' ', $classes );
        
        // build html
        if($this->myticket_depth_couter<4){
        $output .= "\n" . $indent . '<ul class="list-unstyled no-margin ' . $class_names . '">' . "\n";
      }
    }
    
    // add main/sub classes to li's and links
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
        
        // depth dependent classes
        $depth_classes = array(
                               ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
                               ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
                               ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
                               'menu-item-depth-' . $depth
                               );

        // passed classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
        
        
        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        if($this->myticket_depth_couter<2){
          if( $depth == 0 ) {
              
             
              $output .= "\n".'<div class="about clearfix  ' . $class_names . '">';
              $item_output = sprintf( '%1$s<h3><a%2$s>%3$s%4$s%5$s</a></h3>%6$s',
                                     $args->before,
                                     $attributes,
                                     $args->link_before,
                                     apply_filters( 'the_title', $item->title, $item->ID ),
                                     $args->link_after,
                                     $args->after
                                     );
          }else{
              
              $output .= $indent . "\n".'<li class="' . $class_names . '">';
              $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                                     $args->before,
                                     $attributes,
                                     $args->link_before,
                                     apply_filters( 'the_title', $item->title, $item->ID ),
                                     $args->link_after,
                                     $args->after
                                     );
          }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
      }
    }
    
    function end_el(&$output, $item, $depth=0, $args=array()) {
        if( $depth == 0 ) {
            
            if($this->myticket_depth_couter<4){
             $output .= "</div>\n";
             $this->myticket_depth_couter++;
          }
        }
    }
}


//footer right menu nav walker
class myticket_footer_right_walker_nav_menu extends Walker_Nav_Menu {
    
    // add classes to ul sub-menus
    public $myticket_depth_couter = 0;
    function __construct(){
        $this->myticket_depth_couter = 0;
  
    }

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        // depth dependent classes
        $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
        $display_depth = ( $depth + 1); // because it counts the first submenu as 0
        $classes = array(
                         'sub-menu',
                         ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
                         ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
                         'menu-depth-' . $display_depth
                         );
        $class_names = implode( ' ', $classes );
        
        // build html
        if($this->myticket_depth_couter<4){
        $output .= "\n" . $indent . '<ul class="list-unstyled no-margin ' . $class_names . '">' . "\n";
      }
    }
    
    // add main/sub classes to li's and links
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $wp_query;
        $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
        
        // depth dependent classes
        $depth_classes = array(
                               ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
                               ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
                               ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
                               'menu-item-depth-' . $depth
                               );

        // passed classes
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
        
        
        // link attributes
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

        if($this->myticket_depth_couter<2){
          if( $depth == 0 ) {
              
             
              $output .= "\n".'<div class="footer-dashboard">';
              $item_output = sprintf( '%1$s<h3><a%2$s>%3$s%4$s%5$s</a></h3>%6$s',
                                     $args->before,
                                     $attributes,
                                     $args->link_before,
                                     apply_filters( 'the_title', $item->title, $item->ID ),
                                     $args->link_after,
                                     $args->after
                                     );
          }else{
              
              $output .= $indent . "\n".'<li class="' . $class_names . '">';
              $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                                     $args->before,
                                     $attributes,
                                     $args->link_before,
                                     apply_filters( 'the_title', $item->title, $item->ID ),
                                     $args->link_after,
                                     $args->after
                                     );
          }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
      }
    }
    
    function end_el(&$output, $item, $depth=0, $args=array()) {
        if( $depth == 0 ) {
            
            if($this->myticket_depth_couter<4){
             $output .= "</div>\n";
             $this->myticket_depth_couter++;
          }
        }
    }
}

require get_template_directory() . '/inc/setup-woocommerce.php';