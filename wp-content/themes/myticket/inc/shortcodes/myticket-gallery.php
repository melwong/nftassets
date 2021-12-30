<?php
/*
Widget Name: MyTicket Gallery Widget
Description: Create Gallery Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_gallery_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_gallery_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket Gallery', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create Gallery Section', 'myticket'),
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
                    'label' => esc_html__('Gallery title ', 'myticket'),
                    'description' => esc_html__('Go to Gallery > Add New section to add new gallery items', 'myticket'),
                    'default' => ''
                ),
                'show_header' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Show header', 'myticket'),
                ),
                'images_per_page' => array(
                    'type' => 'text',
                    'label' => esc_html__('Max images per page', 'myticket'),
                    'default' => ''
                ),
                'category' => array(
                    'type' => 'text',
                    'label' => esc_html__('Category', 'myticket'),
                    'description' => esc_html__('Restrict gallery to certain category', 'myticket'),
                    'default' => ''
                ),
                'text' => array(
                    'type' => 'text',
                    'label' => esc_html__('Text', 'myticket'),
                    //'description' => esc_html__('Restrict gallery to certain category', 'myticket'),
                    'default' => ''
                ),              
                'icon' => array(
                    'type' => 'text',
                    'label' => esc_html__('Icon', 'myticket'),
                    //'description' => esc_html__('Restrict gallery to certain category', 'myticket'),
                    'default' => ''
                ),
                'class' => array(
                    'type' => 'text',
                    'label' => esc_html__('Class', 'myticket'),
                    // 'description' => esc_html__('Go to Programs > Programs section and pick id from program list', 'myticket'),
                    'default' => ''
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
                'type' => array(
                    'type' => 'radio',
                    'label' => esc_html__( 'Choose gallery type', 'myticket' ),
                    'default' => 'simple',
                    'options' => array(
                        'normal' => esc_html__( 'Normal', 'myticket' ),
                        'carousel' => esc_html__( 'Carousel', 'myticket' ),
                        'minified' => esc_html__( 'Minified', 'myticket' ),     
                        'menu' => esc_html__( 'Menu', 'myticket' ), 
                    )
                ),
          
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-gallery';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_gallery_widget', __FILE__, 'myticket_gallery_widget');

endif;

function myticket_shortcode_gallery($atts, $content = null) {
	$atts = shortcode_atts(array(
		"title"             => '',
		"pagination"        => 'true',
        "images_per_page"   => '16',
        "show_header"       => 'true',
        "type"              => 'normal',
        "link"              => '',
        "text"              => '',
        "icon"              => '',
        "class"             => '',
        "link"              => '',
        "link"              => '',
		"category"          => ''
	), $atts);  
	ob_start();
    
    if( 'normal' == $atts['type'] ) :
	?>

    <div class="galery-wrapper">
        <div class="container">
            <?php if( $title ) : ?>
            <div class="galery-title text-center">
                <h4 class="heading-regular"><?php echo esc_html( $title ); ?></h4>
            </div>
            <?php endif; ?>
            <div class="galery-content">
                <ul>
                    <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                                  'post_status'     => 'publish',
                                  'post_type'       => 'gallery',
                                  'category_name'   => $category,
                                  'posts_per_page'  => $images_per_page,
                                  'paged'           => $paged,
                                  );
                                  $postCount = 0;
                                  $recentPosts = new WP_Query( $args );
                                  
                    if ( $recentPosts->have_posts() ) :
                        while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
                    ?>
                    
                    <li class="col-sm-3 col-xs-6">
                        <div class="galery-item">
                            <?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-responsive' ) ); ?>
                            <div class="galery-content">
                                <h4><?php echo the_title();?></h4>
                                <a href="#" class="popup-click"><span class="lnr lnr-magnifier"></span></a>
                            </div>
                            <div class="box-content-item">
                                <div class="box-img">
                                    <?php the_post_thumbnail( 'myticket-story-large', array( 'class' => 'img-responsive' ) ); ?>
                                </div>
                                <div class="desc">
                                    <?php the_excerpt(); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <?php  endwhile;
                    endif;
                    wp_reset_postdata();
                    ?>
                </ul>
            </div>
            
            <?php if( $pagination == 'true'){ myticket_pagination( $recentPosts ); } ?>

        </div>
        
        <div class="bg-popup"></div>
        <div class="wrapper-popup">
            <a href="javascript:void(0)" class="close-popup"><span class="lnr lnr-cross-circle"></span></a>
            <div class="popup-content">
                <!--content-popup   -->
            </div>
        </div>
    </div>

    <?php elseif( 'minified' == $atts['type'] ) : ?>

    <!-- ============== instagram block starts ============== -->
    <section class="<?php echo esc_attr( $atts['class'] ); ?> instagram-block">
        <div class="container">
            <div class="top-text-header text-center">
                <h4 class="text-uppercase text-sp text-lt"><?php echo esc_attr( $atts['title'] ); ?></h4>
                <span class="follow-at text-spx text-lt txcolor"><?php echo esc_attr( $atts['text'] ); ?></span>
            </div>
        </div>
        <div class="instagram-image-row">
            <ul><?php $args = array(
                'post_status'     => 'publish',
                'post_type'       => 'gallery',
                'category_name'   => $atts['category'],
                'posts_per_page'  => $atts['images_per_page'],
                );
                $recentPosts = new WP_Query( $args );
                if ( $recentPosts->have_posts() ) :
                    while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?><li class="no-padding no-margin no-style" style="width:<?php echo 100/intVal( $atts['images_per_page'] );?>%"><figure><a data-toggle="lightbox" data-gallery="example-gallery" class="lightbox" href="<?php echo the_post_thumbnail_url( 'full' ); ?>"><?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-responsive' ) ); ?></a></figure></li><?php endwhile;
                endif;
                ?></ul>
        </div>
    </section>

    <?php elseif( 'carousel' == $atts['type'] ) : ?>

    <!-- ============== featured menu carousel starts ============== -->
    <section class="<?php echo esc_attr( $atts['class'] ); ?> featured-menu-carousel">
        <div class="container">
            <?php if ( 'true' == $atts['show_header'] ) : ?>
            <!-- == top text header starts == -->
            <div class="wow fadeInUp top-text-header text-center animated" >
                <h4 class="text-uppercase text-sp text-lt"><?php echo esc_attr( $atts['title'] ); ?></h4>
            </div>
            <!-- == top text header ends == -->
            <?php endif; ?>
            <!-- == carousel starts == -->
            <div class="carousel-container">
                <div id="carousel">
                    <?php $args = array(
                    'post_status'     => 'publish',
                    'post_type'       => 'gallery',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'gallery_category',   // taxonomy name
                            'field' => 'name',           // term_id, slug or name
                            'terms' => esc_attr( $atts['category'] ),                  // term id, term slug or term name
                        )
                    ),
                    'posts_per_page'  => esc_attr( $atts['images_per_page'] ),
                    );

                    $recentPosts = new WP_Query( $args );
                    if ( $recentPosts->have_posts() ) :
                        while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
                        $meta = get_post_meta( get_the_ID() );  ?>
                        <div class="carousel-feature feature-slide active">
                            <a href="<?php echo esc_url( $meta['myticket_url'][0] ); ?>"><?php the_post_thumbnail( 'myticket-gallery-carousel', array( 'class' => 'img-responsive' ) ); ?></a>
                            <div class="carousel-caption">
                                <p><?php echo the_title(); ?></p>
                            </div>
                        </div>
                    <?php endwhile;
                    endif;
                    ?>
                </div>
                <div id="carousel-left"><img alt="arrow left" src="<?php echo get_template_directory_uri() .'/images/arrow-left.png'; ?>" /></div>
                <div id="carousel-right"><img alt="arrow right" src="<?php echo get_template_directory_uri() .'/images/arrow-right.png'; ?>" /></div>
            </div>
            <!-- == carousel ends == -->
        </div>
    </section>
    <!-- ============== featured menu carousel ends ============== -->

    <?php elseif( 'menu' == $atts['type'] ) : ?>

    <!-- ============== featured menu block starts ============== -->
    <section class="block featured-menu-block">
        <?php if ( 'true' == $atts['show_header'] ) : ?>
        <div class="container">
            <!-- == top text header starts == -->
            <div class="wow fadeInUp top-text-header text-center">
                <h4 class="text-uppercase text-lt text-sp"><?php echo esc_attr( $atts['title'] ); ?></h4>
            </div>
            <!-- == top text header ends == -->
        </div>
        <?php endif; ?>
        <!-- == featured menu slider starts == -->
        <div class="wow fadeInUp featured-menu-slider">
            <div class="container">
                <ul class="bxslider1 row">

                    <?php $args = array(
                    'post_status'     => 'publish',
                    'post_type'       => 'gallery',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'gallery_category',   // taxonomy name
                            'field' => 'name',                      // term_id, slug or name
                            'terms' => esc_attr( $atts['category'] ),   // term id, term slug or term name
                        )
                    ),
                    'posts_per_page'  => esc_attr( $atts['images_per_page'] ),
                    );

                    $recentPosts = new WP_Query( $args );
                    if ( $recentPosts->have_posts() ) :
                        while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
                        $meta = get_post_meta( get_the_ID() );?>
                        <li class="col-xs-12 col-sm-3">
                            <a data-toggle="lightbox" class="lightbox bghcolor" href="<?php echo the_post_thumbnail_url(); ?>">
                                <figure><?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-responsive' ) ); ?></figure>
                                <div class="menu-info">
                                    <h6 class="text-capitalize text-lt text-sp txcolor"><?php echo the_title(); ?></h6>
                                    <span><?php echo get_the_excerpt(); ?></span>
                                </div>
                            </a>
                        </li>
                        <?php endwhile;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
        <!-- == featured menu slider ends == -->
    </section>
    <!-- ============== featured menu block ends ============== -->

    <?php elseif( 'aboutus' == $atts['type'] ) : ?>

    <!-- About us image grid block starts -->
    <div class="row image-grid-row">
     
        <div class="col-xs-12 col-sm-7 small-image-group wow fadeInLeft">
            <div class="row">
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image1.jpg" alt="About Image 1" /></a></figure>
                </div>
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image2.jpg" alt="About Image 2" /></a></figure>
                </div>
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image3.jpg" alt="About Image 3" /></a></figure>
                </div>
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image4.jpg" alt="About Image 4" /></a></figure>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 big-image wow fadeInRight">
            <figure><a href="#"><img class="img-responsive" src="images/about-image.jpg" alt="About image" /></a></figure>
        </div>
    </div>
    <!-- About us image gallery block ends -->

    <?php endif; 
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
    }
    
