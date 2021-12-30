<?php
/*
Widget Name: MyTicket Partner Widget
Description: Create Partner Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_partner_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_partner_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Partner', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Partner Section', 'myticket'),
                'panels_groups' => array('myticket'),
                'help'        => 'http://myticket_docs.kenzap.com',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(
                'partner1_txt' => array(
                    'type' => 'text',
                    'label' => esc_html__('Partner 1 link', 'myticket'),
                    'default' => ''
                ),
                'partner1_img' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose 1 image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),  

                'partner2_txt' => array(
                    'type' => 'text',
                    'label' => esc_html__('Partner 2 link', 'myticket'),
                    'default' => ''
                ),
                'partner2_img' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose 2 image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),                

                'partner3_txt' => array(
                    'type' => 'text',
                    'label' => esc_html__('Partner 3 link', 'myticket'),
                    'default' => ''
                ),
                'partner3_img' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose 3 image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),                

                'partner4_txt' => array(
                    'type' => 'text',
                    'label' => esc_html__('Partner 4 link', 'myticket'),
                    'default' => ''
                ),
                'partner4_img' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose 4 image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-partner';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_partner_widget', __FILE__, 'myticket_partner_widget');

endif;

function myticket_shortcode_partner( $atts, $content=null ) {

    shortcode_atts( array(
        "type" => '',
        "image" => '',
        "title" => '',
        "subtitle" => '',
        "text" => '',
        "partner1_img" => '',
        "partner2_img" => '',
        "partner3_img" => '',
        "partner4_img" => '',
        "partner1_txt" => '',
        "partner2_txt" => '',
        "partner3_txt" => '',
        "partner4_txt" => ''
    ), $atts );

    ob_start();
    ?> 

    <!-- == media partner wrap starts == -->
    <div class="media-partner">
        <div class="container">
            <div class="col-xs-6 col-sm-3 partner-item wow fadeInUp">
                <figure><img class="img-responsive" src="<?php echo esc_url( $atts['partner1_img'] ); ?>" alt="<?php echo esc_url( $atts['partner1_txt'] ); ?>" /></figure>
            </div>
            <div class="col-xs-6 col-sm-3 partner-item wow fadeInUp">
                <figure><img class="img-responsive" src="<?php echo esc_url( $atts['partner2_img'] ); ?>" alt="<?php echo esc_url( $atts['partner2_txt'] ); ?>" /></figure>
            </div>
            <div class="col-xs-6 col-sm-3 partner-item wow fadeInUp">
                <figure><img class="img-responsive" src="<?php echo esc_url( $atts['partner3_img'] ); ?>" alt="<?php echo esc_url( $atts['partner3_txt'] ); ?>" /></figure>
            </div>
            <div class="col-xs-6 col-sm-3 partner-item wow fadeInUp">
                <figure><img class="img-responsive" src="<?php echo esc_url( $atts['partner4_img'] ); ?>" alt="<?php echo esc_url( $atts['partner4_txt'] ); ?>" /></figure>
            </div>
        </div>
    </div>
    <!-- == media partner wrap ends == -->

    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}