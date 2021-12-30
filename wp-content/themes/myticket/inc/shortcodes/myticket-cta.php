<?php
/*
Widget Name: MyTicket CTA Widget
Description: Create events Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_events_cta_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_events_cta_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket CTA', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create CTA Section', 'myticket'),
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
                    'label' => esc_html__('Title', 'myticket'),
                    'description' => esc_html__('Section title', 'myticket'),
                    'default' => ''
                ),
                'text' => array(
                    'type' => 'text',
                    'label' => esc_html__('Text', 'myticket'),
                    'description' => esc_html__('Section text', 'myticket'),
                    'default' => ''
                ),
                'cta_text' => array(
                    'type' => 'text',
                    'label' => esc_html__('CTA text', 'myticket'),
                    'description' => esc_html__('CTA button text', 'myticket'),
                    'default' => ''
                ),
                'cta_link' => array(
                    'type' => 'text',
                    'label' => esc_html__('CTA link', 'myticket'),
                    'description' => esc_html__('CTA button link', 'myticket'),
                    'default' => ''
                ),           
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-cta';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_events_cta_widget', __FILE__, 'myticket_events_cta_widget');

endif;

