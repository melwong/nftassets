	<section class="section-newsletter">
		<div class="container">
			<div class="section-content">
				<h2><?php echo esc_attr( $instance['title'] ); ?></h2>
				<p><?php echo wp_kses( $instance['subtitle'], array( 
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
				<?php echo do_shortcode( '[mc4wp_form id="' . esc_attr( $atts['form_id'] ) . '"]' );?>
			</div>
		</div>
	</section>