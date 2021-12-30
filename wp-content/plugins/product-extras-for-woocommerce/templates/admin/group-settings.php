<?php
/**
 * The markup for importing groups from another product
 *
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! pewc_is_pro() ) {
	return;
} ?>

<div class="options_group">
	<p class="form-field">
		<?php $args = array(
			'id'			=> 'pewc_display_groups',
			'class' 	=> 'pewc-display-groups',
			'label'		=> __( 'Display groups as', 'woocommerce' ),
			'options'	=> array(
				'standard'		=> __( 'Standard', 'pewc' ),
				'accordion'		=> __( 'Accordion', 'pewc' ),
				'tabs'				=> __( 'Tabs', 'pewc' ),
			)
		);
		woocommerce_wp_select( $args ); ?>
	</p>

</div>
