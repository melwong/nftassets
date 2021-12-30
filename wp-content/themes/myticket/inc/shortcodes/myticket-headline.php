<?php
/*
Widget Name: MyTicket Headline Widget
Description: Create headline Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_headline_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_headline_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Headline', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Headline Section', 'myticket'),
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
                    'label' => esc_html__('Headline Title ', 'myticket'),
                    //'description' => esc_html__('Go to headline > Add New section to add new headline items', 'myticket'),
                    'default' => ''
                ),
                'type' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Choose headline type', 'myticket' ),
                    'default' => 'simple',
                    'options' => array(
                        'normal' => esc_html__( 'Normal', 'myticket' ),
                        //'carousel' => esc_html__( 'Carousel', 'myticket' ),
                        //'minified' => esc_html__( 'Minified', 'myticket' ),     
                        //'menu' => esc_html__( 'Menu', 'myticket' ), 
                    )
                ),
          
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-headline';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_headline_widget', __FILE__, 'myticket_headline_widget');

endif;
    