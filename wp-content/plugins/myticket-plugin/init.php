<?php
/**
 * @package MyTicket
 * @version 1.0.0
 */
/*
Plugin Name: MyTicket
Plugin URI: http://myticket.kenzap.com
Description: This plugin extends default <cite>myticket theme</cite> functionality. To activate all custom elements  features use this plugin.
Author: Kenzap
Version: 1.0.4
Author URI: http://kenzap.com
*/

define( 'KENZAP_PARAM', '1' );
$my_theme = wp_get_theme();
if ( 'MyTicket' == $my_theme || 'MyTicket Child Theme' == $my_theme ) :

	// Add Advanced Options
	if ( !is_customize_preview()  && is_admin() ) {
	  require plugin_dir_path(__FILE__) . 'inc/setup/setup.php';
	}

	//init custom admin styling
	require plugin_dir_path(__FILE__) . 'inc/admin/init.php';

	// register custom post types
	if ( get_option( basename( get_template_directory() ) . '_plugin_version', 0 ) < 2 ) :
	//require plugin_dir_path(__FILE__) . 'custom/woocommerce-pdf-invoices/bootstrap.php';
	require plugin_dir_path(__FILE__) . 'custom/setup.php';
	require plugin_dir_path(__FILE__) . 'custom/post-types/myticket-gallery.php';
	require plugin_dir_path(__FILE__) . 'custom/post-types/myticket-identities.php';
	endif;
	
	// // register custom widgets
	require plugin_dir_path(__FILE__) . 'custom/widgets/myticket-categories.php';
	require plugin_dir_path(__FILE__) . 'custom/widgets/myticket-header.php';
	require plugin_dir_path(__FILE__) . 'custom/widgets/myticket-pricing.php';
	require plugin_dir_path(__FILE__) . 'custom/widgets/myticket-identities.php';
	require plugin_dir_path(__FILE__) . 'custom/widgets/myticket-twitter.php';
	require plugin_dir_path(__FILE__) . 'custom/twitter.php';

	// register shortcodes
	add_shortcode( 'myticket_twitter', 'myticket_shortcode_twitter' );
	add_shortcode( 'myticket_banner', 'myticket_shortcode_banner' );
	add_shortcode( 'myticket_aboutus', 'myticket_shortcode_aboutus' );
	add_shortcode( 'myticket_contact', 'myticket_shortcode_contact' );
	add_shortcode( 'myticket_gallery', 'myticket_shortcode_gallery' );	 

	/* Add shortcode fix to content */
	add_filter( 'logout_url', 'myticket_new_logout_url', 10, 2 );
	add_filter( 'body_class', 'myticket_body_classes' );
	   
	//load suggested plugins
	// require plugin_dir_path(__FILE__) . 'custom/plugins.php';
	// require plugin_dir_path(__FILE__) . 'inc/classes/class-tgm-plugin-activation.php';

endif;  


/* Here start MyTicket supported features declaration that can be used without MyTicket theme */
require plugin_dir_path(__FILE__) . 'custom/post-types/myticket-extention.php'; 

load_plugin_textdomain( 'myticket-plugin', false, __DIR__ . '/languages' );

?>
