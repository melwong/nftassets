<?php
function myticket_custom_css() {
    
    $myticket_main_color = '#ff6600';
    $myticket_sub_color = '#f7ca18';
    if ( get_theme_mod( 'myticket_main_color' ) ) :
        $myticket_main_color = get_theme_mod( 'myticket_main_color' );
    endif;

    if ( get_theme_mod( 'myticket_sub_color' ) ) :
        $myticket_sub_color = get_theme_mod( 'myticket_sub_color' );
    endif;
    
    ob_start();
?>
.no-js #loader {display: none;}.js #loader { display: block; position: absolute; left: 100px; top: 0;}.se-pre-con {position: fixed;left: 0px;top: 0px;width: 100%;height: 100%;z-index: 9999;background: url(<?php echo get_template_directory_uri(); ?>/images/Preloader_4.gif) center no-repeat #fff;}

h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover {color: <?php echo esc_html( $myticket_main_color ); ?>;}
input[type=submit], button {background: <?php echo esc_html( $myticket_main_color ); ?>;}
blockquote {color: <?php echo esc_html( $myticket_main_color ); ?>;}
input:focus, textarea:focus, select:focus {border: 1px solid <?php echo esc_html( $myticket_main_color ); ?>;}
select {border: 1px solid #66ab79;color: <?php echo esc_html( $myticket_main_color ); ?>;}
.bx-wrapper .bx-pager.bx-default-pager a:hover, .bx-wrapper .bx-pager.bx-default-pager a.active {background: <?php echo esc_html( $myticket_main_color ); ?>;}

.blog-admin .admin:hover {color: <?php echo esc_html( $myticket_main_color ); ?>;}

.sidebar-widget>h6 {color: <?php echo esc_html( $myticket_main_color ); ?>;}
.sidebar-widget h6 a:hover {color: <?php echo esc_html( $myticket_main_color ); ?>;}
.archives li a:hover, .archives li a:hover span {color: <?php echo esc_html( $myticket_main_color ); ?>;}
blockquote { border-left: 4px solid <?php echo esc_html( $myticket_main_color ); ?>;}

.sticky .blog-post .area-content h2{border-left:4px solid <?php echo esc_html( $myticket_main_color ); ?>;}
.bottom-footer{background-color: <?php echo myticket_adjust_brightness( esc_html( $myticket_main_color ), -30); ?>;}
.member-wrap:hover .member-info{background-color: <?php echo esc_html( $myticket_main_color ); ?>;}
.member-wrap figure:before{border: 1px solid <?php echo esc_html( $myticket_main_color ); ?>;}
.navbar-default .navbar-nav .sub-menu li a:hover{color:<?php echo esc_html( $myticket_main_color ); ?>;}
.support-tab .nav-tabs>li.active a{background-color: <?php echo esc_html( $myticket_main_color ); ?>}
.fun-fact .box{border-color: <?php echo esc_html( $myticket_main_color ); ?>;}
.nav>li>a:hover,
.nav>li>a:focus,
.nav-tabs>li.active>a,
.nav-tabs>li.active>a:hover,
.nav-tabs>li.active>a:focus{color:<?php echo esc_html( $myticket_main_color ); ?>;}
.nav-tabs>li>a:after, .food-listing-group .food-listing-row:nth-child(2n+1) figure:before,.ui-widget-header,.ui-slider .ui-slider-handle{background-color:<?php echo esc_html( $myticket_main_color ); ?>}
.food-listing-group .food-listing-row:nth-child(2n) figure:before{background-color:<?php echo esc_html( $myticket_main_color ); ?>}

/* outline-color */
.section-gallery .gallery-list .gallery-img a:hover img{outline-color:<?php echo esc_html( $myticket_main_color ); ?>;}

/* border-color */
.woocommerce ul.products li.product a.button,.woocommerce button.button.alt, .woocommerce input.button.alt,select,.ui-slider .ui-slider-handle + .ui-slider-handle,.woocommerce a.btn_white,.woocommerce .pagination-wrapper ul li a:hover, .woocommerce .pagination-wrapper ul li.active a, .woocommerce .pagination-wrapper ul li span.current,.shop_table .coupon .button, .shop_table .actions .button,.cart-steps ul.steps .current a span, .cart-steps ul.steps a:hover span,.cart-steps ul.steps a span,.woocommerce-info,.woocommerce-message,#site-navigation .cart a,.transparent .main-header-dark #site-navigation .sf-menu > li.cart a,.section-search-content .search-result-item .search-result-item-price a,.pagination-wrapper ul li a, .pagination-wrapper ul li span,.section-search-content .search-result-item .search-result-item-price a:hover,.section-refine-search input[type="submit"],.btn-outlined.btn-theme,.btn-outlined.btn-theme:hover,.btn-outlined.btn-theme:active,.section-choose-how-many-tickets .ticket-nav li.selected a,.section-choose-how-many-tickets .ticket-nav li a:hover,.section-event-single-header .ticket-purchase li a,.section-event-single-content .event-info-about ul li:before, .section-event-single-content .event-highlights ul li:before,.section-upcoming-events .section-header a,.section-latest .latest-news .pagination > li > a, .section-latest .latest-news .pagination > li > span,.content-wrapper .map-info a.link-contact,.mobile-cart a,.section-search-content .related-artist .related-artist-info > a, .section-artist-content .related-artist .related-artist-info > a,.section-search-content .artist-details .artist-details-title a, .section-artist-content .artist-details .artist-details-title a{border-color:<?php echo esc_html( $myticket_main_color ); ?>;}

/* background-color */
.woocommerce ul.products li.product a.button:hover,.woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.navbar-default .navbar-nav>li>a:after,.side-cat-list li a, .side-cat-list li span:hover, .menu-sidebox-wrap li a.active, .side-cat-list li span.active,.woocommerce a.btn_white:hover,.shop_table .coupon .button:hover, .shop_table .actions .button:hover,.cart-steps ul.steps .current a span, .cart-steps ul.steps a:hover span,.woocommerce-cart .wc-proceed-to-checkout a.button.alt,.woocommerce a.btn, .woocommerce .site-content a.btn, .btn, input[type=submit], button,.main-header-dark #site-navigation .cart a:hover,input[type="checkbox"]:checked + label::before, input[type="radio"]:checked + label::before,.tooltip-inner,.slider-selection,.section-search-content .search-result-item .search-result-item-price a,.list-item li:before,.section-search-content .search-result-footer .pagination > li > span.current,
.section-upcoming-events .search-result-footer .pagination > li > span.current,
.section-upcoming-events .search-result-footer .pagination > li:hover > a,
.section-search-content .search-result-footer .pagination > li.active > a,
.section-search-content .search-result-footer .pagination > li:hover > a,
.section-refine-search input[type="submit"]:hover,.btn-outlined.btn-theme:hover,.btn-outlined.btn-theme:active,.section-full-events-schedule .section-content .tab-pane .full-event-info .ticket-left-info,.section-full-events-schedule .section-content .tab-pane .nav .schedule-ticket-info,.section-full-events-schedule .section-content .tab-pane .full-event-info-content .book-ticket,.section-choose-how-many-tickets .ticket-nav li.selected a,
.section-choose-how-many-tickets .ticket-nav li a:hover,.section-event-single-header .ticket-purchase li a,.section-event-single-content .event-info h2:before, .section-event-single-content .event-location h2:before, .section-event-single-content .event-highlights h2:before,.section-gallery  .gallery-list .gallery-img a .gallery-view,.section-gallery .gallery-list .gallery-img a .gallery-view,.section-gallery .gallery-pagination .pagination > li > span.current, .section-gallery .gallery-pagination .pagination > li.active > a, .section-gallery .gallery-pagination .pagination > li:hover > a,.section-calendar-events .section-content .tab-content .info .get-ticket,.section-todays-schedule .section-header h2:before,.section-todays-schedule .section-content .get-ticket,.section-upcoming-events .section-header h2:before,.section-upcoming-events .section-header a:hover,.section-upcoming-events .section-content .info .get-ticket,.section-recent-videos .section-header h2:before,.section-recent-videos .section-content .video-title a:before,.section-call-to-action .section-content .action-btn:hover,.section-latest .section-header h2:before,.section-latest .latest-news .pagination > li.active > a,.section-latest .latest-news .pagination > li:hover > a,.woocommerce .pagination-wrapper ul li span.current,.header-1 #site-navigation .cart a,.section-search-content .artist-details .artist-details-title a:hover,.section-artist-content .artist-details .artist-details-title a:hover,.section-search-content .related-artist .related-artist-info > a:hover,.section-artist-content .related-artist .related-artist-info > a:hover{background-color: <?php echo esc_html( $myticket_main_color ); ?>;}

/* background-color !important */
.um-profile-nav, .um-2012.um input[type=submit].um-button, .um-2012.um input[type=submit].um-button:focus, .um-2012.um a.um-button, .um-2012.um a.um-button.um-disabled:hover, .um-2012.um a.um-button.um-disabled:focus, .um-2012.um a.um-button.um-disabled:active,.um-2012.um .um-field-group-head, .picker__box, .picker__nav--prev:hover, .picker__nav--next:hover, .um-2012.um .um-members-pagi span.current, .um-2012.um .um-members-pagi span.current:hover, .um-2012.um .um-profile-nav-item.active a, .um-2012.um .um-profile-nav-item.active a:hover, .upload, .um-modal-header, .um-modal-btn, .um-modal-btn.disabled, .um-modal-btn.disabled:hover, div.uimob800 .um-account-side li a.current, div.uimob800 .um-account-side li a.current:hover,.um-left input[type=submit].um-button, .um-left input[type=submit].um-button:focus, .um-left a.um-button, .um-left  a.um-button.um-disabled:hover,.um-left  a.um-button.um-disabled:focus, .um-left a.um-button.um-disabled:active, .um-password .um-button,.um-2013.um .um-members-pagi span.current{background-color:<?php echo esc_html($myticket_main_color); ?>!important;}

/* background-color +60 */
.um-2013.um .um-members-pagi span.current{background-color:<?php echo myticket_adjust_brightness( esc_html( $myticket_main_color ), 60); ?>!important;}

/* color */
.product-single .woocommerce-Price-amount,.woocommerce ul.products li.product span.woocommerce-Price-amount,.woocommerce a.btn_white,.woocommerce-message:before,.woocommerce ul.products li.product a.button,.woocommerce button.button.alt, .woocommerce input.button.alt,.widget a:hover,.menu-listing-wrap .menu-item-wrap h4 a:hover, .menu-listing-wrap .menu-item-wrap h4.price, .menu-listing-wrap .menu-item-wrap h3.price,.tool-bar .action-btn-wrap .btn:hover, .tool-bar .action-btn-wrap .btn.active, .tool-bar .action-btn-wrap .btn:focus,.woocommerce .pagination-wrapper ul li a:hover, .woocommerce .pagination-wrapper ul li.active a, .woocommerce .pagination-wrapper ul li span.current,ul.steps li.current a, ul.steps li.completed a, ul.steps li:hover a,.shop_table .product-price .woocommerce-Price-amount,.shop_table .coupon .button, .shop_table .actions .button,.cart-steps ul.steps a, .cart-steps ul.steps a span,.woocommerce-info:before,.menu-pop-up span.price,.woocommerce .pagination-wrapper ul li a.current, .pagination-wrapper ul li span.current, .pagination-wrapper ul li a:hover, .pagination-wrapper ul li span:hover,.pagination-wrapper ul li a, .pagination-wrapper ul li span,.section-search-content .search-result-item .search-result-item-price a:hover,.section-refine-search input[type="submit"],.btn-outlined.btn-theme,.section-choose-how-many-tickets .ticket-nav li a i,.section-event-single-header .ticket-purchase li a:hover,.section-event-single-content .event-info-about ul li:before, .section-event-single-content .event-highlights ul li:before,.section-upcoming-events .section-content .info .get-ticket:hover,.section-calendar-events .section-content .tab-content .info .get-ticket:hover,.top-header.top-header-bg .top-left ul li a i,.top-header.top-header-bg .top-right ul li:first-child a,.section-newsletter .section-content .newsletter-form button[type="submit"], .section-newsletter .section-content .newsletter-form input[type="submit"],.section-todays-schedule .section-header h2,.section-todays-schedule .section-content .get-ticket:hover,.section-todays-schedule .section-header .todays-date,.section-upcoming-events .section-header a,.section-upcoming-events .section-content .info .get-ticket:hover,.section-call-to-action .section-content .action-btn,.section-latest .latest-news .pagination > li > a, .section-latest .latest-news .pagination > li > span,.content-wrapper .map-info a.link-contact,.content-wrapper .map-info .content-touch a:hover,.section-latest .latest-tweets .tweet-list p a,.footer-1 > div > h3 a:hover,.footer-1 > div > ul > li.menu-item > a:hover,.footer-2 > div > ul > li > a:hover,.section-search-content .related-artist .related-artist-info > a, .section-artist-content .related-artist .related-artist-info > a,.section-search-content .artist-details .artist-details-title a, .section-artist-content .artist-details .artist-details-title a{color:<?php echo esc_html( $myticket_main_color ); ?>;}

/* color !important*/
.um-2011.um .um-tip:hover, .um-2011.um .um-field-radio.active i, .um-2011.um .um-field-checkbox.active i, .um-2011.um .um-member-name a:hover, .um-2011.um .um-member-more a:hover, .um-2011.um .um-member-less a:hover, .um-2011.um .um-members-pagi a:hover, .um-2011.um .um-cover-add:hover, .um-2011.um .um-profile-subnav a.active, .um-2011.um .um-item-meta a, .um-account-name a:hover, .um-account-nav a.current, .um-account-side li a.current span.um-account-icon, .um-account-side li a.current:hover span.um-account-icon, .um-dropdown li a:hover, i.um-active-color, span.um-active-color,.top-header .top-right ul li:first-child a,.main-header-dark #site-navigation .nav li a:hover{color: <?php echo esc_html($myticket_main_color); ?>!important;}

/* custom */
.section-newsletter:before,.section-call-to-action .section-content:before,.section-latest .latest-news .news-item-img .date{background:<?php echo esc_html( myticket_hex2rgba($myticket_main_color, 0.9) ); ?> }
.panel-grid-cell{padding:0!important;}
.panel-grid{margin:0!important;}

.transparent #site-navigation .cart a{background:transparent;border:1px solid #ffffff!important;}
sticky-header .transparent #site-navigation .cart a{background:transparent;border:1px solid <?php echo esc_html( $myticket_main_color ); ?>!important;}
.transparent #site-navigation .cart a:hover{background:transparent;border: 1px solid <?php echo esc_html( $myticket_main_color ); ?>;}
.main-header-dark #site-navigation .cart a:hover{background:#fff;color:<?php echo esc_html( $myticket_main_color ); ?>!important;;}

<?php
$font1 = get_theme_mod( 'myticket_font1', '0' );
$font2 = get_theme_mod( 'myticket_font2', '0' );
$font3 = get_theme_mod( 'myticket_font3', '0' );
if ( empty($font1) ) { $font1 = '0'; }
if ( empty($font2) ) { $font2 = '0'; }
if ( empty($font3) ) { $font3 = '0'; }

if ( '0' != $font1 || '0' != $font2 || '0' != $font3 ){
  $fonts_arr = myticket::google_fonts();
}

if ( '0' !== $font3 ){ $font3 = $fonts_arr[$font3]; ?>
  body,p,div,strong,b,cite,ul li,span,label,button{font-family:'<?php echo esc_html( $font3 ); ?>'!important;}
<?php } 

if ( '0' !== $font1 ){ $font1 = $fonts_arr[$font1]; ?>
  h1, h2, h3, h4, h5, h6, h7, h2 span, h2 strong, h3 span, h1 strong, h3 strong, h4 a{font-family:'<?php echo esc_html( $font1 ); ?>'!important;}
<?php } 

if ( '0' !== $font2 ){ $font2 = $fonts_arr[$font2]; ?>
  ul li a{font-family:'<?php echo esc_html( $font2 ); ?>'!important;}

?>

<?php } ?>

.logo-block figure{overflow:visible;}
.logo-block img {
    width: <?php echo get_theme_mod( 'myticket_logo_width', '240' ); ?>px!important;
    height:auto;
}
@media (max-width: 767px) {
    .logo-block img {
        width: <?php echo get_theme_mod( 'myticket_logo_mobile_width', '120' ); ?>px!important;
        height:auto;
    }
}
.footer-logo img {
    width: <?php echo get_theme_mod( 'myticket_logo_footer_width', '156' ); ?>px!important;
    height:auto;
}
<?php
$buffer = ob_get_clean();
// Minify CSS
$buffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer );
$buffer = str_replace( ': ', ':', $buffer );
$buffer = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer );
wp_add_inline_style( 'myticket-style', $buffer );
    
}