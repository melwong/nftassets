<?php
/**
 * @package myticket
 * @basic setup
 */


    /**
     * Add custom metaboxes for post and default pages
     */
    function add_myticket_metaboxes() {
        
        /**
         * Initiate the metabox
         */
        $cmb_page = new_cmb2_box( array(
                                'id'            => 'page_metabox',
                                'title'         => esc_attr( 'Settings', 'myticket' ),
                                'object_types'  => array( 'page', ), // Post type
                                'context'       => 'normal',
                                'priority'      => 'high',
                                'show_names'    => true, // Show field names on the left
                                ) );
        
        // Enable Title
        $cmb_page->add_field( array(
                                'name' => esc_attr( 'Disable Title', 'myticket' ),
                                'desc' => esc_attr( 'disable default page title in header', 'myticket' ),
                                'id'   => '_title',
                                'type' => 'checkbox',
                                ) );
        
        // Enable Title
        $cmb_page->add_field( array(
                                'name' => esc_attr( 'Wide Content', 'myticket' ),
                                'desc' => esc_attr( 'Expand page content to 100%', 'myticket' ),
                                'id'   => '_narrow_content',
                                'type' => 'checkbox',
                                ) );

        // Transparent Header
        $cmb_page->add_field( array(
                                'name' => esc_attr( 'Transparent Header', 'myticket' ),
                                'desc' => esc_attr( 'Make navigation header transparent for this page', 'myticket' ),
                                'id'   => '_transparent_header',
                                'type' => 'checkbox',
                                ) );

        // Transparent Header
        $cmb_page->add_field( array(
                                'name' => esc_attr( 'Transparent Tob Bar', 'myticket' ),
                                'desc' => esc_attr( 'Make top navigation bar transparent for this page', 'myticket' ),
                                'id'   => '_transparent_top_header',
                                'type' => 'checkbox',
                                ) );

        // Dark Navigation menu
        $cmb_page->add_field( array(
                                'name' => esc_attr( 'Dark Navigation Menu', 'myticket' ),
                                'desc' => esc_attr( 'If you chose transparent header style in combination with light background use this option to reverse the colors of menu', 'myticket' ),
                                'id'   => '_dark_header',
                                'type' => 'checkbox',
                                ) );
        
    }
    add_action( 'cmb2_init', 'add_myticket_metaboxes' );

    function myticket_products_num( $cols ) {
      // $cols contains the current number of products per page based on the value stored on Options -> Reading
      // Return the number of products you wanna show per page.
      $cols = 8;
      return $cols;
    }
    add_filter( 'loop_shop_per_page', 'myticket_products_num', 20 );

    function myticket_widgets_collection($folders){
        $folders[] = get_template_directory() . 'inc/shortcodes';
        return $folders;
    }
    add_filter('siteorigin_widgets_widget_folders', 'myticket_widgets_collection');


    function myticket_add_widget_tabs($tabs) {
        $tabs[] = array(
            'title' => esc_attr__('MyTicket Widgets', 'myticket'),
            'filter' => array(
                'groups' => array('myticket')
            )
        );

        return $tabs;
    }
    add_filter('siteorigin_panels_widget_dialog_tabs', 'myticket_add_widget_tabs', 20);


    function ocdi_import_files() {
        return array(
            array(
                'import_file_name'           => 'Demo1',
                'import_file_url'            => 'http://themesapi.kenzap.com/demo/myticket.wordpress.xml',
                // 'import_widget_file_url'     => 'http://www.your_domain.com/ocdi/widgets.json',
                'import_customizer_file_url' => 'http://themesapi.kenzap.com/demo/myticket.export.dat',
                //'import_preview_image_url'   => 'http://www.your_domain.com/ocdi/preview_import_image1.jpg',
                'import_notice'              => __( 'If you experience server error 500 during import process please read the following <a target="_blank" href="https://github.com/proteusthemes/one-click-demo-import/blob/master/docs/import-problems.md#user-content-server-error-500" >article</a>. You may also install this theme on <a target="blank" href="http://kenzap.com/signin/?project=myticket" >Kenzap cloud</a> for free or request paid installation/assistance with your hosting environment.', 'myticket' ),
            ),
        );
    }
    add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );


    function ocdi_after_import_setup() {
        // Assign menus to their locations.

        $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
        $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );

        set_theme_mod( 'nav_menu_locations', array(
                'primary' => $main_menu->term_id,
                'primary_mobile' => $main_menu->term_id,
                'footer' => $footer_menu->term_id,
            )
        );

        set_theme_mod( 'myticket_cart', 1 );
        set_theme_mod( 'sidebar_location', 'left' );
         
        // Assign front page and posts page (blog page).
        $front_page_id = get_page_by_title( 'Home' );
        $blog_page_id  = get_page_by_title( 'Blog' );

        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page_id->ID );
        update_option( 'page_for_posts', $blog_page_id->ID );
        update_option( 'siteorigin_panels_settings', 'a:19:{s:10:"post-types";a:2:{i:0;s:4:"page";i:1;s:4:"post";}s:22:"live-editor-quick-link";b:1;s:15:"parallax-motion";s:0:"";s:17:"sidebars-emulator";b:1;s:14:"display-teaser";b:1;s:13:"display-learn";b:1;s:10:"title-html";s:39:"<h3 class="widget-title">{{title}}</h3>";s:16:"add-widget-class";b:0;s:15:"bundled-widgets";b:0;s:19:"recommended-widgets";b:0;s:10:"responsive";b:1;s:13:"tablet-layout";b:0;s:12:"tablet-width";i:1024;s:12:"mobile-width";i:780;s:13:"margin-bottom";i:0;s:22:"margin-bottom-last-row";b:0;s:12:"margin-sides";i:30;s:20:"full-width-container";s:4:"body";s:12:"copy-content";b:1;}');

        myticket_reset_permalinks();
        //myticket_setup_invoice_directories();
    }
    add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );

    function myticket_reset_permalinks() {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure('/%postname%/');
        $wp_rewrite->flush_rules();
    }

    add_theme_support('woocommerce');

    //hide siteorigin widgets from admin widgets  
    add_action( 'current_screen', 'myticket_this_screen' );
    function myticket_this_screen() {

        $current_screen = get_current_screen();
        if( $current_screen ->id === "widgets" ) {

      
        }
    }
	
	//Mel: 20/08/19. Added the below from the older plugin version since the codes below were missing from the new plugin version
	// Check if invoicing folders exists
/* 	if ( !is_customize_preview() && is_admin() ) {

        $wp_upload_dir = wp_upload_dir();
        if(is_writable( $wp_upload_dir['basedir'] )){
            
            if(!is_dir( $wp_upload_dir['basedir']. '/woocommerce-pdf-invoices' . '/attachments/' )){
                myticket_setup_invoice_directories();
            }
        }else{
            add_action( 'admin_notices', 'myticket_permission_notice' );
        }
    } */

    function myticket_permission_notice(){
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php echo esc_html__( 'Please grant write permission to /uploads directory of your server. Required to generate invoices for tickets.', 'myticket' ); ?></p>
        </div>
        <?php
    }
  
    
    /**
     * Creates invoices dir in uploads folder.
     */
    function myticket_setup_invoice_directories() {
        $wp_upload_dir = wp_upload_dir();
        $current_year       = date_i18n( 'Y', current_time( 'timestamp' ) );
        $directories        = apply_filters( 'bewpi_uploads_directories', array(
            $wp_upload_dir['basedir'] . '/woocommerce-pdf-invoices' . '/attachments/' => array(
                '.htaccess',
                'index.php',
            ),
            $wp_upload_dir['basedir'] . '/woocommerce-pdf-invoices' . '/attachments/' . $current_year . '/' => array(
                '.htaccess',
                'index.php',
            ),
            $wp_upload_dir['basedir'] . '/woocommerce-pdf-invoices' . '/fonts/' => array(
                '.htaccess',
                'index.php',
            ),
            $wp_upload_dir['basedir'] . '/woocommerce-pdf-invoices' . '/mpdf/ttfontdata/' => array(
                '.htaccess',
                'index.php',
            ),
            $wp_upload_dir['basedir'] . '/woocommerce-pdf-invoices' . '/templates/invoice/simple/' => array(),
        ) );

        // Create directories and copy files.
        foreach ( $directories as $directory => $files ) {
            if ( ! file_exists( $directory ) ) {
                wp_mkdir_p( $directory );
            }

            foreach ( $files as $file ) {
                $destination_file = $directory . basename( $file );
                if ( file_exists( $destination_file ) ) {
                    continue;
                }

                // $source_file = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/tmp/' . $file;
                // copy( $source_file, $destination_file );
            }
        }

        // Copy fonts from tmp directory to uploads/woocommerce-pdf-invoices/fonts.
        $font_files = array_merge( glob( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/tmp/fonts/*.ttf' ), glob( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/tmp/fonts/*.otf' ) );
        foreach ( $font_files as $font_file ) {
            $destination_file = $wp_upload_dir['basedir'] . '/woocommerce-pdf-invoices' . '/fonts/' . basename( $font_file );
            if ( file_exists( $destination_file ) ) {
                continue;
            }

            copy( $font_file, $destination_file );
        }
    }

?>