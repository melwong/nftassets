<?php  if( 'normal' == $instance['type'] ) : ?>

	<section class="section-gallery" style="background-color:<?php echo sanitize_hex_color( $instance['bg_color'] ); ?>">
		<div class="container">
			<div class="row">
				<h1><?php echo esc_attr( $instance['title'] ); ?></h1>
				<div class="gallery-list row">

				    <?php 
	                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	                $arg = array(
	                              'post_status'     => 'publish',
                                  'post_type'       => 'gallery',
                                  'meta_query' => array(),
                                  'tax_query' => array('relation'=>'AND'),  
	                              'posts_per_page'  => $instance['images_per_page'],
	                              'paged'           => $paged,
                                  );
                                
	                             
	                if ( strlen( $instance['category'] ) ){
                        //echo $instance['category']; die;
	                	array_push( $arg['tax_query'],  array(
	                        'taxonomy' => 'gallery_category',  
	                        'field' => 'name',          
	                        'terms' => $instance['category'],       
				        ) ); 
                    }
                    
                    $postCount = 0;
                    $recentPosts = new WP_Query( $arg );

	                if ( $recentPosts->have_posts() ) :
	                    while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); $postCount++; ?>

							<div class="gallery-img col-xs-6 col-sm-3">
								<?php $meta = get_post_meta( get_the_ID() );  ?>
								<a href="<?php echo esc_url( $meta['myticket_url'][0] ); ?>" <?php if ( strlen($meta['myticket_url'][0]) == 0 ) echo 'data-featherlight="#content-'.esc_attr( $postCount ).'"';?>>
									<div class="gallery-view"><?php esc_html_e( 'VIEW', 'myticket' );?></div>
									<?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-gallery img-responsive' ) ); ?>
								</a>
								<div id="content-<?php echo esc_attr( $postCount ); ?>" class="gallery-lightbox">
									<?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) ); ?>
									<div class="gallery-lightbox-content">
										<h3><?php echo the_title();?></h3>
										<?php the_excerpt(); ?>
									</div>
								</div>
							</div>

	    				<?php  endwhile;
	                endif; ?>
				</div>

				<?php if( $instance['pagination'] ){ myticket_pagination_gallery( 'gallery-pagination', $recentPosts, get_pagenum_link(999999999) ); } ?>

			</div>
		</div>
	</section>

<!-- ============== NOT IMPLEMENTED ============== -->
<?php elseif( 'minified' == $instance['type'] ) : ?>

    <!-- ============== instagram block starts ============== -->
    <section class="<?php echo esc_attr( $instance['class'] ); ?> instagram-block">
        <div class="container">
            <div class="top-text-header text-center">
                <h4 class="text-uppercase text-sp text-lt"><?php echo esc_attr( $instance['title'] ); ?></h4>
                <span class="follow-at text-spx text-lt txcolor"><?php echo esc_attr( $instance['text'] ); ?></span>
            </div>
        </div>
        <div class="instagram-image-row">
            <ul><?php $arg = array(
                'post_status'     => 'publish',
                'post_type'       => 'gallery',
                'category_name'   => $instance['category'],
                'posts_per_page'  => $instance['images_per_page'],
                );
                $recentPosts = new WP_Query( $arg );
                if ( $recentPosts->have_posts() ) :
                    while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?><li class="no-padding no-margin no-style" style="width:<?php echo 100/intVal( $instance['images_per_page'] );?>%"><figure><a data-toggle="lightbox" data-gallery="example-gallery" class="lightbox" href="<?php echo the_post_thumbnail_url( 'full' ); ?>"><?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-responsive' ) ); ?></a></figure></li><?php endwhile;
                endif;
                ?></ul>
        </div>
    </section>

<?php elseif( 'carousel' == $instance['type'] ) : ?>

    <!-- ============== featured menu carousel starts ============== -->
    <section class="<?php echo esc_attr( $instance['class'] ); ?> featured-menu-carousel">
        <div class="container">
            <?php if ( 'true' == $instance['show_header'] ) : ?>
            <!-- == top text header starts == -->
            <div class="wow fadeInUp top-text-header text-center animated" >
                <h4 class="text-uppercase text-sp text-lt"><?php echo esc_attr( $instance['title'] ); ?></h4>
            </div>
            <!-- == top text header ends == -->
            <?php endif; ?>
            <!-- == carousel starts == -->
            <div class="carousel-container">
                <div id="carousel">
                    <?php $arg = array(
                    'post_status'     => 'publish',
                    'post_type'       => 'gallery',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'gallery_category',   // taxonomy name
                            'field' => 'name',           // term_id, slug or name
                            'terms' => esc_attr( $instance['category'] ),                  // term id, term slug or term name
                        )
                    ),
                    'posts_per_page'  => esc_attr( $instance['images_per_page'] ),
                    );

                    $recentPosts = new WP_Query( $arg );
                    if ( $recentPosts->have_posts() ) :
                        while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
                        $meta = get_post_meta( get_the_ID() );  ?>
                        <div class="carousel-feature feature-slide active">
                            <a href="<?php echo esc_url( $meta['myticket_url'][0] ); ?>"><?php the_post_thumbnail( 'myticket-gallery-carousel', array( 'class' => 'img-responsive' ) ); ?></a>
                            <div class="carousel-caption">
                                <p><?php echo the_title(); ?></p>
                            </div>
                        </div>
                    <?php endwhile;
                    endif;
                    ?>
                </div>
                <div id="carousel-left"><img alt="arrow left" src="<?php echo get_template_directory_uri() .'/images/arrow-left.png'; ?>" /></div>
                <div id="carousel-right"><img alt="arrow right" src="<?php echo get_template_directory_uri() .'/images/arrow-right.png'; ?>" /></div>
            </div>
            <!-- == carousel ends == -->
        </div>
    </section>
    <!-- ============== featured menu carousel ends ============== -->

<?php elseif( 'menu' == $instance['type'] ) : ?>

    <!-- ============== featured menu block starts ============== -->
    <section class="block featured-menu-block">
        <?php if ( 'true' == $instance['show_header'] ) : ?>
        <div class="container">
            <!-- == top text header starts == -->
            <div class="wow fadeInUp top-text-header text-center">
                <h4 class="text-uppercase text-lt text-sp"><?php echo esc_attr( $instance['title'] ); ?></h4>
            </div>
            <!-- == top text header ends == -->
        </div>
        <?php endif; ?>
        <!-- == featured menu slider starts == -->
        <div class="wow fadeInUp featured-menu-slider">
            <div class="container">
                <ul class="bxslider1 row">

                    <?php $arg = array(
                    'post_status'     => 'publish',
                    'post_type'       => 'gallery',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'gallery_category',   // taxonomy name
                            'field' => 'name',                      // term_id, slug or name
                            'terms' => esc_attr( $instance['category'] ),   // term id, term slug or term name
                        )
                    ),
                    'posts_per_page'  => esc_attr( $instance['images_per_page'] ),
                    );

                    $recentPosts = new WP_Query( $arg );
                    if ( $recentPosts->have_posts() ) :
                        while ( $recentPosts->have_posts() ) : $recentPosts->the_post();
                        $meta = get_post_meta( get_the_ID() );?>
                        <li class="col-xs-12 col-sm-3">
                            <a data-toggle="lightbox" class="lightbox bghcolor" href="<?php echo the_post_thumbnail_url(); ?>">
                                <figure><?php the_post_thumbnail( 'myticket-gallery', array( 'class' => 'img-responsive' ) ); ?></figure>
                                <div class="menu-info">
                                    <h6 class="text-capitalize text-lt text-sp txcolor"><?php echo the_title(); ?></h6>
                                    <span><?php echo get_the_excerpt(); ?></span>
                                </div>
                            </a>
                        </li>
                        <?php endwhile;
                    endif;
                    ?>
                </ul>
            </div>
        </div>
        <!-- == featured menu slider ends == -->
    </section>
    <!-- ============== featured menu block ends ============== -->

<?php elseif( 'aboutus' == $instance['type'] ) : ?>

    <!-- About us image grid block starts -->
    <div class="row image-grid-row">
     
        <div class="col-xs-12 col-sm-7 small-image-group wow fadeInLeft">
            <div class="row">
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image1.jpg" alt="About Image 1" /></a></figure>
                </div>
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image2.jpg" alt="About Image 2" /></a></figure>
                </div>
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image3.jpg" alt="About Image 3" /></a></figure>
                </div>
                <div class="col-xs-6 col-sm-6 small-image-wrap wow fadeInUp">
                    <figure><a href="#"><img class="img-responsive" src="images/about-image4.jpg" alt="About Image 4" /></a></figure>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 big-image wow fadeInRight">
            <figure><a href="#"><img class="img-responsive" src="images/about-image.jpg" alt="About image" /></a></figure>
        </div>
    </div>
    <!-- About us image gallery block ends -->
<?php endif; 