<?php
/**
 * Custom Shipping Methods for WooCommerce - Custom Shipping Settings
 *
 * @version 1.5.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$availability_extra_desc = ' ' . __( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' );
$pro_link                = '<a target="_blank" href="https://wpfactory.com/item/custom-shipping-methods-for-woocommerce/">' . 'Custom Shipping Methods for WooCommerce Pro' . '</a>';
$pro_desc_short          = sprintf( '.<br><em>' . 'This shortcode is available in %s plugin only' . '</em>', $pro_link );
$pro_desc                = sprintf( 'This option is available in %s plugin only.', $pro_link );

$available_shortcodes = apply_filters( 'alg_wc_custom_shipping_methods_shortcodes_desc', array(
	'[qty]'                                           => __( 'number of items', 'custom-shipping-methods-for-woocommerce' ),
	'[cost]'                                          => __( 'total cost of items', 'custom-shipping-methods-for-woocommerce' ),
	'[weight]'                                        => __( 'total weight of items', 'custom-shipping-methods-for-woocommerce' ),
	'[volume]'                                        => __( 'total volume of items', 'custom-shipping-methods-for-woocommerce' ),
	'[fee percent="10" min_fee="20" max_fee=""]'      => __( 'percentage based fees', 'custom-shipping-methods-for-woocommerce' ),
	'[costs_table prop="qty" table="1-10|10-5|20-0"]' => __( 'costs table', 'custom-shipping-methods-for-woocommerce' ) .
		sprintf( ' (' . __( 'check examples %s', 'custom-shipping-methods-for-woocommerce' ) . ')',
			'<a target="_blank" href="https://wpfactory.com/item/custom-shipping-methods-for-woocommerce/#costs_table">' .
				__( 'here', 'custom-shipping-methods-for-woocommerce' ) . '</a>' ) .
			apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc_short ),
	'[distance]' => __( 'distance', 'custom-shipping-methods-for-woocommerce' ) .
		sprintf( ' (' . __( 'check examples %s', 'custom-shipping-methods-for-woocommerce' ) . ')',
			'<a target="_blank" href="https://wpfactory.com/item/custom-shipping-methods-for-woocommerce/#distance-shortcode">' .
				__( 'here', 'custom-shipping-methods-for-woocommerce' ) . '</a>' ) .
			apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc_short ),
) );
$cost_desc = '';
$cost_desc .= '<ul>';
foreach ( $available_shortcodes as $shortcode => $shortcode_desc ) {
	$cost_desc .= "<li>* <code>{$shortcode}</code> - {$shortcode_desc}.</li>";
}
$cost_desc .= '</ul>';

$settings = array(
	'title' => array(
		'title'             => __( 'Method title', 'woocommerce' ),
		'type'              => 'text',
		'description'       => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		'default'           => __( 'Custom shipping', 'custom-shipping-methods-for-woocommerce' ),
		'desc_tip'          => true,
		'css'               => 'width:100%',
	),
);
if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_icon_desc_enabled', 'no' ) ) {
	$settings = array_merge( $settings, array(
		'alg_wc_csm_icon' => array(
			'title'             => __( 'Method icon (optional)', 'woocommerce' ),
			'type'              => 'text',
			'description'       => __( 'Frontend icon (URL).', 'custom-shipping-methods-for-woocommerce' ),
			'default'           => '',
			'desc_tip'          => true,
			'css'               => 'width:100%',
		),
		'alg_wc_csm_desc' => array(
			'title'             => __( 'Method description (optional)', 'woocommerce' ),
			'type'              => 'textarea',
			'description'       => __( 'Frontend description.', 'custom-shipping-methods-for-woocommerce' ),
			'default'           => '',
			'desc_tip'          => true,
			'css'               => 'width:100%',
		),
	) );
}
$settings = array_merge( $settings, array(
	'tax_status' => array(
		'title'             => __( 'Tax status', 'woocommerce' ),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'default'           => 'taxable',
		'options'           => array(
			'taxable' => __( 'Taxable', 'woocommerce' ),
			'none'    => _x( 'None', 'Tax status', 'woocommerce' ),
		),
		'css'               => 'width:100%',
	),
	'cost' => array(
		'title'             => __( 'Cost', 'woocommerce' ),
		'type'              => 'text',
		'placeholder'       => '',
		'description'       => $cost_desc,
		'default'           => '0',
		'desc_tip'          => __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ),
		'css'               => 'width:100%',
	),
	'min_cost_limit' => array(
		'title'             => __( 'Min cost limit', 'woocommerce' ),
		'desc_tip'          => __( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' ),
		'type'              => 'number',
		'placeholder'       => '',
		'default'           => '0',
		'css'               => 'width:100%',
		'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'cost' ),
		'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
	),
	'max_cost_limit' => array(
		'title'             => __( 'Max cost limit', 'woocommerce' ),
		'desc_tip'          => __( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' ),
		'type'              => 'number',
		'placeholder'       => '',
		'default'           => '0',
		'css'               => 'width:100%',
		'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'cost' ),
		'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
	),
	'free_shipping_min_amount' => array(
		'title'             => __( 'Free shipping min amount', 'woocommerce' ),
		'desc_tip'          => __( 'Free shipping minimum order amount.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
			__( 'Calculated per package / shipping class.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
			__( 'Ignored if set to zero.', 'custom-shipping-methods-for-woocommerce' ),
		'type'              => 'number',
		'placeholder'       => '',
		'default'           => '0',
		'css'               => 'width:100%',
		'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'cost' ),
		'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
	),
	'availability' => array(
		'title'             => __( 'Availability', 'woocommerce' ),
		'type'              => 'title',
		'default'           => '',
		'description'       => __( 'Method availability.', 'custom-shipping-methods-for-woocommerce' ),
	),
	'min_cost' => array(
		'title'             => __( 'Min cart cost', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Minimum total cart cost.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '0.0000000001', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'max_cost' => array(
		'title'             => __( 'Max cart cost', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Maximum total cart cost.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '0.0000000001', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'min_weight' => array(
		'title'             => __( 'Min cart weight', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Minimum total cart weight.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '0.0000000001', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'max_weight' => array(
		'title'             => __( 'Max cart weight', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Maximum total cart weight.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '0.0000000001', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'min_volume' => array(
		'title'             => __( 'Min cart volume', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Minimum total cart volume.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '0.0000000001', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'max_volume' => array(
		'title'             => __( 'Max cart volume', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Maximum total cart volume.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '0.0000000001', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'min_qty' => array(
		'title'             => __( 'Min cart quantity', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Minimum total cart quantity.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '1', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'max_qty' => array(
		'title'             => __( 'Max cart quantity', 'woocommerce' ),
		'type'              => 'number',
		'description'       => __( 'Maximum total cart quantity.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc,
		'default'           => 0,
		'desc_tip'          => true,
		'custom_attributes' => array( 'step' => '1', 'min' => '0' ),
		'css'               => 'width:100%',
	),
	'min_distance' => array(
		'title'             => __( 'Min distance', 'woocommerce' ),
		'type'              => 'number',
		'desc_tip'          => __( 'Minimum distance.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc . ' ' .
			__( '"Distance calculation" option must be filled in.', 'custom-shipping-methods-for-woocommerce' ),
		'default'           => 0,
		'css'               => 'width:100%',
		'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'distance' ),
		'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
	),
	'max_distance' => array(
		'title'             => __( 'Max distance', 'woocommerce' ),
		'type'              => 'number',
		'desc_tip'          => __( 'Maximum distance.', 'custom-shipping-methods-for-woocommerce' ) . $availability_extra_desc . ' ' .
			__( '"Distance calculation" option must be filled in.', 'custom-shipping-methods-for-woocommerce' ),
		'default'           => 0,
		'css'               => 'width:100%',
		'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ), 'distance' ),
		'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
	),
	'distance_calculation' => array(
		'title'             => __( 'Distance calculation', 'woocommerce' ),
		'type'              => 'text',
		'desc_tip'          => __( 'Used for "Min distance" and "Max distance" options.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
			sprintf( __( 'You should use %s shortcode here.', 'custom-shipping-methods-for-woocommerce' ), '<strong>[distance]</strong>' ),
		'default'           => '',
		'css'               => 'width:100%',
		'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'readonly' => 'readonly' ) ),
		'description'       => apply_filters( 'alg_wc_custom_shipping_methods_settings', $pro_desc ),
	),
) );

$shipping_classes = WC()->shipping->get_shipping_classes();
if ( ! empty( $shipping_classes ) ) {
	$settings['class_costs'] = array(
		'title'             => __( 'Shipping class costs', 'woocommerce' ),
		'type'              => 'title',
		'default'           => '',
		'description'       => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'woocommerce' ),
			admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
	);
	foreach ( $shipping_classes as $shipping_class ) {
		if ( ! isset( $shipping_class->term_id ) ) {
			continue;
		}
		$settings[ 'class_cost_' . $shipping_class->term_id ] = array(
			/* translators: %s: shipping class name */
			'title'             => sprintf( __( '"%s" shipping class cost', 'woocommerce' ), esc_html( $shipping_class->name ) ),
			'type'              => 'text',
			'placeholder'       => __( 'N/A', 'woocommerce' ),
			'description'       => __( 'See "Cost" option description above for available options.', 'custom-shipping-methods-for-woocommerce' ),
			'default'           => $this->get_option( 'class_cost_' . $shipping_class->slug ),
			'desc_tip'          => true,
			'css'               => 'width:100%',
		);
	}
	$settings['no_class_cost'] = array(
		'title'             => __( 'No shipping class cost', 'woocommerce' ),
		'type'              => 'text',
		'placeholder'       => __( 'N/A', 'woocommerce' ),
		'description'       => __( 'See "Cost" option description above for available options.', 'custom-shipping-methods-for-woocommerce' ),
		'default'           => '',
		'desc_tip'          => true,
		'css'               => 'width:100%',
	);
	$settings['type'] = array(
		'title'             => __( 'Calculation type', 'woocommerce' ),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'default'           => 'class',
		'options'           => array(
			'class' => __( 'Per class: Charge shipping for each shipping class individually', 'woocommerce' ),
			'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'woocommerce' ),
		),
		'css'               => 'width:100%',
	);
	$settings['limit_calc'] = array(
		'title'             => __( 'Limits calculation', 'woocommerce' ),
		'desc_tip'          => __( 'For "Min cost limit" and "Max cost limit" options.', 'custom-shipping-methods-for-woocommerce' ),
		'type'              => 'select',
		'class'             => 'wc-enhanced-select',
		'default'           => 'class',
		'options'           => array(
			'class' => __( 'Per class: Check limits for each shipping class individually', 'custom-shipping-methods-for-woocommerce' ),
			'order' => __( 'Per order: Check limits for final cost only', 'custom-shipping-methods-for-woocommerce' ),
			'all'   => __( 'All: Check limits for each shipping class individually and then for final cost', 'custom-shipping-methods-for-woocommerce' ),
		),
		'css'               => 'width:100%',
	);
}

$settings = array_merge( $settings, array(
	'advanced' => array(
		'title'             => __( 'Advanced', 'custom-shipping-methods-for-woocommerce' ),
		'type'              => 'title',
		'default'           => '',
		'description'       => __( 'Advanced settings.', 'custom-shipping-methods-for-woocommerce' ),
	),
	'return_url' => array(
		'title'             => __( 'Custom return URL', 'custom-shipping-methods-for-woocommerce' ),
		'type'              => 'text',
		'description'       => __( 'Will be used instead of the standard "Order received" page.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
			__( 'Ignored if empty.', 'custom-shipping-methods-for-woocommerce' ),
		'default'           => '',
		'desc_tip'          => true,
		'css'               => 'width:100%',
	),
) );

return $settings;
