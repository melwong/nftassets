<?php
/**
 * Include the Customizer Library
 * @since 2.3.3
 */

 // Exit if accessed directly
 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

add_action( 'customize_register', 'pewc_add_customizer_section' );

function pewc_add_customizer_section( $wp_customize ) {
  pewc_add_product_extras_section( $wp_customize );
}

function pewc_add_product_extras_section( $wp_customize ) {
  $wp_customize->add_section(
    'pewc_section',
    array(
      'title'    => __( 'Product Add-Ons Ultimate', 'woocommerce' ),
      'priority' => 100,
      'panel'    => 'woocommerce',
    )
  );

  $wp_customize->add_setting(
    'pewc_price_label',
    array(
      'default'              => '',
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_setting(
    'pewc_price_display',
    array(
      'default'              => 'before',
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_setting(
    'pewc_show_totals',
    array(
      'default'              => 'all',
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_setting(
    'pewc_product_total_label',
    array(
      'default'              => __( 'Product total', 'pewc' ),
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_setting(
    'pewc_options_total_label',
    array(
      'default'              => __( 'Options total', 'pewc' ),
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_setting(
    'pewc_flatrate_total_label',
    array(
      'default'              => __( 'Flat rate total', 'pewc' ),
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_setting(
    'pewc_grand_total_label',
    array(
      'default'              => __( 'Grand total', 'pewc' ),
      'type'                 => 'option',
      'capability'           => 'manage_woocommerce',
      // 'sanitize_callback'    => 'wc_bool_to_string',
      // 'sanitize_js_callback' => 'wc_string_to_bool',
    )
  );

  $wp_customize->add_control(
    'pewc_price_label',
    array(
      'label'    => __( 'Price label', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_price_label',
      'type'     => 'text'
    )
  );

  $wp_customize->add_control(
    'pewc_price_display',
    array(
      'label'    => __( 'Price label display', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_price_display',
      'type'        => 'select',
      'choices'     => array(
        'before'			=> __( 'Before price', 'pewc' ),
        'after'				=> __( 'After price', 'pewc' ),
        'hide'				=> __( 'Hide price', 'pewc' )
      ),
    )
  );


  $wp_customize->add_control(
    'pewc_show_totals',
    array(
      'label'    => __( 'Display totals fields', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_show_totals',
      'type'        => 'select',
      'choices'     => array(
        'all'           => __( 'Show totals', 'woocommerce' ),
        'none'          => __( 'Hide totals', 'woocommerce' ),
        'total'         => __( 'Total only', 'woocommerce' ),
      ),
    )
  );

  $wp_customize->add_control(
    'pewc_product_total_label',
    array(
      'label'    => __( 'Product total label', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_product_total_label',
      'type'        => 'text'
    )
  );

  $wp_customize->add_control(
    'pewc_options_total_label',
    array(
      'label'    => __( 'Options total label', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_options_total_label',
      'type'        => 'text'
    )
  );

  $wp_customize->add_control(
    'pewc_flatrate_total_label',
    array(
      'label'    => __( 'Flat rate total label', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_flatrate_total_label',
      'type'        => 'text'
    )
  );

  $wp_customize->add_control(
    'pewc_grand_total_label',
    array(
      'label'    => __( 'Grand total label', 'woocommerce' ),
      'section'  => 'pewc_section',
      'settings' => 'pewc_grand_total_label',
      'type'        => 'text'
    )
  );

}
