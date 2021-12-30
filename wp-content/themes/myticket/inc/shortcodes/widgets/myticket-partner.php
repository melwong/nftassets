<?php 
$image_url = "";
if ( $instance['partner1_img'] != '' ){
    $partner1_img = wp_get_attachment_image_src($instance['partner1_img'],"myticket-gallery",false);
}
if ( $instance['partner3_img'] != '' ){
    $partner3_img = wp_get_attachment_image_src($instance['partner3_img'],"myticket-gallery",false);
}
if ( $instance['partner2_img'] != '' ){
    $partner2_img = wp_get_attachment_image_src($instance['partner2_img'],"myticket-gallery",false);
}
if ( $instance['partner4_img'] != '' ){
    $partner4_img = wp_get_attachment_image_src($instance['partner4_img'],"myticket-gallery",false);
}
?>
	<section class="section-sponsors">
		<div class="container">
			<div class="section-content">
				<ul class="row">
					<li class="col-sm-3">
						<a href="<?php echo esc_url($instance['partner1_txt']);?>">
							<img src="<?php echo esc_url( $partner1_img[0] ); ?>" alt="image">
						</a>
					</li>
					<li class="col-sm-3">
						<a href="<?php echo ($instance['partner2_txt']);?>">
							<img src="<?php echo esc_url( $partner2_img[0] ); ?>" alt="image">
						</a>
					</li>
					<li class="col-sm-3">
						<a href="<?php echo esc_url($instance['partner3_txt']);?>">
							<img src="<?php echo esc_url( $partner3_img[0] ); ?>" alt="image">
						</a>
					</li>
					<li class="col-sm-3">
						<a href="<?php echo esc_url($instance['partner4_txt']);?>">
							<img src="<?php echo esc_url( $partner4_img[0] ); ?>" alt="image">
						</a>
					</li>
				</ul>
			</div>
		</div>
	</section>