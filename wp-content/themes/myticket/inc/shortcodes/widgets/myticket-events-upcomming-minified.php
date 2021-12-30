    <?php 

    if ( $instance['events_per_page'] == '' ){
        $instance['events_per_page'] = 3;
    }

    global $myticket_args;
    global $myticket_pagination;
    global $myticket_pagenum_link;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $myticket_args = array(
        'posts_per_page' => $instance['events_per_page'],
        'post_type' => array('product'), //, 'product_variation'
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'meta_query' => array(),
        'paged' => $paged,
		//Mel: 20/08/19. Added then upgraded to theme 1.0.7.
        //'tax_query' => array('relation'=>'AND'),  
        'meta_key' => 'myticket_datetime',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
		//Mel: 20/08/19. Added then upgraded to theme 1.0.7. To stop the display of "hidden" products (Catalog visibility: hidden)
		'tax_query'         => array(
			'relation'      => 'AND',
			array(
				'taxonomy'  => 'product_visibility',
				'terms'     => array('exclude-from-catalog'),
				'field'     => 'name',
				'operator'  => 'NOT IN',
			),
		), //End
    );

    //select event period
    switch ( $instance['type'] ){

        case "upcomming":

            $temp =  array(
                'key'       => 'myticket_datetime',
                'compare'   => '>',
                'value'     => time(),
                'type'      => 'NUMERIC'
            );
            array_push( $myticket_args['meta_query'], $temp ); 

        break; 
        case "past":

            $temp =  array(
                'key'       => 'myticket_datetime',
                'compare'   => '<',
                'value'     => time(),
                'type'      => 'NUMERIC'
            );
            array_push( $myticket_args['meta_query'], $temp ); 

        break; 
    }

    $product_cat_arr = array(
        'taxonomy'     => 'product_cat',
        'field'        => 'name',
        'terms'        => $instance['category']
    );
    if ( strlen($instance['category']) > 0 )
        array_push( $myticket_args['tax_query'], $product_cat_arr );

    $products = new WP_Query( $myticket_args );

    ?>

    <section class="section-upcoming-events">
        <div class="container">
            <div class="row">

                <?php  if ( $instance['show_header'] ) : ?>

                    <div class="section-header">
                        <h2><?php echo esc_html( $instance['title'] ); ?></h2>
                        <p><?php echo esc_html( $instance['text'] ); ?></p>
                        <a href="<?php echo esc_url( $instance['cta_link'] ); ?>"><?php echo esc_html( $instance['cta_text'] ); ?></a>
                    </div>

                <?php endif;?>

                <div class="section-content">
                    <ul class="clearfix">

                        <?php if ( $products->have_posts() ) : 
                            while ( $products->have_posts() ) : 

                                $products->the_post(); 
                                $product = wc_get_product(get_the_ID());
                                $meta = get_post_meta( get_the_ID() ); //print_r($meta); 
                                $day = date_i18n( "d", intval( $meta['myticket_datetime'][0] ) );
                                $month = date_i18n( "M", intval( $meta['myticket_datetime'][0] ) );
                                $year = date_i18n( "Y", intval( $meta['myticket_datetime'][0] ) );
                                $link = $meta['myticket_link'][0];
                                ?>

                                <li>
                                    <div class="date">
                                        <a href="<?php echo get_permalink( get_the_ID() ); ?>">
                                            <span class="day"><?php echo esc_attr($day); ?></span>
                                            <span class="month"><?php echo esc_attr($month); ?></span>
                                            <span class="year"><?php echo esc_attr($year); ?></span>
                                        </a>
                                    </div>
                                    <?php if( $instance['price'] ){ ?>
                                        <div class="price">
                                            <span class="txt"><?php echo $product->get_price_html(); ?></span>
                                        </div>
                                    <?php } ?>
                                    <a href="<?php echo get_permalink( get_the_ID() ); ?>">
										
										<!--Mel: 14/12/19. Added to make product title clickable. Source: https://bit.ly/2LRtwXF -->
										<a class="title" href="<?php echo get_permalink( get_the_ID() ); ?>">
										<!--Mel: End-->
                                        <?php the_post_thumbnail( 'myticket-event', array( 'class' => 'img-responsive' ) ); ?>
                                    </a>
                                    <div class="info">

                                        <?php 
                                        $btn_text_arr = [];
                                        $btn_text_arr[0] = __( 'Get Ticket', 'myticket' );
                                        $btn_text_arr[1] = __( 'Sold Out', 'myticket' );
                                        $btn_text_arr[2] = __( 'View More', 'myticket' );
                                        ?>
										
										
										<?php myticket_buy_btn( get_the_ID(), 'class="get-ticket"', $btn_text_arr ); //Mel: 13/12/19. To move buy button to the top of product title?>

                                        <p>
										<!--Mel: 14/12/19. Added to make product title clickable. Source: https://bit.ly/2LRtwXF -->
										<a class="title" href="<?php echo get_permalink( get_the_ID() ); ?>">

										<?php echo get_the_title(); ?> <span><?php echo esc_html( $meta['myticket_title'][0] ); ?></span></a></p>
                                        <?php if(isset($link)) { ?>
                                            <a href="<?php echo esc_url($link);?>" class="get-ticket"><?php echo esc_html($btn_text_arr[2]); ?></a>
                                        <?php }else{ 
                                            //Mel: 13/12/19. To move buy button to the top of product title
											//myticket_buy_btn( get_the_ID(), 'class="get-ticket"', $btn_text_arr );
                                        } ?>
                                      
                                    </div>
                                </li>

                            <?php endwhile;  ?> 
                        <?php endif; ?>

                    </ul>
                </div>

                <?php 
                $myticket_pagination = $instance['pagination'];
                $myticket_pagenum_link = get_pagenum_link(999999999);
                ?>
                <?php if( $myticket_pagination ){ myticket_pagination_gallery( 'search-result-footer', $products, $myticket_pagenum_link ); } ?>   
            </div>
        </div>
    </section>




<?php 
wp_reset_postdata();
        