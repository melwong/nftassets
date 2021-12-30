<?php
    
    /**
     * myticket_heading widget class
     *
     * @since 1.0.0
     */
    
    add_action( 'widgets_init', 'myticket_heading' );
    
    function myticket_heading() {
        
        register_widget( 'myticket_heading' );
    }

    class myticket_heading extends WP_Widget {
        
        function __construct() {
            $widget_ops = array( 'classname' => 'myticket_heading', 'description' => esc_html__( 'MyTicket heading widget', 'myticket' ) );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'myticket_heading' );
            parent::__construct( 'myticket_heading', esc_html__( 'MyTicket heading widget', 'myticket'), $widget_ops, $control_ops );
        }
        
        function widget($args, $instance) {
            
            $cache = wp_cache_get( 'myticket_heading_widget', 'widget' );
            
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

            <div class="search-event-title">
                <h2><span><?php echo esc_html( $instance['title'] ); ?></span> <?php echo esc_html( $instance['subtitle'] ); ?></h2>
            </div>

            <?php echo $after_widget; ?>
            <?php
            wp_reset_postdata();
           

            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            
            $cache[$args['widget_id']] = ob_get_flush();
            wp_cache_set( 'myticket_heading_widget', $cache, 'widget' );
        }
        
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title'] = strip_tags( $new_instance['title'] );
            $instance['subtitle'] = strip_tags( $new_instance['subtitle'] );

            $this->flush_widget_cache();
            
            $alloptions = wp_cache_get( 'alloptions', 'options' );
            if ( isset( $alloptions['widget_popular_entries'] ) )
                delete_option( 'widget_popular_entries' );
            
            return $instance;
        }
        
        function flush_widget_cache() {
            wp_cache_delete( 'myticket_heading_widget', 'widget' );
        }
        
        function form( $instance ) {
            $title     = isset( $instance['title'] ) ?  $instance['title']: '';
            $subtitle    = isset( $instance['subtitle'] ) ? $instance['subtitle']: ''; 
            ?>

            <p><label for="<?php echo esc_html($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'myticket' ); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'title' )); ?>" name="<?php echo esc_html($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

            <p><label for="<?php echo esc_html($this->get_field_id( 'subtitle' )); ?>"><?php esc_html_e( 'Subtitle:', 'myticket' ); ?></label>
            <input class="widefat" id="<?php echo esc_html($this->get_field_id( 'subtitle' )); ?>" name="<?php echo esc_html($this->get_field_name( 'subtitle' )); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" /></p>
      
            <?php
        }
    }
?>