<?php
/*
Widget Name: MyTicket Single Event Widget
Description: Create Event Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_events_single_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_events_single_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Single Event', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Single Event Section', 'myticket'),
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
                'show_about' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Show about', 'myticket'),
                    'description' => esc_html__('Enable/Disable about section of your event.', 'myticket'),
                ),
                'show_features' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Show highlights', 'myticket'),
                    'description' => esc_html__('Enable/Disable highlights section of your event.', 'myticket'),
                ),
                'link' => array(
                    'type' => 'text',
                    'label' => esc_html__('Button link', 'myticket'),
                    'description' => esc_html__('Override default single product link with custom URL.', 'myticket'),
                ),
                'features_content' => array(
                    'type' => 'tinymce',
                    'label' => __( 'Specify highlights here', 'myticket' ),
                    'default' => '',
                    'rows' => 10,
                    'default_editor' => 'html',
                    'button_filters' => array(
                        'mce_buttons' => array( $this, 'filter_mce_buttons' ),
                        'mce_buttons_2' => array( $this, 'filter_mce_buttons_2' ),
                        'mce_buttons_3' => array( $this, 'filter_mce_buttons_3' ),
                        'mce_buttons_4' => array( $this, 'filter_mce_buttons_5' ),
                        'quicktags_settings' => array( $this, 'filter_quicktags_settings' ),
                    ),
                ),
                'show_map' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Show map', 'myticket'),
                    'description' => esc_html__('Enable/Disable map section of your event. Coordinates can be entered when editing products under Products section from your admin menu.', 'myticket'),
                ),
                'zoom' => array(
                    'type' => 'slider',
                    'label' => esc_html__('Map zoom level', 'myticket'),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => 15,
                    'min' => 0,
                    'max' => 20,
                    'integer' => true,
                ), 
                'hue' => array(
                    'type' => 'color',
                    'label' => esc_html__('Map hue/color', 'myticket'),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => '#ccc'
                ), 
                'saturation' => array(
                    'type' => 'slider',
                    'default' => -80,
                    'min' => -100,
                    'max' => 100,
                    'integer' => true,
                    'label' => esc_html__('Map saturation/brightness level', 'myticket'),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => '-80'
                ), 
                'type' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Map pointer type', 'myticket' ),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => 'simple',
                    'options' => array(
                        'balloon' => esc_html__( 'Balloon', 'myticket' ),
                        'pointer' => esc_html__( 'Pointer', 'myticket' ),   
                    )
                ),
                'a_repeater' => array(
                    'type' => 'repeater',
                    'label' => esc_html__( 'Add event features' , 'myticket' ),
                    'item_name'  => esc_html__( 'Click here to setup feature record', 'myticket' ),
                    'item_label' => array(
                        'selector'     => "[id*='repeat_text']",
                        'update_event' => 'change',
                        'value_method' => 'val'
                    ),
                    'fields' => array(
                        'title' => array(
                            'type' => 'text',
                            'label' => esc_html__('Title', 'myticket'),
                            'default' => ''
                        ),
                        'icon' => array(
                            'type' => 'text',
                            'label' => esc_html__('Icon', 'myticket'),
                            'description' => esc_html__('Please provide icon shortcode. Ex.: fa fa-mobile fa-3x. Full catalog can be found here: http://fontawesome.io/icons/', 'myticket'),
                            'default' => ''
                        ),
                        'visibility' => array(
                            'type' => 'checkbox',
                            'label' => esc_html__( 'Temporary hide this record', 'myticket' )
                        ),
                    )
                )
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-events-single';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_events_single_widget', __FILE__, 'myticket_events_single_widget');

endif;

