<?php
/*
Widget Name: MyTicket Schedule Events Widget
Description: Create events Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_events_schedule_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_events_schedule_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Schedule Events', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Schedule Events Section', 'myticket'),
                'panels_groups' => array('myticket'),
                'help'        => 'http://myticket_docs.kenzap.com',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(

                'show_header' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Show header', 'myticket'),
                    'description' => esc_html__('Enable/Disable carousel section with dates (Month/Year).', 'myticket'),
                ),
                'events_per_page' => array(
                    'type' => 'number',
                    'label' => esc_html__('Max events per page', 'myticket'),
                    'description' => esc_html__('Specify the maximum number of events listed per single page', 'myticket'),
                    'default' => ''
                ),
                'category' => array(
                    'type' => 'text',
                    'label' => esc_html__('Category', 'myticket'),
                    'description' => esc_html__('Restrict events to certain category', 'myticket'),
                    'default' => ''
                ),
                'orderby' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Choose default sorting', 'myticket' ),
                    'default' => 'simple',
                    'options' => array(
                        '' => esc_html__( 'None', 'myticket' ),
                        'popularity' => esc_html__( 'Popularity', 'myticket' ),
                        'rating' => esc_html__( 'Rating', 'myticket' ),
                        'newest' => esc_html__( 'Newest', 'myticket' ),
                        'lowestprice' => esc_html__( 'Lowest price', 'myticket' ),
                        'highestprice' => esc_html__( 'Highest price', 'myticket' ),
 
                    )
                ),  
                'type' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Choose event list type', 'myticket' ),
                    'default' => 'simple',
                    'options' => array(
                        'schedule' => esc_html__( 'Upcoming Events', 'myticket' ),
                        'past' => esc_html__( 'Past Events', 'myticket' ),
                        'all' => esc_html__( 'All Events', 'myticket' ), 
                    )
                ),
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-events-schedule';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_events_schedule_widget', __FILE__, 'myticket_events_schedule_widget');

endif;

