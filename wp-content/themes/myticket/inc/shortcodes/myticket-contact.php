<?php

/*
Widget Name: MyTicket Contacts Widget
Description: List Limited Product Offers With Countdown Timers
Author: Kenzap
Author URI: http://kenzap.com
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_contact_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_contact_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Contacts', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Display contacts block with map and form', 'myticket'),
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
                    'label' => esc_html__('Contacts Title', 'myticket'),
                    'default' => ''
                ),
                'title1' => array(
                    'type' => 'text',
                    'label' => esc_html__('Subtitle left', 'myticket'),
                    'default' => ''
                ),
                'title2' => array(
                    'type' => 'text',
                    'label' => esc_html__('Subtitle right', 'myticket'),
                    'default' => ''
                ),
                'text' => array(
                    'type' => 'textarea',
                    'label' => esc_html__('Some text', 'myticket'),
                    'default' => ''
                ),
                'latitude' => array(
                    'type' => 'text',
                    'label' => esc_html__('Map latitude', 'myticket'),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => ''
                ),
                'longitude' => array(
                    'type' => 'text',
                    'label' => esc_html__('Map longitude', 'myticket'),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => ''
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
                'balloon' => array(
                    'type' => 'text',
                    'label' => esc_html__('Map balloon text', 'myticket'),
                    'description' => esc_html__('Important! You have to clear browsing cache after making changes here', 'myticket'),
                    'default' => ''
                ),
                'icon_row1' => array(
                    'type' => 'text',
                    'label' => esc_html__('Icon Row One', 'myticket'),
                    'default' => 'map-icon.png'
                ),
                'icon_row2' => array(
                    'type' => 'text',
                    'label' => esc_html__('Icon Row Two', 'myticket'),
                    'default' => 'phone-icon.png'
                ),
                'form_id' => array(
                    'type' => 'text',
                    'label' => esc_html__('Contact form ID', 'myticket'),
                    'default' => ''
                ),
                'email' => array(
                    'type' => 'text',
                    'label' => esc_html__('Email address', 'myticket'),
                    'default' => ''
                ),
                'phone' => array(
                    'type' => 'text',
                    'label' => esc_html__('Phone number to call', 'myticket'),
                    'description' => esc_html__('Should be a well formatted phone number with country code.', 'myticket'),
                    'default' => ''
                ),
                'phone_nice' => array(
                    'type' => 'text',
                    'label' => esc_html__('Phone number to display', 'myticket'),
                    'default' => ''
                ),
                'address' => array(
                    'type' => 'text',
                    'label' => esc_html__('Address text', 'myticket'),
                    'default' => ''
                ),   
                'btn_text1' => array(
                    'type' => 'text',
                    'label' => esc_html__('Button text 1', 'myticket'),
                    'default' => ''
                ),                 
                'btn_link1' => array(
                    'type' => 'text',
                    'label' => esc_html__('Button link 1', 'myticket'),
                    'default' => '#'
                ),     
                'btn_text2' => array(
                    'type' => 'text',
                    'label' => esc_html__('Button text 2', 'myticket'),
                    'default' => ''
                ),   
                'btn_link2' => array(
                    'type' => 'text',
                    'label' => esc_html__('Button link 2', 'myticket'),
                    'default' => '#'
                ),                                  
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-contact';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_contact_widget', __FILE__, 'myticket_contact_widget');

endif;

function myticket_shortcode_contact( $atts, $content=null ) {

    shortcode_atts( array(
        "title" => '',
        "address_part1" => '',
        "address_part2" => '',
        "text" => '',
        "icon_row1" => '',
        "icon_row2" => '',
        "form_id" => '',
        "email" => '',
        "phone" => '',
        "phone_nice" => '',
        "latitude" => '',
        "longitude" => '',
        "balloon" => '',
        "type"  => 'balloon',
        "zoom"  => '15',
        "hue"  => '-80',
        "saturation"  => '#ccc',
    ), $atts );

    ob_start();

    $icon_row1 = '';
    if( '' != $atts['icon_row1'] ){
        $icon_row1 = get_template_directory_uri() .'/images/'.$atts['icon_row1'];
    }

    $icon_row2 = '';
    if( '' != $atts['icon_row2'] ){
        $icon_row2 = get_template_directory_uri() .'/images/'.$atts['icon_row2'];
    }

    $myticket_enable_map = true;
    ?> 

    <div class="main-container">
        <div class="page-main contact-us">
            <div class="map-contact">
                <div id="map" data-latitude="<?php echo esc_attr( $atts['latitude'] ); ?>" data-longitude="<?php echo esc_attr( $atts['longitude'] ); ?>" data-balloon="<?php echo esc_attr( $atts['balloon'] ); ?>" data-pointer="<?php echo esc_attr( $atts['type'] ); ?>" data-saturation="<?php echo esc_attr( $atts['saturation'] ); ?>" data-hue="<?php echo esc_attr( $atts['hue'] ); ?>" data-zoom="<?php echo esc_attr( $atts['zoom'] ); ?>"></div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="map-info">
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="title-visit">
                                        <h3 class="font-montserrat-light font25 text-capitalize"> <?php echo esc_attr( $atts['title1'] ); ?> </h3>
                                    </div>
                                    <div class="content-visit">
                                        <p class="font-montserrat-light font17"> <?php echo esc_attr( $atts['address'] ); ?> </p>
                                        <a href="<?php echo esc_url( $atts['btn_link1'] ); ?>" class="link-contact link-visit font-montserrat-light font17 text-capitalize"> <?php echo esc_attr( $atts['btn_text1'] ); ?></a>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="title-touch">
                                        <h3 class="font-montserrat-light font25 text-capitalize"> <?php echo esc_attr( $atts['title2'] ); ?> </h3>
                                    </div>
                                    <div class="content-touch">
                                        <div class="content-telp">
                                            <span class="font-montserrat-light font17 text-uppercase"><?php esc_html_e( 'tel', 'myticket' ); ?></span>
                                            <a href="tel:<?php echo esc_attr( $atts['phone'] ); ?>" class="font-montserrat-light font17"><?php echo esc_attr( $atts['phone_nice'] ); ?></a>
                                        </div>
                                        <div class="content-mail">
                                            <span class="font-montserrat-light font17 text-uppercase"><?php esc_html_e( 'email', 'myticket' ); ?></span>
                                            <a href="mailto:<?php echo esc_attr( $atts['email'] ); ?>" class="font-montserrat-light font17"><?php echo esc_attr( $atts['email'] ); ?> </a>
                                        </div>
                                        <div class="content-address">
                                            <a href="<?php echo esc_url( $atts['btn_link2'] ); ?>" class="link-contact link-touch font-montserrat-light font17"> <?php echo esc_attr( $atts['btn_text2'] ); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <?php echo do_shortcode( '[contact-form-7 id="'. esc_attr( $atts['form_id'] ) .'" title="Contact us form"]' ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="separates"></div>
    </div>

    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
