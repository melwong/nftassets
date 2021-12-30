<?php
    
    /**
     * myticket_categories widget class
     *
     * @since 1.0.0
     */
    
    add_action( 'widgets_init', 'myticket_categories' );
    
    function myticket_categories() {
        
        register_widget( 'myticket_categories' );
    }

    class myticket_categories extends WP_Widget {
        
        function __construct() {
            $widget_ops = array( 'classname' => 'myticket_categories', 'description' => esc_html__( 'MyTicket categories filter widget', 'myticket' ) );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'myticket_categories' );
            parent::__construct( 'myticket_categories', esc_html__( 'MyTicket categories filter widget', 'myticket'), $widget_ops, $control_ops );
        }
        
        function widget($args, $instance) {
            
            $cache = wp_cache_get( 'myticket_categories_widget', 'widget' );
            
            if ( !is_array($cache) ){
                $cache = array();
            }
            
            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            
            if ( isset( $cache[ $args['widget_id'] ] ) ) {
                echo $cache[ $args['widget_id'] ];
                return;
            }
            
            ob_start();
            extract($args);
            
            $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Price', 'myticket' ) : $instance['title'], $instance, $this->id_base );
            if ( empty( $instance['number_step'] ) || ! $number = absint( $instance['number_step'] ) ) {
                $instance['number_step'] = 5;
            }
            ?>
            <?php echo $before_widget; ?>

            <?php
            $categories = explode( ",", $instance['categories'] );
            $product_category_list = explode( ",", $_COOKIE['product_category_list'] );
            ?>

            <div class="search-filter-seat-features">
                <h3><?php echo esc_html( $instance['title'] ); ?></h3>
                <div class="checkbox">
                    <input class="styled" type="checkbox" checked="">
                    <label>
                        <?php esc_html_e( 'All', 'myticket'); ?>
                    </label>
                </div>
                <?php if ( sizeof($categories) > 0 ) : 
                    foreach ( $categories as $category ) : ?>

                        <div class="checkbox">
                            <input <?php if ( in_array(trim($category),$product_category_list) ){ echo 'checked';} ?> class="styled myticket-widget-category-checkbox" data-category="<?php echo esc_attr( trim($category) ); ?>" type="checkbox">
                            <label >
                                <?php echo esc_attr( trim($category) ); ?>
                            </label>
                        </div>

                    <?php endforeach;
                endif; ?>
            </div>

            <?php echo $after_widget; ?>
            <?php
            wp_reset_postdata();

            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set( 'myticket_categories_widget', $cache, 'widget' );
        }
        
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['categories'] = strip_tags( $new_instance['categories'] );

            $this->flush_widget_cache();
            
            $alloptions = wp_cache_get( 'alloptions', 'options' );
            if ( isset( $alloptions['widget_popular_entries'] ) )
                delete_option( 'widget_popular_entries' );
            
            return $instance;
        }
        
        function flush_widget_cache() {
            wp_cache_delete( 'myticket_categories_widget', 'widget' );
        }
        
        function form( $instance ) {
            $title     = isset( $instance['title'] ) ?  $instance['title']: '';
            $categories    = isset( $instance['categories'] ) ? $instance['categories']: ''; 
            ?>

            <p><label for="<?php echo esc_html($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'myticket' ); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'title' )); ?>" name="<?php echo esc_html($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

            <p><label for="<?php echo esc_html($this->get_field_id( 'categories' )); ?>"><?php esc_html_e( 'Separate categories by comma:', 'myticket' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_html($this->get_field_id( 'categories' )); ?>" name="<?php echo esc_html($this->get_field_name( 'categories' )); ?>" type="text" value="<?php echo esc_attr( $categories ); ?>" ><?php echo esc_attr( $categories ); ?></textarea>To find categories go to Products / Categories. Categories are Case-sensitive Ex.: VIP Lounge, Seating, Standing..</p>
      
            <?php
        }
    }
?>