<?php

    $products = new WP_Query( $myticket_args );
    $states = array();
    while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); //print_r($meta); 

        array_push($states, $meta['myticket_datetime'][0]);

    endwhile;

    if ( $myticket_show_header ) : ?>

        <?php 
        if ( count($states) ) {  

            $monthsyearsdays = $days = $dofs = $months = $years = array();
            sort($states); 
            foreach ( $states as $state ) { 
                
                $dof = date_i18n( 'l', intval( $state ) );
                $day = date_i18n( 'd', intval( $state ) );
                $month = date_i18n( 'M', intval( $state ) );
                $year = date_i18n( 'Y', intval( $state ) );
                if ( !in_array($day.$month.$year, $monthsyearsdays, true) ){

                    array_push($monthsyearsdays, $day.$month.$year);
                    array_push($dofs, $dof);
                    array_push($days, $day);
                    array_push($months, $month);
                    array_push($years, $year);
                }
            } 
        } ?>
        <div class="section-header">
            <ul class="nav nav-tabs event-tabs" role="tablist">
                <?php 
                $i = 1;
                if ( count($monthsyearsdays) ) { 
                    foreach ( $monthsyearsdays as $monthsyearsday ) { 

                        $dof = $dofs[$i-1];
                        $day = $days[$i-1];
                        $year = $years[$i-1];
                        $month = $months[$i-1]; ?>
                        <li role="presentation" <?php if ( $i==1 ){ echo 'class="active"'; } ?>>
                            <a href="#tab<?php echo  esc_attr($monthsyearsday);?>"  data-toggle="tab">
                                <strong><?php echo esc_attr($dof); ?></strong>
                                <?php echo esc_attr($day); ?>
                                <span><?php echo esc_attr($month.' '.$year); ?></span>
                            </a>
                        </li>
                        <?php $i++;
                    } 
                } ?>
            </ul>
        </div>

    <?php endif; ?>

    <div class="section-content">
        <div class="tab-content">

            <?php 
            $daymonthyear = $daymonthyear_sub = ''; $e = 0; $ee = 1;
            while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); //print_r($meta); 

                $time = date_i18n( get_option( 'time_format' ), intval( $meta['myticket_datetime'][0] ) );
                $time_arr = array();
                if ( strpos( strtoupper(get_option( 'time_format' )), "A") != -1 )
                    $time_arr = explode( ' ', $time );

                $day = date_i18n( 'd', intval( $meta['myticket_datetime'][0] ) );
                $month = date_i18n( 'M', intval( $meta['myticket_datetime'][0] ) );
                $year = date_i18n( 'Y', intval( $meta['myticket_datetime'][0] ) );  

                if ( $day.$month.$year != $daymonthyear ) :

                    $e++; $ee = 1;
                    $daymonthyear_sub = '';
                    if ( $daymonthyear != '' ){ echo '</div></div>'; } ?>

                    <div  class="tab-pane <?php if ( $daymonthyear == '' ){ echo 'active'; } $daymonthyear = $day.$month.$year; ?>" id="tab<?php echo esc_attr($daymonthyear); ?>">
                        <div id="tab_main_cont<?php echo esc_attr( $e ); ?>" class="row" >

                    <?php 
                endif;

                $stock = $meta['_stock'][0];
                if ( $stock == '' ){
                    $stock = esc_attr__( 'Unlimited Tickets', 'myticket' );
                }else{
                    $stock = $stock.' '.esc_attr__( ' Tickets Left', 'myticket' );
                }
                if ( $meta['_stock_status'][0] == 'outofstock' ) {
                    $stock = esc_attr__( 'No Tickets Left', 'myticket' );
                }
                ?>
             
                <ul style="display:none" data-date="<?php echo esc_attr($daymonthyear); ?>"  class="tab_left tab_left_<?php echo esc_attr($daymonthyear); ?>">
                    <li <?php if ( $ee == 1 ) { echo 'class="active"'; } ?> >
                        <a href="#tab<?php echo esc_attr($e.'-hr'.$ee); ?>" aria-controls="tab<?php echo esc_attr($e.'-hr'.$ee); ?>"  data-toggle="tab">
                            <span class="schedule-time">
                                <?php if ( sizeof($time_arr) > 0 ){
                                    echo esc_attr($time_arr[0]).' <strong>'.esc_attr($time_arr[1]).'</strong>'; 
                                }else{
                                    echo esc_attr($time);
                                }
                                ?>
                            </span>
                            <span class="schedule-title"><?php the_title(); ?></span>
                            <span class="schedule-ticket-info"><?php echo esc_attr($stock); ?></span>
                        </a>
                    </li>
                </ul>

                <div id="tab_right_<?php echo esc_attr($daymonthyear.'_'.$ee); ?>" style="display:none" class="tab_right tab_right_<?php echo esc_attr($daymonthyear); ?>" >

                    <div  class="tab-pane <?php if ( $ee == 1 ) { echo 'active'; } ?>" id="tab<?php echo esc_attr($e.'-hr'.$ee); ?>">
                        <?php the_post_thumbnail( 'myticket-schedule', array( 'class' => 'img-responsive' ) ); ?>
                        <div class="full-event-info">
                            <div class="full-event-info-header">
                                <h2><?php the_title(); ?></h2>
                                <span class="ticket-left-info"><?php echo esc_attr($stock); ?></span>
                                <div class="clearfix"></div>
                                <span class="event-date-info"><?php echo date_i18n( 'l, M d Y | '.get_option( 'time_format' ), intval( $meta['myticket_datetime'][0] ) ); ?></span>
                                <span class="event-venue-info"><?php echo esc_html( $meta['myticket_address'][0] ); ?></span>
                            </div>
                            <div class="full-event-info-content">
                                <?php the_excerpt(); ?>
                                <?php $btn_text_arr = [];
                                $btn_text_arr[0] = esc_attr__( 'Book Ticket', 'myticket' );
                                $btn_text_arr[1] = esc_attr__( 'Sold Out', 'myticket' );
                                $btn_text_arr[2] = esc_attr__( 'View More', 'myticket' );
                                myticket_buy_btn( get_the_ID(), 'class="book-ticket"', $btn_text_arr ); ?>
                            </div>
                        </div>
                    </div>

                </div>

                <?php $ee++;

            endwhile; ?> 

                        <!-- tabpanel -->
                        </div>
                    <!-- row -->
                    </div>
        </div>
    </div>       
          