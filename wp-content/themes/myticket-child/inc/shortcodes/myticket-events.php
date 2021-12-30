<?php
/*
Widget Name: MyTicket Events Widget
Description: Create Events Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_events_widget2 extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_events_widget2',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Events List - Child', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Events List Section', 'myticket'),
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
                    'label' => esc_html__('Show filter', 'myticket'),
                    'description' => esc_html__('Enable/Disable extra fields for events sorting and search', 'myticket'),
                ),
                'locations' => array(
                    'type' => 'textarea',
                    'label' => esc_html__('Filter locations', 'myticket'),
                    'description' => esc_html__('Override default location list. Separate locations by ",". Ex.: Arena Berlin, Belgrade Stadium.. If empty all locations are queried. To specify event location go to Products > Edit product > Event Title', 'myticket'),
                    'default' => ''
                ),
                'type' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Choose event list type', 'myticket' ),
                    'default' => 'simple',
                    'options' => array(
                        'upcomming' => esc_html__( 'Upcoming Events', 'myticket' ),
                        'past' => esc_html__( 'Past Events', 'myticket' ),
                        'all' => esc_html__( 'All Events', 'myticket' ), 
                    )
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
                    'description' => esc_html__('Restrict events to certain category. Categories are case-sensitive. Ex.: Sports, Events, Concerts..', 'myticket'),
                    'default' => ''
                ),
                'relation' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Query relation', 'myticket' ),
                    'description' => esc_html__('This rule tells database how to filter results. If user selects two categories Golf and Swimming AND will show only those events that are in Golf and Swimming simultaneously. If user selects Golf and Swimming categories OR will picks up all events within Golf category and unites them with all events from Swimming category. The more checkboxes user ticks with OR relation the more results will be shown and vice versa if AND is selected.', 'myticket'),
                    'default' => 'AND',
                    'options' => array(
                        'AND' => esc_html__( 'AND', 'myticket' ),
                        'OR' => esc_html__( 'OR', 'myticket' ),
                    )

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
                'bg_color' => array(
                    'type' => 'color',
                    'label' => __( 'Background color', 'myticket' ),
                    'default' => '#373737'
                ),
                'pagination' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Enable/disable pagination', 'myticket'),
                ),
                'sidebar' => array(
                    'type' => 'select',
                    'label' => esc_html__( 'Sidebar Location', 'myticket' ),
                    'description' => esc_html__( 'Select sidebar location. You can change siderbar position from left to right under in Customizer > Advanced > Widget sidebar location', 'myticket' ),
                    'default' => 'sidebar-left',
                    'options' => array(
                        'sidebar-none' => esc_html__( 'Sidebar None', 'myticket' ),
                        'sidebar-left' => esc_html__( 'Sidebar Left', 'myticket' ),
                        'sidebar-right' => esc_html__( 'Sidebar Right', 'myticket' ),
                    )
                ),
                'widget' => array(
                    'type' => 'select',
                    'label' => esc_html__( 'Sidebar Source', 'myticket' ),
                    'description' => esc_html__( 'Select which sidebar to use. You can add/remove/change widgets for the selected sidebar under Appearence > Widgets section', 'myticket' ),
                    'default' => 'sidebar1',
                    'options' => array(
                        'myticket-widget-area1' => esc_html__( 'MyTicket Sidebar #1', 'myticket' ),
                        'myticket-widget-area2' => esc_html__( 'MyTicket Sidebar #2', 'myticket' ),
                        'myticket-widget-area3' => esc_html__( 'MyTicket Sidebar #3', 'myticket' ),
                    )
                ),
                'sidebar_size' => array(
                    'type' => 'select',
                    'label' => esc_html__( 'Sidebar Size', 'myticket' ),
                    'description' => esc_html__( 'Try to minimalize the width of all sidebar widgets and maximize place for website contents or vise versa', 'myticket' ),
                    'default' => 'sidebar-left',
                    'options' => array(
                        'sidebar-min' => esc_html__( 'Sidebar Minimize', 'myticket' ),
                        'sidebar-max' => esc_html__( 'Sidebar Maximize', 'myticket' ),
                        //'sidebar-right' => esc_html__( 'Sidebar Right', 'myticket' ),
                    )
                ),
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-events';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_events_widget2', __FILE__, 'myticket_events_widget2');

endif;

