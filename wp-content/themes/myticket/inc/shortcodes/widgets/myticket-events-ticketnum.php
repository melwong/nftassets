<?php 

$myticket_args = array(
    'p' => $instance['event_id'],
    'post_type' => array('product'), //, 'product_variation'
    'post_status' => 'publish',
);

$products = new WP_Query( $myticket_args );

while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); 

    //print_r($meta);
    $_regular_price = $meta['_regular_price'][0];
    if ( $_regular_price == '' ){ $_regular_price = '0'; }
    if ( strlen( $meta['_sale_price'][0] ) > 0 ){ $_regular_price =  $meta['_sale_price'][0]; }

    $image_gallery_arr = explode( ",", $meta['_product_image_gallery'][0] );
    ?>


    <?php if( $instance['show_header'] ) : ?>

        <?php $stock = $meta['_stock'][0];
        if ( $stock == '' ){
            $stock = esc_attr__( 'unlimited tickets', 'myticket' );
        }else{
            $stock = $stock.' '.esc_attr__( ' tickets left', 'myticket' );
        }
        if ( $meta['_stock_status'][0] == 'outofstock' ) {
            $stock = esc_attr__( 'No Tickets Left', 'myticket' );
        } ?>

        <section class="section-featured-header order-tickets-without-seat" style="background-image: url('<?php echo get_the_post_thumbnail_url( $products->post, 'myticket-story-large' ); ?>')">
            <div class="container">
                <div class="section-content">
                    <p><?php echo esc_attr($meta['myticket_title'][0]); ?> <strong><?php echo the_title(); ?></strong> <span><?php echo date_i18n(  get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $meta['myticket_datetime'][0] ) ); ?></span></p>
                    <div class="tickets-left">
                        <i class="fa fa-info-circle" aria-hidden="true"></i><?php echo esc_attr($stock); ?>
                    </div>
                </div>
            </div>
        </section>

    <?php endif; ?>
    
    <section class="section-page-header">
        <div class="container">
            <h1 class="entry-title"><?php echo esc_attr( $instance['subtitle'] ); ?></h1>
        </div>
    </section>

    <section class="section-page-content">
        <div class="container">
            <div class="row">
                <?php if ( class_exists('WooCommerce') ) { ?>
                <div id="primary" class="col-sm-12 col-md-12">
                    <?php wc_print_notices();
                    global $woocommerce;
                    ?> 
                    <div class="section-choose-how-many-tickets">
                        <ul class="ticket-nav">

                            <?php $_product = wc_get_product( get_the_ID() );
                            if( $_product->is_type( 'simple' ) ) { ?>


                                <li><a href="<?php echo esc_url( (($woocommerce->cart->get_cart_url()==get_site_url())?get_site_url()."/cart/":$woocommerce->cart->get_cart_url()) ); ?><?php echo esc_url( $current_url.'?quantity=1&add-to-cart='.$instance['event_id'] ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>1</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>
                                <li><a href="<?php echo esc_url( (($woocommerce->cart->get_cart_url()==get_site_url())?get_site_url()."/cart/":$woocommerce->cart->get_cart_url()) ); ?><?php echo esc_url( $current_url.'?quantity=2&add-to-cart='.$instance['event_id'] ); ?>" data-quantity="2" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>2</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>
                                <li><a href="<?php echo esc_url( (($woocommerce->cart->get_cart_url()==get_site_url())?get_site_url()."/cart/":$woocommerce->cart->get_cart_url()) ); ?><?php echo esc_url( $current_url.'?quantity=3&add-to-cart='.$instance['event_id'] ); ?>" data-quantity="3" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>3</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>
                                <li><a href="<?php echo esc_url( (($woocommerce->cart->get_cart_url()==get_site_url())?get_site_url()."/cart/":$woocommerce->cart->get_cart_url()) ); ?><?php echo esc_url( $current_url.'?quantity=4&add-to-cart='.$instance['event_id'] ); ?>" data-quantity="4" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>4</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>

                        
                            <?php } else {  

                                //$link = get_permalink( $id ); ?>

                                <li><a href="<?php echo get_permalink( $instance['event_id'] );?>" data-quantity="1" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>1</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>
                                <li><a href="<?php echo get_permalink( $instance['event_id'] );?>" data-quantity="2" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>2</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>
                                <li><a href="<?php echo get_permalink( $instance['event_id'] );?>" data-quantity="3" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>3</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>
                                <li><a href="<?php echo get_permalink( $instance['event_id'] );?>" data-quantity="4" data-product_id="<?php echo esc_attr( $instance['event_id'] ); ?>" data-product_sku="" ><span>4</span> <?php esc_attr_e( ' ticket', 'myticket' ); ?></a></li>

                            <?php } ?>
                            <li><a href="<?php echo get_permalink($instance['event_id']);?>"><i class="fa fa-plus" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
                   
<?php 
endwhile;

wp_reset_postdata();
        