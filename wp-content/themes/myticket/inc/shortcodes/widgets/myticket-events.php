
    <?php if( $instance['show_header'] ) : ?>

        <section class="section-refine-search">
            <div class="container">
                <div class="row">
                    <form>
                        <div class="keyword col-sm-6 col-md-4">
                            <label><?php esc_attr_e( 'Search Keyword', 'myticket' ) ?></label>
                            <input type="text" class="form-control hasclear myticket-search-value" value="<?php echo esc_attr($_GET['event']); ?>" placeholder="<?php esc_attr_e( 'Search', 'myticket' ) ?>">
                            <span class="clearer">
                                <i class="fa fa-times hvr-wobble-top " aria-hidden="true"></i>
                            </span>
                        </div>
                        <?php 
                        global $wpdb;
                        if ( strlen($instance['locations'])>0 ) {

                            $states = explode(",", $instance['locations']);
                            if ( count($states) ) {  ?>
                                <div class="location col-sm-6 col-md-3">
                                    <label><?php esc_attr_e( 'Location', 'myticket' ) ?></label>
                                    <select id="myticket-location" class="selectpicker dropdown myticket-location-value">
                                        <option value=""><?php esc_attr_e( 'All Locations', 'myticket' ) ?></option>
                                        <?php $ee=0; foreach ($states as $state) { 
                                            print '<option value="'.trim($states[$ee]).'">' . trim($states[$ee]) . '</option>';$ee++;
                                        } ?>
                                    </select>
                                </div>
                            <?php }
                        }else{
                            
                            $states = $wpdb->get_results(
                                $wpdb->prepare(
                                    "SELECT DISTINCT meta_value, post_status FROM {$wpdb->postmeta} p LEFT JOIN {$wpdb->posts} o ON o.ID = p.post_id WHERE p.meta_key = %s AND o.post_status = 'publish' ORDER BY p.meta_value"
                                    ,'myticket_title'
                                )
                            );
                            if ( count($states) ) {  ?>
                                <div class="location col-sm-6 col-md-3">
                                    <label><?php esc_attr_e( 'Location', 'myticket' ) ?></label>
                                    <select id="myticket-location" class="selectpicker dropdown myticket-location-value">
                                        <option value=""><?php esc_attr_e( 'All Locations', 'myticket' ) ?></option>
                                        <?php foreach ($states as $state) { 
                                            print '<option value="'.trim($state->meta_value).'">' . trim($state->meta_value) . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            <?php } 
                        }

                        $states = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT DISTINCT meta_value, post_status FROM {$wpdb->postmeta} p LEFT JOIN {$wpdb->posts} o ON o.ID = p.post_id WHERE p.meta_key = %s AND o.post_status = 'publish' ORDER BY p.meta_value"
                                ,'myticket_datetime'
                            )
                        );
                        if (count($states)) {  ?>
                            <div class="event-date col-sm-6 col-md-3">
                                <label><?php esc_attr_e( 'Event Month', 'myticket' ) ?></label>
                                <select id="myticket-time" class="selectpicker dropdown">
                                    <option value=""><?php esc_attr_e( 'All Dates', 'myticket' ) ?></option>
                                    <?php $dates = array();
                                    foreach ($states as $state) { 

                                        switch ( $instance['type'] ){

                                            case "upcomming":

                                                if ( time() < intval( $state->meta_value ) ){

                                                    $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                                    if ( !in_array($date, $dates, true) && $state->post_status=='publish' ){
                                                        print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . $date . '</option>';
                                                        array_push($dates, $date);
                                                    }
                                                }
                        
                                            break; 
                                            case "past":

                                                if ( time() > intval( $state->meta_value ) ){
       
                                                    $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                                    if ( !in_array($date, $dates, true) && $state->post_status=='publish' ){
                                                        print '<option value="'.date_i18n( 'm_Y', intval( $state->meta_value ) ).'">' . $date . '</option>';
                                                        array_push($dates, $date);
                                                    }
                                                }

                                            break; 
                                            default:
                                                    $date = date_i18n( 'M Y', intval( $state->meta_value ) );
                                                    if ( !in_array($date, $dates, true) && $state->post_status=='publish' ){
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
                            <input type="submit" class="myticket-search-btn" value="<?php esc_attr_e( 'Search', 'myticket' ) ?>">
                        </div>
                    </form>
                </div>
            </div>
        </section>

    <?php endif; ?>

    <section class="section-search-content">
        <div class="container">
            <div class="row">

                <?php 
                if ( $instance['relation'] != 'AND' && $instance['relation'] != 'OR' )
                    $instance['relation'] = 'AND';
                
                if ( $instance['sidebar'] == 'sidebar-left' ) :  ?>
                    <div id="secondary" class="<?php if ( $instance['sidebar_size'] == 'sidebar-min' ){ echo 'col-md-4 col-lg-3'; }else{ echo 'col-sm-12 col-md-4'; } ?>">

                        <div class="search-filter">

                            <?php if ( is_active_sidebar( $instance['widget'] ) ) : ?>
                                <ul id="myticket-sidebar">
                                    <?php dynamic_sidebar( $instance['widget'] ); ?>
                                </ul>
                            <?php endif; ?>

                        </div>

                    </div>
                <?php endif; ?>

                <div id="primary" class="<?php if ( $instance['sidebar'] == 'sidebar-none' ) { echo 'col-md-12 col-lg-12'; }else{ if ( $instance['sidebar_size'] == 'sidebar-min' ){ echo 'col-md-8 col-lg-9'; }else{ echo 'col-sm-12 col-md-8'; } } ?>  myticket-content" data-pagenum_link="<?php echo get_pagenum_link(999999999); ?>" data-events_per_page="<?php echo esc_attr( $instance['events_per_page'] ); ?>" data-pagination="<?php echo esc_attr( $instance['pagination'] ); ?>" data-list_style="<?php echo esc_attr( $instance['list_style'] );?>" data-type="<?php echo esc_attr( $instance['type'] ); ?>" data-category="<?php echo esc_attr( $instance['category'] ); ?>" data-relation="<?php echo esc_attr( $instance['relation'] ); ?>">
                
                    <div class="search-result-header">
                        <div class="row">
                            <div class="col-sm-7">
                                <h2 id="myticket-sri-cont" data-all="<?php esc_attr_e( 'Showing all records', 'myticket' ) ?>" data-search="<?php esc_attr_e( 'Search results for', 'myticket' ) ?>"> </h2>
                                <span id="myticket-search-numbers-info"><?php esc_attr_e( 'Showing', 'myticket' ) ?> <span id="myticket_prr" ></span> <?php esc_attr_e( 'of', 'myticket' ) ?> <span id="myticket_pcr" ></span> <?php esc_attr_e( 'Results', 'myticket' ) ?></span>
                            </div>
                            <div class="col-sm-5">
                                <label><?php esc_attr_e( 'Sort By:', 'myticket' ) ?></label>
                                <select id="myticket-sorting" class="cat selectpicker dropdown" data-active="<?php if ( isset($_COOKIE['product_order']) ){ $instance['orderby'] = $_COOKIE['product_order']; } echo esc_attr( $instance['orderby'] );?>">
                                    <option value=""><?php esc_attr_e( 'Default', 'myticket' ) ?></option>
                                    <option <?php if ( $instance['orderby'] == 'alphabetical' ){ echo "selected"; } ?> value="alphabetical"><?php esc_attr_e( 'Alphabetically', 'myticket' ) ?></option>
                                    <option <?php if ( $instance['orderby'] == 'popularity' ){ echo "selected"; } ?> value="popularity"><?php esc_attr_e( 'Popularity', 'myticket' ) ?></option>
                                    <option <?php if ( $instance['orderby'] == 'rating' ){ echo "selected"; } ?> value="rating"><?php esc_attr_e( 'Rating', 'myticket' ) ?></option>
                                    <option <?php if ( $instance['orderby'] == 'newest' ){ echo "selected"; } ?> value="newest"><?php esc_attr_e( 'Newest', 'myticket' ) ?></option>
                                    <option <?php if ( $instance['orderby'] == 'lowestprice' ){ echo "selected"; } ?> value="lowestprice"><?php esc_attr_e( 'Lowest Price', 'myticket' ) ?></option>
                                    <option <?php if ( $instance['orderby'] == 'highestprice' ){ echo "selected"; } ?> value="highestprice"><?php esc_attr_e( 'Highest Price', 'myticket' ) ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="search-result-cont">
                        <?php 
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
                            //Mel: 20/08/19. Added when upgraded to latest 1.0.7 theme.
							//'tax_query' => array('relation'=>$instance['relation']), 
                            'meta_key' => 'myticket_datetime',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
							//Mel: 20/08/19. Added when upgraded to latest 1.0.7 theme. To stop the display of "hidden" products (Catalog visibility: hidden)
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

                        //category filter
                        $myticket_category_list_arr = explode(",", $_COOKIE['product_category_list']);
                        if ( strlen( $instance['category'] ) > 1 )
                            array_push($myticket_category_list_arr, $instance['category']);

                        $myticket_category_list_arr = array_unique($myticket_category_list_arr);
                        foreach($myticket_category_list_arr as $category){

                            $product_cat_arr = array(
                                'taxonomy'     => 'product_cat',
                                'field'        => 'name',
                                'terms'        => esc_attr( $category )
                            );
                            if ( strlen($category) > 0 )
                                array_push( $myticket_args['tax_query'], $product_cat_arr );
                        }
                
                        //custom string search
                        if ( $_GET['event'] != '' ){
                            $myticket_args['s'] = $_GET['event'];
                        }

                        //product order sorting
                        switch ( $instance['orderby'] ) {
                            case 'lowestprice':
                                $myticket_args['meta_key'] = '_price';  
                                $myticket_args['orderby'] = array( 'meta_value_num' => 'ASC' );  
                            break;
                            case 'highestprice':
                                $myticket_args['meta_key'] = '_price';  
                                $myticket_args['orderby'] = array( 'meta_value_num' => 'DESC' );  
                            break;
                            case 'newest':
                                $myticket_args['orderby'] = array( 'date' => 'DESC' );   
                            break;
                            case 'popularity':
                                $myticket_args['orderby'] = 'meta_value_num';  
                                $myticket_args['order'] = 'DESC';
                                $myticket_args['orderby_meta_key'] = 'total_sales';
                            break;
                            case 'rating':
                                $myticket_args['orderby'] = 'meta_value_num';  
                                $myticket_args['order'] = 'DESC';
                                $myticket_args['orderby_meta_key'] = '_wc_average_rating';
                            break;
                            case 'alphabetical':
                                $myticket_args['orderby'] = 'title';
                                $myticket_args['order'] = 'ASC';
                            break;
                        }
                        $myticket_pagination = $instance['pagination'];
                        $myticket_pagenum_link = get_pagenum_link(999999999);
                        require get_template_directory() . '/template-parts/ajax-myticket-list.php';  ?>
                    </div>
                </div>

                <?php if ( $instance['sidebar'] == 'sidebar-right' ) : ?>

                    <div id="secondary" class="<?php if ( $instance['sidebar_size'] == 'sidebar-min' ){ echo 'col-md-4 col-lg-3'; }else{ echo 'col-sm-12 col-md-4'; } ?>">

                        <?php if ( is_active_sidebar( $instance['widget'] ) ) : ?>
                            <ul id="myticket-sidebar">
                                <?php dynamic_sidebar( $instance['widget'] ); ?>
                            </ul>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php wp_reset_postdata(); ?>
