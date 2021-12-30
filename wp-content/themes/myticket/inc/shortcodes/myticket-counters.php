<?php
/*
Widget Name: MyTicket Counters Widget
Description: Create events Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_events_counters_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_events_counters_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Counters', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Counting Section', 'myticket'),
                'panels_groups' => array('myticket'),
                'help'        => 'http://myticket_docs.kenzap.com',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(
                'title1' => array(
                    'type' => 'text',
                    'label' => esc_html__('Title 1', 'myticket'),
                    'description' => esc_html__('Section title', 'myticket'),
                    'default' => ''
                ),
                'value1' => array(
                    'type' => 'text',
                    'label' => esc_html__('Value 1', 'myticket'),
                    'description' => esc_html__('Section value', 'myticket'),
                    'default' => ''
                ),
                'title2' => array(
                    'type' => 'text',
                    'label' => esc_html__('Title 2', 'myticket'),
                    'description' => esc_html__('Section title', 'myticket'),
                    'default' => ''
                ),
                'value2' => array(
                    'type' => 'text',
                    'label' => esc_html__('Value 2', 'myticket'),
                    'description' => esc_html__('Section value', 'myticket'),
                    'default' => ''
                ),
                'title3' => array(
                    'type' => 'text',
                    'label' => esc_html__('Title 3', 'myticket'),
                    'description' => esc_html__('Section title', 'myticket'),
                    'default' => ''
                ),
                'value3' => array(
                    'type' => 'text',
                    'label' => esc_html__('Value 3', 'myticket'),
                    'description' => esc_html__('Section value', 'myticket'),
                    'default' => ''
                ),               
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-counters';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_events_counters_widget', __FILE__, 'myticket_events_counters_widget');

endif;

