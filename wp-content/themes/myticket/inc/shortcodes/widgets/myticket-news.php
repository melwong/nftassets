<?php
if ($instance['records_per_page'] == '')
	$instance['records_per_page'] == 3;

$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
$myticket_args = array(
  'post_status'    => 'publish',
  'post_type' 	   => 'post',
  'ignore_sticky_posts' => 1,
  'post_thumbnail' => 1,
  'category_name'  => $instance['category'],
  'posts_per_page' => $instance['records_per_page'],
  'paged'          => $paged,
  'meta_query' => array( 
    array(
        'key' => '_thumbnail_id'
    ) 
   )
  );
  $postCount = 0;
  $recentPosts = new WP_Query( $myticket_args );

  ?>

	<section id="section-latest" class="section-latest">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 <?php if ( $instance['twitter'] ) { echo 'col-md-8'; }else{ echo 'col-md-12'; } ?> ">
					<div class="latest-news">
						<div class="section-header">
							<h2><?php echo esc_attr( $instance['title'] ); ?></h2>
						</div>
						<div class="section-content">
							<ul class="clearfix">

								<?php if ( $recentPosts->have_posts() ) :
	    							while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>

										<li class="row news-item">
											<div class="col-sm-5 news-item-img">
												<div class="date">
													<a href="<?php echo get_the_permalink(); ?>">
														<span class="day"><?php echo date( 'd', get_post_time( 'U', true ) ); ?></span>
														<span class="month"><?php echo date( 'F', get_post_time( 'U', true ) ); ?></span>
														<span class="year"><?php echo date( 'Y', get_post_time( 'U', true ) ); ?></span>
													</a>
												</div>
												<a href="<?php echo get_the_permalink(); ?>">
													<?php the_post_thumbnail( 'sayidan-story-large', array( 'class' => 'img-responsive' ) ); ?> 
												</a>
											</div>
											<div class="col-sm-7 news-item-info">
												<h3><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<span class="meta-data"><?php echo get_the_date(); ?> | <?php esc_html_e( 'By', 'myticket' ); ?> <?php echo get_the_author_link(); ?></span>
												<?php the_excerpt(); ?>
											</div>
										</li>

									<?php endwhile; ?>
								<?php endif;?>

							</ul>
							<div class="new-item-pagination">
								<nav aria-label="Page navigation" class="pull-left">
									<?php myticket_pagination_news($recentPosts); ?>
								</nav>
							</div>
						</div>
					</div>
				</div>
				
				<?php if ( $instance['twitter'] ) : ?>

					<div class="col-sm-12 <?php if ( $instance['twitter'] ) { echo 'col-md-4'; }else{ echo 'col-md-12'; } ?> ">
						<div class="latest-tweets">
							<div class="section-header">
								<h2><?php echo esc_attr( $instance['title_right'] ); ?></h2>
							</div>

							<?php if (!is_user_logged_in()) echo do_shortcode( '[myticket_twitter twitter_c_key="'.$instance['twitter_c_key'].'" twitter_c_secret="'.$instance['twitter_c_secret'].'" twitter_a_token="'.$instance['twitter_a_token'].'" twitter_a_key="'.$instance['twitter_a_key'].'" twitter_username="'.$instance['twitter_username'].'" twitter_max="'.$instance['twitter_max'].'" title_twitter="'.$instance['title_twitter'].'" twitter_logo="'.$instance['twitter_logo'].'" title_twitter="'.$instance['title_twitter'].'" ]' ); ?>
						</div>
					</div>

				<?php endif; ?>
			</div>
		</div>
	</section>
<?php wp_reset_postdata(); ?>