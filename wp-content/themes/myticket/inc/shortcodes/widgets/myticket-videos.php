    

    <section class="section-recent-videos">
        <div class="container">
            <div class="row">
                <div class="section-header">
                    <h2><?php echo esc_html( $instance['title'] ); ?></h2>
                </div>
                <div class="section-content">
                    <ul class="row clearfix">

                        <?php $myticket_args = array(
                          'post_status' => 'publish',
                          'post_type' => 'post',
                          'category_name' => $instance['category'],
                          'posts_per_page' => $instance['events_per_page'],
                          );
                          $ee = 0;
                          $recentPosts = new WP_Query( $myticket_args );
                          if ( $recentPosts->have_posts() ) :
                    
                            while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>

                                <li class="col-sm-3">
                                    <div class="video">
                                        <a class="play-youtube" data-featherlight="iframe" href="<?php  echo preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$2", get_the_content() ); ?>?autoplay=1"><?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-responsive' ) ); ?></a>
                                        <div class="video-player">
                                            <a class="play-youtube" data-featherlight="iframe"  href="<?php  echo preg_replace("/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","//www.youtube.com/embed/$2", get_the_content() ); ?>?autoplay=1"><i class="fa fa-play" aria-hidden="true"></i></a> 
                                            <span>YouTube</span>
                                        </div>
                                    </div>
                                    <h3 class="video-title">
                                        <a href="#"><?php echo get_the_title(); ?></a>
                                    </h3>
                                </li>

                            <?php endwhile; ?>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>
    </section>
<?php 
wp_reset_postdata();     