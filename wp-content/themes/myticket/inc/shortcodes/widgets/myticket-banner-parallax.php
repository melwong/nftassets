<?php

	$image_url = "";
	if ( $instance['image'] != '' ){
	    $image_url = wp_get_attachment_image_src($instance['image'],"full",false);
	}

	?>
	<section class="section-video-parallax" style="background-image: url('<?php echo esc_url($image_url[0]); ?>');">
		<div class="container">
			<div class="section-content">
				<h2><?php echo esc_html($instance['title']); ?></h2>
				<p><?php echo esc_html($instance['text']); ?></p>
				<a data-featherlight="iframe" class="play-youtube" href="<?php echo esc_url($instance['button_url']); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/play-btn.png" alt="image"></a>
			</div>
		</div>
	</section>
