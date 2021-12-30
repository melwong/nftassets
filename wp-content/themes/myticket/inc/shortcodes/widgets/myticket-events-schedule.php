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

                    <?php $states = $wpdb->get_results(
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

                                    //select event period
                                    switch ( $instance['type'] ){

                                        
                                        case "schedule":

                                            if ( intval( $state->meta_value ) > time() ){

                                                $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                                if ( !in_array($date, $dates, true) ){
                                                    print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . $date . '</option>';
                                                    array_push($dates, $date);
                                                }
                                            }

                                        break; 
                                        case "past":

                                            if ( intval( $state->meta_value ) < time() ){

                                                $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                                if ( !in_array($date, $dates, true) ){
                                                    print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . $date . '</option>';
                                                    array_push($dates, $date);
                                                }
                                            }
                                        break; 
                                        default:

                                            $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                            if ( !in_array($date, $dates, true) ){
                                                print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . $date . '</option>';
                                                array_push($dates, $date);
                                            }
                                        break;
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

<section class="section-full-events-schedule">
    <div class="container">
        <?php if ( $instance['events_per_page'] == '' ){ $instance['events_per_page'] = -1;} ?>
        <div class="row schedule-result-cont" data-category="<?php echo esc_attr( $instance['category'] ); ?>" data-type="<?php echo esc_attr( $instance['type'] ); ?>" data-show_header="<?php echo esc_attr( $instance['show_header'] ); ?>" data-events_per_page="<?php echo esc_attr( $instance['events_per_page'] ); ?>">

            <?php
            global $myticket_args;
            global $myticket_show_header;

            $myticket_show_header = $instance['show_header'];
            $myticket_args = array(
                'posts_per_page' => $instance['events_per_page'],
                'post_type' => array('product'), //, 'product_variation'
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'meta_query' => array(),
                //Mel: 25/08/19. To stop the display of "hidden" products (Catalog visibility: hidden)
				//'tax_query' => array('relation'=>'AND'),  
                'meta_key' => 'myticket_datetime',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
				//Mel: 25/08/19. To stop the display of "hidden" products (Catalog visibility: hidden)
				'tax_query'         => array(
					'relation'      => $instance['relation'],
					array(
						'taxonomy'  => 'product_visibility',
						'terms'     => array('exclude-from-catalog'),
						'field'     => 'name',
						'operator'  => 'NOT IN',
					),
				),//End
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

                case "schedule":

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

            require get_template_directory() . '/template-parts/ajax-myticket-schedule.php'; 

            ?>
           
        </div>
    </div>
</section>

<?php 
wp_reset_postdata();
        