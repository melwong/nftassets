<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myticket
 */


add_action('wp_ajax_nopriv_myticket_filter_schedule_ajax', 'myticket_filter_schedule_ajax');
add_action('wp_ajax_myticket_filter_schedule_ajax', 'myticket_filter_schedule_ajax');
if ( ! function_exists( 'myticket_filter_schedule_ajax' ) ) {
    function myticket_filter_schedule_ajax() {
        global $woocommerce;
        global $myticket_args;
        global $myticket_show_header;
        global $myticket_search_value;

        $ppp                      = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
        $cat                      = (isset($_POST['cat'])) ? $_POST['cat'] : "";
        $type                     = (isset($_POST['type'])) ? $_POST['type'] : "";
        $myticket_show_header     = (isset($_POST['show_header'])) ? $_POST['show_header'] : "";
        $events_per_page          = (isset($_POST['events_per_page'])) ? $_POST['events_per_page'] : 0;
        $product_order            = (isset($_POST['product_order'])) ? $_POST['product_order'] : "";
        $myticket_search_value    = (isset($_POST['search_value'])) ? $_POST['search_value'] : '';
        $myticket_search_location = (isset($_POST['search_location'])) ? $_POST['search_location'] : '';
        $myticket_search_month    = (isset($_POST['search_month'])) ? $_POST['search_month'] : '';

        $myticket_args = array(
            'posts_per_page' => $events_per_page,
            'post_type' => array('product'), //, 'product_variation'
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'meta_query' => array(),
            'tax_query' => array('relation'=>'AND'),  
            'meta_key' => 'myticket_datetime',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
        );

        //product search query
        if ( strlen( $myticket_search_value ) > 0 ) {

            $myticket_args['s'] = $myticket_search_value;
        }

        //product search location
        if ( strlen( $myticket_search_location ) > 0 ) {

            $myticket_args['meta_key'] = 'myticket_title';
            $myticket_args['meta_value'] = $myticket_search_location;
        }

        //product search period
        if ( strlen( $myticket_search_month ) > 0 ) {

            $dates_arr = explode( "_", $myticket_search_month );
            

            $first = mktime(0,0,0,intval($dates_arr[0]),1,intval($dates_arr[1]));
            $last = mktime(23,59,00,intval($dates_arr[0])+1,0,intval($dates_arr[1]));

            $myticket_period_arr =  array(
                'key' => 'myticket_datetime',
                'value' => array(intval($first), intval($last)),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            );

            array_push( $myticket_args['meta_query'], $myticket_period_arr ); 
        }

        //select event period
        switch ( $type ){

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

        ob_start();

        require get_template_directory() . '/template-parts/ajax-myticket-schedule.php';

        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer);
    }
}