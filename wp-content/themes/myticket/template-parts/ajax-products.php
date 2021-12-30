<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myticket
 */

add_action('wp_ajax_nopriv_myticket_product_modal_ajax', 'myticket_product_modal_ajax');
add_action('wp_ajax_myticket_product_modal_ajax', 'myticket_product_modal_ajax');
if ( ! function_exists( 'myticket_product_modal_ajax' ) ) {
    function myticket_product_modal_ajax() {
        global $woocommerce;

        $id       = (isset($_POST['id'])) ? $_POST['id'] : "";
        $_product = wc_get_product( $id );

        ob_start(); 

        $metering = get_theme_mod( 'myticket_metering', 'g' );

        global $wp;
        $current_url = home_url(add_query_arg(array(),$wp->request));

        $myticket_title = get_post_meta( $_product->id, 'myticket_title', '');
        if( sizeof($myticket_title)==0 ) $myticket_title[0] = '';

        $myticket_calories = get_post_meta( $_product->id, 'myticket_calories', '');
        if( sizeof($myticket_calories)==0 ) $myticket_calories[0] = '';

        $myticket_proteins = get_post_meta( $_product->id, 'myticket_proteins', '');
        if( sizeof($myticket_proteins)==0 ) $myticket_proteins[0] = '';

        $myticket_fats = get_post_meta( $_product->id, 'myticket_fats', '');
        if( sizeof($myticket_fats)==0 ) $myticket_fats[0] = '';

        $myticket_carbohydrates = get_post_meta( $_product->id, 'myticket_carbohydrates', '');
        if( sizeof($myticket_carbohydrates)==0 ) $myticket_carbohydrates[0] = '';

        $myticket_ingredients = get_post_meta( $_product->id, 'myticket_ingredients', '');
        if( sizeof($myticket_ingredients)==0 ) $myticket_ingredients[0] = '';

        ?>

        <!-- Modal -->
          <div class="modal-dialog" role="document">
            <div class="modal-content">              
              <div class="modal-body">
                <figure>
                    <?php echo ($_product->get_image( 'myticket-blog' )); ?>
                </figure>
                
                <!--inner starts-->
                <div class="inner">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 top text-sp">
                            <span class="price pull-right"><?php echo wc_price( $_product->get_price() ); ?></span>
                            <h3 class="product-single-modal"><?php echo esc_html($_product->post->post_title); ?>
                                <span><?php echo esc_attr($myticket_title[0]); ?></span>
                            </h3>
                        </div>

                        <div class="col-xs-12 col-sm-12"><hr></div>

                        <div class="col-xs-12 col-sm-12 content">
                            <p> 
                                <?php echo wp_kses( $_product->post->post_excerpt, array( 
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
                            </p>
                        </div>

                        <div class="col-xs-12 col-sm-6 ingredients">
                            <h6><?php esc_html_e('INGREDIENTS','myticket');?></h6>
                            <ul>
                                <?php $myticket_ingredients_arr = explode( PHP_EOL, $myticket_ingredients[0] );
                                foreach($myticket_ingredients_arr as $ingredient){

                                    echo '<li>'.$ingredient.'</li>';
                                } ?>
                            </ul>
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <h6><?php esc_html_e('NUTRITION FACTS','myticket');?></h6>     
                            <div class="facts-table">                                
                                <table>
                                    <tbody>
                                        <?php if( $myticket_calories[0]!='' ) :?>
                                        <tr>
                                            <td><span><?php echo esc_html__('Calories', 'myticket'); ?></span></td>
                                            <td><span><?php echo esc_attr($myticket_calories[0].$metering); ?></span></td>
                                        </tr>
                                        <?php endif;?>
                                        <?php if( $myticket_proteins[0]!='' ) :?>
                                        <tr>
                                            <td><span><?php echo esc_html__('Proteins', 'myticket'); ?></span></td>
                                            <td><span><?php echo esc_attr($myticket_proteins[0].$metering); ?></span></td>
                                        </tr>
                                        <?php endif;?>
                                        <?php if( $myticket_fats[0]!='' ) :?>
                                        <tr>
                                            <td><span><?php echo esc_html__('Fats', 'myticket'); ?></span></td>
                                            <td><span><?php echo esc_attr($myticket_fats[0].$metering); ?></span></td>
                                        </tr>
                                        <?php endif;?>
                                        <?php if( $myticket_carbohydrates[0]!='' ) :?>
                                        <tr>
                                            <td><span><?php echo esc_html__('Carbohydrates', 'myticket'); ?></span>  </td>
                                            <td><span><?php echo esc_attr($myticket_carbohydrates[0].$metering); ?></span></td>
                                        </tr>
                                        <?php endif;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--inner ends-->
                <div class="woocommerce product-single-modal">
                    <a href="<?php echo esc_url( $current_url.'?add-to-cart='.$_product->id ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $_product->id ); ?>" data-product_sku="" class="button product_type_simple add_to_cart_button ajax_add_to_cart btn hvr-wobble-top btn-big " ><?php esc_attr_e( 'ADD TO CART', 'myticket' ) ?></a> 
                </div>    
              </div>
            </div>
            <!--model content ends-->
          </div>

        <!--model ends-->
        <?php
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}

add_action('wp_ajax_nopriv_myticket_get_order_data_ajax', 'myticket_get_order_data_ajax');
add_action('wp_ajax_myticket_get_order_data_ajax', 'myticket_get_order_data_ajax');
if ( ! function_exists( 'myticket_get_order_data_ajax' ) ) {
    function myticket_get_order_data_ajax() {
        global $woocommerce;

        $id       = (isset($_POST['id'])) ? $_POST['id'] : "";
        $item_id  = (isset($_POST['item_id'])) ? $_POST['item_id'] : "";
        $token    = (isset($_POST['token'])) ? $_POST['token'] : "";

        //check if backend is restricted to certain app IDs only. Customizer > MyTicket section
        $myticket_app_private = get_theme_mod( 'myticket_app_private', 0 );
        $myticket_app_ids = get_theme_mod( 'myticket_app_ids', '' );
        if( 1 == $myticket_app_private ):
            $pos = strrpos($myticket_app_ids, $token);
            if ($pos === false) { 
                
                ob_start();
                $output['success'] = false;
                $output['code'] = 401;
                $output['reason'] = 'Unauthorized';
                echo json_encode($output);   
                $buffer = ob_get_clean();
                wp_reset_postdata();
                wp_die($buffer); 
            }
        endif;

        $order = new WC_Order( $id );
 
        ob_start();
        $output = [];
        $output['success'] = true;
        $output['id'] = $id;
        $output['item_id'] = $item_id;
        $output['order_status'] = $order->get_status();
        $output['order_total'] = $order->get_total();
        $output['order_meta'] = get_post_meta($_POST['id']);
        $output['order_items'] = [];

        foreach ($order->get_items() as $key => $lineItem) {
            $order_items = array('name' => $lineItem['name'], 'id' => $lineItem->get_id(), 'quantity' => $lineItem['quantity'], 'meta_data' => $lineItem->get_meta_data());
            array_push( $output['order_items'], $order_items );    
        }

        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}

add_action('wp_ajax_nopriv_myticket_set_order_status_ajax', 'myticket_set_order_status_ajax');
add_action('wp_ajax_myticket_set_order_status_ajax', 'myticket_set_order_status_ajax');
if ( ! function_exists( 'myticket_set_order_status_ajax' ) ) {
    function myticket_set_order_status_ajax() {
        global $woocommerce;

        //check if backend is restricted to certain apps only.
        $myticket_app_private = get_theme_mod( 'myticket_app_private', '' );

        $output   = [];
        $id       = (isset($_POST['id'])) ? $_POST['id'] : "";
        $item_id  = (isset($_POST['item_id'])) ? $_POST['item_id'] : "";
        $status   = (isset($_POST['status'])) ? $_POST['status'] : "";
        $token    = (isset($_POST['token'])) ? $_POST['token'] : "";

        $order = new WC_Order($id);
        $validated = true;
        if($item_id==""){

            //combined ticket
            $order->update_status($status, 'Updated from MyTicket Scanner App');
            $output['order_status'] = $order->get_status();

            //validate all individual tickets automatically
            foreach ($order->get_items() as $key => $lineItem) {

                wc_update_order_item_meta($lineItem->get_id(), "status", $status);
                wc_update_order_item_meta($lineItem->get_id(), "device ID", $token);
            }
        }else{

            //split ticket
            wc_update_order_item_meta($item_id, "status", $status);
            wc_update_order_item_meta($item_id, "device ID", $token);
            $output['order_status'] = $status;

            //check if all separately sold tickets are validated
            foreach ($order->get_items() as $key => $lineItem) {

                $custom_field = wc_get_order_item_meta( $lineItem->get_id(), 'status', true ); 
                if($custom_field!='validated'){
                    $validated = false;
                }
            }

            //mark all order as validated if all separate tickets are validated
            if($validated){
                $order->update_status($status, 'Updated from MyTicket App ('.$token.')');
            }else{
                if($status!='validated')
                    $order->update_status($status, 'Updated from MyTicket App ('.$token.')');
            }
        }

        ob_start();
        
        $output['success'] = true;
        $output['id'] = $id;
        $output['validated'] = $validated;
        $output['item_id'] = $item_id;
        
        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}

add_action('wp_ajax_nopriv_myticket_cart_data_ajax', 'myticket_cart_data_ajax');
add_action('wp_ajax_myticket_cart_data_ajax', 'myticket_cart_data_ajax');
if ( ! function_exists( 'myticket_cart_data_ajax' ) ) {
    function myticket_cart_data_ajax() {
        global $woocommerce;

        ob_start();
        $output = [];
        $output['success'] = true;
        $output['cart_contents_count'] = WC()->cart->get_cart_contents_count();

        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}

//user not logged in and logged in hook
add_action('wp_ajax_nopriv_myticket_more_products_ajax', 'myticket_more_products_ajax');
add_action('wp_ajax_myticket_more_products_ajax', 'myticket_more_products_ajax');
if ( ! function_exists( 'myticket_more_products_ajax' ) ) {
    function myticket_more_products_ajax() {
        global $woocommerce;

    
        $ppp     = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
        $cat     = (isset($_POST['cat'])) ? $_POST['cat'] : 0;
        $offset  = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
        $id      = (isset($_POST['post_id'])) ? $_POST['post_id'] : "";
        $limit   = 8;

        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $ppp,
            'cat'            => $cat,
            'offset'         => $offset,
        );

        // Related products are found from category and tag
        $tags_array = array(0);
        $cats_array = array(0);

        // Get tags
        $terms = wp_get_post_terms($id, 'product_tag');
        foreach ( $terms as $term ) $tags_array[] = $term->term_id;

        // Get categories (removed by NerdyMind)
        //*
        $terms = wp_get_post_terms($id, 'product_cat');
        foreach ( $terms as $term ) $cats_array[] = $term->term_id;

        // Don't bother if none are set
        if ( sizeof($cats_array)==1 && sizeof( $tags_array)==1 ) return array();

        // Meta query
        $meta_query = array();
        $meta_query[] = $woocommerce->query->visibility_meta_query();
        $meta_query[] = $woocommerce->query->stock_status_meta_query();

        // Get the posts
        $related_posts = get_posts( apply_filters('woocommerce_product_related_posts', array(
            'posts_per_page' => $limit,
            'post_type'      => 'product',
            'offset'         => $offset,
            'meta_query'     => $meta_query,
            'tax_query'      => array(
                'relation'      => 'OR',
                array(
                    'taxonomy'     => 'product_cat',
                    'field'        => 'id',
                    'terms'        => $cats_array
                ),
                array(
                    'taxonomy'     => 'product_tag',
                    'field'        => 'id',
                    'terms'        => $tags_array
                )
            )
        ) ) );


        ob_start();
        $_pf = new WC_Product_Factory(); 
        foreach ( $related_posts as $post_item ) : setup_postdata( $post_item ); 
        $product_related = $_pf->get_product($post_item->ID); ?>

                <div class="item col-sm-3 col-xs-12">
                    <div class="product-img position-relative">
                        <a href="<?php echo get_post_permalink($post_item->ID); ?>">
                            <img id="zoom" class="img-responsive" src="<?php echo get_the_post_thumbnail_url( $post_item, 'myticket-related' ); ?>" alt="<?php echo esc_html( $post_item->post_title ); ?>"/>
                        </a>
                        <div class="icon-wishlist position-absolute ">
                            <?php if( YITH_WCWL()->is_product_in_wishlist( $product_related->id ) ) : ?>  
                                <a title="<?php esc_attr_e( 'Already in wishlist', 'myticket' ) ?>" href="?add_to_wishlist=<?php echo esc_attr( $product_related->id ); ?>">
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                </a> 
                            <?php else: ?>
                                <a title="<?php esc_attr_e( 'Add to wishlist', 'myticket' ) ?>" href="?add_to_wishlist=<?php echo esc_attr( $product_related->id ); ?>">
                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                </a> 
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-content text-item">
                        <h5 class="product-edit">
                            <a href="<?php echo get_post_permalink($post_item->ID); ?>" class="font-montserrat font16"><?php echo esc_attr( $post_item->post_title ); ?></a>
                        </h5>
                        <p class="product-edit1 price font-montserrat font16"><?php echo wc_price($product_related->get_display_price()); ?></p>
                    </div>
                </div>

        <?php endforeach; 
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer);
    }
}

