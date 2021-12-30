<?php
/*
/*
   Plugin Name: Woocommerce Bulk Attributes 3.1
   Plugin URI: http://www.woocommercebulkattributes.com/
   Description: The plugin that allow to add WooCommerce Attributes terms in bulk
   Version: 3.1
   Author: TNA Consult
   Author URI: http://www.woocommercebulkattributes.com/
   
*/

wp_register_style('custom_css',plugins_url().'/WooCommerce-Bulk-Attributes/formating.css');
//wp_register_script( 'custom_script', 'http://code.jquery.com/jquery.min.js');

wp_enqueue_style( 'custom_css' );
//wp_enqueue_script( 'custom_script' );

// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {
    // Add a new submenu under Settings:
    add_options_page(__('Test Settings','menu-test'), __('Test Settings','menu-test'), 'manage_options', 'testsettings', 'mt_settings_page');

    // Add a new submenu under Tools:
    add_management_page( __('Test Tools','menu-test'), __('Test Tools','menu-test'), 'manage_options', 'testtools', 'mt_tools_page');

    // Add a new top-level menu (ill-advised):
    add_menu_page(__('WC Bulk Attributes Manager','menu-test'), __('WC Bulk Attributes Manager','menu-test'), 'manage_options', 'mt-top-level-handle', 'my_submenu_page_callback' );
     add_submenu_page('mt-top-level-handle', __('Bulk Add Attributes Terms','menu-test'), __('Bulk Add Attributes Terms','menu-test'), 'manage_options', 'mt-top-level-handle', 'my_submenu_page_callback');
    // Add a submenu to the custom top-level menu:
    add_submenu_page('mt-top-level-handle', __('Bulk Add Attributes Terms To Product','menu-test'), __('Bulk Add Attributes Terms To Product','menu-test'), 'manage_options', 'sub-page', 'mt_sublevel_page');
	
	 //Edit by dev bulk delete:
    add_submenu_page('mt-top-level-handle', __('bulk delete','menu-test'), __('Bulk Delete','menu-test'), 'manage_options', 'sub-page-delete', 'mt_sub_page_delete');
	//Edit end 
    
}

//include "formating.php";
function my_submenu_page_callback()
{
	include "attribute.php";
}
	
function mt_sublevel_page()
{
	include "attribute-products.php";
	
} 

//Edit by dev
function mt_sub_page_delete()
{
	include "attribute-products.php";
	
} 
//Edit end

?>
