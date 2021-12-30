<?php
    
    /**
     * myticket_identities widget class
     *
     * @since 1.0.0
     */
    
    add_action( 'widgets_init', 'identities' );
    
    function identities() {
        
        register_widget( 'myticket_identities' );
    }

    class myticket_identities extends WP_Widget {
        
        function __construct() {
            $widget_ops = array( 'classname' => 'myticket_popular_tags', 'description' => esc_html( 'A widget that displays records registered as identities', 'myticket' ) );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'myticket_identities' );
            parent::__construct( 'myticket_identities', esc_html( 'MyTicket Identities', 'myticket'), $widget_ops, $control_ops );
        }
        
        function widget($args, $instance) {
            
            $cache = wp_cache_get( 'widget_identities', 'widget' );
            
            if ( !is_array($cache) ){
                $cache = array();
            }
            
            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            
            // if ( isset( $cache[ $args['widget_id'] ] ) ) {
            //     echo esc_html( $cache[ $args['widget_id'] ] );
            //     return;
            // }
            
            ob_start();
            extract($args);
            
            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html( 'Identities', 'myticket' ) : $instance['title'], $instance, $this->id_base );
            if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
                $number = 4;
            }

            $category = $instance['category'];
            $featured = $instance['featured'];
            ?>
            <?php echo $before_widget; ?>
            <?php
            // $meta_query   = WC()->query->get_meta_query();
            // $meta_query[] = array(
            //     'key'   => '_featured',
            //     'value' => 'yes'
            // );
            $myticket_args = array(
                'post_status'    => 'publish',
                'post_type'      => 'identities',
                'posts_per_page' => $number,
                'category_name'  => $category,
            );

            $myticket_args_featured = array(
                'post_status'    => 'publish',
                'post_type'      => 'identities',
                'posts_per_page' => '1',
                'p'              => $featured,
                //'category_name'  => 'Featured',
            );
            ?>

            <?php $loop = new WP_Query( $myticket_args_featured );
                while ( $loop->have_posts() ) : $loop->the_post();  $meta = get_post_meta( get_the_ID() ); ?>

                    <?php $myticket_cta_text = $meta['myticket_cta_text'][0];
                    $myticket_cta_link = $meta['myticket_cta_link'][0];
                    if ( $myticket_cta_text == '' ){
                        $myticket_cta_text = esc_html( 'Follow', 'myticket' );
                    } 
                    if ( $myticket_cta_link == '' ){
                        $myticket_cta_link = '#';
                    } 

                    ?>

                    <div class="artist-details">
                      
                        <?php if ( has_post_thumbnail( $loop->post->ID ) ) 
                            echo get_the_post_thumbnail( $loop->post->ID, 'myticket-event' ); 
                        else 
                            echo '<img src="' . woocommerce_placeholder_img_src() . '" alt="<?php echo the_title(); ?>" width="70px" height="70px" />'; 
                        ?>
                  
                        <!--<img src="images/artist-img-profile.jpg" alt="<?php echo the_title(); ?>">-->
                        <div class="artist-details-title">
                            <h3><?php echo the_title(); ?></h3>
                            <a href="<?php esc_url( $myticket_cta_link ); ?>"><?php echo esc_html( $myticket_cta_text ); ?></a>
                        </div>
                        
                        <div class="artist-details-info">
                            <h4><?php echo esc_html( $meta['myticket_title'][0] ); ?></h4>
                            <p><?php echo esc_html( $meta['myticket_desc'][0] ); ?></p>
                        </div>
                    </div>

                <?php endwhile;
            wp_reset_query(); ?>

            <div class="related-artist">

                <h3><?php echo esc_html( $title ); ?></h3>
                <ul class="related-artist-list">

                    <?php $loop = new WP_Query( $myticket_args );
                        while ( $loop->have_posts() ) : $loop->the_post();  $meta = get_post_meta( get_the_ID() ); ?>

                            <?php $myticket_cta_text = $meta['myticket_cta_text'][0];
                            if ( $myticket_cta_text == '' ){
                                    $myticket_cta_text = esc_html( 'Follow', 'myticket' );
                            } ?>

                            <li class="related-artist-item">
                                <div class="related-artist-img col-md-12 col-lg-4">
                                    <a href="<?php echo esc_url( $meta['_cta_link'][0] ); ?>">
                                        <?php if ( has_post_thumbnail( $loop->post->ID ) ) 
                                            echo get_the_post_thumbnail( $loop->post->ID, 'myticket-mini' ); 
                                        else 
                                            echo '<img src="' . woocommerce_placeholder_img_src() . '" alt="<?php echo the_title(); ?>" width="70px" height="70px" />'; 
                                        ?>
                                    </a>
                                </div>
                                <div class="related-artist-info col-md-12 col-lg-8">
                                    <h4>
                                        <a href="<?php echo esc_url( $meta['myticket_cta_link'][0] ); ?>"><?php echo the_title(); ?></a>
                                    </h4>
                                    <a href="<?php echo esc_url( $meta['myticket_cta_link'][0] ); ?>"><?php echo esc_html( $myticket_cta_text ); ?></a>
                                </div>
                            </li>

                        <?php endwhile;
                    wp_reset_query(); ?>

<!--
                    <li class="related-artist-item">
                        <div class="related-artist-img col-md-12 col-lg-4">
                            <a href="#"><img src="images/related-artist-2.jpg" alt="image"></a>
                        </div>
                        <div class="related-artist-info col-md-12 col-lg-8">
                            <h4><a href="#">Elizabeth Simmons</a></h4>
                            <a href="#">Follow</a>
                        </div>
                    </li>
                    <li class="related-artist-item">
                        <div class="related-artist-img col-md-12 col-lg-4">
                            <a href="#"><img src="images/related-artist-3.jpg" alt="image"></a>
                        </div>
                        <div class="related-artist-info col-md-12 col-lg-8">
                            <h4><a href="#">Christina Guerrero</a></h4>
                            <a href="#">Follow</a>
                        </div>
                    </li>
                    <li class="related-artist-item">
                        <div class="related-artist-img col-md-12 col-lg-4">
                            <a href="#"><img src="images/related-artist-4.jpg" alt="image"></a>
                        </div>
                        <div class="related-artist-info col-md-12 col-lg-8">
                            <h4><a href="#">Michelle Cunningham</a></h4>
                            <a href="#">Follow</a>
                        </div>
                    </li>
                    -->
                </ul>
            </div>

            <?php echo $after_widget; ?>
            <?php
            wp_reset_postdata();
           

            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set( 'widget_identities', $cache, 'widget' );
        }
        
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            print_r($new_instance);
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['category'] = strip_tags( $new_instance['category'] );
            $instance['featured'] = strip_tags( $new_instance['featured'] );
            $instance['number'] = (int) $new_instance['number'];
            $this->flush_widget_cache();
            
            $alloptions = wp_cache_get( 'alloptions', 'options' );
            if ( isset( $alloptions['widget_popular_entries'] ) )
                delete_option( 'widget_popular_entries' );
            
            return $instance;
        }
        
        function flush_widget_cache() {
            wp_cache_delete( 'widget_identities', 'widget' );
        }
        
        function form( $instance ) {
            $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
            $category  = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
            $featured  = isset( $instance['featured'] ) ? esc_attr( $instance['featured'] ) : '';
            $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5; ?>
            <p>
                <label for="<?php echo esc_html($this->get_field_id( 'title' )); ?>"><?php echo esc_html( 'Title:', 'myticket' ); ?></label>
                <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'title' )); ?>" name="<?php echo esc_html($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo esc_html($this->get_field_id( 'featured' )); ?>"><?php echo esc_html( 'Featured ID:', 'myticket' ); ?></label>
                <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'featured' )); ?>" name="<?php echo esc_html($this->get_field_name( 'featured' )); ?>" type="number" value="<?php echo $featured; ?>" />
            </p>
            <p>
                <label for="<?php echo esc_html($this->get_field_id( 'category' )); ?>"><?php echo esc_html( 'Category:', 'myticket' ); ?></label>
                <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'category' )); ?>" name="<?php echo esc_html($this->get_field_name( 'category' )); ?>" type="text" value="<?php echo $category; ?>" />
            </p>
            <p>
                <label for="<?php echo esc_html($this->get_field_id( 'number' )); ?>"><?php echo esc_html( 'Max records:', 'myticket' ); ?></label>
                <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'number' )); ?>" name="<?php echo esc_html($this->get_field_name( 'number' )); ?>" type="number" value="<?php echo $number; ?>" />
            </p>

<!--        <p>
                <label for="<?php echo esc_html($this->get_field_id( 'featured' )); ?>"></label>
                <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'featured' )); ?>" name="<?php echo esc_html($this->get_field_name( 'featured' )); ?>" type="checkbox" checked="<?php if ( $featured ) echo 'true'; ?>" value="<?php echo $featured; ?>" />
                <?php echo esc_html( "dd".$featured.'If checked only first identities record is taken from the list and displayed as featured.', 'myticket' ); ?>
            </p> -->
            <p>
                <label for="<?php echo $this->get_field_id( 'featured' ); ?>"><?php echo esc_html( 'Go to Identities > Add New in order to add new records to this widget', 'myticket' ); ?></label>
            </p>
            <?php
        }
    }
?>