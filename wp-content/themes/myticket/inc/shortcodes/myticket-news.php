<?php

/*
Widget Name: Myticket News Widget
Description: Create news Signup Section
Author: Kenzap
Author URI: http://kenzap.com
Widget URI: http://kenzap.com/,
Video URI: http://kenzap.com/
*/

if( class_exists( 'SiteOrigin_Widget' ) ) : 

class myticket_news_widget extends SiteOrigin_Widget {

    function __construct() {
        //Here you can do any preparation required before calling the parent constructor, such as including additional files or initializing variables.

        //Call the parent constructor with the required arguments.
        parent::__construct(
            // The unique id for your widget.
            'myticket_news_widget',

            // The name of the widget for display purposes.
            esc_html__('MyTicket News', 'myticket'),

            // The $widget_options array, which is passed through to WP_Widget.
            // It has a couple of extras like the optional help URL, which should link to your sites help or support page.
            array(
                'description' => esc_html__('Create News Signup Section', 'myticket'),
                'panels_groups' => array('myticket'),
                'help'        => 'http://myticket_docs.kenzap.com',
            ),

            //The $control_options array, which is passed through to WP_Widget
            array(
            ),

            //The $form_options array, which describes the form fields used to configure SiteOrigin widgets. We'll explain these in more detail later.
            array(
                'title' => array(
                    'type' => 'text',
                    'label' => esc_html__('News title', 'myticket'),
                    'default' => ''
                ),
                'title_right' => array(
                    'type' => 'text',
                    'label' => esc_html__('News title right', 'myticket'),
                    'default' => ''
                ),
                'category' => array(
                    'type' => 'text',
                    'label' => esc_html__('News category', 'myticket'),
                    'default' => ''
                ),
                'records_per_page' => array(
                    'type' => 'text',
                    'label' => esc_html__('Max news records', 'myticket'),
                    //'description' => esc_html__('May not apply to all news types', 'myticket'),
                    'default' => ''
                ),
                'twitter' => array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Enable/Disable Tweets', 'myticket'),
                    'description' => esc_html__('Works only if Twitter settings below are provided.', 'myticket'),
                ),
                'title_twitter' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter title', 'myticket'),
                    'default' => ''
                ),
                'twitter_c_key' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter Consumer Key:', 'myticket'),
                    //'description' => esc_html__('Go to Mailchimp for WP > Forms to find your form id', 'myticket'),
                    'default' => ''
                ),
                'twitter_c_secret' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter Consumer Secret:', 'myticket'),
                    //'description' => esc_html__('Go to Mailchimp for WP > Forms to find your form id', 'myticket'),
                    'default' => ''
                ),          
                'twitter_a_token' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter Access Token:', 'myticket'),
                    //'description' => esc_html__('Go to Mailchimp for WP > Forms to find your form id', 'myticket'),
                    'default' => ''
                ),         
                'twitter_a_key' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter Access Token Secret:', 'myticket'),
                    //'description' => esc_html__('Go to Mailchimp for WP > Forms to find your form id', 'myticket'),
                    'default' => ''
                ),   
                'twitter_username' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter Username:', 'myticket'),
                    //'description' => esc_html__('Go to Mailchimp for WP > Forms to find your form id', 'myticket'),
                    'default' => ''
                ),                   
                'twitter_max' => array(
                    'type' => 'text',
                    'label' => esc_html__('Twitter max tweets:', 'myticket'),
                    'description' => esc_html__('Maximum number of tweets to query', 'myticket'),
                    'default' => ''
                ),  
                'twitter_logo' => array(
                    'type' => 'media',
                    'label' => esc_html__( 'Choose logo', 'myticket' ),
                    'choose' => esc_html__( 'Choose image', 'myticket' ),
                    'update' => esc_html__( 'Set image', 'myticket' ),
                    'library' => 'image',
                    'fallback' => true
                ),   
            ),

            //The $base_folder path string.
            plugin_dir_path(__FILE__)
        );
    }

    function get_template_name($instance) {
        return 'myticket-news';
    }

    function get_template_dir($instance) {
        return 'widgets';
    }
}

siteorigin_widget_register('myticket_news_widget', __FILE__, 'myticket_news_widget');

endif;