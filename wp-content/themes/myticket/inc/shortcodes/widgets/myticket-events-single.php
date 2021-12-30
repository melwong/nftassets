<?php 

$myticket_show_header = $instance['show_header'];
$myticket_args = array(
    'p' => $instance['event_id'],
    'post_type' => array('product'), //, 'product_variation'
    'post_status' => 'publish',
);

$products = new WP_Query( $myticket_args );

while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); 

    $image_gallery_arr = explode( ",", $meta['_product_image_gallery'][0] ); ?>


    <?php if( $instance['show_header'] ) : ?>

        <section class="section-event-single-featured-header" style="background-image: url('<?php echo get_the_post_thumbnail_url( $products->post, 'myticket-story-large' ); ?>')" >
            <div class="container">
                <div class="section-content">
                    <h1><?php echo the_title(); ?></h1> 
                    <p>
                        <span>
                            <i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo esc_attr($meta['myticket_title'][0]);?> <?php echo esc_attr($meta['myticket_address'][0]);?>
                        </span>
                    </p>
                </div>
            </div>
        </section>

    <?php endif; ?>

    <?php if ( class_exists( 'WooCommerce' ) ) : ?>

        <section class="section-event-single-header">
            <div class="container">
                <h1><?php echo esc_html($instance['subtitle']);?></h1>
                <ul class="ticket-purchase">
                    <li>
                        <?php esc_html_e('Ticket from', 'myticket'); ?>
                    </li>
                    <li>
                        <span>
                            <?php $product = wc_get_product( get_the_ID() );
                            echo $product->get_price_html(); ?>
                        </span>
                    </li>
                    <li>     
                        <?php if ( $instance['link'] != '' ){ ?>

                            <a href="<?php echo esc_url($instance['link']); ?>" class="get-ticket"><?php echo esc_html__( 'Purchase Ticket', 'myticket' ); ?></a>

                        <?php }else{ ?>

                            <?php $btn_text_arr = [];
                            $btn_text_arr[0] = esc_attr__( 'Purchase Ticket', 'myticket' );
                            $btn_text_arr[1] = esc_attr__( 'Sold Out', 'myticket' );
                            $btn_text_arr[2] = esc_attr__( 'View More', 'myticket' );
                            myticket_buy_btn( get_the_ID(), 'class="get-ticket"', $btn_text_arr, true ); 

                        } ?>
                    </li>
                </ul>
            </div>
        </section>

        <section class="section-event-single-content">
            <div class="container">
                <div class="row">
                    <div id="primary" class="col-sm-12 col-md-12">

                        <?php if (sizeof($instance['a_repeater'])>0) : ?>
                            <div class="event-features">
                                <ul>
                                    <?php foreach( $instance['a_repeater'] as $item ){

                                        //visibility
                                        if ( $item['visibility'] == '' ){ ?>

                                            <li>
                                                <i class="<?php echo esc_attr($item['icon']); ?>" aria-hidden="true"></i>
                                                <?php echo wp_kses( $item['title'], array( 
                                                    'a' => array(
                                                        'href' => array(),
                                                        'title' => array()
                                                    ),
                                                    'br' => array(),
                                                    'b' => array(),
                                                    'tr' => array(),
                                                    'th' => array(),
                                                    'td' => array(),
                                                    'em' => array(),
                                                    'span' => array(
                                                        'id' => array(),
                                                        'class' => array(),),
                                                    'i' => array( 
                                                        'id' => array(),
                                                        'class' => array(),),
                                                    'strong' => array(),
                                                    'span' => array(
                                                        'href' => array(),
                                                        'class' => array(),
                                                    ),
                                                    'div' => array(
                                                        'id' => array(),
                                                        'class' => array(),
                                                    ),
                                                    ) ); ?>
                                            </li>
                                        <?php }
                                    } ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if( $instance['show_about'] ) : ?>
                            <div class="event-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="event-info-img">
                                            <div id="slider" class="flexslider">

                                                <?php if ( sizeof($image_gallery_arr) > 0 ) : ?>
                                                    <ul class="slides">
                                                        <?php foreach( $image_gallery_arr as $item ){

                                                            $img = wp_get_attachment_image_src( $item, 'myticket-story' );
                                                            echo '<li><img src="'.esc_url($img[0]).'" alt=""></li>';

                                                        } ?>
                                                    </ul>
                                                <?php endif; ?>

                                            </div>
                                            <div id="carousel" class="flexslider">

                                                <?php if ( sizeof($image_gallery_arr) > 0 ) : ?>
                                                    <ul class="slides">
                                                        <?php foreach( $image_gallery_arr as $item ){

                                                            $img = wp_get_attachment_image_src( $item, 'myticket-story' );
                                                            echo '<li><img src="'.esc_url($img[0]).'" alt=""></li>';

                                                        } ?>
                                                    </ul>
                                                <?php endif; ?>

                                            </div>                                  
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="event-info-about">
                                            <h2><?php esc_html_e('About This Event', 'myticket'); ?></h2>
                                            <?php echo the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if( $instance['show_features'] ) : ?>

                            <div class="event-highlights">
                                <?php echo ($instance['features_content']); ?>
                            </div>

                        <?php endif;?>

                        <?php if( $instance['show_map'] ) : ?>

                            <div class="event-location">
                                <h2><?php esc_html_e('Location', 'myticket'); ?></h2>
                                <p><span><?php echo esc_attr($meta['myticket_title'][0]);?></span> <?php echo esc_attr($meta['myticket_address'][0]);?> </p>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

    <?php endif; ?>
    
    <?php if( $instance['show_map'] ) : ?>

        <section class="event-map">

            <?php
            $myticket_coordinates_arr = explode(",", $meta['myticket_coordinates'][0]);
          
            ?>

            <div class="map-contact">
                <div id="map" data-latitude="<?php echo esc_attr( $myticket_coordinates_arr[0] ); ?>" data-longitude="<?php echo esc_attr( $myticket_coordinates_arr[1] ); ?>" data-balloon="<?php echo esc_attr( $meta['myticket_title'][0] ); ?>" data-pointer="<?php echo esc_attr( $instance['type'] ); ?>" data-saturation="<?php echo esc_attr( $instance['saturation'] ); ?>" data-hue="<?php echo esc_attr( $instance['hue'] ); ?>" data-zoom="<?php echo esc_attr( $instance['zoom'] ); ?>"></div>
            </div>

        </section>

    <?php endif; ?>
                   
<?php 
endwhile;

wp_reset_postdata();
        