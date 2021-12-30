<section class="section-call-to-action">
    <div class="container">
        <div class="row">
            <div class="section-content">
                <ul class="row clearfix">
                    <li class="col-sm-12 col-md-9">
                        <h3><?php echo esc_html( $instance['title'] ); ?></h3>
                        <p><?php echo esc_html( $instance['text'] ); ?></p>
                    </li>
                    <li class="col-sm-12 col-md-3">
                        <a href="<?php echo esc_url( $instance['cta_link'] ); ?>" class="action-btn"><?php echo esc_html( $instance['cta_text'] ); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php 
wp_reset_postdata();     