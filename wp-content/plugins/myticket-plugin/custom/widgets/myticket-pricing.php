<?php
    
    /**
     * myticket_pricing_filter widget class
     *
     * @since 1.0.0
     */
    
    add_action( 'widgets_init', 'myticket_pricing_filter' );
    
    function myticket_pricing_filter() {
        
        register_widget( 'myticket_pricing_filter' );
    }

    class myticket_pricing_filter extends WP_Widget {
        
        function __construct() {
            $widget_ops = array( 'classname' => 'myticket_pricing_filter', 'description' => esc_html__( 'MyTicket pricing filter widget', 'myticket' ) );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'myticket_pricing_filter' );
            parent::__construct( 'myticket_pricing_filter', esc_html__( 'MyTicket pricing filter widget', 'myticket'), $widget_ops, $control_ops );
        }
        
        function widget($args, $instance) {
            
            $cache = wp_cache_get( 'myticket_pricing_widget', 'widget' );
            
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
            if ( isset($_COOKIE['product_pricing_low']) ){ $instance['number_min_def'] = $_COOKIE['product_pricing_low']; } 
            if ( isset($_COOKIE['product_pricing_high']) ){ $instance['number_max_def'] = $_COOKIE['product_pricing_high']; } 
            ?>

            <div class="widget-search-filter-price">
                <h3><?php echo esc_html( $title ); ?></h3>
                <!-- Filter by price interval: <b>€ 10</b> <input id="price-range" type="text" class="span2" value="" data-slider-min="10" data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]"/> <b>€ 1000</b> -->

                <input id="price-range" type="text" class="span2" value="" data-slider-min="<?php echo esc_attr($instance['number_min']); ?>" data-slider-max="<?php echo esc_attr($instance['number_max']); ?>" data-slider-step="<?php echo esc_attr($instance['number_step']); ?>" data-slider-value="[<?php echo esc_attr($instance['number_min_def']); ?>,<?php echo esc_attr($instance['number_max_def']); ?>]"/>
            </div>

            <!--category list ends-->
            <?php echo $after_widget; ?>
            <?php
            wp_reset_postdata();
           

            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set( 'myticket_pricing_widget', $cache, 'widget' );
        }
        
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['number_min'] = (int) $new_instance['number_min'];
            $instance['number_max'] = (int) $new_instance['number_max'];
            $instance['number_min_def'] = (int) $new_instance['number_min_def'];
            $instance['number_max_def'] = (int) $new_instance['number_max_def'];
            $instance['number_step'] = (int) $new_instance['number_step'];
            $this->flush_widget_cache();
            
            $alloptions = wp_cache_get( 'alloptions', 'options' );
            if ( isset( $alloptions['widget_popular_entries'] ) )
                delete_option( 'widget_popular_entries' );
            
            return $instance;
        }
        
        function flush_widget_cache() {
            wp_cache_delete( 'myticket_pricing_widget', 'widget' );
        }
        
        function form( $instance ) {
            $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
            $number_min    = isset( $instance['number_min'] ) ? absint( $instance['number_min'] ) : 0; 
            $number_max    = isset( $instance['number_max'] ) ? absint( $instance['number_max'] ) : 200; 
            $number_min_def    = isset( $instance['number_min_def'] ) ? absint( $instance['number_min_def'] ) : 5; 
            $number_max_def    = isset( $instance['number_max_def'] ) ? absint( $instance['number_max_def'] ) : 170;
            $number_step    = isset( $instance['number_step'] ) ? absint( $instance['number_step'] ) : 0; ?>

            <p><label for="<?php echo esc_html($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'madang' ); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'title' )); ?>" name="<?php echo esc_html($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo $title; ?>" /></p>

            <p><label for="<?php echo $this->get_field_id( 'number_min' ); ?>"><?php esc_html_e( 'Min value:', 'madang' ); ?></label>
            <input id="<?php echo esc_html($this->get_field_id( 'number_min' )); ?>" name="<?php echo esc_html($this->get_field_name( 'number_min' )); ?>" type="text" value="<?php echo esc_html($number_min); ?>" size="10" /></p>


            <p><label for="<?php echo $this->get_field_id( 'number_max' ); ?>"><?php esc_html_e( 'Max value:', 'madang' ); ?></label>
            <input id="<?php echo esc_html($this->get_field_id( 'number_max' )); ?>" name="<?php echo esc_html($this->get_field_name( 'number_max' )); ?>" type="text" value="<?php echo esc_html($number_max); ?>" size="10" /></p>


            <p><label for="<?php echo $this->get_field_id( 'number_min_def' ); ?>"><?php esc_html_e( 'Min default value:', 'madang' ); ?></label>
            <input id="<?php echo esc_html($this->get_field_id( 'number_min_def' )); ?>" name="<?php echo esc_html($this->get_field_name( 'number_min_def' )); ?>" type="text" value="<?php echo esc_html($number_min_def); ?>" size="10" /></p>


            <p><label for="<?php echo $this->get_field_id( 'number_max_def' ); ?>"><?php esc_html_e( 'Max default value:', 'madang' ); ?></label>
            <input id="<?php echo esc_html($this->get_field_id( 'number_max_def' )); ?>" name="<?php echo esc_html($this->get_field_name( 'number_max_def' )); ?>" type="text" value="<?php echo esc_html($number_max_def); ?>" size="10" /></p>
  

            <p><label for="<?php echo $this->get_field_id( 'number_step' ); ?>"><?php esc_html_e( 'Step value:', 'madang' ); ?></label>
            <input id="<?php echo esc_html($this->get_field_id( 'number_step' )); ?>" name="<?php echo esc_html($this->get_field_name( 'number_step' )); ?>" type="text" value="<?php echo esc_html($number_step); ?>" size="10" /></p>
      
            <?php
        }
    }
?>