<?php if ( 'simple' == $instance['type'] ) : ?>




<?php elseif ( 'advanced' == $instance['type'] ) : ?>

	<!-- ============== About us Banner starts ============== -->
	<?php 

	//hero-1
	$image_url = "";
	if ( $instance['image'] != '' ){
	    $image_url = wp_get_attachment_image_src($instance['image'],"full",false);
	}

	if ( $instance['show_gradient'] ) { 

		$rgba1 = myticket_hex2rgba( esc_html( $instance['bg_color1'] ), intval($instance['bg_opacity'])/10 );
		$rgba2 = myticket_hex2rgba( esc_html( $instance['bg_color2'] ), intval($instance['bg_opacity'])/10 );

		$style = "background: -moz-linear-gradient(-45deg, ".$rgba1." 0%, ".$rgba2." 100%); background: -webkit-linear-gradient(-45deg, ".$rgba1." 0%, ".$rgba2." 100%);background: linear-gradient(135deg, ".$rgba1." 0%, ".$rgba2." 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$instance['bg_color1']."', endColorstr='".$instance['bg_color2']."',GradientType=1 ); "; 
	}else{
	
	} ?>

	<section class="hero-1" style="background-image: url('<?php echo esc_url($image_url[0]); ?>');">
		<div class="hero-after" style="<?php echo esc_html($style); ?>"></div>
		<div class="container">
			<div class="row">
				<div class="hero-content">
					<h1 class="hero-title"><?php echo esc_html($instance['title']); ?></h1>
					<p class="hero-caption"><?php echo esc_html($instance['subtitle']); ?></p>
					<div class="hero-search">
						<form action="<?php echo esc_url($instance['action']); ?>">
							<input type="text" name="event" placeholder="<?php esc_html_e( 'Search Artist, Team, or Venue', 'myticket' ); ?>">
						</form>
					</div>
					<div class="hero-location">
						<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php esc_html_e( 'San Francisco', 'myticket' ); ?> <a href="<?php echo esc_url($instance['action']); ?>"><?php esc_html_e( 'Change Location', 'myticket' ); ?></a></p>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php elseif ( 'advanced2' == $instance['type'] ) : ?>

	<!-- ============== About us Banner starts ============== -->
	<?php 

	//hero-2
	$image_url = "";
	if ( $instance['image'] != '' ){
	    $image_url = wp_get_attachment_image_src($instance['image'],"full",false);
	}

	if ( $instance['show_gradient'] ) { 

		$rgba1 = myticket_hex2rgba( esc_html( $instance['bg_color1'] ), intval($instance['bg_opacity'])/10 );
		$rgba2 = myticket_hex2rgba( esc_html( $instance['bg_color2'] ), intval($instance['bg_opacity'])/10 );

		$style = "background: -moz-linear-gradient(-45deg, ".$rgba1." 0%, ".$rgba2." 100%); background: -webkit-linear-gradient(-45deg, ".$rgba1." 0%, ".$rgba2." 100%);background: linear-gradient(135deg, ".$rgba1." 0%, ".$rgba2." 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$instance['bg_color1']."', endColorstr='".$instance['bg_color2']."',GradientType=1 ); "; 
	}else{
	
	} ?>

	<section class="hero-2" style="background-image: url('<?php echo esc_url($image_url[0]); ?>');">
		<div class="hero-after" style="<?php echo esc_html($style); ?>"></div>
		<div class="container">
			<div class="row">
				<div class="hero-content">
					<p class="hero-caption"><?php echo wp_kses( $instance['title'], array( 
				        'a' => array(
				            'href' => array(),
				            'title' => array()
				        ),
				        'br' => array(),
				        'b' => array(),
				        'tr' => array(),
				        'th' => array(),
				        'td' => array(),
				        'em' => array(),
				        'span' => array(
				            'id' => array(),
				            'class' => array(),),
				        'i' => array( 
				            'id' => array(),
				            'class' => array(),),
				        'strong' => array(),
				        'span' => array(
				            'href' => array(),
				            'class' => array(),
				        ),
				        'div' => array(
				            'id' => array(),
				            'class' => array(),
				        ),
				    	) ); ?>	
				    </p>
					<h1 class="hero-title"><?php echo wp_kses( $instance['subtitle'], array( 
				        'a' => array(
				            'href' => array(),
				            'title' => array()
				        ),
				        'br' => array(),
				        'b' => array(),
				        'tr' => array(),
				        'th' => array(),
				        'td' => array(),
				        'em' => array(),
				        'span' => array(
				            'id' => array(),
				            'class' => array(),),
				        'i' => array( 
				            'id' => array(),
				            'class' => array(),),
				        'strong' => array(),
				        'span' => array(
				            'href' => array(),
				            'class' => array(),
				        ),
				        'div' => array(
				            'id' => array(),
				            'class' => array(),
				        ),
				    	) ); ?></h1>
					<ul class="count-down"></ul>
					<div class="hero-location">
						<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo esc_html($instance['text']); ?></p>
					</div>
					<div class="hero-purchase-ticket">
						<a href="<?php echo esc_url($instance['button_url']); ?>"><?php echo esc_html($instance['button_text']); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php elseif ( 'advanced3' == $instance['type'] ) : ?>

	<!-- ============== About us Banner starts ============== -->
	<?php 

	//hero-3
	$image_url = "";
	if ( $instance['image'] != '' ){
	    $image_url = wp_get_attachment_image_src($instance['image'],"full",false);
	}

	if ( $instance['show_gradient'] ) { 

		$rgba1 = myticket_hex2rgba( esc_html( $instance['bg_color1'] ), intval($instance['bg_opacity'])/10 );
		$rgba2 = myticket_hex2rgba( esc_html( $instance['bg_color2'] ), intval($instance['bg_opacity'])/10 );

		$style = "background: -moz-linear-gradient(-45deg, ".$rgba1." 0%, ".$rgba2." 100%); background: -webkit-linear-gradient(-45deg, ".$rgba1." 0%, ".$rgba2." 100%);background: linear-gradient(135deg, ".$rgba1." 0%, ".$rgba2." 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$instance['bg_color1']."', endColorstr='".$instance['bg_color2']."',GradientType=1 ); "; 
	}else{
	
	} 


	$myticket_args = array(
	    'p' => $instance['action'],
	    'post_type' => array('product'), //, 'product_variation'
	    'post_status' => 'publish',
	    'posts_per_page' => '1'
	);

	$products = new WP_Query( $myticket_args );

	while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); 

		$stock = $meta['_stock'][0];
        if ( $stock == '' ){
            $stock = esc_attr__( 'unlimited tickets', 'myticket' );
        }else{
            $stock = $stock.' '.esc_attr__( ' tickets left!', 'myticket' );
        }
        if ( $meta['_stock_status'][0] == 'outofstock' ) {
            $stock = esc_attr__( 'No Tickets Left', 'myticket' );
        } 

        if($instance['button_url']==''){
        	$instance['button_url'] = get_permalink($instance['action']);
        } 

   		if($instance['title']==''){
        	$instance['title'] = get_the_title();
        } 

        if($instance['subtitle']==''){
        	$instance['subtitle'] = $meta['myticket_title'][0];
        } 
        
        if($instance['text']==''){
        	$instance['text'] = $meta['myticket_address'][0];
        } 
		?>

		<section class="hero-3" style="background-image: url('<?php echo esc_url($image_url[0]); ?>');">
			<div class="hero-after" style="<?php echo esc_html($style); ?>"></div>
			<div class="container">
				<div class="row">
					<div class="hero-content">
						<div class="hero-date">
							<span class="day"><?php echo date_i18n(  "d", intval( $meta['myticket_datetime'][0] ) ); ?></span>
							<span class="month"><?php echo date_i18n(  "M", intval( $meta['myticket_datetime'][0] ) ); ?></span>
						</div>
						<h1 class="hero-title"><?php echo wp_kses( $instance['title'], array( 
				        'a' => array(
				            'href' => array(),
				            'title' => array()
				        ),
				        'br' => array(),
				        'b' => array(),
				        'tr' => array(),
				        'th' => array(),
				        'td' => array(),
				        'em' => array(),
				        'span' => array(
				            'id' => array(),
				            'class' => array(),),
				        'i' => array( 
				            'id' => array(),
				            'class' => array(),),
				        'strong' => array(),
				        'span' => array(
				            'href' => array(),
				            'class' => array(),
				        ),
				        'div' => array(
				            'id' => array(),
				            'class' => array(),
				        ),
				    	) ); ?></h1>
						<p class="hero-caption"><?php echo wp_kses( $instance['subtitle'], array( 
				        'a' => array(
				            'href' => array(),
				            'title' => array()
				        ),
				        'br' => array(),
				        'b' => array(),
				        'tr' => array(),
				        'th' => array(),
				        'td' => array(),
				        'em' => array(),
				        'span' => array(
				            'id' => array(),
				            'class' => array(),),
				        'i' => array( 
				            'id' => array(),
				            'class' => array(),),
				        'strong' => array(),
				        'span' => array(
				            'href' => array(),
				            'class' => array(),
				        ),
				        'div' => array(
				            'id' => array(),
				            'class' => array(),
				        ),
				    	) ); ?></p>
						<div class="hero-location">
							<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo esc_attr($instance['text']); ?> </p>
						</div>
						<div class="hero-purchase-ticket">
							<span><?php echo esc_attr($stock); ?></span>
							<a href="<?php echo esc_url($instance['button_url']); ?>"><?php echo esc_html($instance['button_text']); ?></a>
						</div>
					</div>
				</div>
			</div>
		</section>

	<?php endwhile; ?>

<?php elseif ( 'aboutus' == $instance['type'] ) : ?>

	<!-- ============== About us Banner starts ============== -->
	<?php if ( $instance['show_gradient'] ) $style = "background: rgba(217,30,24,.9);background: -moz-linear-gradient(-45deg,  rgba(217,30,24,.9) 0%, rgba(255,102,0,.9) 100%); background: -webkit-linear-gradient(-45deg,  rgba(217,30,24,.9) 0%,rgba(255,102,0,.9) 100%);background: linear-gradient(135deg, rgba(217,30,24,.9) 0%,rgba(255,102,0,.9) 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d91e18', endColorstr='#ff6600',GradientType=1 ); "; 

	//hero-1
	$image_url = "";
	if ( $instance['image'] != '' ){
	    $image_url = wp_get_attachment_image_src($instance['image'],"full",false);
	}

	?>

	<section class="<?php echo esc_attr( $instance['class'] ); ?> banner banner-image about-us-banner " style="">
	    <div class="bannerwrap">
	        <figure><img src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_html( $instance['title'] ); ?>" /></figure>
	    </div>
	</section>
	<!-- ============== About us Banner ends ============== -->

<?php elseif ( 'artist' == $instance['type'] ) : ?>

	<?php $image_url = "";
	if ( $instance['image'] != '' ){
	    $image_url = wp_get_attachment_image_src($instance['image'],"full",false);
	} ?>
	<section class="section-artist-featured-header" style="background-image:url(<?php echo esc_url($image_url[0]); ?>)">
		<div class="container">
			<div class="section-content">
				<h1><?php echo esc_html( $instance['title'] ); ?></h1>
				<p><?php echo esc_html( $instance['subtitle'] ); ?></p>
			</div>
		</div>
	</section>

<?php endif; ?>