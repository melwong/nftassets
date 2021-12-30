<?php
/**
 * myticket Theme Customizer.
 *
 * @package myticket
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function myticket_customize_register( $wp_customize ) {
    
    //add description
    $wp_customize->add_setting( 'myticket_desc',
                                array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_desc', array(
                                'label'     => esc_html__( 'Description', 'myticket' ),
                                'section'   => 'title_tagline',
                                'priority'  => 10,
                                'type'      => 'textarea'
                                ) );
    
    //add footnote
    $wp_customize->add_setting( 'myticket_footnote',
                                 array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_footnote', array(
                                'label'     => esc_html__( 'Footer Note', 'myticket' ),
                                'section'   => 'title_tagline',
                                'priority'  => 10,
                                'type'      => 'textarea'
                                ) );
    
    //add social note
    $wp_customize->add_setting( 'myticket_footsocialnote',
                                 array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_footsocialnote', array(
                                'label'     => esc_html__( 'Footer Social Note', 'myticket' ),
                                'section'   => 'title_tagline',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );

    //add widget location
    $wp_customize->add_setting( 'sidebar_location', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'sidebar_location', array(
                                'label'     => esc_html__( 'Widget sidebar location', 'myticket' ),
                                'section'   => 'myticket_advanced',
                                'priority'  => 30,
                                'type'      => 'radio',
                                'choices'   => array(
                                        'left'  => 'Left',
                                        'right' => 'Right',
                                        //'colored' => 'Colored',
                                 ),
                                 ) );

    //add social network support
    $wp_customize->add_section( 'myticket_social_section' , array(
                                'title'       => esc_html__( 'Social Networks', 'myticket' ),
                                'priority'    => 23,
                                'description' => 'Set up social network links and icons, enter Twitter API keys.',
                                ) );

    $wp_customize->add_setting( 'social_blog', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'social_blog', array(
                                'label'     => esc_html__( 'Enable/disable social share in blog', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'checkbox'
                                ) );

    $wp_customize->add_setting( 'facebook', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'facebook', array(
                                'label'     => esc_html__( 'Facebook', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'youtube', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                 ) );
    
    $wp_customize->add_control( 'youtube', array(
                                'label'     => esc_html__( 'Youtube', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'twitter', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'twitter', array(
                                'label'     => esc_html__( 'Twitter', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'linkedin', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'linkedin', array(
                                'label'     => esc_html__( 'LinkedIn', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'pinterest', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'pinterest', array(
                                'label'     => esc_html__( 'Pinterest', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'google', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'google', array(
                                'label'     => esc_html__( 'Google', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'tumblr', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'tumblr', array(
                                'label'     => esc_html__( 'Tumblr', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'instagram', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'instagram', array(
                                'label'     => esc_html__( 'Instagram', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'vimeo', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'vimeo', array(
                                'label'     => esc_html__( 'Vimeo', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'vk', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'vk', array(
                                'label'     => esc_html__( 'Vkontakte', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
    
    $wp_customize->add_setting( 'disqus', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'disqus', array(
                                'label'     => esc_html__( 'Disqus', 'myticket' ),
                                'section'   => 'myticket_social_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );
 
    $wp_customize->add_setting( 'kenzap', array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                     )  );
    
    $wp_customize->add_control( 'kenzap', array(
           'label'     => esc_html__( 'Kenzap', 'myticket' ),
           'section'   => 'myticket_social_section',
           'priority'  => 10,
           'type'      => 'text'
    ) );


    //add fonts section
    $wp_customize->add_section( 'myticket_fonts_section' , array(
            'title'       => esc_html__( 'Fonts', 'myticket' ),
            'priority'    => 23,
            'description' => esc_html__( 'Override default theme fonts using Google library.', 'myticket' ).' <a href="https://fonts.google.com">'.esc_html__( 'Fonts demo', 'myticket' ).'</a>',
    ) );
    
    $wp_customize->add_setting( 'myticket_font1', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_font1', array(
                                'label'     => esc_html__( 'Heading font family', 'myticket' ),
                                'section'   => 'myticket_fonts_section',
                                'priority'  => 10,
                                'type'      => 'select',
                                'choices'   => myticket::google_fonts(),
                                ) );

    $wp_customize->add_setting( 'myticket_font2', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_font2', array(
                                'label'     => esc_html__( 'Menu font family', 'myticket' ),
                                'section'   => 'myticket_fonts_section',
                                'priority'  => 30,
                                'type'      => 'select',
                                'choices'   => myticket::google_fonts(),
                                ) );

    $wp_customize->add_setting( 'myticket_font3', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_font3', array(
                                'label'     => esc_html__( 'Body font family', 'myticket' ),
                                'section'   => 'myticket_fonts_section',
                                'priority'  => 50,
                                'type'      => 'select',
                                'choices'   => myticket::google_fonts(),
                                ) );


    // add "Header" section
    $wp_customize->add_section( 'myticket_header' , array(
                                'title'      => esc_html__( 'Header', 'myticket' ),
                                'priority'   => 22,
                                ) );
    
    // add setting for page comment toggle checkbox
    $wp_customize->add_setting( 'myticket_page_comment_toggle', array(
                                'default' => 1,
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    // add control for page comment toggle checkbox
    $wp_customize->add_control( 'myticket_page_comment_toggle', array(
                                'label'     => esc_html__( 'Show comments on pages?', 'myticket' ),
                                'section'   => 'myticket_advanced',
                                'priority'  => 10,
                                'type'      => 'checkbox'
                                ) );
    
    // enable cart icon
    $wp_customize->add_setting( 'myticket_cart', array(
                                 'default' => 1,
                                 'sanitize_callback' => 'myticket_sanitize_text',
                                ) );

    // add control for page comment toggle checkbox
    $wp_customize->add_control( 'myticket_cart', array(
                                'label'     => esc_html__( 'Enable Cart', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 10,
                                'description' => 'If using WooCommerce plugin enable this option. Choose plan button will be removed.',
                                'type'      => 'checkbox'
                                ) );

    //enable disable sticky header
    $wp_customize->add_setting( 'myticket_sticky', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_sticky', array(
                                'label'     => esc_html__( 'Sticky header', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 10,
                                'description' => 'Enable/disable sticky header during page scroll',
                                'type'      => 'checkbox'
                                ) );

    //enable disable tob navigation bar
    $wp_customize->add_setting( 'myticket_top_header', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_top_header', array(
                                'label'     => esc_html__( 'Top header', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 10,
                                'description' => 'Enable/disable top navigation header',
                                'type'      => 'checkbox'
                                ) );

    //enable disable sign in cont
    $wp_customize->add_setting( 'myticket_sign_cont', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_sign_cont', array(
                                'label'     => esc_html__( 'Sign in container', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 10,
                                'description' => 'Enable/disable sign in container on the right',
                                'type'      => 'checkbox'
                                ) );

    //login link
    $wp_customize->add_setting( 'myticket_login_link', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_login_link', array(
                                'label'     => esc_html__( 'Login Link', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 20,
                                'type'      => 'text'
                                ) );

    //register link
    $wp_customize->add_setting( 'myticket_register_link', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_register_link', array(
                                'label'     => esc_html__( 'Register Link', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 20,
                                'type'      => 'text'
                                ) );

    //custom top header left side HTML code
    $wp_customize->add_setting( 'myticket_header_left_html', array(
                                'sanitize_callback' => 'myticket_sanitize_textarea',
                                ) );
    
    $wp_customize->add_control( 'myticket_header_left_html', array(
                                'label'     => esc_html__( 'Custom HTML code (left)', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 30,
                                'description' => esc_html__( 'Override left top header contacts container with custom HTML code', 'myticket' ),
                                'type'      => 'textarea'
                                ) );

    //custom top header right side HTML code
    $wp_customize->add_setting( 'myticket_header_right_html', array(
                                'sanitize_callback' => 'myticket_sanitize_textarea',
                                ) );
    
    $wp_customize->add_control( 'myticket_header_right_html', array(
                                'label'     => esc_html__( 'Custom HTML code (right)', 'myticket' ),
                                'section'   => 'myticket_header',
                                'priority'  => 30,
                                'description' => esc_html__( 'Override right top header sign in container with custom HTML code', 'myticket' ),
                                'type'      => 'textarea'
                                ) );

    //add logo support
    $wp_customize->add_section( 'myticket_logo_section' , array(
                                'title'       => esc_html__( 'Logo', 'myticket' ),
                                'priority'    => 20,
                                'description' => 'Upload a logo to replace the default site name and description in the header',
                                ) );
    
    $wp_customize->add_setting( 'myticket_logo',
                                array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                )  );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'myticket_logo', array(
                                'label'    => esc_html__( 'Logo Desktop', 'myticket' ),
                                'section'  => 'myticket_logo_section',
                                'settings' => 'myticket_logo',
                                ) ) );
    
    //Logo Desktop Width
    $wp_customize->add_setting( 'myticket_logo_width', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_logo_width', array(
                                'section'   => 'myticket_logo_section',
                                'priority'  => 10,
                                'description' => 'Maximum width of your desktop logo in px. Height will be adjusted automatically.',
                                'type'      => 'number'
                                ) );

    //add mobile logo support
    $wp_customize->add_setting(
                               'myticket_logo_mobile',
                                array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                     )
                                );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'myticket_logo_mobile', array(
                                'label'    => esc_html__( 'Logo Mobile', 'myticket' ),
                                'section'  => 'myticket_logo_section',
                                'settings' => 'myticket_logo_mobile',
                                ) ) );
    
    //Logo Mobile Width
    $wp_customize->add_setting( 'myticket_logo_mobile_width', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_logo_mobile_width', array(
                                'section'   => 'myticket_logo_section',
                                'priority'  => 10,
                                'description' => 'Maximum width of your mobile logo in px. Height will be adjusted automatically.',
                                'type'      => 'number'
                                ) );

    //add footer logo support
    $wp_customize->add_setting( 'myticket_logo_footer',
                               array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                               ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'myticket_logo_footer', array(
                              'label'    => esc_html__( 'Logo Footer', 'myticket' ),
                              'section'  => 'myticket_logo_section',
                              'settings' => 'myticket_logo_footer',
                              ) ) );
    
    //Logo Footer Width
    $wp_customize->add_setting( 'myticket_logo_footer_width', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_logo_footer_width', array(
                                'section'   => 'myticket_logo_section',
                                'priority'  => 10,
                                'description' => 'Maximum width of your footer logo in px. Height will be adjusted automatically.',
                                'type'      => 'number'
                                ) );
    
    //Theme Main Color
    $wp_customize->add_setting( 'myticket_main_color', array(
                                'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
            $wp_customize,
            'main_color',
            array(
                'label'      => esc_html__( 'Theme Main Color', 'myticket' ),
                'section'    => 'colors',
                'settings'   => 'myticket_main_color',
            ) )
    );
    
    $wp_customize->remove_control( 'header_textcolor' );
    $wp_customize->remove_control( 'background_color' );


	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->add_section( 'custom_css', array(
                                                    'title' => esc_html__( 'Custom CSS', 'myticket' ),
                                                    'description' => esc_html__( 'Add custom CSS here', 'myticket' ),
                                                    'panel' => '', // Not typically needed.
                                                    'priority' => 160,
                                                    'capability' => 'edit_theme_options',
                                                    'theme_supports' => '', // Rarely needed.
                                                    ) );
    
    
    // // add "MyTicket" section
    // $wp_customize->add_panel( 'myticket_master_panel' , array(
    //                          'title'        => esc_html__( 'MyTicket', 'myticket' ),
    //                          'priority'     => 80,
    //                          'capability'   => 'edit_theme_options',
    //                          ) );

    // $wp_customize->add_section( 'myticket_app_section', array(
    //                             'priority'       => 10,
    //                             'capability'     => 'edit_theme_options',
    //                             'theme_supports' => '',
    //                             'title'          => esc_html__('Mobile App', 'myticket'),
    //                             'description'    => esc_html__('Set up rules for MyTicket scanner app', 'myticket'),
    //                             'panel'  => 'myticket_master_panel',
    //                             ) );

    // $wp_customize->add_section( 'myticket_ticketing_section', array(
    //                             'priority'       => 10,
    //                             'capability'     => 'edit_theme_options',
    //                             'theme_supports' => '',
    //                             'title'          => esc_html__('Ticketing', 'myticket'),
    //                             'description'    => esc_html__('Set up general rules for ticketing operations', 'myticket'),
    //                             'panel'  => 'myticket_master_panel',
    //                             ) );

    // //combine tickets into single file
    // $wp_customize->add_setting( 'myticket_combine', array(
    //                            'sanitize_callback' => 'myticket_sanitize_text',
    //                             ) );
    
    // $wp_customize->add_control( 'myticket_combine', array(
    //                            'label'     => esc_html__( 'Combine tickets', 'myticket' ),
    //                            'section'   => 'myticket_ticketing_section',
    //                            'priority'  => 10,
    //                            'description' => 'Upon cart and checkout combine events into a single record if quantity is more than 1 ticket. By enabling this option you will also have only one invoice per order with no option to split qr-code generated invoices for each attendee.',
    //                            'type'      => 'checkbox'
    //                             ) );

    // //combine tickets into single file
    // $wp_customize->add_setting( 'myticket_participants', array(
    //                         'sanitize_callback' => 'myticket_sanitize_text',
    //                         ) );

    // $wp_customize->add_control( 'myticket_participants', array(
    //                         'label'     => esc_html__( 'Participant Data', 'myticket' ),
    //                         'section'   => 'myticket_ticketing_section',
    //                         'priority'  => 10,
    //                         'description' => 'Upon cart page force users to enter ticket holder email and full name to use it for QR-code scanning.',
    //                         'type'      => 'checkbox'
    //                         ) );
        
    // //combine tickets into single file
    // $wp_customize->add_setting( 'myticket_email_thankyou', array(
    //     'sanitize_callback' => 'myticket_sanitize_text',
    //     'default' => 0
    //     ) );

    // $wp_customize->add_control( 'myticket_email_thankyou', array(
    //     'label'     => esc_html__( 'Email Immediately', 'myticket' ),
    //     'section'   => 'myticket_ticketing_section',
    //     'priority'  => 10,
    //     'description' => 'Send QR-code emails immediately after order is placed (without status confirmed).',
    //     'type'      => 'checkbox'
    //     ) );

    // //combine tickets into single file
    // $wp_customize->add_setting( 'myticket_email_order_completed', array(
    //     'sanitize_callback' => 'myticket_sanitize_text',
    //     'default' => 1
    //     ) );

    // $wp_customize->add_control( 'myticket_email_order_completed', array(
    //     'label'     => esc_html__( 'Email After Confirmation', 'myticket' ),
    //     'section'   => 'myticket_ticketing_section',
    //     'priority'  => 10,
    //     'description' => 'Send QR-code emails after order status changes to confirmed.',
    //     'type'      => 'checkbox'
    //     ) );


    // //checkout thank you page popup
    // $wp_customize->add_setting( 'myticket_app_private', array(
    //                            'sanitize_callback' => 'myticket_sanitize_text',
    //                             ) );
    
    // $wp_customize->add_control( 'myticket_app_private', array(
    //                            'label'     => esc_html__( 'Private access', 'myticket' ),
    //                            'section'   => 'myticket_app_section',
    //                            'priority'  => 10,
    //                            'description' => 'Restrict access to apps with IDs listed below.',
    //                            'type'      => 'checkbox'
    //                             ) );

    // //products per page
    // $wp_customize->add_setting( 'myticket_app_ids', array(
    //                            'sanitize_callback' => 'myticket_sanitize_text',
    //                             ) );
    
    // $wp_customize->add_control( 'myticket_app_ids', array(
    //                            'label'     => esc_html__( 'Allowed app IDs', 'myticket' ),
    //                            'section'   => 'myticket_app_section',
    //                            'priority'  => 10,
    //                            'description' => 'Open MyTicket app > Settings > Scroll down to find its unique ID number and provide it below. Specify only one ID per line. Ex.: E4RR5R6R.',
    //                            'type'      => 'textarea'
    //                             ) );

    // add "Advanced" section
    $wp_customize->add_panel( 'myticket_ecommerce_panel' , array(
                             'title'      => esc_html__( 'E-commerce', 'myticket' ),
                             'description'    =>  esc_html__('E-commerce, banner, units, products master settings.', 'myticket'),
                             'priority'   => 80,
                             'capability'     => 'edit_theme_options',
                             ) );

    $wp_customize->add_section( 'myticket_ecommerce', array(
                                'priority'       => 10,
                                'capability'     => 'edit_theme_options',
                                'theme_supports' => '',
                                'title'          => esc_html__('Products', 'myticket'),
                                'description'    => esc_html__('Configure product visual and styling settings.', 'myticket'),
                                'panel'  => 'myticket_ecommerce_panel',
                                ) );

    $wp_customize->add_section( 'myticket_ecommerce2', array(
                                'priority'       => 10,
                                'capability'     => 'edit_theme_options',
                                'theme_supports' => '',
                                'title'          => esc_html__('Support', 'myticket'),
                                'description'    => esc_html__('Cart, checkout banner and support page settings.', 'myticket'),
                                'panel'  => 'myticket_ecommerce_panel',
                                ) );

    //products per page
    $wp_customize->add_setting( 'myticket_products_num', array(
                               'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_products_num', array(
                               'label'     => esc_html__( 'Number of products', 'myticket' ),
                               'section'   => 'myticket_ecommerce',
                               'priority'  => 10,
                               'description' => 'Default number of products per category in grid',
                               'type'      => 'text'
                                ) );

    //checkout thank you page popup
    $wp_customize->add_setting( 'myticket_thankyou', array(
                               'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_thankyou', array(
                               'label'     => esc_html__( 'Thank You Page', 'myticket' ),
                               'section'   => 'myticket_ecommerce',
                               'priority'  => 10,
                               'description' => 'Override default MyTicket checkout thank you page and display default WooCommerce page',
                               'type'      => 'checkbox'
                                ) );

    //add widget location
    $wp_customize->add_setting( 'sidebar_sprod', array(
                               'sanitize_callback' => 'myticket_sanitize_text',
    ) );
    
    $wp_customize->add_control( 'sidebar_sprod', array(
                               'label'     => esc_html__( 'Enable sidebar in single product', 'myticket' ),
                               'section'   => 'myticket_ecommerce',
                               'priority'  => 30,
                               'type'      => 'radio',
                               'default'   => '1',
                               'choices'   => array(
                                        '1' => 'Enable',
                                        '0' => 'Disable',
                                        //'colored' => 'Colored',
                                ),
    ) );

    //add contacts support
    $wp_customize->add_section( 'myticket_contacts_section' , array(
                                'title'       => esc_html__( 'Contacts', 'myticket' ),
                                'priority'    => 30,
                                'description' => esc_html__( 'Set up contact details.', 'myticket' ),
                                ) );


    //phone number 1
    $wp_customize->add_setting( 'myticket_phone',
                               array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_phone', array(
                                'label'     => esc_html__( 'Phone Number 1', 'myticket' ),
                                'section'   => 'myticket_contacts_section',
                                'priority'  => 20,
                                'type'      => 'text'
                                ) );
    
    //phone number 2
    $wp_customize->add_setting( 'myticket_phone2',
                               array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_phone2', array(
                               'label'     => esc_html__( 'Phone Number 2', 'myticket' ),
                               'section'   => 'myticket_contacts_section',
                               'priority'  => 21,
                               'type'      => 'text'
                               ) );
    
    //email
    $wp_customize->add_setting( 'myticket_email',
                               array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_email', array(
                                'label'     => esc_html__( 'Email', 'myticket' ),
                                'section'   => 'myticket_contacts_section',
                                'priority'  => 10,
                                'type'      => 'text'
                                ) );

    //address
    $wp_customize->add_setting( 'myticket_address',
                               array(
                                     'sanitize_callback' => 'myticket_sanitize_text',
                                ) );
    
    $wp_customize->add_control( 'myticket_address', array(
                                'label'     => esc_html__( 'Address', 'myticket' ),
                                'description' => esc_html__( 'Your address may not appear on website but it will be used by search engines. Make sure it can be found in Google Maps', 'myticket' ),
                                'section'   => 'myticket_contacts_section',
                                'priority'  => 50,
                                'type'      => 'textarea'
                                ) );

    // add "Advanced" section
    $wp_customize->add_section( 'myticket_advanced' , array(
                                 'title'      => esc_html__( 'Advanced', 'myticket' ),
                                 'priority'   => 100,
                                 ) );
    
    // add setting for page comment toggle checkbox
    $wp_customize->add_setting( 'myticket_minified', array(
                                 'default' => 1,
                                 'sanitize_callback' => 'myticket_sanitize_text',
                                 ) );
    
    // add control for page comment toggle checkbox
    $wp_customize->add_control( 'myticket_minified', array(
                                 'label'     => esc_html__( 'Minify JS and CSS', 'myticket' ),
                                 'section'   => 'myticket_advanced',
                                 'priority'  => 10,
                                 'description' => 'May significantly improve website performance and overall load times',
                                 'type'      => 'checkbox'
                                 ) );

    // add setting for page comment toggle checkbox
    $wp_customize->add_setting( 'myticket_maps_api', array(
                                 'default' => 1,
                                 'sanitize_callback' => 'myticket_sanitize_text',
                                 ) );

    // add control for page comment toggle checkbox
    $wp_customize->add_control( 'myticket_maps_api', array(
                                 'label'     => esc_html__( 'Google Maps API Key', 'myticket' ),
                                 'section'   => 'myticket_advanced',
                                 'priority'  => 15,
                                 'description' => 'Can be obtained here: https://developers.google.com/maps/documentation/javascript/get-api-key',
                                 'type'      => 'text'
                                 ) );
    

}
add_action( 'customize_register', 'myticket_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function myticket_customize_preview_js() {
	wp_enqueue_script( 'myticket_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'myticket_customize_preview_js' );


function myticket_sanitize_text( $str ) {
    return wp_kses( $str, array( 
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
    ) );
} 

function myticket_sanitize_textarea( $str ) {

    return wp_kses( $str, array( 
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
    ) );
} 

