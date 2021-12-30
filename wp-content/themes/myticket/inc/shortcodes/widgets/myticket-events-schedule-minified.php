<?php if( $instance['show_header'] ) : ?>

    <section class="section-refine-search">
        <div class="container">
            <div class="row">
                <form>
                    <div class="keyword col-sm-6 col-md-4">
                        <label><?php esc_attr_e( 'Search Keyword', 'myticket' ) ?></label>
                        <input type="text" class="form-control hasclear myticket-search-value" placeholder="<?php esc_attr_e( 'Search', 'myticket' ) ?>">
                        <span class="clearer">
                            <i class="fa fa-times hvr-wobble-top " aria-hidden="true"></i>
                        </span>
                    </div>
                    <?php 
                    global $wpdb;
                    $states = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_value"
                            ,'myticket_title'
                        )
                    );
                    if (count($states)) {  ?>
                        <div class="location col-sm-6 col-md-3">
                            <label><?php esc_attr_e( 'Location', 'myticket' ) ?></label>
                            <select id="myticket-location" class="selectpicker dropdown myticket-location-value">
                                <option value=""><?php esc_attr_e( 'All Locations', 'myticket' ) ?></option>
                                <?php foreach ($states as $state) { 
                                    print '<option value="'.$state->meta_value.'">' . $state->meta_value . '</option>';
                                } ?>
                            </select>
                        </div>
                    <?php } ?>

                    <?php 
                    $states = $wpdb->get_results(
                        $wpdb->prepare(
                            "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_value"
                            ,'myticket_datetime'
                        )
                    );
                    if (count($states)) {  ?>
                        <div class="event-date col-sm-6 col-md-3">
                            <label><?php esc_attr_e( 'Event Month', 'myticket' ) ?></label>
                            <select id="myticket-month" class="selectpicker dropdown">
                                <option value=""><?php esc_attr_e( 'All Dates', 'myticket' ) ?></option>
                                <?php $dates = array();
                                foreach ($states as $state) { 

                                    $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                    if ( !in_array($date, $dates, true) ){
                                        print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . $date . '</option>';
                                        array_push($dates, $date);
                                    }
                                } ?>
                            </select>
                        </div>
                    <?php } ?>
                    <div class="col-sm-6 col-md-2">
                        <input type="submit" class="myticket-schedule-btn" value="<?php esc_attr_e( 'Search', 'myticket' ) ?>">
                    </div>
                </form>
            </div>
        </div>
    </section>

<?php endif; ?>


    <?php 
    if ( $instance['events_per_page'] == '' ){
        $instance['events_per_page'] = 4;
    }

    $myticket_show_header = $instance['show_header'];
    $myticket_args = array(
        'posts_per_page' => 4,
        'post_type' => array('product'), //, 'product_variation'
        'post_status' => 'publish',
        'ignore_sticky_posts' => 1,
        'meta_query' => array(),
        'tax_query' => array('relation'=>'AND'),  
        'meta_key' => 'myticket_datetime',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
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
        case "today":

            $temp =  array(
                'key'       => 'myticket_datetime',
                'compare'   => '<',
                'value'     => time()+(24*30*60*60),
                'type'      => 'NUMERIC'
            );
            array_push( $myticket_args['meta_query'], $temp ); 

            $temp =  array(
                'key'       => 'myticket_datetime',
                'compare'   => '>',
                'value'     => time(),
                'type'      => 'NUMERIC'
            );
            array_push( $myticket_args['meta_query'], $temp ); 

        break; 
    }

    $day = date_i18n( "d", intval( time() ) );
    $month = date_i18n( "M", intval( time() ) );
    $year = date_i18n( "Y", intval( time() ) );

    $products = new WP_Query( $myticket_args );
    ?>
       
    <section class="section-todays-schedule">
        <div class="container">
            <div class="row">
                <div class="section-header">
                    <h2><?php echo esc_attr( $instance['title'] ); ?></h2>
                    <span class="todays-date"><i class="fa fa-calendar" aria-hidden="true"></i> <strong><?php echo date_i18n( "d", time() );?></strong> <?php echo date_i18n( "F", time() ); ?> <?php echo date_i18n( "Y", time() ); ?> </span>
                </div>
                <div class="section-content">
                    <ul class="clearfix">
						<?php 
     					$daymonthyear = $daymonthyear_sub = ''; $ee = 1;
     					$btn_text_arr = [];
     					$btn_text_arr[0] = esc_attr__( 'Get Ticket', 'myticket' );
     					$btn_text_arr[1] = esc_attr__( 'Sold Out', 'myticket' );
     					$btn_text_arr[2] = esc_attr__( 'View More', 'myticket' );

            			while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); 

                            $day_today = date_i18n( "d", intval( $meta['myticket_datetime'][0] ) );
                            $hide_class = "";
                            if ( $instance['type'] == 'today' && $day_today != $day){ $hide_class = "hide"; }

	     					$time = date_i18n( get_option( 'time_format' ), intval( $meta['myticket_datetime'][0] ) );
							if ( strpos( strtoupper(get_option( 'time_format' )), "A") != -1 )
							    $time_arr = explode( ' ', $time );
	            			?>

	                        <li class="<?php echo esc_attr( $hide_class ); ?> event-<?php echo esc_attr( $ee ); ?>">

	                            <span class="event-time">
	       							<?php if ( sizeof($time_arr) > 0 ){
	                                    echo esc_attr($time_arr[0]).' <strong>'.esc_attr($time_arr[1]).'</strong>'; 
	                                }else{
	                                    echo esc_attr($time);
	                                } ?>
								</span>
	                            <strong class="event-name"><?php echo get_the_title(); ?></strong>
	                            <?php myticket_buy_btn( get_the_ID(), 'class="get-ticket"', $btn_text_arr ); ?>
	                            
	                        </li>

	                        <?php $ee++; ?>
	                    <?php endwhile; ?>
                    </ul>

                    <strong class="event-list-label"><a href="<?php echo esc_url($instance['schedule_link']); ?>" ><?php esc_attr_e( 'Full Event', 'myticket' ); ?></a> <span><?php esc_attr_e( 'Schedules', 'myticket' ); ?></span></strong>
                </div>
            </div>
        </div>
    </section>

<?php 
wp_reset_postdata();
        