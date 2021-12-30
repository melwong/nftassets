<?php

if(!function_exists('patrios_setup')):
    function patrios_setup(){
        load_theme_textdomain( 'patrios', get_parent_theme_file_path() . '/languages' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'patrios-large', 1140, 570, true );
        add_image_size( 'patrios-medium', 660, 400, true );
        add_image_size( 'patrios-portfo', 600, 500, true );
        add_image_size( 'patrios-portfo2', 600, 580, true );
        add_image_size( 'patrios-blog', 360, 443, true );
        add_image_size( 'patrios-campaign', 360, 330, true );
        add_theme_support( 'post-formats', array( 'audio','gallery','image','link','quote','video' ) );
        add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
        add_theme_support( 'automatic-feed-links' );

        if ( ! isset( $content_width ) ){
            $content_width = 660;
        }
        add_theme_support( 'woocommerce' );
    }
    add_action('after_setup_theme','patrios_setup');
endif;


if(!function_exists('patrios_pagination')):

    function patrios_pagination( $page_numb , $max_page ){
        $big = 999999999;
        echo '<div class="themeum-pagination" data-preview="'.__( "PREV","patrios" ).'" data-nextview="'.__( "NEXT","patrios" ).'">';
        echo paginate_links( array(
            'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'        => '?paged=%#%',
            'current'       => $page_numb,
            'prev_text'     => ('PREV'),
            'next_text'     => ('NEXT'),
            'total'         => $max_page,
            'type'          => 'list',
        ) );
        echo '</div>';
    }

endif;

/*-------------------------------------------------------
 *              Themeum Comment
 *-------------------------------------------------------*/
if(!function_exists('patrios_comment')):
    function patrios_comment($comment, $args, $depth){
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
            case 'pingback' : 
            case 'trackback' :
             ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <?php
            break;
            default :
            global $post;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-body">
                <div class="comment-top clearfix">
                    <div class="comment-avartar pull-left">
                        <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
                    </div>
                    <div class="comment-context">
                        <div class="comment-head">
                            <?php printf( '<span class="comment-author">%1$s</span>', get_comment_author_link() ); ?>
                            <span class="comment-date"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo get_comment_date() ?></span>
                            <?php edit_comment_link( esc_html__( 'Edit', 'patrios' ), '<span class="edit-link">', '</span>' ); ?>
                        </div>
                        <?php if ( '0' == $comment->comment_approved ) : ?>
                            <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'patrios' ); ?></p>
                        <?php endif; ?>
                        <span class="comment-reply">
                            <?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="fa fa-reply" aria-hidden="true"></i> '.esc_html__( 'Reply', 'patrios' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                        </span>
                    </div>
                </div>
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>
            </div>
        <?php
            break;
        endswitch;
    }

endif;

/*-------------------------------------------------------
*           Themeum Breadcrumb
*-------------------------------------------------------*/
if(!function_exists('patrios_breadcrumbs')):
    function patrios_breadcrumbs(){ ?>
        <ol class="breadcrumb">
            <li><a href="<?php echo esc_url(site_url()); ?>" class="breadcrumb_home"><?php esc_html_e('Home', 'patrios') ?></a></li>
            <?php
                if(function_exists('is_product')){
                    $shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
                    if(is_product()){
                        echo "<li><a href='".$shop_page_url."'>shop</a></li>";
                    }
                }
            ?>
            <li class="active">

                <?php if(function_exists('is_shop')){ if(is_shop()){ esc_html_e('shop','patrios'); } } ?>

                <?php if( is_tag() ) { ?>
                <?php esc_html_e('Posts Tagged ', 'patrios') ?><span class="raquo">/</span><?php single_tag_title(); echo('/'); ?>
                <?php } elseif (is_day()) { ?>
                <?php esc_html_e('Posts made in', 'patrios') ?> <?php the_time('F jS, Y'); ?>
                <?php } elseif (is_month()) { ?>
                <?php esc_html_e('Posts made in', 'patrios') ?> <?php the_time('F, Y'); ?>
                <?php } elseif (is_year()) { ?>
                <?php esc_html_e('Posts made in', 'patrios') ?> <?php the_time('Y'); ?>
                <?php } elseif (is_search()) { ?>
                <?php esc_html_e('Search results for', 'patrios') ?> <?php the_search_query() ?>
                <?php } elseif (is_single()) { ?>
                <?php $category = get_the_category();
                    if ( $category ) {
                        $catlink = get_category_link( $category[0]->cat_ID );
                        echo ('<a href="'.esc_url($catlink).'">'.esc_html($category[0]->cat_name).'</a> '.'<span class="raquo"></span> ');
                    } elseif (get_post_type() == 'product'){
                        echo get_the_title();
                    } ?>
                <?php } elseif (is_category()) { ?>
                <?php single_cat_title(); ?>
                <?php } elseif (is_tax()) { ?>
                <?php
                $themeum_taxonomy_links = array();
                $themeum_term = get_queried_object();
                $themeum_term_parent_id = $themeum_term->parent;
                $themeum_term_taxonomy = $themeum_term->taxonomy;
                while ( $themeum_term_parent_id ) {
                    $themeum_current_term = get_term( $themeum_term_parent_id, $themeum_term_taxonomy );
                    $themeum_taxonomy_links[] = '<a href="' . esc_url( get_term_link( $themeum_current_term, $themeum_term_taxonomy ) ) . '" title="' . esc_attr( $themeum_current_term->name ) . '">' . esc_html( $themeum_current_term->name ) . '</a>';
                    $themeum_term_parent_id = $themeum_current_term->parent;
                }
                if ( !empty( $themeum_taxonomy_links ) ) echo implode( ' <span class="raquo">/</span> ', array_reverse( $themeum_taxonomy_links ) ) . ' <span class="raquo">/</span> ';
                    echo esc_html( $themeum_term->name );
                } elseif (is_author()) {
                    global $wp_query;
                    $curauth = $wp_query->get_queried_object();
                    esc_html_e('Posts by ', 'patrios'); echo ' ',$curauth->nickname;
                } elseif (is_page()) {
                    echo get_the_title();
                } elseif (is_home()) {
                    esc_html_e('Blog', 'patrios');
                } ?>
            </li>
        </ol>
    <?php
    }
endif;

/*-----------------------------------------------------
 *              Coming Soon Page Settings
 *----------------------------------------------------*/
if ( get_theme_mod( 'comingsoon_en', false ) ) {
    if(!function_exists('patrios_my_page_template_redirect')):
        function patrios_my_page_template_redirect()
        {
            if( is_page() || is_home() || is_category() || is_single() )
            {
                if( !is_super_admin( get_current_user_id() ) ){
                    get_template_part( 'coming','soon');
                    exit();
                }
            }
        }
        add_action( 'template_redirect', 'patrios_my_page_template_redirect' );
    endif;

    if(!function_exists('patrios_cooming_soon_wp_title')):
        function patrios_cooming_soon_wp_title(){
            return 'Coming Soon';
        }
        add_filter( 'wp_title', 'patrios_cooming_soon_wp_title' );
    endif;
}



if(!function_exists('patrios_css_generator')){
    function patrios_css_generator(){

        $output = '';

        /* ***************************************
        **********      Theme Options   **********
        ****************************************** */



                $major_color = get_theme_mod( 'major_color', '#c9366f' );
                $hover_color = get_theme_mod( 'hover_color', '#2fc4a8' );


                 if($major_color){
                    $output .= 'a,.bottom-widget .contact-info i,.widget ul li a:hover,.widget-blog-posts-section .entry-title  a:hover,.entry-header h2.entry-title.blog-entry-title a:hover,.main-menu-wrap .navbar-toggle:hover,.woocommerce .star-rating span:before,.patrios-post .content-item-title a:hover, .woocommerce ul.products li.product .added_to_cart,.woocommerce ul.products li.product:hover .added_to_cart, .woocommerce div.product p.price, .woocommerce div.product span.price, .product_meta .sku_wrapper span.sku, .woocommerce-message::before, .woocommerce a.remove,.themeum-grid-post.single-post .thm-post-meta>div i, .patrios-review-cont i, 
                        
                        .wpneo-tabs-menu li.wpneo-current a,.tab-rewards-wrapper h3,
                        ul.wpneo-crowdfunding-update li .wpneo-crowdfunding-update-title,
                        .btn.btn-border-patrios,.entry-summary .wrap-btn-style a.btn-style,
                        .themeum-campaign-img .thm-camp-hvr .thm-ch-icon,.comingsoon .social-share li a:hover,.team-content-title a:hover,.themeum-tab-category .thm-cat-icon,.single-product-item span.thm-raise-sp, .view-campaign a,.themeum-campaign-author span.thm-raise-sp
                        { color: '. esc_attr($major_color) .'; }';

                        $output .= '.wpneo-wrapper ul.thm-single-category li a{ color: '. esc_attr($major_color) .'!important; }';
                }

                if($major_color){
                    $output .= '.woocommerce-tabs .wc-tabs>li.active:before, .team-content4, .classic-slider .owl-dots .active>span,
                    .project-navigator a.prev:hover,.project-navigator a.next:hover,.woocommerce #respond input#submit:hover,
                    .themeum-pagination .page-numbers li a:not(.prev):hover,.themeum-pagination .page-numbers li span.current,
                    .woocommerce nav.woocommerce-pagination ul li a:hover,.woocommerce nav.woocommerce-pagination ul li span.current,
                    .form-submit input[type=submit], .woocommerce div.product span.onsale, .woocommerce-tabs .nav-tabs>li.active>a, .woocommerce-tabs .nav-tabs>li.active>a:focus, .woocommerce-tabs .nav-tabs>li.active>a:hover, .woocommerce a.button:hover, .woocommerce span.onsale, .woocommerce .product-thumbnail-outer a.ajax_add_to_cart:hover, .woocommerce .woocommerce-info, .woocommerce a.added_to_cart,.wpneo-pagination ul li span.current,
                    .wpneo-pagination ul li a:hover,.wpneo-links li a:hover, .wpneo-links li.active a,
                    ul.wpneo-crowdfunding-update li:hover span.round-circle,.themeum-product-slider .slick-next:hover,.themeum-product-slider .slick-prev:hover,
                    .patrios_wooshop_widgets .ui-slider-range,
                    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,.woocommerce .widget_price_filter .ui-slider-horizontal,.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content,
                    .patrios_wooshop_widgets .widget .button,.thm-progress-bar .progress-bar,.order-view .label-info,.post-meta-info-list-in a:hover, button.patrios-remind-me:hover,.thm-btn.btn-bordered:hover,.thm-progress-bar .progress-bar,.themeum-campaign-post .themeum-campaign-img::after,.countdown-section,.mchimp-newsletter input[type=submit],#neo-progressbar>div,.themeum-campaign-time-exp .thm-progress-bar .progress .progress-bar,.wpneo-single-sidebar button.wpneo_donate_button, .thm-btn,.progressbar-content-wrapper .thm-progress-bar .progress .progress-bar, .themeum-campaign-time-exp .thm-progress-bar .progress .progress-bar, .product-slider-items .thm-progress-bar .progress .progress-bar, .quantity_box .qwpneo_donate_button,
                    
                    .widget .tagcloud a:hover, .mc4wp-form-fields button, .carousel-control-next i.fa.fa-angle-right, .carousel-control-prev i.fa.fa-angle-left,
                    .btn{ background: '. esc_attr($major_color) .'; }';
                }

                if($major_color){

                    $output .= '#wpneo-tab-reviews .submit{ background: '. esc_attr($major_color) .'!important; }';

                    $output .= 'a.wpneo-fund-modal-btn.wpneo-link-style1, .themeum-campaign-post .entry-title a:hover, .patrios-post .content-item-title a:hover, .modal .modal-content .modal-body input[type="submit"]:hover, .woocommerce form.woocommerce-ResetPassword.lost_reset_password .form-row input[type="submit"]:hover, .comments-area .comment-form input[type="submit"]:hover { color: '. esc_attr($major_color) .'!important; }';

                    $output .= 'input:focus, textarea:focus, keygen:focus, select:focus,.classic-slider.layout2 .classic-slider-btn:hover,
                    .classic-slider.layout3 .classic-slider-btn:hover,.classic-slider.layout4 .classic-slider-btn:hover,.portfolio-slider .portfolio-slider-btn:hover,.info-wrapper a.white, .woocommerce form.woocommerce-ResetPassword.lost_reset_password .form-row input[type="submit"], .comments-area .comment-form input[type="submit"]{ border-color: '. esc_attr($major_color) .'; }';

                    $output .= '.wpneo-tabs-menu li.wpneo-current { border-bottom: 2px solid '. esc_attr($major_color) .'; }';


                    $output .= '.wpcf7-submit,.project-navigator a.prev,.project-navigator a.next,
                        .woocommerce nav.woocommerce-pagination ul li a:hover,.woocommerce nav.woocommerce-pagination ul li span.current,.portfolio-slider .portfolio-slider-btn,.wpcf7-form input:focus,
                        .btn.btn-border-patrios,
                        .btn.btn-border-white:hover{ border: 2px solid '. esc_attr($major_color) .'; }

                        .wpcf7-submit:hover, .classic-slider.layout2 .classic-slider-btn:hover,.classic-slider.layout3 .classic-slider-btn:hover,.classic-slider.layout4 .classic-slider-btn:hover,.classic-slider.layout4 .container >div,.portfolio-slider .portfolio-slider-btn:hover,
                    .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,.mc4wp-form-fields .send-arrow button, .themeum-woo-product-details .addtocart-btn .add_to_cart_button:hover, .themeum-woo-product-details .addtocart-btn .added_to_cart:hover,.post-meta-info-list-in a:hover,
                    .patrios-login-register a.patrios-dashboard, .patrios-login-register a.patrios-dashboard:hover,
                     .wpneo-single-sidebar button.wpneo_donate_button,.thm-btn,.btn, .modal .modal-content .modal-body input[type="submit"],


                     .wpneo_donate_button, #wpneo-tab-reviews .submit, .wpneo-edit-btn, .wpneo-image-upload-btn, .wpneo-save-btn, #wpneo_active_edit_form, .removeCampaignRewards, #addreward, .btn-style1, #addcampaignupdate, .wpneo-profile-button, .dashboard-btn-link, .wpneo_login_form_div #wp-submit, .wpneo-submit-campaign, #addreward, input[type="submit"].wpneo-submit-campaign, .dashboard-btn-link, .label-primary, .btn-style1, #wpneo-tab-reviews .submit, .dashboard-head-date input[type="submit"], .wp-crowd-btn-primary, .wpneo_withdraw_button, .wpneo-dashboard-head-left ul li.active, .wpneo-pagination ul li a:hover, .wpneo-pagination ul li span.current
                     {   background-color: '. esc_attr($major_color) .'; border-color: '. esc_attr($major_color) .'; }';
                }

                if($major_color){
                    $output .= '.carousel-woocommerce .owl-nav .owl-next:hover,.carousel-woocommerce .owl-nav .owl-prev:hover,.themeum-latest-post-content .entry-title a:hover,.common-menu-wrap .nav>li.current>a,
                    .header-solid .common-menu-wrap .nav>li.current>a,.portfolio-filter a:hover,.portfolio-filter a.active,.latest-review-single-layout2 .latest-post-title a:hover, .blog-arrows a:hover, .mchimp-newsletter input[type=submit],.common-menu-wrap .nav>li:hover>ul{ border-color: '. esc_attr($major_color) .';  }';
                }

                if($major_color){
                    $output .= '.woocommerce-MyAccount-navigation ul li.is-active, 
                    .woocommerce-MyAccount-navigation ul li:hover, 
                    .carousel-woocommerce .owl-nav .owl-next:hover, 
                    .carousel-woocommerce .owl-nav .owl-prev:hover, 
                    .portfolio-thumb-wrapper-layout4 .portfolio-thumb:before, 
                    .btn.btn-slider:hover, 
                    .mc4wp-form-fields button:hover, .preloader_dots span.dot,
                    .btn.btn-slider:focus{ background: '. esc_attr($major_color) .'; border-color: '. esc_attr($major_color) .'; }';
                }

                if($hover_color){
                    $output .= '.wpneo-single-sidebar .wpneo_donate_button:hover{
                        background: '.esc_attr($hover_color).';
                    }';
                }
                if($hover_color){
                    $output .= '.social-share-wrap ul li a:hover,.wpneo-tabs-menu li a:hover,
                                .footer-wrap .social-share li a:hover,
                                .bottom-widget .widget ul li a:hover,
                                .latest-blog-content .latest-post-button:hover,
                                .entry-summary .wrap-btn-style a.btn-style:hover,
                                .topbar .social-share ul >li a:hover,
                                .patrios-post .blog-post-meta li a:hover,
                                .themeum-campaign-post .entry-category a:hover,
                                .woocommerce ul.products li.product:hover .button.add_to_cart_button,
                                .themeum-campaign-post .entry-author a:hover,.themeum-campaign-post h3 a:hover,
                                .themeum-woo-product-details .product-content h4 a:hover,
                                .wpneo-campaign-creator-details p:first-child a:hover,
                                #mobile-menu ul li.active>a,#mobile-menu ul li a:hover,
                                .wpneo-listings-dashboard .wpneo-listing-content h4 a:hover,
                                .wpneo-listings-dashboard .wpneo-listing-content .wpneo-author a:hover,
                                .product-timeline ul li,.themeum-campaign-post h3 a:hover,.thm-btn.btn-bordered,.thm-btn:hover,
                                .btn.btn-patrios:hover,
                                input[type=submit]:hover,
                                input[type="button"].wpneo-image-upload:hover,
                                input[type="button"].wpneo-image-upload-btn:hover,
                                input[type="button"]#addreward:hover,
                                .wpneo-edit-btn:hover,
                                .wpneo-image-upload.float-right:hover,
                                .wpneo-save-btn:hover,
                                .dashboard-btn-link:hover,
                                #wpneo_active_edit_form:hover,
                                #addcampaignupdate:hover,
                                .wpneo_donate_button:hover,
                                .wpneo-profile-button:hover,
                                .select_rewards_button:hover,
                                .woocommerce-page #payment #place_order:hover,
                                .woocommerce input.button:hover,
                                input[type="submit"].wpneo-submit-campaign:hover,
                                .themeum-tab-inner .themeum-campaign-post-content h3 a:hover.themeum-pagination .page-numbers>li:first-child a.prev:hover,
                                .themeum-pagination .page-numbers>li:last-child a.next:hover,
                                .page-numbers li.p-2.first span:hover,
                                .themeum-tab-inner .themeum-campaign-post-content h3 a:hover,
                                .themeum-campaign-exp-content h3 a:hover, .themeum-campaign-post h3 a:hover,.thm-explore ul li a:hover, .themeum-campaign-post a.thm-love-btn.active, .blog-post-item-col .content-item-title a:hover, .menu-footer-menu li a:hover, .common-menu-wrap .nav>li>ul li a:hover, .widget ul li a:hover, .patrios-bio-social .jssocials-shares a:hover{
                        color: '.esc_attr($hover_color).';
                    }';
                }

                $scolor = get_post_meta(get_the_ID(), 'themeum_subtitle_color', true);
                $output .= '.subtitle-cover::before { background: '. $scolor .' }';

               

            if( get_theme_mod( 'custom_preset_en', true ) ) {
                $hover_color = get_theme_mod( 'hover_color', '#00bca0' );
                if( $hover_color ){
                    $output .= 'a:hover, .widget.widget_rss ul li a,.main-menu-wrap .navbar-toggle:hover,.footer-copyright a:hover,.entry-summary .wrap-btn-style a.btn-style:hover{ color: '.esc_attr( $hover_color ) .'; }';
                    $output .= '.error-page-inner a.btn.btn-primary.btn-lg:hover,.btn.btn-primary:hover,input[type=button]:hover,
                    .widget.widget_search #searchform .btn-search:hover,.team-content2,
                    .woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.order-view .label-info:hover, .info-wrapper .btn.btn-patrios:hover{ background-color: '.esc_attr( $hover_color ) .'; }';

                    $output .= '.woocommerce a.button:hover, .info-wrapper .btn.btn-patrios:hover{ border-color: '.esc_attr( $hover_color ) .'; }';
                }

            }



        /* *******************************
        **********  Quick Style **********
        ********************************** */

        $bstyle = $mstyle = $h1style = $h2style = $h3style = $h4style = $h5style = '';
        //body
        if ( get_theme_mod( 'body_font_size', '14' ) ) {
            $bstyle .= 'font-size:'.get_theme_mod( 'body_font_size', '14' ).'px;';
        }
        if ( get_theme_mod( 'body_google_font', 'Montserrat' ) ) {
            $bstyle .= 'font-family:'.get_theme_mod( 'body_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'body_font_weight', '400' ) ) {
            $bstyle .= 'font-weight: '.get_theme_mod( 'body_font_weight', '400' ).';';
        }
        if ( get_theme_mod('body_font_height', '24') ) {
            $bstyle .= 'line-height: '.get_theme_mod('body_font_height', '24').'px;';
        }
        if ( get_theme_mod('body_font_color', '#000') ) {
            $bstyle .= 'color: '.get_theme_mod('body_font_color', '#000').';';
        }

        //menu
        $mstyle = '';
        if ( get_theme_mod( 'menu_font_size', '14' ) ) {
            $mstyle .= 'font-size:'.get_theme_mod( 'menu_font_size', '14' ).'px;';
        }
        if ( get_theme_mod( 'menu_google_font', 'Montserrat' ) ) {
            $mstyle .= 'font-family:'.get_theme_mod( 'menu_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'menu_font_weight', '400' ) ) {
            $mstyle .= 'font-weight: '.get_theme_mod( 'menu_font_weight', '500' ).';';
        }
        if ( get_theme_mod('menu_font_height', '30') ) {
            $mstyle .= 'line-height: '.get_theme_mod('menu_font_height', '30').'px;';
        }
        if ( get_theme_mod('menu_font_color', '#000') ) {
            $mstyle .= 'color: '.get_theme_mod('menu_font_color', '#000').';';
        }

        //heading1
        $h1style = '';
        if ( get_theme_mod( 'h1_font_size', '42' ) ) {
            $h1style .= 'font-size:'.get_theme_mod( 'h1_font_size', '42' ).'px;';
        }
        if ( get_theme_mod( 'h1_google_font', 'Montserrat' ) ) {
            $h1style .= 'font-family:'.get_theme_mod( 'h1_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'h1_font_weight', '600' ) ) {
            $h1style .= 'font-weight: '.get_theme_mod( 'h1_font_weight', '700' ).';';
        }
        if ( get_theme_mod('h1_font_height', '42') ) {
            $h1style .= 'line-height: '.get_theme_mod('h1_font_height', '42').'px;';
        }
        if ( get_theme_mod('h1_font_color', '#333') ) {
            $h1style .= 'color: '.get_theme_mod('h1_font_color', '#333').';';
        }

        # heading2
        $h2style = '';
        if ( get_theme_mod( 'h2_font_size', '36' ) ) {
            $h2style .= 'font-size:'.get_theme_mod( 'h2_font_size', '36' ).'px;';
        }
        if ( get_theme_mod( 'h2_google_font', 'Montserrat' ) ) {
            $h2style .= 'font-family:'.get_theme_mod( 'h2_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'h2_font_weight', '700' ) ) {
            $h2style .= 'font-weight: '.get_theme_mod( 'h2_font_weight', '600' ).';';
        }
        if ( get_theme_mod('h2_font_height', '42') ) {
            $h2style .= 'line-height: '.get_theme_mod('h2_font_height', '42').'px;';
        }
        if ( get_theme_mod('h2_font_color', '#333') ) {
            $h2style .= 'color: '.get_theme_mod('h2_font_color', '#333').';';
        }

        //heading3
        $h3style = '';
        if ( get_theme_mod( 'h3_font_size', '26' ) ) {
            $h3style .= 'font-size:'.get_theme_mod( 'h3_font_size', '26' ).'px;';
        }
        if ( get_theme_mod( 'h3_google_font', 'Montserrat' ) ) {
            $h3style .= 'font-family:'.get_theme_mod( 'h3_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'h3_font_weight', '700' ) ) {
            $h3style .= 'font-weight: '.get_theme_mod( 'h3_font_weight', '600' ).';';
        }
        if ( get_theme_mod('h3_font_height', '28') ) {
            $h3style .= 'line-height: '.get_theme_mod('h3_font_height', '28').'px;';
        }
        if ( get_theme_mod('h3_font_color', '#333') ) {
            $h3style .= 'color: '.get_theme_mod('h3_font_color', '#333').';';
        }

        //heading4
        $h4style = '';
        if ( get_theme_mod( 'h4_font_size', '18' ) ) {
            $h4style .= 'font-size:'.get_theme_mod( 'h4_font_size', '18' ).'px;';
        }
        if ( get_theme_mod( 'h4_google_font', 'Montserrat' ) ) {
            $h4style .= 'font-family:'.get_theme_mod( 'h4_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'h4_font_weight', '700' ) ) {
            $h4style .= 'font-weight: '.get_theme_mod( 'h4_font_weight', '600' ).';';
        }
        if ( get_theme_mod('h4_font_height', '26') ) {
            $h4style .= 'line-height: '.get_theme_mod('h4_font_height', '26').'px;';
        }
        if ( get_theme_mod('h4_font_color', '#333') ) {
            $h4style .= 'color: '.get_theme_mod('h4_font_color', '#333').';';
        }

        //heading5
        $h5style = '';
        if ( get_theme_mod( 'h5_font_size', '16' ) ) {
            $h5style .= 'font-size:'.get_theme_mod( 'h5_font_size', '16' ).'px;';
        }
        if ( get_theme_mod( 'h5_google_font', 'Montserrat' ) ) {
            $h5style .= 'font-family:'.get_theme_mod( 'h5_google_font', 'Montserrat' ).';';
        }
        if ( get_theme_mod( 'h5_font_weight', '700' ) ) {
            $h5style .= 'font-weight: '.get_theme_mod( 'h5_font_weight', '600' ).';';
        }
        if ( get_theme_mod('h5_font_height', '24') ) {
            $h5style .= 'line-height: '.get_theme_mod('h5_font_height', '24').'px;';
        }
        if ( get_theme_mod('h5_font_color', '#333') ) {
            $h5style .= 'color: '.get_theme_mod('h5_font_color', '#333').';';
        }

        $output .= 'body,.wpneo-wrapper{'.$bstyle.'}';
        $output .= '.common-menu-wrap .nav>li>a, .thm-explore ul li a, .thm-explore a, .common-menu-wrap .nav> li > ul li a, .common-menu-wrap .nav > li > ul li.mega-child > a{'.$mstyle.'}';
        $output .= 'h1{'.$h1style.'}';
        $output .= 'h2{'.$h2style.'}';
        $output .= 'h3{'.$h3style.'}';
        $output .= 'h4{'.$h4style.'}';
        $output .= 'h5{'.$h5style.'}';


        #Header
        if ( get_theme_mod( 'header_color', '#fff' ) ) {
            $output .= '.site-header{ background: '.esc_attr( get_theme_mod( 'header_color', '#fff' ) ) .'; }';
        }

        # Shop Sub Header OverLay Color
        $themeum_subtitle_color = get_post_meta( get_the_ID(), 'themeum_subtitle_color', true );
        if ( $themeum_subtitle_color )  {
            $output .= '.patrios-overlay-cont .subtitle-cover::before{ background: '. $themeum_subtitle_color .'; }';
        }

        # Category Color
        $terms = get_query_var('product_cat');
        $category = get_term_by( 'slug', $terms, 'product_cat' );
    
        if($category){    
            $subcolor    = 'product_cat_color_custom_order_' . $category->term_id;
            $output     .= '.wrappers-content .subtitle-cover::before{ background: '. get_option($subcolor) .'; }';
            $output .= '.themeum-campaign-post .entry-title a:hover,.themeum-campaign-post .entry-category a:hover{color: '. get_option($subcolor) .'!important;}';
            $output .= '.themeum-campaign-post a.thm-love-btn,.thm-grid-list a.thm-love-btn,.themeum-author-dsc a:hover,
            .themeum-campaign-exp-content h3 a:hover,.themeum-campaign-exp-content .entry-category a:hover{color: '. get_option($subcolor) .';}';
            $output .= '.progressbar-content-wrapper .thm-progress-bar .progress .progress-bar, 
            .product-slider-items .thm-progress-bar .progress .progress-bar{background: '. get_option($subcolor) .';}';
        }
        #end this process

        $output .= '.site-header{ padding-top: '. (int) esc_attr( get_theme_mod( 'header_padding_top', '10' ) ) .'px; }';

        $output .= '.site-header{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'header_padding_bottom', '10' ) ) .'px; }';

        //sticky Header
        if ( get_theme_mod( 'header_fixed', false ) ){
            $output .= '.site-header.sticky{ position:fixed;top:0;left:auto; z-index:99999;margin:0 auto; width:100%;-webkit-animation: fadeInDown 300ms;animation: fadeInDown 300ms;}';
            $output .= '.site-header.sticky.header-transparent .main-menu-wrap{ margin-top: 0;}';
            if ( get_theme_mod( 'sticky_header_color', '#fff' ) ){
                $sticybg = get_theme_mod( 'sticky_header_color', '#fff');
                $output .= '.site-header.sticky{ background-color: '.$sticybg.';}';
            }
            $output .= '@keyframes fadeInDown {
              from {
                opacity: 0;
                transform: translate3d(0, -50%, 0);
              }

              to {
                opacity: 1;
                transform: none;
              }
            }';
        }
        
        //logo
        if (get_theme_mod( 'logo_width' )) {
            $output .= '.themeum-navbar-header .themeum-navbar-brand img{width:'.get_theme_mod( 'logo_width', 150 ).'px; max-width:none;}';
        }

        if (get_theme_mod( 'logo_height' )) {
            $output .= '.themeum-navbar-header .themeum-navbar-brand img{height:'.get_theme_mod( 'logo_height' ).'px;}';
        }

        // sub header
        $output .= '.subtitle-cover h2{font-size:'.get_theme_mod( 'sub_header_title_size', '48' ).'px;color:'.get_theme_mod( 'sub_header_title_color', '#000' ).';}';

        $output .= '.breadcrumb>li+li:before, .subtitle-cover .breadcrumb, .subtitle-cover .breadcrumb>.active{color:'.get_theme_mod( 'breadcrumb_text_color', '#fff' ).';}';
        $output .= '.subtitle-cover .breadcrumb a{color:'.get_theme_mod( 'breadcrumb_link_color', '#fff' ).';}';
        $output .= '.subtitle-cover .breadcrumb a:hover{color:'.get_theme_mod( 'breadcrumb_link_color_hvr', '#fff' ).';}';

        $output .= '.subtitle-cover{background:'.get_theme_mod( 'sub_header_bg_color', '#f0f0f0' ).'; padding:'.get_theme_mod( 'sub_header_padding_top', '120' ).'px 0 '.get_theme_mod( 'sub_header_padding_bottom', '65' ).'px; margin-bottom: '.get_theme_mod( 'sub_header_margin_bottom', '88' ).'px;}';

        //body
        if (get_theme_mod( 'body_bg_img')) {
            $output .= 'body{ background-image: url("'.esc_attr( get_theme_mod( 'body_bg_img' ) ) .'");background-size: '.esc_attr( get_theme_mod( 'body_bg_size', 'cover' ) ) .';    background-position: '.esc_attr( get_theme_mod( 'body_bg_position', 'left top' ) ) .';background-repeat: '.esc_attr( get_theme_mod( 'body_bg_repeat', 'no-repeat' ) ) .';background-attachment: '.esc_attr( get_theme_mod( 'body_bg_attachment', 'fixed' ) ) .'; }';
        }
        $output .= 'body{ background-color: '.esc_attr( get_theme_mod( 'body_bg_color', '#fff' ) ) .'; }';


        // Button color setting...
        $output .= '.btn.btn-patrios,input[type=submit],,
                    input[type="button"].wpneo-image-upload-btn,input[type="button"]#addreward,.wpneo-edit-btn,
                    .wpneo-save-btn,.wpneo-cancel-btn,
                    .dashboard-btn-link,#wpneo_active_edit_form,#addcampaignupdate,
                    .wpneo_donate_button,.wpneo-profile-button,.select_rewards_button,
                    .woocommerce-page #payment #place_order,.btn.btn-white:hover,
                    .btn.btn-border-patrios:hover,.btn.btn-border-white:hover,.woocommerce input.button,
                    input[type="submit"].wpneo-submit-campaign{ background-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#c9366f' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'button_bg_color', '#c9366f' ) ) .'; color: '.esc_attr( get_theme_mod( 'button_text_color', '#fff' ) ) .' !important; }';

        if ( get_theme_mod( 'button_hover_bg_color', '#00bca0' ) ) {
            $output .= '.btn.btn-patrios:hover,input[type=submit]:hover,input[type="button"].wpneo-image-upload-btn:hover,
                input[type="button"]#addreward:hover,.wpneo-edit-btn:hover,
                .wpneo-save-btn:hover,
                .dashboard-btn-link:hover,#wpneo_active_edit_form:hover,#addcampaignupdate:hover,
                .wpneo_donate_button:hover,.wpneo-profile-button:hover,.select_rewards_button:hover,
            .woocommerce-page #payment #place_order:hover,
            input[type="submit"].wpneo-submit-campaign:hover{ background-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#ff5997' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'button_hover_bg_color', '#ff5997' ) ) .'; color: '.esc_attr( get_theme_mod( 'button_hover_text_color', '#fff' ) ) .' !important; }';


        }

        //menu color
        if ( get_theme_mod( 'navbar_text_color', '#000' ) ) {
            $output .= '.header-solid .common-menu-wrap .nav>li.menu-item-has-children:after, .header-borderimage .common-menu-wrap .nav>li.menu-item-has-children:after, .header-solid .common-menu-wrap .nav>li>a, .header-borderimage .common-menu-wrap .nav>li>a,
            .header-solid .common-menu-wrap .nav>li>a:after, .header-borderimage .common-menu-wrap .nav>li>a:after,.patrios-search{ color: '.esc_attr( get_theme_mod( 'navbar_text_color', '#000' ) ) .'; }';
        }

        $output .= '.header-solid .common-menu-wrap .nav>li>a:hover, .header-borderimage .common-menu-wrap .nav>li>a:hover,.header-solid .common-menu-wrap .nav>li>a:hover:after, .header-borderimage .common-menu-wrap .nav>li>a:hover:after,
        .patrios-search-wrap a.patrios-search:hover{ color: '.esc_attr( get_theme_mod( 'navbar_hover_text_color', '#c9366f' ) ) .'; }';

        $output .= '.header-solid .common-menu-wrap .nav>li.active>a, .header-borderimage .common-menu-wrap .nav>li.active>a{ color: '.esc_attr( get_theme_mod( 'navbar_active_text_color', '#c9366f' ) ) .'; }';

        //submenu color
        $output .= '.common-menu-wrap .nav>li ul{ background-color: '.esc_attr( get_theme_mod( 'sub_menu_bg', '#fff' ) ) .'; }';

        $output .= '.common-menu-wrap .nav>li>ul li a,.common-menu-wrap .nav > li > ul li.mega-child > a{ color: '.esc_attr( get_theme_mod( 'sub_menu_text_color', '#000' ) ) .'; border-color: '.esc_attr( get_theme_mod( 'sub_menu_border', '#eef0f2' ) ) .'; }';

        $output .= '
        .common-menu-wrap .sub-menu li.active a,.common-menu-wrap .sub-menu li.active.mega-child .active a,
        .common-menu-wrap .sub-menu.megamenu > li.active.mega-child > a,.common-menu-wrap .nav > li > ul li.mega-child > a:hover,.common-menu-wrap .nav>li.current-menu-parent.menu-item-has-children > a:after,.common-menu-wrap .nav>li.current-menu-item.menu-item-has-children > a:after { color: '.esc_attr( get_theme_mod( 'sub_menu_text_color_hover', '#c9366f' ) ) .';}';
        $output .= '.common-menu-wrap .nav>li > ul::after{ border-color: transparent transparent '.esc_attr( get_theme_mod( 'sub_menu_bg', '#262626' ) ) .' transparent; }';

        //bottom
        $output .= '.bottom{ background-color: '.esc_attr( get_theme_mod( 'bottom_color', '#202020' ) ) .'; }';

        $output .= '.bottom .widget ul li a{ color: '.esc_attr( get_theme_mod( 'bottom_text_color', '#dedede' ) ) .'; }';

        $output .= '.bottom-wrap{ padding-top: '. (int) esc_attr( get_theme_mod( 'bottom_padding_top', '90' ) ) .'px; }';

        $output .= '.bottom-wrap{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'bottom_padding_bottom', '90' ) ) .'px; }';

        //footer
        $output .= '#footer{ background-color: '.esc_attr( get_theme_mod( 'copyright_bg_color', '#1c1c1c' ) ) .'; }';

        $output .= '#footer{ padding-top: '. (int) esc_attr( get_theme_mod( 'copyright_padding_top', '30' ) ) .'px; }';
        $output .= '#footer{ padding-bottom: '. (int) esc_attr( get_theme_mod( 'copyright_padding_bottom', '30' ) ) .'px; }';
        $output .= '.footer-copyright,.footer-copyright a{ color: '.esc_attr( get_theme_mod( 'copyright_text_color', '#797979' ) ) .'; }';



        $output .= "body.error404,body.page-template-404{
            width: 100%;
            height: 100%;
            min-height: 100%;}";

        return $output;
    }
}
