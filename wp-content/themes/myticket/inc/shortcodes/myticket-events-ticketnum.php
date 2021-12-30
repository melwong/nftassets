<?php
/*
Widget Name: MyTicket Select Ticket Without Seats Widget
Description: Create Event Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_events_ticketnum_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_events_ticketnum_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Select Ticket Without Seats', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Select Ticket Without Seats Section', 'myticket'),
                'panels_groups' => array('myticket'),
                'help'        => 'http://myticket_docs.kenzap.com',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(
                'subtitle' => array(
                    'type' => 'text',
                    'label' => esc_html__('Subtitle', 'myticket'),
                    'description' => esc_html__('Goes immidiately below banner section on the left side.', 'myticket'),
                    'default' => ''
                ),
                'event_id' => array(
                    'type' => 'text',
                    'label' => esc_html__('Event ID', 'myticket'),
                    'description' => esc_html__('Go to Products section from your admin menu. From the products list view hover on the desired record. ID number will show up. Copy it here.', 'myticket'),
                    'default' => ''
                ),
                'show_header' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Show header', 'myticket'),
                    'description' => esc_html__('Enable/Disable banner section of your event.', 'myticket'),
                ),
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-events-ticketnum';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_events_ticketnum_widget', __FILE__, 'myticket_events_ticketnum_widget');

endif;

