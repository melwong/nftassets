<section class="section-calendar-events">
    <div class="container">
        <div class="row">

            <?php 
            $price = false;


            if ( $instance['events_per_page'] == '' ){
                $instance['events_per_page'] = -1;
            }
            
            $myticket_args = array(
                'posts_per_page' => $instance['events_per_page'],
                'post_type' => array('product'), //, 'product_variation'
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'meta_query' => array(),
                //Mel: 02/07/19
				//'tax_query' => array('relation'=>'AND'),  
                'meta_key' => 'myticket_datetime',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
				//Mel: 02/07/19. To stop the display of "hidden" products (Catalog visibility: hidden)
				'tax_query'         => array(
					'relation'      => 'AND',
					array(
						'taxonomy'  => 'product_visibility',
						'terms'     => array('exclude-from-catalog'),
						'field'     => 'name',
						'operator'  => 'NOT IN',
					),
				),
            );

            if( $instance['category'] != '' ){

                $temp = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'name',
                    'terms'    => $instance['category'],
                );
                array_push( $myticket_args['tax_query'], $temp ); 
            }

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

            $products = new WP_Query( $myticket_args );
            $states = array();
            while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); //print_r($meta); 

                array_push($states, $meta['myticket_datetime'][0]);

            endwhile;

            if ( $instance['show_header'] ) : ?>

                <?php 
                if ( count($states) ) {  

                    $monthsyears = $months = $years = array();
                    foreach ( $states as $state ) { 

                        $month = date_i18n( 'M', intval( $state ) );
                        $year = date_i18n( 'Y', intval( $state ) );

                        if ( !in_array($month.$year, $monthsyears, true) ){

                            array_push($monthsyears, $month.$year);
                            array_push($months, $month);
                            array_push($years, $year);
                        }
                    } 
                } ?>
                <div class="section-header">
                    <ul class="nav nav-tabs event-tabs" role="tablist">
                        <?php 
                        $i = 1;
                        if ( count($monthsyears) ) { 
                            foreach ( $monthsyears as $monthsyear ) { 

                                $year = $years[$i-1];
                                $month = $months[$i-1]; ?>
                                <li role="presentation" <?php if ( $i==1 ){ echo 'class="active"'; } ?>>
                                    <a href="#tab<?php echo esc_attr($monthsyear);?>" role="tab" data-toggle="tab"><?php echo esc_attr($month); ?><span><?php echo esc_attr($year); ?></span></a>
                                </li>
                                <?php $i++;
                            } 
                        } ?>
                    </ul>
                </div>

            <?php endif; ?>

            <div class="section-content">
                <div class="tab-content">

                    <?php $monthyear = '';
                    if ( $products->have_posts() ) : 
                            while ( $products->have_posts() ) : 

                                $products->the_post(); 
                                $product = wc_get_product(get_the_ID());
                                $meta = get_post_meta( get_the_ID() ); //print_r($meta); 
                                $day = date_i18n( "d", intval( $meta['myticket_datetime'][0] ) );
                                $month = date_i18n( "M", intval( $meta['myticket_datetime'][0] ) );
                                $year = date_i18n( "Y", intval( $meta['myticket_datetime'][0] ) );
                                if ( $monthyear != $month.$year ){

                                    if ( $monthyear != '' ){ echo '</ul></div>'; }
                                    echo '<div role="tabpanel" class="tab-pane '.(($monthyear == '')?"active":"").'" id="tab'.$month.$year.'"><ul class="clearfix">';
                                    $monthyear = $month.$year;
                                } 

                                //get action lik                  
                                $btn_text_arr = [];
                                $btn_text_arr[0] = esc_attr__( 'Get Ticket', 'myticket' );
                                $btn_text_arr[1] = esc_attr__( 'Sold Out', 'myticket' );
                                $btn_text_arr[2] = esc_attr__( 'View More', 'myticket' );
                                ?>
                                
                                <li>
                                    <div class="date">
                                        <a href="#">
                                            <span class="day"><?php echo esc_attr($day); ?></span>
                                            <span class="month"><?php echo esc_attr($month); ?></span>
                                            <span class="year"><?php echo esc_attr($year); ?></span>
                                        </a>
                                    </div>
                                    <?php if($price){ ?>
                                        <div class="price">
                                            <span class="txt"><?php echo $product->get_price_html(); ?></span>
                                        </div>
                                    <?php } ?>
                                    <a href="<?php echo esc_url( $link );?>">
                                        <?php the_post_thumbnail( 'myticket-event', array( 'class' => 'img-responsive' ) ); ?>
                                    </a>
                                    <div class="info">
                                        <p><?php the_title();?> <span><?php echo esc_attr( $meta['myticket_title'][0] ); ?></span></p>
                                        <?php myticket_buy_btn( get_the_ID(), 'class="get-ticket"', $btn_text_arr ); ?>
                                    </div>
                                </li>

                            <?php endwhile; ?>
                                </ul>
                            </div>
                                 
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>

<?php 
wp_reset_postdata();
        