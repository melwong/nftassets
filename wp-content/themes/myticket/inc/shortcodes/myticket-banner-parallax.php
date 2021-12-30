<?php

/*
Widget Name: MyTicket Banner Parallax Widget
Description: Create Different Styles myticket Banner
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_banner_parallax_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_banner_parallax_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Banner Parallax', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Parallax Banner', 'myticket'),
                'panels_groups' => array('myticket'),
                'help'        => 'http://myticket_docs.kenzap.com',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => esc_html__('Banner Title', 'myticket'),
                    'default' => ''
                ),
                'text' => array(
                    'type' => 'text',
                    'label' => esc_html__('Banner Text', 'myticket'),
                    'default' => ''
                ),
                'image' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose banner image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'button_url' => array(
                    'type' => 'link',
                    'label' => esc_html__('Button Link', 'myticket'),
                    'description' => esc_html__('Specify banner youtube video url here.', 'myticket'),
                    'default' => '#'
                ),
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-banner-parallax';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_banner_parallax_widget', __FILE__, 'myticket_banner_parallax_widget');

endif;


