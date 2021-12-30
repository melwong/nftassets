<?php
/*-------------------------------------------*
 *      Themeum Widget Registration
 *------------------------------------------*/

if(!function_exists('patrios_widdget_init')):

    function patrios_widdget_init(){
        $bottomcolumn = get_theme_mod( 'bottom_column', '3' );

        register_sidebar(
            array(
                'name'          => esc_html__( 'Sidebar', 'patrios' ),
                'id'            => 'sidebar',
                'description'   => esc_html__( 'Widgets in this area will be shown on Sidebar.', 'patrios' ),
                'before_title'  => '<h3 class="widget_title">',
                'after_title'   => '</h3>',
                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                'after_widget'  => '</div>'
            )
        );

        global $woocommerce;
        if($woocommerce) {
            register_sidebar(array(
                'name'          => __( 'Shop', 'patrios' ),
                'id'            => 'shop',
                'description'   => __( 'Widgets in this area will be shown on Shop Sidebar.', 'patrios' ),
                'before_title'  => '<h3 class="widget_title">',
                'after_title'   => '</h3>',
                'before_widget' => '<div id="%1$s" class="widget %2$s" >',
                'after_widget'  => '</div>'
                )
            );
        }         

        register_sidebar(array(
            'name'          => esc_html__( 'Bottom 1', 'patrios' ),
            'id'            => 'bottom1',
            'description'   => esc_html__( 'Widgets in this area will be shown before Bottom 1.' , 'patrios'),
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div class="bottom-widget"><div id="%1$s" class="widget %2$s" >',
            'after_widget'  => '</div></div>'
            )
        );

        register_sidebar(array(
            'name'          => esc_html__( 'Bottom 2', 'patrios' ),
            'id'            => 'bottom2',
            'description'   => esc_html__( 'Widgets in this area will be shown before Bottom 2.' , 'patrios'),
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div class="bottom-widget"><div id="%1$s" class="widget %2$s" >',
            'after_widget'  => '</div></div>'
            )
        );

        register_sidebar(array(
            'name'          => esc_html__( 'Bottom 3', 'patrios' ),
            'id'            => 'bottom3',
            'description'   => esc_html__( 'Widgets in this area will be shown before Bottom 3.' , 'patrios'),
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div class="bottom-widget"><div id="%1$s" class="widget %2$s" >',
            'after_widget'  => '</div></div>'
            )
        );

        register_sidebar(array(
            'name'          => esc_html__( 'Bottom 4', 'patrios' ),
            'id'            => 'bottom4',
            'description'   => esc_html__( 'Widgets in this area will be shown before Bottom 4.' , 'patrios'),
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div class="bottom-widget"><div id="%1$s" class="widget %2$s" >',
            'after_widget'  => '</div></div>'
            )
        );

        register_sidebar(array(
            'name'          => esc_html__( 'Bottom 5', 'patrios' ),
            'id'            => 'bottom5',
            'description'   => esc_html__( 'Widgets in this area will be shown before Bottom 5.' , 'patrios'),
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            'before_widget' => '<div class="bottom-widget"><div id="%1$s" class="widget %2$s" >',
            'after_widget'  => '</div></div>'
            )
        );




    }

    add_action('widgets_init','patrios_widdget_init');

endif;

if ( ! function_exists( 'patrios_fonts_url' ) ) :
    function patrios_fonts_url() {
    $fonts_url = '';

    $montserrat = _x( 'on', 'Montserrat font: on or off', 'patrios' );

    if ( 'off' !== $montserrat ) {
    $font_families = array();

    if ( 'off' !== $montserrat ) {
    $font_families[] = 'Montserrat:100,200,300,400,500,600,700';
    }

    $query_args = array(
    'family'  => urlencode( implode( '|', $font_families ) ),
    'subset'  => urlencode( 'latin,latin-ext' ),
    );

    $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    return esc_url_raw( $fonts_url );
    }
endif;


/*-------------------------------------------*
 *      Themeum Style
 *------------------------------------------*/
if(!function_exists('patrios_style')):

    function patrios_style(){

        wp_enqueue_style( 'default-google-font', '//fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700' );

        wp_enqueue_style( 'patrios-font', patrios_fonts_url(), array(), null );

        wp_enqueue_media();
        wp_enqueue_style( 'bootstrap', PATRIOS_CSS . 'bootstrap.min.css',false,'all');
        if ( is_rtl() ) {
            wp_enqueue_style( 'bootstrap-rtl', PATRIOS_CSS . 'bootstrap-rtl.min.css',false,'all');
        }
        wp_enqueue_style( 'font-awesome', PATRIOS_CSS . 'font-awesome.min.css',false,'all');
        wp_enqueue_style( 'patrios-font-style', PATRIOS_CSS . 'patrios-font-style.css',false,'all');
        wp_enqueue_style( 'patrios-font', PATRIOS_CSS . 'patrios-font.css',false,'all');
        wp_enqueue_style( 'magnific-popup', PATRIOS_CSS . 'magnific-popup.css',false,'all');
        wp_enqueue_style( 'patrios-main', PATRIOS_CSS . 'main.css',false,'all');
        wp_enqueue_style( 'patrios-woocommerce', PATRIOS_CSS . 'woocommerce.css',false,'all');
        wp_enqueue_style( 'js-social', PATRIOS_CSS . 'jssocials.css',false,'all');
        wp_enqueue_style( 'patrios-custom.css', PATRIOS_CSS . 'custom.css',false,'all');
        wp_enqueue_style( 'slick-css', PATRIOS_CSS . 'slick.min.css',false,'all');
        wp_enqueue_style( 'patrios-responsive', PATRIOS_CSS . 'responsive.css',false,'all');
         
        wp_enqueue_style( 'patrios-style',get_stylesheet_uri());
        wp_add_inline_style( 'patrios-style', PATRIOS_css_generator() );

        wp_enqueue_script('popper',PATRIOS_JS.'popper.min.js',array(),false,true);
        wp_enqueue_script('bootstrap',PATRIOS_JS.'bootstrap.min.js',array(),false,true);
        wp_enqueue_script('loopcounter',PATRIOS_JS.'loopcounter.js',array(),false,true);
        wp_enqueue_script('js-social',PATRIOS_JS.'jssocials.min.js',array(),false,true);
        wp_enqueue_script('slick-js',PATRIOS_JS.'slick.min.js',array(),false,true);
        if( get_theme_mod( "google_map_api" ) ){ wp_enqueue_script('gmaps','https://maps.googleapis.com/maps/api/js?key='.get_theme_mod( "google_map_api" ),array(),false,true); }
        wp_enqueue_script('jquery.magnific-popup.min',PATRIOS_JS.'jquery.magnific-popup.min.js',array(),false,true);
        
        if( get_theme_mod( 'custom_preset_en', true ) == 0 ) {
            wp_enqueue_style( 'themeum-preset', get_parent_theme_file_uri(). '/css/presets/preset' . get_theme_mod( 'preset', '1' ) . '.css', array(),false,'all' );
        }
        if ( is_singular() ) {wp_enqueue_script( 'comment-reply' );}
        wp_enqueue_script('patrios-main',PATRIOS_JS.'main.js',array(),false,true);

        // For Ajax URL
        wp_enqueue_script('patrios-main');
        wp_localize_script( 'patrios-main', 'ajax_objects', array( 
            'ajaxurl'           => admin_url( 'admin-ajax.php' ),
            'redirecturl'       => home_url('/'),
            'loadingmessage'    => __('Sending user info, please wait...','patrios')
        ));

    }

    add_action('wp_enqueue_scripts','patrios_style');

endif;

if(!function_exists('patrios_admin_style')):

    function patrios_admin_style(){
        wp_enqueue_media();
        wp_register_script('thmpostmeta', get_parent_theme_file_uri() .'/js/admin/post-meta.js');
        wp_enqueue_script('themeum-widget-js', get_parent_theme_file_uri().'/js/widget-js.js', array('jquery'));
        wp_enqueue_script('thmpostmeta');

        if( is_admin() ) {    
            wp_enqueue_style( 'wp-color-picker' ); 
            wp_enqueue_script('patrios-colorpicker', get_parent_theme_file_uri().'/js/admin-colorpicker.js',  array( 'wp-color-picker' ),false,true);
        }

    }
    add_action('admin_enqueue_scripts','patrios_admin_style');

endif;


/*-------------------------------------------------------
*           Include the TGM Plugin Activation class
*-------------------------------------------------------*/
add_action( 'tgmpa_register', 'patrios_plugins_include');

if(!function_exists('patrios_plugins_include')):

    function patrios_plugins_include()
    {
        $plugins = array(
                array(
                    'name'                  => esc_html__( 'Patrios Core', 'patrios' ),
                    'slug'                  => 'patrios-core',
                    'source'                => esc_url('http://demo.themeum.com/wordpress/plugins/patrios/patrios-core.zip'),
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ), 
                array(
                    'name'                  => 'Woocoomerce',
                    'slug'                  => 'woocommerce',
                    'required'              => true, 
                    'version'               => '', 
                    'force_activation'      => false,
                    'force_deactivation'    => false, 
                    'external_url'          => 'https://downloads.wordpress.org/plugin/woocommerce.3.0.8.zip', 
                ),
                array(
                    'name'                  => esc_html__( 'WP Pagebuilder', 'patrios' ),
                    'slug'                  => 'wp-pagebuilder',
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/wp-pagebuilder.zip'),
                ), 
                array(
                    'name'                  => esc_html__( 'WP Page Builder Pro', 'patrios' ),
                    'slug'                  => 'wp-pagebuilder-pro',
                    'source'                => esc_url( get_template_directory_uri().'/lib/packages/wp-pagebuilder-pro.zip'),
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),
                array(
                    'name'                  => esc_html__( 'WP Crowdfunding', 'patrios' ),
                    'slug'                  => 'wp-crowdfunding',
                    'source'                => esc_url( get_template_directory_uri().'/lib/packages/wp-croudfunding.zip'),
                    'required'              => true,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),
                array(
                    'name'                  => 'Patrios Demo Importer',
                    'slug'                  => 'patrios-demo-importer',
                    'source'                => esc_url('http://demo.themeum.com/wordpress/plugins/patrios/patrios-demo-importer.zip'),
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => '',
                ),
                array(
                    'name'                  => esc_html__( 'WP Mega Menu', 'patrios' ),
                    'slug'                  => 'wp-megamenu',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/wp-megamenu.zip'),
                ),
                array(
                    'name'                  => esc_html__( 'MailChimp for WordPress', 'patrios' ),
                    'slug'                  => 'mailchimp-for-wp',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/mailchimp-for-wp.4.1.3.zip'),
                ),
                array(
                    'name'                  => esc_html__( 'Widget Importer & Exporter', 'patrios' ),
                    'slug'                  => 'widget-importer-exporter',
                    'required'              => false,
                    'version'               => '',
                    'force_activation'      => false,
                    'force_deactivation'    => false,
                    'external_url'          => esc_url('https://downloads.wordpress.org/plugin/widget-importer-exporter.1.4.5.zip'),
                ),
               

            );

        $config = array(
            'domain'            => 'patrios',
            'default_path'      => '',
            'parent_menu_slug'  => 'themes.php',
            'parent_url_slug'   => 'themes.php',
            'menu'              => 'install-required-plugins',
            'has_notices'       => true,
            'is_automatic'      => false,
            'message'           => '',
            'strings'           => array(
                        'page_title'                                => esc_html__( 'Install Required Plugins', 'patrios' ),
                        'menu_title'                                => esc_html__( 'Install Plugins', 'patrios' ),
                        'installing'                                => esc_html__( 'Installing Plugin: %s', 'patrios' ),
                        'oops'                                      => esc_html__( 'Something went wrong with the plugin API.', 'patrios'),
                        'return'                                    => esc_html__( 'Return to Required Plugins Installer', 'patrios'),
                        'plugin_activated'                          => esc_html__( 'Plugin activated successfully.','patrios'),
                        'complete'                                  => esc_html__( 'All plugins installed and activated successfully. %s', 'patrios' )
                )
    );

    tgmpa( $plugins, $config );

    }

endif;
