<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Create a new cmp_render_html class that will extend the CMP_Coming_Soon_and_Maintenance
 */
class CMP_Coming_Soon_and_Maintenance_Render_HTML extends CMP_Coming_Soon_and_Maintenance {

    // Render Background
    public function cmp_background( $niteoCS_banner, $themeslug ) {

    	$size = $this->isMobile() ? 'large' : 'full';
        $html = '';

        // change background if preview is set
        if ( isset( $_GET['background'] ) && !empty($_GET['background']) ) {
            $niteoCS_banner = $_GET['background'] == '1' ? 0 : esc_attr($_GET['background']);
        }

        switch ( $niteoCS_banner ) {
            // custom media
            case '0':
                $banner_ids = $this->cmp_get_banner_ids();
                
                if ( !empty($banner_ids) ) {
                    $banner_url = wp_get_attachment_image_src( $banner_ids[mt_rand(0, count( $banner_ids ) - 1)], $size);

                    if ( isset($banner_url[0]) ) {
                        $banner_url = $banner_url[0];
                    }

                } else {
                    // send default image
                    $banner_url = $this->cmp_themeURL( $themeslug ).$themeslug.'/img/'.$themeslug.'_banner_'.$size.'.jpg';
                }

                $html = '<div id="background-image" class="image" style="background-image:url(\''.esc_url( $banner_url ).'\')"></div>';
                break;

            case '1':
                // unsplash
                $background_class = 'image';
                $unplash_feed   = get_option('niteoCS_unsplash_feed', '3');

                switch ( $unplash_feed ) {
                    // specific photo from id
                    case '0':
                        $params = array('feed' => '0', 'url' => get_option('niteoCS_unsplash_0', '') );
                        $unsplash = $this->niteo_unsplash(  $params );
                        break;

                    // random from user
                    case '1':
                        $params = array('feed' => '1', 'custom_str' => get_option('niteoCS_unsplash_1', '') );
                        $unsplash = $this->niteo_unsplash(  $params );
                        break;

                    // random from collection
                    case '2':
                        $params = array('feed' => '2', 'url' => get_option('niteoCS_unsplash_2', '') );
                        $unsplash = $this->niteo_unsplash(  $params );
                        break;

                    // random photo
                    case '3':
                        $params = array('feed' => '3', 'url' => get_option('niteoCS_unsplash_3', ''), 'feat' => get_option('niteoCS_unsplash_feat', '0') );
                        $unsplash = $this->niteo_unsplash(  $params );
                        break;
                    default:
                        break;
                }
                // get raw url from response
                if ( isset( $unsplash['response'] ) && $unsplash['response'] == '200' ) {
                    $body = json_decode ($unsplash['body'], true );

                    if ( isset( $body[0] ) ) {
                        foreach ( $body as $item ) {
                            $unsplash_download  = $item['links']['download_location'];
                        }

                    } else {
                        $unsplash_download      = $body['links']['download_location'];
                    } 

                    switch ( $themeslug ) {
                        case 'element':
                            $width = 1;
                            $height = 0.6;
                            break;
                        
                        default:
                            $width = 1;
                            $height = 1;
                            break;
                    }

                    ob_start(); ?>

                    <script>
                        var unsplash_download = '<?php echo esc_url( $unsplash_download );?>';

                        var width = document.documentElement.clientWidth * <?php echo esc_attr( $width );?>;
                        var height = document.documentElement.clientHeight * <?php echo esc_attr( $height );?>;
                        var dimension = 'w=' + width;
                        if ( width < height ) {
                            dimension = 'h=' + height;
                        }

                        var unsplash_url = '<?php echo esc_url( $unsplash_download );?>?client_id=41f043163758cf2e898e8a868bc142c20bc3f5966e7abac4779ee684088092ab';

                        fetch(unsplash_url, {
                            method: 'GET',
                        })
                        .then((res) => {
                            return res.json();
                        })
                        .then((data) => {

                            var imgContainer  = '<div id="background-image" class="image" style="background-image:url()"></div>'; 

                            var image = document.createElement('div');

                            image.id = 'background-image';


                            var container = document.getElementById("background-wrapper");

                            if ( container == null ) {
                                container = document.getElementById("banner-wrapper");
                            }

                            container.insertAdjacentElement('afterbegin', image);

                            var unsplashImg = new Image();

                            unsplashImg.onload = function() {
                                image.className = 'image loaded';
                                image.style.backgroundImage = `url("${unsplashImg.src}")`;
                            }

                            unsplashImg.src = `${data.url}&fit=crop&${dimension}`;
 
                        })
                        .catch(function(error) { console.log(error.message); });
                    </script>
                    <?php 

                    $html = ob_get_clean();
                } 

                break;

            case '2':
                // default image
                $banner_url = $this->cmp_themeURL( $themeslug ).$themeslug.'/img/'.$themeslug.'_banner_'.$size.'.jpg';
                $html = '<div id="background-image" class="image" style="background-image:url(\''.esc_url( $banner_url ).'\')"></div>';
                break;

            case '3':
                // Pattern
                $niteoCS_banner_pattern = get_option('niteoCS_banner_pattern', 'sakura');

                if ( $niteoCS_banner_pattern != 'custom' ) {
                    $banner_url =  plugin_dir_url( dirname( __FILE__ ) ).'img/patterns/'.esc_attr( $niteoCS_banner_pattern ).'.png';   

                } else {
                    $banner_url = get_option('niteoCS_banner_pattern_custom');
                    $banner_url = wp_get_attachment_image_src( $banner_url, 'large' );
                    if ( isset($banner_url[0]) ){
                        $banner_url = $banner_url[0];
                    }
                }
                $html = '<div id="background-image" class="pattern" style="background-image:url(\''.esc_url( $banner_url ).'\')"></div>';
                break;

            case '4':
                // Color
                $color = get_option('niteoCS_banner_color', '#e5e5e5');

                $html ='<div id="background-image" class="color loaded" style="background-color:'.esc_url( $color ).'"></div>';
                break;

            case '5':
                $html = '<div id="player" class="video-banner"></div>';
                break;

            case '6':
                // Gradient
                $background_class = 'gradient';
                $niteoCS_gradient = get_option('niteoCS_gradient', '#1A2980:#26D0CE');
                if ( $niteoCS_gradient == 'custom' ) {
    				$niteoCS_gradient_one = get_option('niteoCS_banner_gradient_one', '#e5e5e5');
    				$niteoCS_gradient_two = get_option('niteoCS_banner_gradient_two', '#e5e5e5');
                } else {
    				$gradient = explode(":", $niteoCS_gradient);
    				$niteoCS_gradient_one 			= $gradient[0];
    				$niteoCS_gradient_two 			= $gradient[1];	
                }

                
                $html = '<div id="background-image" class="gradient loaded" style="background:-moz-linear-gradient(-45deg, '.esc_attr( $niteoCS_gradient_one ).' 0%, '.esc_attr( $niteoCS_gradient_two ).' 100%);background:-webkit-linear-gradient(-45deg, '.esc_attr( $niteoCS_gradient_one ).' 0%, '.esc_attr( $niteoCS_gradient_two ).' 100%);background:linear-gradient(135deg,'.esc_attr( $niteoCS_gradient_one ).' 0%, '.esc_attr( $niteoCS_gradient_two ).' 100%)"></div>';
                break;

            // CHAMELEON BACKGROUND
            case '7': 
                $html ='<div id="background-image" class="color chameleon loaded"></div>';
                break;

            default:
                break;
        }

        // add overlay to images/videos
        if ( $niteoCS_banner != '4' && $niteoCS_banner != '6' && $niteoCS_banner != '7') {
            $overlay = $this->background_overlay( $themeslug );
            $html .= $overlay;
        }

        // add text overlay
        $html .= $this->background_text_overlay( $themeslug );

        return $html;
    }


    // render slider
    public function cmp_slider( $themeslug ) {

        // change background if preview is set
        if ( isset( $_GET['background'] ) && $_GET['background'] !== '1' ) {
            echo $this->cmp_background( $_GET['background'], $themeslug );
            return;
        }

        $niteoCS_banner     = get_option('niteoCS_banner', '1');
        $slider_count       = get_option('niteoCS_slider_count', '3');
        $slider_effect      = get_option('niteoCS_slider_effect', 'true');
        $slider_autoplay    = get_option('niteoCS_slider_auto', '1');
        $size               = $this->isMobile() ? 'large' : 'full';
        $banner_ids         = $this->cmp_get_banner_ids();

        // break slider if only one custom image uploaded
        if ( $niteoCS_banner == '0' && isset( $banner_ids ) && count( $banner_ids ) < 2 ) {
            echo $this->cmp_background( '0', $themeslug ) ;
            return false;
        } ?>

        <div id="slider-wrapper">

             <div id="slider" class="slides effect-<?php echo esc_attr( $slider_effect );?>" data-autoplay="<?php echo esc_attr( $slider_autoplay );?>">
                <?php
                switch ( $niteoCS_banner ) {
                   // custom media
                    case '0':
                        if ( isset( $banner_ids ) ) {
                            foreach ( $banner_ids as $id ) {
                                $slide_url = wp_get_attachment_image_src( $id, $size);
                                
                                if ( isset( $slide_url[0] ) ) {
                                    $slide_url = $slide_url[0];
                                } ?>
                                <div class="slide">
                                    <div class="slide-background" style="background-image:url('<?php echo esc_url( $slide_url ); ?>')"></div>
                                </div>
                                <?php 
                            }
                        }
                        break;

                    // unsplash
                    case '1':
                        $unplash_feed   = get_option('niteoCS_unsplash_feed', '3');

                        switch ( $unplash_feed ) {
                            // specific photo from id
                            case '0':
                                $params = array( 'feed' => '0', 'url' => get_option('niteoCS_unsplash_0', ''), 'count' => $slider_count );
                                $unsplash = $this->niteo_unsplash(  $params );
                                break;

                            // random from user
                            case '1':
                                $params = array( 'feed' => '1', 'custom_str' => get_option('niteoCS_unsplash_1', ''), 'count' => $slider_count  );
                                $unsplash = $this->niteo_unsplash(  $params );
                                break;

                            // random from collection
                            case '2':
                                $params = array( 'feed' => '2', 'url' => get_option('niteoCS_unsplash_2', ''), 'count' => $slider_count  );
                                $unsplash = $this->niteo_unsplash(  $params );
                                break;

                            // random photo
                            case '3':
                                $params = array( 'feed' => '3', 'url' => get_option('niteoCS_unsplash_3', ''), 'feat' => get_option('niteoCS_unsplash_feat', '0'), 'count' => $slider_count  );
                                $unsplash = $this->niteo_unsplash(  $params );
                                break;

                            default:
                                break;
                        }

                        // get raw url from response
                        if ( isset( $unsplash['response'] ) && $unsplash['response'] == '200' ) {
                            $unsplash_body = json_decode($unsplash['body'], true);

                            $imgs = array();

                            if ( isset( $unsplash_body[0] ) ) {
                                foreach ( $unsplash_body as $item ) {
                                    array_push( $imgs, $item['urls']['raw']);
                                }

                            } else {
                                $imgs[0] = $unsplash_body['urls']['raw'];
                            }

                            $imgs = json_encode( $imgs ); 

                            switch ( $themeslug ) {
                                case 'element':
                                    $width = 1;
                                    $height = 0.6;
                                    break;
                                
                                default:
                                    $width = 1;
                                    $height = 1;
                                    break;
                            }
                            ?>

                            <script>
                                var imgs = <?php echo $imgs;?>;

                                var width = document.documentElement.clientWidth * <?php echo $width;?>;
                                var height = document.documentElement.clientHeight * <?php echo $height;?>;
                                var dimension = 'w=' + width;
                                if ( width < height ) {
                                    dimension = 'h=' + height;
                                }
                                var query  = '?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&fit=max&' + dimension;
                                var img = '';

                                for ( i=0; i < imgs.length; i++ ) {
                                    var slide = document.createElement('div');

                                    slide.className = 'slide';
                                    img = imgs[i] + query;
                                    var slide_background = '<div class="slide-background" style="background-image:url(\''+img+'\')"></div>'; 

                                    slide.innerHTML = slide_background;
                                    document.getElementById('slider').appendChild(slide);
                                }
                            </script>

                            <?php
                        }

                    default:
                        break;
                } ?>
            </div>

            <?php 
            echo $this->background_overlay( $themeslug );

            ?>

        </div>

        <div class="slider-nav prev"></div>
        <div class="slider-nav next"></div>
        
        <?php

        // render dot navigation for apollo theme
        if ( $themeslug == 'apollo' ) { 

            if ( $niteoCS_banner == '0') {
                $slider_count = count( $banner_ids );
            } 
           
            echo '<div class="dot-nav">';

            for ( $i=0; $i < $slider_count; $i++ ) { 
                $slide_nm = $i + 1;

                if ( $i == 0 ) {
                    echo '<div class="slide-number active" data-slide="0">0' . $slide_nm  . '</div>';
                } else {
                    echo '<div class="slide-number" data-slide="' . $i . '">0' . $slide_nm . '</div>';
                }
                
            }

            echo '</div>';

        }

        return;
    }

    /**
     * render Overlay element.
     *
     * @since 2.8
     * @return HTML 
     **/
    public function background_overlay( $themeslug ) {

        $overlay = get_option('niteoCS_overlay', 'solid-color');
        $opacity = get_option('niteoCS_overlay[opacity]', '0.4');
        $color = get_option('niteoCS_overlay[color]', '#0a0a0a');   
        $html = '';

        switch ( $overlay ) {
            case 'solid-color':     
                $html = '<div class="background-overlay solid-color" style="background-color:'.esc_attr( $color ).';opacity:'.esc_attr( $opacity ).'"></div>';
                break;

            case 'gradient':
                $overlay_gradient  = get_option('niteoCS_overlay[gradient]', '#d53369:#cbad6d');

                if ( $overlay_gradient == 'custom' ) {
                    $gradient_one = get_option('niteoCS_overlay[gradient_one]', '#e5e5e5');
                    $gradient_two = get_option('niteoCS_overlay[gradient_two]', '#e5e5e5');
                    
                } else {
                    $gradient = explode(":", $overlay_gradient);
                    $gradient_one = $gradient[0];
                    $gradient_two = $gradient[1]; 
                }

                
                $html = '<div class="background-overlay gradient" style="background:-moz-linear-gradient(-45deg, '.esc_attr( $gradient_one ).' 0%, '.esc_attr( $gradient_two ).' 100%);background:-webkit-linear-gradient(-45deg, '.esc_attr( $gradient_one ).' 0%, '.esc_attr( $gradient_two ).' 100%);background:linear-gradient(135deg,'.esc_attr( $gradient_one ).' 0%, '.esc_attr( $gradient_two ).' 100%);opacity:'.esc_attr( $opacity ).'"></div>';
                break;
            
            default:
                break;
        }

        return $html;
    }


    /**
     * render graphic Overlay text.
     *
     * @since 2.9.5
     * @return HTML 
     **/
    public function background_text_overlay( $themeslug ) {
        $overlay_text_status = get_option('niteoCS_overlay_text[status]', '1');

        // return if overlay is disabled
        if ( $overlay_text_status == '0' || !in_array( $themeslug, $this->cmp_overlay_text_themes() ) ) {
            return;
        }

        $heading           = stripslashes(get_option('niteoCS_overlay_text[heading]', 'NEW WEBSITE ON THE WAY!'));
        $paragraph         = stripslashes(get_option('niteoCS_overlay_text[paragraph]', ''));
        $button_text       = stripslashes(get_option('niteoCS_overlay_text[button_text]', 'Call to Action!'));
        $button_url        = get_option('niteoCS_overlay_text[button_url]', '');

        $heading        = ( $heading == '' ) ? '' : '<h2 class="animated fadeInRight delay-small">'.esc_html( $heading ).'</h2>';
        $paragraph      = ( $paragraph == '' ) ? '' : '<div class="animated fadeInRight delay-big">'. wpautop( esc_html( $paragraph ) ) . '</div>';
        $button         = ( $button_text == '' ) ? '' : '<a href="'.esc_html( $button_url ).'" class="button animated fadeInRight delay-huge" target="_blank">'.esc_html( $button_text ).'</a>';

        return '<div class="overlay-content">' . $heading . $paragraph . $button . '</div>';

    }


    // render Social Icons
    public function cmp_social_icons( $mode = 'icon', $title = false, $themeslug = false, $ulclass = '', $liclass = '' ) {

        $html = '';
        $ulclass = ( $ulclass != '' ) ? ' ' . $ulclass : $ulclass;
        $liclass = ( $liclass != '' ) ? ' ' . $liclass : $liclass;

        if ( $title == true ) {
            $soc_title = stripslashes( get_option('niteoCS_soc_title', 'GET SOCIAL WITH US') );
            $html = ( $soc_title == '' ) ? '' : '<h2 class="soc-title">' . esc_html( $soc_title ) . '</h2>';
        }
        
        // migrate social media to new option after update 1.4.0
        if ( get_option('niteoCS_socialmedia') ) {

            $socialmedia = stripslashes( get_option('niteoCS_socialmedia') );
            $socialmedia = json_decode( $socialmedia, true );
            //sort social icons array by hidden, then order key
            uasort( $socialmedia  , array($this,'sort_social') );

            $html = $html.'<ul class="social-list' . $ulclass . '">';

            $theme_html = ( $themeslug == 'stylo' ) ? '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="3em" height="3em" viewBox="0 0 80 80" xml:space="preserve"><circle transform="rotate(-90 40 40)" class="another-circle" cx="40" cy="40" r="36" /></svg>' : '';

            ob_start();

            foreach ( $socialmedia as $social ) {

                if ( $social['hidden'] == '0' && $social['active'] == '1') {
               
                    switch ($social['name']) {
                        case 'envelope-o':
                            echo ( $mode == 'text' ) ? '<li class="social-child' . $liclass . '"><a href="mailto:'.antispambot(esc_html($social['url'])).'" target="_blank" class="social-'.$social['name'].'">Email</a></li>' : '<li class="social-child' . $liclass . '"><a href="mailto:'.antispambot(esc_html($social['url'])).'" target="_blank" class="social-'.$social['name'].'">'.$theme_html.'<i class="fa fa-'.$social['name'].'" aria-hidden="true"></i></a></li>';
                            break;

                        case 'phone':
                            echo ( $mode == 'text' ) ? '<li class="social-child' . $liclass . '"><a href="tel:'.esc_attr($social['url']).'" target="_blank" class="social-'.$social['name'].'">'.__('Phone', 'cmp-coming-soon-maintenance').'</a></li>' : '<li class="social-child' . $liclass . '"><a href="tel:'.esc_html($social['url']).'" target="_blank" class="social-'.$social['name'].'">'.$theme_html.'<i class="fa fa-'.$social['name'].'" aria-hidden="true"></i></a></li>';
                            break;

                        case 'whatsapp':
                            echo ( $mode == 'text' ) ? '<li class="social-child' . $liclass . '"><a href="https://api.whatsapp.com/send?phone='.esc_html(str_replace('+', '', $social['url'])).'" target="_blank" class="social-'.$social['name'].'">'.ucfirst($social['name']).'</a></li>' : '<li class="social-child' . $liclass . '"><a href="https://api.whatsapp.com/send?phone='.esc_html(str_replace('+', '', $social['url'])).'" target="_blank" class="social-'.$social['name'].'">'.$theme_html.'<i class="fa fa-'.$social['name'].'" aria-hidden="true"></i></a></li>';
                            break;

                        case 'youtube':
                            echo ( $mode == 'text' ) ? '<li class="social-child' . $liclass . '"><a href="'.esc_url($social['url']).'" target="top" class="social-'.$social['name'].'">YouTube</a></li>' : '<li class="social-child' . $liclass . '"><a href="'.esc_url($social['url']).'" target="top" class="social-'.$social['name'].'">'.$theme_html.'<i class="fa fa-'.$social['name'].'" aria-hidden="true"></i></a></li>';
                            break;

                        default:
                            echo ( $mode == 'text' ) ? '<li class="social-child' . $liclass . '"><a href="'.esc_url($social['url']).'" target="top" class="social-'.$social['name'].'">'.ucfirst($social['name']).'</a></li>' : '<li class="social-child' . $liclass . '"><a href="'.esc_url($social['url']).'" target="top" class="social-'.$social['name'].'">'.$theme_html.'<i class="fa fa-'.$social['name'].'" aria-hidden="true"></i></a></li>';
                            break;
                    } 
                } 
            }

            $social_list = ob_get_clean();

            if ( $social_list != '' ) {
                return $html.$social_list.'</ul>';
            }
        }

        return;
        
    }

    // Render Logo
    public function cmp_logo( $themeslug, $class = '' ) {

        $logo_type = get_option('niteoCS_logo_type', 'text');

        $html = '';
        $class = ( $class != '' ) ? ' ' . $class : $class;

        switch ( $logo_type ) {
            case 'graphic':
                // get logo id
                $logo_id = get_option('niteoCS_logo_id');

                // get logo
                if ( $logo_id != '' ) {
                    $logo_url = wp_get_attachment_image_src( $logo_id, 'full' );
                }

                if ( isset($logo_url[0]) ) {
                    $html = '<div class="logo-wrapper image' . esc_attr( $class ) . '"><a href="'. esc_url( get_site_url() ) .'" style="text-decoration:none"><img src="'.esc_url( $logo_url[0] ).'" class="graphic-logo" alt="logo"></a></div>';
                }
                break;

            case 'text':
                $text_logo = stripslashes(get_option('niteoCS_text_logo', get_bloginfo( 'name', 'display' )));

                $html = '<div class="logo-wrapper text text-logo-wrapper' . esc_attr( $class ) . '"><a href="'. esc_url( get_site_url() ) .'" style="text-decoration:none;color:inherit"><h1 class="text-logo">'.esc_html( $text_logo ).'</h1></a></div>';
                break;

            case 'disabled':
            default:
                break;
        } 

        return $html;
    }


    // render subscribe form
    public function cmp_subscribe_form( $label = FALSE, $firstname = FALSE, $lastname = FALSE ) {
        // process emails first
        $response = $this->niteo_subscribe( true );
        
        $html = '';
        
        // get current theme
        $theme = $this->cmp_selectedTheme();

        // get type of susbscribe
        $subscribe = get_option('niteoCS_subscribe_type', '2');
        
        // if subscribers is 3rd party plugin, render form by shortcode
        if ( $subscribe == '1' ) {
            $replace  = array('<p>', '</p>' );
            $html =  str_replace($replace, '', do_shortcode( stripslashes( get_option('niteoCS_subscribe_code') ))) ; 

        // CMP subscribe form
        } else if ( $subscribe == '2' ) {

            // get GDPR message
            $niteoCS_subscribe_label    = stripslashes( get_option('niteoCS_subscribe_label') );

            //  get translation if exists
            $translation            = json_decode( get_option('niteoCS_translation'), true );
            $placeholder            = isset($translation[4]['translation']) ? stripslashes( $translation[4]['translation'] ) : 'Insert your email address.';
            $placeholder_firstname  = isset($translation[10]['translation']) ? stripslashes( $translation[10]['translation'] ) : 'First Name';
            $placeholder_lastname   = isset($translation[11]['translation']) ? stripslashes( $translation[11]['translation'] ) : 'Last Name';
            $submit                 = isset($translation[8]['translation']) ? stripslashes( $translation[8]['translation'] ) : 'Submit';

            // overwrite it with theme specific requirements
            if ( $this->cmp_selectedTheme() == 'stylo' ) {
                $placeholder            =  '&#xf003;  ' . $placeholder;
                $placeholder_firstname  =  '&#xf2c0;  ' . $placeholder_firstname;
                $placeholder_lastname   =  '&#xf2c0;  ' . $placeholder_lastname;
            }

            // overwrite it with theme specific requirements
            if ( $this->cmp_selectedTheme() == 'pluto' ) {
                $placeholder            =  '&#xf003;  ' . $placeholder;
            }

            $submit = ( $this->cmp_selectedTheme() == 'postery' ) ? '&#xf1d9;' : $submit;
            $submit = ( $this->cmp_selectedTheme() == 'juno' ) ? '&#xf1d8;' : $submit;
            $submit = ( $this->cmp_selectedTheme() == 'agency' ) ? '&#xf105;' : $submit;

            ?>
            
            <form id="subscribe-form" method="post" class="cmp-subscribe">
                <div class="cmp-form-inputs">

                    <?php wp_nonce_field('save_options','save_options_field'); ?>
                    <?php
                    // display placeholders or labels
                    switch ( $label ) {
                        case TRUE:
                            if ( $firstname === TRUE ) { ?>
                                <div class="firstname input-wrapper">
                                    <label for="firstname-subscribe"><?php echo esc_attr( $placeholder_firstname );?></label>
                                    <input type="text" id="firstname-subscribe" name="cmp_firstname">
                                </div>
                                <?php 
                            }

                            if ( $lastname === TRUE ) { ?>
                                <div class="lastname input-wrapper">
                                    <label for="lastname-subscribe"><?php echo esc_attr( $placeholder_lastname );?></label>
                                    <input type="text" id="lastname-subscribe" name="cmp_lastname">
                                </div>
                                <?php 
                            } ?>
                            <div class="email input-wrapper">
                                <label for="email-subscribe"><?php echo esc_attr( $placeholder );?></label>
                                <input type="email" id="email-subscribe" name="email" required>
                            </div>
                            <?php 
                            break;

                        case FALSE: 
                            if ( $firstname === TRUE ) { ?>
                                <input type="text" id="firstname-subscribe" name="cmp_firstname" placeholder="<?php echo esc_attr( $placeholder_firstname );?>">
                                <?php 
                            }

                            if ( $lastname === TRUE ) { ?>
                                <input type="text" id="lastname-subscribe" name="cmp_lastname" placeholder="<?php echo esc_attr( $placeholder_lastname );?>">
                                <?php 
                            } ?>

                            <input type="email" id="email-subscribe" name="email" placeholder="<?php echo esc_attr( $placeholder );?>" required> 
                            <?php 
                            break;

                        default:
                            break;
                    } 

                    if ( $this->cmp_selectedTheme() == 'mercury' ) { ?>
                        <button type="submit" id="submit-subscribe" value="<?php echo esc_attr( $submit );?>"><?php echo esc_attr( $submit );?></button>
                        <?php 
                    } else { ?>
                        <input type="submit" id="submit-subscribe" value="<?php echo esc_attr( $submit );?>">
                        <?php 
                    } ?>

                    <div style="display: none;">
                        <input type="text" name="form_honeypot" value="" tabindex="-1" autocomplete="off">
                    </div>

                    <div id="subscribe-response"><?php echo isset( $response ) ? $response : '';?></div>

                    <div id="subscribe-overlay"></div>
                </div>

                <?php 
                // render Subscribe form Message/GDPR
                if ( $niteoCS_subscribe_label != '' ) {

                    $allowed_html = array(
                        'a' => array(
                            'href' => array(),
                            'title' => array()
                        ),
                    );
                    ?>

                    <div class="cmp-form-notes">
                    <?php echo wpautop(wp_kses( $niteoCS_subscribe_label, $allowed_html )); ?>
                    </div>
                    <?php 
                } ?>

            </form>

            <script>
                /* Subscribe form script */
                <?php 
                $url = parse_url( admin_url() );
                $path = isset($url['path']) ? $url['path'] : '/wp-admin/';
                ?>
                
                var ajaxurl = '<?php echo esc_attr($path);?>admin-ajax.php';
                var security = '<?php echo wp_create_nonce( 'cmp-subscribe-action' );?>';
                var msg = '';

                window.addEventListener('load',function(event) {
                    subForm( 'subscribe-form', 'submit-subscribe', 'subscribe-response', 'email-subscribe', 'firstname-subscribe', 'lastname-subscribe' );
                });

                subForm = function(formID, buttonID, resultID, emailID, firstnameID, lastnameID) {

                    let selectForm = document.getElementById(formID); // Select the form by ID.
                    let selectButton = document.getElementById(buttonID); // Select the button by ID.
                    let selectResult = document.getElementById(resultID); // Select result element by ID.
                    let emailInput =  document.getElementById(emailID); // Select email input by ID.
                    let firstnameInput =  document.getElementById(firstnameID); // Select firstname input by ID.
                    let lastnameInput =  document.getElementById(lastnameID); // Select lastname input by ID.
                    let firstname = '';
                    let lastname = '';
                    selectButton.onclick = function(e){ // If clicked on the button.

                        if ( emailInput.value !== '' ) {
                            firstname = firstnameInput === null ? '' : firstnameInput.value;
                            lastname = lastnameInput === null ? '' : lastnameInput.value;
                            
                            // I'm using the whatwg-fetch polyfill and a polyfill for Promises.
                            fetch(ajaxurl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
                                    'Access-Control-Allow-Origin': '*',
                                },
                                body: `action=niteo_subscribe&ajax=true&form_honeypot=&email=${emailInput.value}&firstname=${firstname}&lastname=${lastname}&security=${security}`,
                                credentials: 'same-origin'
                            })
                            .then((res) => {
                                return res.json();
                            })
                            .then((data) => {
                                selectResult.innerHTML = data.message; // Display the result inside result element.
                                selectForm.classList.add('-subscribed');
                                if (data.status == 1) {
                                    selectForm.classList.remove('-subscribe-failed');
                                    selectForm.classList.add('-subscribe-successful');
                                    emailInput.value = '';
                                    firstnameInput ? firstnameInput.value = '' : null;
                                    lastnameInput ? lastnameInput.value = '' : null;
                                    <?php do_action('cmp-successfull-subscribe-action'); ?>

                                } else {
                                    selectForm.classList.add('-subscribe-failed');
                                }
                            })
                            .catch(function(error) { console.log(error.message); });
                        }
                    }

                    selectForm.onsubmit = function(){ // Prevent page refresh
                        return false;
                    }
                }
            </script>
            <?php 
        }

        return $html;

    }

    /**
     * returns body content.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_body() {

        $body = stripslashes( get_option('niteoCS_body', '') );

        if ( isset($GLOBALS['wp_embed']) ) {
            $body = $GLOBALS['wp_embed']->autoembed( $body );
        }

        $body = wpautop( do_shortcode( $body ) );

        return $body;
    }

    /**
     * render body title.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_title( $class = '' ) {
        global $allowedposttags;

        $title = stripslashes( get_option('niteoCS_body_title', 'SOMETHING IS HAPPENING!') );

        // wrap text between stars in title in span for future formatting
        $title_array = explode('*', $title);

        if ( count($title_array) == 3 ) {
            $title = '<span class="cmp-title light">' . $title_array[0] . '</span><span class="cmp-title bold">' . $title_array[1] . '</span><span class="cmp-title light">' . $title_array[2] . '</span>';
        }

        $title = ( $title == '' ) ? '' : '<h2 class="cmp-title animated '. $class .'">' . wp_kses( $title, $allowedposttags ) . '</h2>';

        return $title;

    }

    /**
     * render Google fonts link.
     *
     * @since 2.4
     * @param  array[font_family],[font_variant]
     * @return HTML 
     **/
    public function cmp_get_fonts( $heading_font = array(), $content_font = array() ) {
        $custom = '';
        $google_fonts = '';
        $custom_font = '';

        // get google fonts html
        if ( $heading_font['variant'] !== 'Not Applicable' || $content_font['variant'] !== 'Not Applicable' ) {

            // get fonts subset
            $google_fonts = $this->cmp_get_google_fonts();
            $heading_break = FALSE;
            $content_break = FALSE;

            foreach ( $google_fonts as $font => $val ) {

                if ( $val['text'] == $heading_font['family'] ) {
                    $heading_subsets =  $val['subset'];
                    $heading_break = TRUE;
                }

                if ( $val['text'] == $content_font['family'] ) {
                    $content_subsets =  $val['subset'];
                    $content_break = TRUE;
                }

                if ( $heading_break === TRUE && $content_break === TRUE ) {
                    break;
                }
            }

            if ( $heading_subsets === null || $content_subsets === null ) {
                $subset = ($heading_subsets === null) ? $content_subsets : $heading_subsets;
                $subset = ($content_subsets === null) ? $heading_subsets : $content_subsets;
                
            } else {
                $subset = array_unique( array_merge( $heading_subsets, $content_subsets ) );
            }

            if ( $heading_font['variant'] !== 'Not Applicable' ) {
                $heading = esc_attr( str_replace(' ', '+', $heading_font['family']) ) .':'. esc_attr(str_replace('italic', 'i', $heading_font['variant'] ));
            }

            if ( $content_font['variant'] !== 'Not Applicable' ) {
                $content = esc_attr( str_replace(' ', '+', $content_font['family']) ) .':400,700,'. esc_attr(str_replace('italic', '', $content_font['variant'] ));
            }

            $separator = ( $heading !== null && $content !== null ) ? '%7C' : '';


            $google_fonts = '<link href="https://fonts.googleapis.com/css?family='. $heading . $separator . $content .'&amp;subset=' . implode(',', $subset) . '" rel="stylesheet">';
            
        }

        // get custom font html
        if ( $heading_font['variant'] === 'Not Applicable' || $content_font['variant'] === 'Not Applicable' ) {

            $custom_fonts = json_decode(get_option('niteoCS_custom_fonts'), true);
            $custom_font = '';

            foreach ( $custom_fonts as $custom ) {

                if ( $custom['id'] === $heading_font['family'] ) {
                    if ( is_array($custom['ids']) ) {
                        $custom_font .= $this->cmp_get_font_src( $heading_font['family'], $custom['ids'] );
                    }
                }
                
                
                if ( $custom['id'] === $content_font['family'] ) {
                     if ( is_array($custom['ids']) ) {
                        $custom_font .= $this->cmp_get_font_src( $content_font['family'], $custom['ids'] );
                    }
                }

            }
        }

        return $google_fonts . PHP_EOL . $custom_font . PHP_EOL;
    }


    /**
     * helper function to render style css for custom fonts
     *
     * @since 3.5
     * @param  string,array
     * @return HTML 
     **/
    public function cmp_get_font_src( $family, $ids ) {

        foreach ( $ids as $attachment_id ) {

            $url = wp_get_attachment_url($attachment_id);
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $new_src = '';
            
            switch ($ext) {
                case 'eot':
                    $eot = 'src: url("'.esc_url($url).'");' . PHP_EOL;
                    break;
                case 'woff':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("woff"),';
                    break;
                case 'woff2':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("woff2"),';
                    break;
                case 'otf':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("opentype"),';
                    break;
                case 'ttf':
                    $new_src = 'url("'.esc_url($url).'")' . ' format("truetype"),';
                    break;
                case 'svg':
                    $new_src = 'url("'.esc_url($url).'#filename")' . ' format("svg"),';
                    break;
                default:
                    break;
            }

            $src .=  $new_src;
        }

        return '<style>'. PHP_EOL .'@font-face {font-family: "'.$family.'";' . PHP_EOL . $eot  . 'src: ' . rtrim( $src, ',').';}'. PHP_EOL .'</style>';

    }

    /**
     * render theme head SEO.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_seo() {
        ob_start();

        $title = stripslashes( get_option('niteoCS_title', get_bloginfo('name').' Coming soon!') );
        $descr = stripslashes( get_option('niteoCS_descr', 'Just another Coming Soon Page') );

        $seo_img_id = get_option('niteoCS_seo_img_id');
        $seo_img_url = wp_get_attachment_image_src($seo_img_id, 'large');
        $seo_img_url = isset($seo_img_url[0]) ? $seo_img_url[0] : $this->cmp_get_background_img();
        ?>
        <!-- SEO -->
        <title><?php echo esc_html( $title ); ?></title>
        <meta name="description" content="<?php echo esc_html( $descr ); ?>"/>
        
        <!-- og meta for facebook, googleplus -->
        <meta property="og:title" content="<?php echo esc_html( $title ); ?>"/>
        <meta property="og:description" content="<?php echo esc_html( $descr ); ?>"/>
        <meta property="og:url" content="<?php echo get_home_url()?>"/>
        <meta property="og:type" content="website" />
        <meta property="og:image" content="<?php echo esc_url( $seo_img_url );?>"/>

        <!-- twitter meta -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_html( $title ); ?>"/>
        <meta name="twitter:description" content="<?php echo esc_html( $descr ); ?>"/>
        <meta name="twitter:url" content="<?php echo get_home_url();?>"/>
        <meta name="twitter:image" content="<?php echo esc_url( $seo_img_url );?>"/>

        <?php 
        // display favicon
        $favicon_id = get_option('niteoCS_favicon_id');

        if ( $favicon_id && $favicon_id != '' ) {
            $favicon_url = wp_get_attachment_image_src( $favicon_id, 'thumbnail' );
            if ( isset($favicon_url[0]) ) { 
                echo '<link id="favicon" rel="shortcut icon" href="' . $favicon_url[0] . '" type="image/x-icon"/>';
            } 
        } else {
           wp_site_icon();
        }

        if ( get_option( 'blog_public', '1' ) == '0' ) {
            echo '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL; 
        } 

        $html = ob_get_clean();

        return $html;
    }

    /**
     * render custom CSS.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_custom_css() {

        $css = '';

        $themeslug = $this->cmp_selectedTheme();

        if ( isset($_GET['theme']) && !empty($_GET['theme']) ) {
            $themeslug  = esc_attr($_GET['theme']);
        }

        $banner = get_option('niteoCS_banner', '2');

        if ( isset( $_GET['background'] ) && !empty($_GET['background']) ) {
            $banner = esc_attr( $_GET['background'] );

            if ( $themeslug === 'pluto' && $banner === '4' ) {
                $banner = '2';
            }
        }

        // add CMP CSS to all themes
        ob_start();

        // add blur effect if enabled
        $blur = get_option('niteoCS_effect_blur', '0.0');

        if (  $blur != '0.0' ) {
             ?>
            <!-- blur effect -->
            <style>
                #background-image,
                .slide-background,
                .video-banner {filter:blur(<?php echo esc_attr($blur);?>px);}
                #background-image,
                .video-banner,
                .background-overlay,
                .slide-background {transform:scale(1.1);}
                #background-wrapper, #slider-wrapper {overflow:hidden;}
                #background-image:not(.slide) {background-attachment: initial;}
            </style>

            <?php 
        } ?>

        <style>
            /* wp video shortcode  */
            .wp-video {margin: 0 auto;}
            .wp-video-shortcode {max-width: 100%;}
            /* google recaptcha badge */
            .grecaptcha-badge {display: none;}
        </style>

        <?php 
        if ( get_option('niteoCS_logo_custom_size', '0') !== '0' ) { ?>
        <!-- custom logo height -->
        <style>
            @media screen and (min-width:1024px) {
            .logo-wrapper img {max-height: <?php echo esc_attr(get_option('niteoCS_logo_size', '0'));?>px}
            }
        </style>
        <?php 
        }

        // CHAMELEON BACKGROUND STYLES
        if ( $themeslug === 'pluto' && $banner === '2' ): ?>
            <style>
                #background-image {
                    -webkit-animation: chameleon 19s infinite;
                        animation: chameleon 19s infinite;
                }
                @-webkit-keyframes chameleon {
                0% {background: #2ecc71;}
                25% { background: #f1c40f;}
                50% { background: #e74c3c;}
                75% {background: #3498db;}
                100% {background: #2ecc71;}
                }
                @keyframes chameleon {
                0% {background: #2ecc71; }
                25% {background: #f1c40f;}
                50% {background: #e74c3c;}
                75% {background: #3498db;}
                100% {background: #2ecc71;}
                }
            </style>
        <?php endif;?>

        <?php 
        // check for premium themes special effects
        if ( in_array( $themeslug, $this->cmp_premium_themes_installed() ) )  { 

            $effect = get_option('niteoCS_special_effect', 'disabled');

            // change effect for preview 
            if ( isset($_GET['effect']) && is_numeric($_GET['effect'])) {
                $effect = $_GET['effect'];
            }

            switch ( $effect ) {
                 case 'constellation':
                 case '1': ?>
                    <!-- constellation effect -->
                    <style>
                        .particles-js-canvas-el {position: absolute; top:0; left:0;}
                        <?php 
                        switch ( $themeslug ) {
                            case 'frame': ?>
                                 .particles-js-canvas-el {z-index: -1;}
                                 #background-image, .video-banner {z-index: -3;}
                                 .background-overlay {z-index: -2;}
                                 <?php 
                                break;

                            case 'stylo':
                            case 'eclipse': ?>
                                .particles-js-canvas-el {z-index: 1;}
                                <?php
                                break;
                            default:
                                break;
                        } ?>
                    </style>
                    <?php
                     break;

                 case 'bubbles':
                 case '2': ?>
                    <!-- constellation effect -->
                    <style>
                        canvas {position: absolute; top:0; left:0;}
                    </style>
                    <?php
                     break;
                 
                 default:
                     break;
            }
        }

        $css = ob_get_clean();

        $custom_css = ( get_option('niteoCS_custom_css', '') != '' ) ? '<style>'.stripslashes( wp_filter_nohtml_kses( get_option('niteoCS_custom_css') ) ).'</style>' : '';

        $css .= $custom_css;

        return $css;
    }

    /**
     * render copyright.
     *
     * @since 2.4
     * @return HTML 
     **/
    public function cmp_get_copyright() {
        if ( get_option( 'niteoCS_copyright', 'Made by <a href="https://niteothemes.com">NiteoThemes</a> with love.' ) !== '' ) { 
            $copyright = stripslashes( get_option('niteoCS_copyright', 'Made by <a href="https://niteothemes.com">NiteoThemes</a> with love.') );
            $allowed_html = array(
                'a' => array(
                    'href' => array(),
                    'title' => array(),
                    'target' => array(),
                ),
                'br' => array(),
                'em' => array(),
                'strong' => array(),
            );

            return '<p class="copyright">' . wp_kses( $copyright, $allowed_html ) . '</p>';
            
        }

        return false;
    }

    /**
     * @since 3.2
     * echo CSS styles to head
     * @return void
     **/
    public function cmp_enqueue_styles( $themeslug = 'hardwork', $font_ani = false, $slider = '0', $banner_type = '2', $fa = false, $gutenberg = false ) {

        $ver = $this->cmp_theme_version( $themeslug );

        if ( $gutenberg === true ) {
            echo '<link rel="stylesheet" href="'.includes_url('/css/dist/block-library/style.min.css').'" type="text/css" media="all" />' . PHP_EOL;
        }
        
        // theme stylesheet
        echo '<link rel="stylesheet" href="' . $this->cmp_themeURL( $themeslug ) . $themeslug.'/style.css?v='.$ver . '" type="text/css" media="all">' . PHP_EOL;

        if ( $font_ani !== false || $font_ani !== 'none' ) {
            echo '<link rel="stylesheet" href="'. esc_url( $this->cmp_asset_url('/css/animate.min.css') ) . '">' . PHP_EOL;
        }

        if ( $slider == '1' && ($banner_type == '0' || $banner_type == '1') ) {
            echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" rel="stylesheet">' . PHP_EOL;
        }

        if ( $fa === true ) {
            echo '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >' . PHP_EOL;
        }

    }

    /**
     * echo custom external CSS or Scripts
     *
     * @return echo HTML 
     **/
    public function cmp_head_scripts() {

        $this->cmp_wp_head();

        do_action('cmp-before-header-scripts');

        $head_scripts = json_decode( get_option('niteoCS_head_scripts', '[]'), true );
        
        if ( !empty( $head_scripts ) ) {
            foreach ( $head_scripts as $script ) {
                if ( $script != '' ) {
                    $file = pathinfo( $script );
                    switch ( $file['extension'] ) {
                        case 'js':
                            echo '<script src="' . esc_url( $script ). '"></script>' . PHP_EOL;
                            break;
                        case 'css':
                            echo '<link href="' . esc_url( $script ). '" rel="stylesheet">' . PHP_EOL;
                            break;
                        default:
                            break;
                    }
                }
            }

            do_action('cmp-after-header-scripts');
        }

        switch ( get_option('niteoCS_analytics_status', 'disabled') ) {
            //disabled analytics
            case 'disabled':
                break;
            // google analytics
            case 'google':

                if ( get_option('niteoCS_analytics', '') !== '' ) { ?>
                    <!-- Google analytics code -->
                    <script>
                      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                      ga('create', '<?php echo esc_attr(get_option('niteoCS_analytics'));?>', 'auto');
                      ga('send', 'pageview');

                    </script>
                    <?php 
                } 

                break;
            // other js code
            case 'other':
                if ( get_option('niteoCS_analytics_other', '') !== '' ) {
                    $niteoCS_analytics_other = get_option('niteoCS_analytics_other', ''); ?>
                    <script>
                      <?php echo stripslashes( esc_js( $niteoCS_analytics_other ) );?>
                    </script>
                    <?php 
                } 

                break;
            default:
                break;
        }

        return;
    }


    /**
     * echo Javascripts for Themes.
     *
     * @param  array background type, themeslug
     * @return echo HTML 
     **/
    public function cmp_javascripts( $background, $themeslug ) {

        do_action('cmp-before-footer-scripts');

        $jquery = FALSE;

        if ( isset($_GET['background']) && is_numeric($_GET['background']) ) {
            $background = esc_attr($_GET['background']);
        } ?>
        
        <!-- Fade in background image after load -->
        <script>
            window.addEventListener("load",function(event) {
                init();
            });

            function init(){

                var image = document.getElementById('background-image');
                var body = document.getElementById('body');
                if ( image === null ) {
                    image = document.getElementById('body');
                } 

                if ( image != null ) {
                    image.classList.add('loaded');
                    body.classList.add('loaded');
                }

                <?php
                // theme specific function after init
                switch ( $themeslug ) {
                    case 'fifty': ?>
                        var contentWrapper = document.getElementsByClassName('content-wrapper')[0];
                        setTimeout(function(){ contentWrapper.className += " overflow"; }, 1500);
                        
                        <?php 
                    break;

                    case 'hardwork_premium': ?>
                        var contentWrapper = document.getElementsByClassName('section-body')[0];
                        setTimeout(function(){ contentWrapper.className += " overflow"; }, 1500);
                        <?php 
                    break;
                    
                    default:
                        break;
                } ?>
            }
        </script>

        <?php 
        // include background scripts
        switch ( $background ) {
            // VIDIM script for background video 
            case '5': ?>
                <script src='<?php echo plugins_url('cmp-coming-soon-maintenance/js/external/vidim.min.js');?>'></script>
                <script>
                    <?php 
                    $video_poster   = wp_get_attachment_image_src( get_option('niteoCS_video_thumb'), 'large' );

                    if ( !empty( $video_poster ) ) {
                        $video_poster = $video_poster[0];       
                    }
                    // video
                    $source = get_option('niteoCS_banner_video');
                    
                    // sanitize source
                    switch ( $source ) {
                        case 'youtube':
                            $source = 'YouTube';
                            break;
                        case 'local':
                            $source = 'video/mp4';
                            break;
                        default:
                            break;
                    }

                    switch ( $source ) {
                        case 'YouTube':
                            $banner_url = get_option('niteoCS_youtube_url'); ?>
                        
                            var myBackground = new vidim( '#player', {
                                src: '<?php echo esc_url( $banner_url ); ?>',
                                type: 'YouTube',
                                poster: '<?php echo esc_url( $video_poster ); ?>',
                                quality: 'hd1080',
                                muted: true
                            });

                        <?php 
                            break;

                        case 'vimeo':
                            $banner_url = get_option('niteoCS_vimeo_url'); ?>
                            var myBackground = new vidim( '#player', {
                                src: '<?php echo esc_url( $banner_url ); ?>',
                                type: 'vimeo',
                                poster: '<?php echo esc_url( $video_poster ); ?>',
                                }
                            );
                            <?php
                            break;

                        case 'video/mp4':
                            $banner_url = get_option('niteoCS_video_file_url');
                            $banner_url = wp_get_attachment_url( $banner_url ); ?>
                            var myBackground = new vidim( '#player', {
                                src: [
                                    {
                                      type: 'video/mp4',
                                      src: '<?php echo esc_url( $banner_url ); ?>',
                                    },
                                ],
                                poster: '<?php echo esc_url( $video_poster ); ?>',
                            });
                            <?php 
                            break;
                        default:
                            break;
                    } ?>
                </script>
                <?php 
                break;

            // SLIDER SCRIPTS FOR UNSPLASH or Custom IMAGES
            case '0': 
            case '1':
                $slider = get_option('niteoCS_slider', '0');

                if (  $slider == '1' ) {
                    $slider_effect      = get_option('niteoCS_slider_effect', 'true');
                    $slider_autoplay    = get_option('niteoCS_slider_auto', '1');

                    switch ( $slider_effect ) {
                        // slice effect scripts
                        case 'slice':
                            ?>
                            <script src='<?php echo plugins_url('js/external/imagesloaded.pkgd.min.js', __DIR__);?>'></script>
                            <script src='<?php echo plugins_url('js/external/anime.min.js', __DIR__);?>'></script>
                            <script src='<?php echo plugins_url('js/external/uncover.js', __DIR__);?>'></script>
                            <script src='<?php echo $this->cmp_themeURL($themeslug).$themeslug.'/js/slice.js';?>'></script>
                            <?php 
                            break;

                        // mask transition effect scripts
                        case 'mask-transition':
                            if ( !$jquery ) {
                                echo '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" Crossorigin="anonymous"></script>';
                                $jquery = TRUE;
                            } ?>
                            <script src='<?php echo $this->cmp_themeURL($themeslug).$themeslug.'/js/modernizr-custom.js';?>'></script>
                            <script src='<?php echo $this->cmp_themeURL($themeslug).$themeslug.'/js/imagesloaded.pkgd.min.js';?>'></script>
                            <script src='<?php echo $this->cmp_themeURL($themeslug).$themeslug.'/js/mask-transition.js';?>'></script>

                            <?php 
                            break;
                        // default (true, false) means slick carousel scripts
                        default:
                            if ( !$jquery ) {
                                echo '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" Crossorigin="anonymous"></script>';
                                $jquery = TRUE;
                            } ?>
                            <!-- slick carousel script -->
                            <script src='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js'></script>
                            <script>
                                $('#slider').slick({
                                    slide: '.slide',
                                    slidesToShow: 1,
                                    arrows: false,
                                    fade: <?php echo esc_attr( $slider_effect );?>,
                                    speed: 1000,
                                    autoplay: <?php echo esc_attr( $slider_autoplay );?>,
                                    autoplaySpeed: 7000,
                                });

                                $('.prev').click(function() {
                                    $('#slider').slick('slickPrev');
                                });

                                $('.next').click(function() {
                                    $('#slider').slick('slickNext');
                                });

                                // custom dots navigation
                                $('.slide-number').click(function() {
                                    $('#slider').slick('slickGoTo', parseInt($(this).data('slide')) );
                                    
                                });

                                // update custom dots on change
                                $('#slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
                                  $('.slide-number').removeClass('active');
                                  $('[data-slide="' + nextSlide + '"]').addClass('active');
                                });

                            </script>
                            <?php
                            break;
                    }

                }

                break;
            
            default:
                break;
        } 

        // render redirect script if CMP is in redirect mode
        if ( $this->cmp_mode() == 3 ) {
            $url = get_option('niteoCS_URL_redirect');
            $time = get_option('niteoCS_redirect_time'); ?>

            <script>
                setTimeout(function() {
                  window.location.href = "<?php echo esc_url( $url );?>";
                }, <?php echo esc_attr( $time * 1000 );?>);
            </script>

            <?php
        }

        // check for CMP Subscribe shortcode to include CF7 captcha
        $subscribe = get_option('niteoCS_subscribe_type');
        $sub_cf7 = false;
        if ( $subscribe == '1' ) {
            $subscribe_code = get_option('niteoCS_subscribe_code');
            if ( strpos($subscribe_code, 'contact-form-7')  !== false ) {
                $sub_cf7 = true;
            }
        }

        // include jquery and CF7 scripts for CF7 themes or themes using CF7 for subscribe form
        if ( (in_array( $themeslug, $this->cmp_cf7_themes() ) && get_option('niteoCS_contact_form_type') == 'cf7') || $sub_cf7 === true ) {

            $site_url = str_replace( '/', '\/', site_url() );

            if ( !$jquery ) {
                echo '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" Crossorigin="anonymous"></script>';
                $jquery = TRUE;
            }

            if ( class_exists('WPCF7') ) {
                $sitekeys = WPCF7::get_option( 'recaptcha');
                if ( $sitekeys ) {
                    $sitekeys = array_keys( $sitekeys ); ?>
                    <!-- CF7 Recaptcha script -->
                    <script type='text/javascript' src='https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr($sitekeys[0]);?>&#038;ver=3.0'></script>
                    <script>!function(e,t){var a=function(){e.execute("<?php echo esc_attr($sitekeys[0]);?>",{action:"homepage"}).then(function(e){for(var t=document.getElementsByTagName("form"),a=0;a<t.length;a++)for(var n=t[a].getElementsByTagName("input"),r=0;r<n.length;r++){var c=n[r];if("g-recaptcha-response"===c.getAttribute("name")){c.setAttribute("value",e);break}}})};e.ready(a),document.addEventListener("wpcf7submit",a,!1)}(grecaptcha);</script>
                    <?php 
                } ?>
                
                <script type='text/javascript'>
                /* <![CDATA[ */
                var wpcf7 = {"apiSettings":{"root":"<?php echo $site_url;?>\/index.php?rest_route=\/contact-form-7\/v1","namespace":"contact-form-7\/v1"}};
                /* ]]> */
                </script>
                <script src='<?php echo plugins_url('contact-form-7/includes/js/scripts.js?ver=5.0.1');?>'></script>
                <?php
            }
        }

        // special effects scripts for premium themes
        if ( in_array( $themeslug, $this->cmp_premium_themes_installed() ) )  { 

            $effect = get_option('niteoCS_special_effect', 'disabled');

            // change effect for preview 
            if ( isset($_GET['effect']) && is_numeric($_GET['effect'])) {
                $effect = $_GET['effect'];
            }

            switch ( $effect ) {
                case 'constellation':
                case '1': ?>
                    <!-- load external Particles script -->
                    <script src="//cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
                    <!-- INI particles -->
                    <script>
                        var wrapper=document.getElementById("background-wrapper"),background=null===wrapper?"slider-wrapper":"background-wrapper";particlesJS(background,{particles:{number:{value:100,density:{enable:!0,value_area:1e3}},color:{value:"<?php echo esc_attr( get_option('niteoCS_special_effect[constellation][color]', '#ffffff') );?>"},shape:{type:"circle",stroke:{width:0,color:"#fff"},polygon:{nb_sides:5}},opacity:{value:.6,random:!1,anim:{enable:!1,speed:1,opacity_min:.1,sync:!1}},size:{value:2,random:!0,anim:{enable:!1,speed:40,size_min:.1,sync:!1}},line_linked:{enable:!0,distance:120,color:"<?php echo esc_attr( get_option('niteoCS_special_effect[constellation][color]', '#ffffff') );?>",opacity:.4,width:1}},interactivity:{detect_on:"canvas",events:{onhover:{enable:!0,mode:"grab"},onclick:{enable:!1},resize:!0},modes:{grab:{distance:140,line_linked:{opacity:1}},bubble:{distance:400,size:40,duration:2,opacity:8,speed:3},repulse:{distance:200,duration:.4},push:{particles_nb:4},remove:{particles_nb:2}}},retina_detect:!0});
                    </script>
                    <?php 
                    break;

                case 'bubbles':
                case '2':
                    if ( !$jquery ) {
                        echo '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" Crossorigin="anonymous"></script>';
                        $jquery = TRUE;
                    } ?>
                    <!-- INI bubbles -->
                    <script>
                        var $wrapper=$("#background-wrapper").length?$("#background-wrapper"):$("#slider-wrapper");$wrapper.append("<canvas></canvas><canvas></canvas><canvas></canvas>"),function(a){var e=$wrapper.children("canvas"),n=e[0],r=e[1],i=e[2],o={circle:{amount:18,layer:3,color:[<?php echo $this->hex2rgba( get_option('niteoCS_special_effect[constellation][color]', '#ffffff') );?>],alpha:.3},line:{amount:12,layer:3,color:[<?php echo $this->hex2rgba( get_option('niteoCS_special_effect[constellation][color]', '#ffffff') );?>],alpha:.3},speed:.5,angle:20};if(n.getContext){n.getContext("2d");var t,l,c,d=r.getContext("2d"),m=i.getContext("2d"),w=window.Math,s=o.angle/360*w.PI*2,p=[],u=[];requestAnimationFrame=window.requestAnimationFrame||window.mozRequestAnimationFrame||window.webkitRequestAnimationFrame||window.msRequestAnimationFrame||window.oRequestAnimationFrame||function(a,e){setTimeout(a,1e3/60)},cancelAnimationFrame=window.cancelAnimationFrame||window.mozCancelAnimationFrame||window.webkitCancelAnimationFrame||window.msCancelAnimationFrame||window.oCancelAnimationFrame||clearTimeout;var h=function(){t=a(window).width(),l=a(window).height(),e.each(function(){this.width=t,this.height=l})},v=function(){var a,e,n,r,i,h,f,y,g,A,F,C,b,x,q=w.sin(s),R=w.cos(s);if(o.circle.amount>0&&o.circle.layer>0){d.clearRect(0,0,t,l);for(var k=0,S=p.length;k<S;k++){var $=(I=p[k]).x,P=I.y,T=I.radius,z=I.speed;$>t+T?$=-T:$<-T?$=t+T:$+=q*z,P>l+T?P=-T:P<-T?P=l+T:P-=R*z,I.x=$,I.y=P,a=$,e=P,n=T,r=I.color,i=I.alpha,h=void 0,(h=d.createRadialGradient(a,e,n,a,e,0)).addColorStop(0,"rgba("+r[0]+","+r[1]+","+r[2]+","+i+")"),h.addColorStop(1,"rgba("+r[0]+","+r[1]+","+r[2]+","+(i-.1)+")"),d.beginPath(),d.arc(a,e,n,0,2*w.PI,!0),d.fillStyle=h,d.fill()}}if(o.line.amount>0&&o.line.layer>0){m.clearRect(0,0,t,l);var G=0;for(S=u.length;G<S;G++){$=(I=u[G]).x,P=I.y;var I,j=I.width;z=I.speed;$>t+j*q?$=-j*q:$<-j*q?$=t+j*q:$+=q*z,P>l+j*R?P=-j*R:P<-j*R?P=l+j*R:P-=R*z,I.x=$,I.y=P,f=$,y=P,g=j,A=I.color,F=I.alpha,void 0,void 0,x=void 0,C=f+w.sin(s)*g,b=y-w.cos(s)*g,(x=m.createLinearGradient(f,y,C,b)).addColorStop(0,"rgba("+A[0]+","+A[1]+","+A[2]+","+F+")"),x.addColorStop(1,"rgba("+A[0]+","+A[1]+","+A[2]+","+(F-.1)+")"),m.beginPath(),m.moveTo(f,y),m.lineTo(C,b),m.lineWidth=3,m.lineCap="round",m.strokeStyle=x,m.stroke()}}c=requestAnimationFrame(v)},f=function(){if(p=[],u=[],o.circle.amount>0&&o.circle.layer>0)for(var a=0;a<o.circle.amount/o.circle.layer;a++)for(var e=0;e<o.circle.layer;e++)p.push({x:w.random()*t,y:w.random()*l,radius:w.random()*(20+5*e)+(20+5*e),color:o.circle.color,alpha:.2*w.random()+(o.circle.alpha-.1*e),speed:o.speed*(1+.5*e)});if(o.line.amount>0&&o.line.layer>0)for(var n=0;n<o.line.amount/o.line.layer;n++)for(var r=0;r<o.line.layer;r++)u.push({x:w.random()*t,y:w.random()*l,width:w.random()*(20+5*r)+(20+5*r),color:o.line.color,alpha:.2*w.random()+(o.line.alpha-.1*r),speed:o.speed*(1+.5*r)});cancelAnimationFrame(c),c=requestAnimationFrame(v)};a(document).ready(function(){h(),f()}),a(window).resize(function(){h(),f()})}}(jQuery);
                    </script>
                    <?php 
                    break;
                
                default:
                    break;
            }
        } 

        // check if content includes iframe, and if yes, include full-width video resize script
        $content = $this->cmp_get_body();

        if ( strpos( $content, 'iframe' ) !== false ) { ?>
            <!-- Script for full-width size of embedded UT and VIMEO Iframes -->
            <script>
                (function ( window, document, undefined ) {
                  var iframes = document.getElementsByTagName( 'iframe' );
                  for ( var i = 0; i < iframes.length; i++ ) {
                    var iframe = iframes[i],
                    players = /www.youtube.com|player.vimeo.com/;
                    if ( iframe.src.search( players ) > 0 ) {
                      var videoRatio        = ( iframe.height / iframe.width ) * 100;
                      iframe.style.position = 'absolute';
                      iframe.style.top      = '0';
                      iframe.style.left     = '0';
                      iframe.width          = '100%';
                      iframe.height         = '100%';
                      var wrap              = document.createElement( 'div' );
                      wrap.className        = 'fluid-vids';
                      wrap.style.width      = '100%';
                      wrap.style.position   = 'relative';
                      wrap.style.paddingTop = videoRatio + '%';
                      var iframeParent      = iframe.parentNode;
                      iframeParent.className = 'video-paragraph';
                      iframeParent.insertBefore( wrap, iframe );
                      wrap.appendChild( iframe );
                    }
                  }
                })( window, document );
            </script>
            <?php 
        }

        $footer_scripts = json_decode( get_option('niteoCS_footer_scripts', '[]'), true );

        if ( !empty( $footer_scripts ) ) {
            foreach ( $footer_scripts as $f_script ) {
                if ( $f_script != '' ) {
                    $file = pathinfo( $f_script );
                    switch ( $file['extension'] ) {
                        case 'js':
                            echo '<script src="' . esc_url( $f_script ). '"></script>' . PHP_EOL;
                            break;
                        case 'css':
                            echo '<link href="' . esc_url( $f_script  ). '" rel="stylesheet">' . PHP_EOL;
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        do_action('cmp-after-footer-scripts');

        $this->cmp_wp_footer();

        // render nt&cmp notes
        echo $this->cmp_render_nt_info();

        return false;
    }

    /**
     * render Contact Form.
     *
     * @since 2.5
     * @return HTML 
     **/
    public function cmp_contact_form() {
        $form_type = get_option('niteoCS_contact_form_type', 'cf7');

        if ( $form_type == 'disabled' ) {
            return false;
        }

        $form_id = get_option('niteoCS_contact_form_id', '');

        switch ( $form_type ) {
            case 'cf7':
                $replace  = array('<p>', '</p>' );
                $html =  str_replace( $replace, '', do_shortcode('[contact-form-7 id='.$form_id.']') ) ; 
                break;
            
            default:
                $html = '';
                break;
        }

        return $html;
    }

    /**
     * render niteothemes info
     *
     * @since 3.2.3
     * @return HTML 
     **/
    public function cmp_render_nt_info() {

        $html = '
        <!-- Build by CMP – Coming Soon & Maintenance Plugin by NiteoThemes -->
        <!-- Visit plugin page https://wordpress.org/plugins/cmp-coming-soon-maintenance/ -->
        <!-- More CMP Themes on https://niteothemes.com -->';

        return $html;
    }

    /**
     * get array of banner ids
     *
     * @since 3.4.8
     * @return array 
     **/
    public function cmp_get_banner_ids() {

        $banner_id = get_option('niteoCS_banner_id');
        $banner_ids = array();
                
        if ( $banner_id != '' ) {
            $banner_ids = explode(',', $banner_id);
        }

        return $banner_ids;
    }

    /**
     * filter wp_head and wp_footer and return whitelisted hooks
     *
     * @since 3.5.6
     * @return array 
     **/
    // public function cmp_filter_head_scripts_and_styles( $allowed, $in_footer ) {

    //     // we must fake wp_head to actually get all scripts and styles 
    //     ob_start();
    //     wp_head();
    //     ob_end_clean();

    //     $handles = array();
    //     $handles['scripts'] = array();
    //     $handles['styles'] = array();
    
    //     // Print all loaded Scripts
    //     global $wp_scripts;
    //     global $wp_styles;

    //     // get scripts
    //     foreach( $allowed['scripts'] as $handle ) :

    //         if ( !$in_footer ) {
    //             if ( in_array( $handle, $wp_scripts->groups ) && isset($wp_scripts->registered[$handle]) ) {
    //                 $handles['scripts'][] = $wp_scripts->registered[$handle]->src;
    //             }
    //         } else {
    //             if ( in_array( $handle, $wp_scripts->groups ) && in_array( $handle, $wp_scripts->in_footer ) && isset($wp_scripts->registered[$handle]) ) {
    //                 $handles['scripts'][] = $wp_scripts->registered[$handle]->src;
    //             }
    //         }

    //     endforeach;
    
    //     // get_styles
    //     foreach( $wp_styles->queue as $style ) :
    //         if ( isset( $wp_styles->registered[$style] ) && is_object( $wp_styles->registered[$style] ) && in_array( $wp_styles->registered[$style]->handle, $allowed['styles'] ) ) {
    //             $handles['styles'][] =  $wp_styles->registered[$style]->src;
    //         }
    //     endforeach;
    
    //     return $handles;

    // }

    /**
     * print whitelisted scripts and styles to cmp_head
     *
     * @since 3.5.6
     * @return html 
     **/
    public function cmp_wp_head() {

        $handles_allowed = array(
            'scripts' => array(
            ),
            'styles' => array(
            )
        );

        // $handles_src = $this->cmp_filter_head_scripts_and_styles( $handles_allowed, false );

        // foreach ( $handles_src['scripts'] as $src ) { 
        //     echo '<script src="' . esc_url( $src ) . '"></script>' . PHP_EOL;
        // }

        // foreach ( $handles_src['styles'] as $src ) { 
        //     echo '<link href="' . esc_url( $src ). '" rel="stylesheet">' . PHP_EOL;
        // }

        // handle actions added by allowed plugins in wp_head
 
        // Plugin Name: Insert Headers and Footers
        if ( class_exists('InsertHeadersAndFooters') ) {
            $ihaf = new InsertHeadersAndFooters();
            $ihaf->frontendHeader();
        }

    }

    /**
     * filtered wp_footer for CMP
     *
     * @since 3.5.6
     * @return html 
     **/
    public function cmp_wp_footer() {

        /**
         * Detect plugin. For use on Front End only.
         */
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // $handles_allowed = array(
        //     'scripts' => array(
        //         'simpleanalytics_script'
        //     ),
        //     'styles' => array(

        //     )
        // );

        // $handles_src = $this->cmp_filter_head_scripts_and_styles( $handles_allowed, true );

        // foreach ( $handles_src['scripts'] as $src ) { 
        //     echo '<script src="' . esc_url( $src ). '"></script>' . PHP_EOL;
        // }


        // foreach ( $handles_src['styles'] as $src ) { 
        //     echo '<link href="' . esc_url( $src ). '" rel="stylesheet">' . PHP_EOL;
        // }
        


        // handle actions added by allowed plugins in wp_footer
        // Plugin Name: Insert Headers and Footers
        if ( class_exists('InsertHeadersAndFooters') ) {
            $ihaf = new InsertHeadersAndFooters();
            $ihaf->frontendFooter();
        }

        // Plugin Name: SimpleAnalytics

        if ( is_plugin_active( 'simpleanalytics/simple-analytics.php' ) ) {
            echo '<script src="https://cdn.simpleanalytics.io/hello.js"></script>' . PHP_EOL;
        } 

    }

}