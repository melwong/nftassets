<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myticket
 */


add_action('wp_ajax_nopriv_myticket_filter_list_ajax', 'myticket_filter_list_ajax');
add_action('wp_ajax_myticket_filter_list_ajax', 'myticket_filter_list_ajax');
if ( ! function_exists( 'myticket_filter_list_ajax' ) ) {
    function myticket_filter_list_ajax() {
        global $woocommerce;
        global $myticket_args;
        global $myticket_pagination;
        global $myticket_pagenum_link;
        global $myticket_search_value;

        $ppp                      = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
        $cat                      = (isset($_POST['cat'])) ? $_POST['cat'] : "";
        $tag                      = (isset($_POST['product_tag'])) ? $_POST['product_tag'] : "";
        $events_per_page          = (isset($_POST['events_per_page'])) ? $_POST['events_per_page'] : 0;
        $events_relation          = (isset($_POST['events_relation'])) ? $_POST['events_relation'] : "AND";
        $offset                   = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
        $product_list             = (isset($_POST['product_list'])) ? $_POST['product_list'] : "";
        $product_order            = (isset($_POST['product_order'])) ? $_POST['product_order'] : "";
        $product_pricing_low      = (isset($_POST['product_pricing_low'])) ? $_POST['product_pricing_low'] : 0;
        $product_pricing_high     = (isset($_POST['product_pricing_high'])) ? $_POST['product_pricing_high'] : 1000000;
        $myticket_pagenum_link    = (isset($_POST['pagenum_link'])) ? $_POST['pagenum_link'] : '';
        $myticket_pagination      = (isset($_POST['pagination'])) ? $_POST['pagination'] : '';
        $myticket_search_value    = (isset($_POST['search_value'])) ? $_POST['search_value'] : '';
        $myticket_search_location = (isset($_POST['search_location'])) ? $_POST['search_location'] : '';
        $myticket_search_time     = (isset($_POST['search_time'])) ? $_POST['search_time'] : '';
        $myticket_category_list   = (isset($_POST['product_category_list'])) ? $_POST['product_category_list'] : '';
        $myticket_type            = (isset($_POST['product_type'])) ? $_POST['product_type'] : '';


        $product_pricing_arr =  array(
            'key' => '_price',
            'value' => array(intval(preg_replace('/[^0-9]/', '', $product_pricing_low)), intval(preg_replace('/[^0-9]/', '', $product_pricing_high))),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC'
        );

        $myticket_args = array(
            'posts_per_page' => $events_per_page,
            'post_type' => array('product'), //, 'product_variation'
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'meta_query' => array(),
            'paged' => $paged,
            'tax_query' => array('relation'=>$events_relation),       
        );

        //product order sorting
        switch ( $product_order ) {
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

        //select event period
        if ( strlen( $myticket_search_time ) == 0 ) {
            switch ( $myticket_type ){

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
        }

        //product search query
        if ( strlen( $myticket_search_value ) > 0 ) {

            $myticket_args['s'] = $myticket_search_value;
        }

        //product search location
        if ( strlen( $myticket_search_location ) > 0 ) {

            $myticket_args['meta_key'] = 'myticket_title';
            $myticket_args['meta_value'] = $myticket_search_location;
        }

        //product search time period
        if ( strlen( $myticket_search_time ) > 0 ) {

            $dates_arr = explode( "_", $myticket_search_time );

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

        //filter by categories
        if ( strlen( $myticket_category_list ) > 1 ) {
            $myticket_category_list = trim($myticket_category_list,",");
            $myticket_category_list_arr = explode(",", $myticket_category_list);
            foreach($myticket_category_list_arr as $category){

                $product_cat_arr = array(
                    'taxonomy'     => 'product_cat',
                    'field'        => 'name',
                    'terms'        => esc_attr( $category)
                );
                array_push( $myticket_args['tax_query'], $product_cat_arr );
            }
        }

        array_push( $myticket_args['meta_query'], $product_pricing_arr ); 

        ob_start();

        require get_template_directory() . '/template-parts/ajax-myticket-list.php';

        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer);
    }
}