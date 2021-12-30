    <?php 

    if ( $instance['events_per_page'] == '' ){
        $instance['events_per_page'] = 3;
    }

    $myticket_args = array(
        'posts_per_page' => $instance['events_per_page'],
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
    }

    $products = new WP_Query( $myticket_args );

    ?>

    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        
        <section class="section-event-category">
            <div class="container">
                <div class="row">
                    <div class="section-header">
                        <h2><?php echo esc_html( $instance['title'] ); ?></h2>
                    </div>
                    <div class="section-content">
                        <ul class="row clearfix">

                            <?php 
                            $categories_arr = explode(",", $instance['categories']);
        
                            foreach ($categories_arr as $cat) :

                                $category = get_term_by('slug', $cat, 'product_cat');
                                $category = get_term_by('slug', $cat, 'product_cat');
                                $thumbnail_id = "";
                                $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
                                $image = wp_get_attachment_image_src( $thumbnail_id, "myticket-event" );
                                $category_link = get_term_link( $category->term_id, 'product_cat' );
                                $ee++; ?>

                                <li class="category-<?php echo esc_attr( $ee ); ?> col-sm-4" data-category="<?php echo esc_attr($tag->name); ?>">
                                    <?php if ( isset( $image ) ) : ?>
                                        <img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php echo esc_attr( $category->description ); ?>">
                                    <?php endif; ?>                 
                                    <a href="<?php echo esc_url( $category_link ); ?>"><span><?php echo esc_attr($category->name); ?></span></a>
                                </li>

                            <?php endforeach;  ?>   
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    <?php endif; ?>

<?php 
wp_reset_postdata();
        