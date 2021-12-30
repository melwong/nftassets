<?php  if( 'normal' == $instance['type'] ) : ?>

    <section class="section-page-header">
        <div class="container">
            <h1 class="entry-title"><?php echo esc_html( $instance['title'] ); ?></h1>
        </div>
    </section>

<!-- ============== NOT IMPLEMENTED ============== -->
<?php elseif( 'minified' == $instance['type'] ) : ?>

   
<?php endif; 