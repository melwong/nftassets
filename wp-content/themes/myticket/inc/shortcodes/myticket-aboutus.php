<?php
/*
Widget Name: MyTicket Aboutus Widget
Description: Create About Us Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_aboutus_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_aboutus_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket About us', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create About Us Section', 'myticket'),
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
                    'label' => esc_html__('About us title', 'myticket'),
                    'default' => ''
                ),
                'text' => array(
                    'type' => 'textarea',
                    'label' => esc_html__('About us text', 'myticket'),
                    'default' => ''
                ),
                'text_right' => array(
                    'type' => 'textarea',
                    'label' => esc_html__('About us text right', 'myticket'),
                    'default' => ''
                ),
                'text_left' => array(
                    'type' => 'textarea',
                    'label' => esc_html__('About us text left', 'myticket'),
                    'default' => ''
                ),
                'img1' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose first image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'img2' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose second image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'img3' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose third image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'img4' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose fourth image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'img5' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose fifth image', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),
                'button_url' => array(
                    'type' => 'link',
                    'label' => esc_html__('Button Link', 'myticket'),
                    'description' => esc_html__('Button url', 'myticket'),
                    'default' => '#'
                ),
                'button_text' => array(
                    'type' => 'text',
                    'label' => esc_html__('Button Text', 'myticket'),
                    'description' => esc_html__('Button CTA text', 'myticket'),
                    'default' => ''
                ),
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-aboutus';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_aboutus_widget', __FILE__, 'myticket_aboutus_widget');

endif;

function myticket_shortcode_aboutus( $atts, $content=null ) {

    shortcode_atts( array(
        "type" => '',
        "title" => '',
        "text" => '',
        "text_left" => '',
        "text_right" => '',
        "placeholder" => '',
        "button_text" => '',
        "button_url" => '',
    ), $atts );

    ob_start();
    ?> 

    <!-- ============== About us starts ============== -->
    <section class="block about-us-block">
        <div class="container">
            <!-- == whole about us content wrap starts == -->
            <div class="about-us-content">
                <div class="text-center top-description wow fadeInUp">
                    <h2 class="text-sp text-lt"><?php echo esc_attr( $atts['title'] ); ?></h2>
                    <p><?php echo esc_attr( $atts['text'] ); ?></p>
                </div>

                <!-- About us image grid block starts -->
                <div class="row image-grid-row">
                    <div class="col-xs-12 col-sm-7 small-image-group wow fadeInLeft">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 small-image-wrap wow fadeInUp">
                                <figure><a data-featherlight="<?php echo esc_url( $atts['img1_full'] ); ?>" href="<?php echo esc_url( $atts['img1_full'] ); ?>" ><img class="img-responsive" src="<?php echo esc_url( $atts['img1'] ); ?>" alt="About Image 1" /></a></figure>
                            </div>
                            <div class="col-xs-12 col-sm-6 small-image-wrap wow fadeInUp">
                                <figure><a data-featherlight="<?php echo esc_url( $atts['img2_full'] ); ?>" href="<?php echo esc_url( $atts['img2_full'] ); ?>" ><img class="img-responsive" src="<?php echo esc_url( $atts['img2'] ); ?>" alt="About Image 2" /></a></figure>
                            </div>
                            <div class="col-xs-12 col-sm-6 small-image-wrap wow fadeInUp">
                                <figure><a data-featherlight="<?php echo esc_url( $atts['img4_full'] ); ?>" href="<?php echo esc_url( $atts['img4_full'] ); ?>" ><img class="img-responsive" src="<?php echo esc_url( $atts['img4'] ); ?>" alt="About Image 3" /></a></figure>
                            </div>
                            <div class="col-xs-12 col-sm-6 small-image-wrap wow fadeInUp">
                                <figure><a data-featherlight="<?php echo esc_url( $atts['img5_full'] ); ?>" href="<?php echo esc_url( $atts['img5_full'] ); ?>" ><img class="img-responsive" src="<?php echo esc_url( $atts['img5'] ); ?>" alt="About Image 4" /></a></figure>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-5 big-image wow fadeInRight">
                        <figure><a data-featherlight="<?php echo esc_url( $atts['img3_full'] ); ?>" href="<?php echo esc_url( $atts['img3_full'] ); ?>" ><img class="img-responsive" src="<?php echo esc_url( $atts['img3'] ); ?>" alt="About image" /></a></figure>
                    </div>
                </div>
                <!-- About us image gallery block ends -->

                <!-- 2 columns paragraph starts -->
                <article class="wow fadeInUp">
                    <p><?php echo esc_attr( $atts['text_left'] ); ?></p>
                    <p><?php echo esc_attr( $atts['text_right'] ); ?></p>
                </article>

                <div class="text-center center-btn wow flipInX">
                    <a href="<?php echo esc_url( $atts['button_url'] ); ?>" class="btn btn-outlined btn-theme btn-lg" data-wow-delay="0.7s"><?php echo esc_attr( $atts['button_text'] ); ?></a>
                </div>
                <!-- 2 columns paragraph ends -->

            </div>
            <!-- == whole about us content wrap starts == -->
        </div>

        <?php echo myticket_fix_shortcode( $content ); ?>

    </section>
  
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}