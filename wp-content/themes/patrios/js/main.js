/*global $:false
  _____ _
 |_   _| |__   ___ _ __ ___   ___ _   _ _ __ ___
   | | | '_ \ / _ \ '_ ` _ \ / _ \ | | | '_ ` _ \
   | | | | | |  __/ | | | | |  __/ |_| | | | | | |
   |_| |_| |_|\___|_| |_| |_|\___|\__,_|_| |_| |_|

*  --------------------------------------
*         Table of Content
*  --------------------------------------
*   1. Digit Counter
*   2. Magnific Popup Shop
*   3. Sticky Nav
*   4. Coming Soon Page
*   5. Google Map
*   6. Perform AJAX Login
*   7. Register New User
*   8. Slick Slider Loading
*   9. Testimonial & Slider
*  --------------------------------------
*  -------------------------------------- */

jQuery(document).ready(function($){'use strict';

    //============================
    // 1. preloader
    //============================
    $(window).on('load', function() {
        $('#preloader').delay(1000).fadeOut('slow', function() { $(this).remove(); });
    });

    

    // /* --------------------------------------
    // *       1. Home Slider
    // *  -------------------------------------- */


    // Slider Code
    if ($('.slider_content_wrapper').length > 0) {
        var control = false;
        if ($('.slider_content_wrapper').data('control') == 'yes') { control = true; }
        var autoplay = false;
        if ($('.slider_content_wrapper').data('autoplay') == 'yes') { autoplay = true; }

        $('.slider_content_wrapper').slick({
            rtl: rtl,
            autoplay: autoplay,
            dots: control,
            dotsClass: 'thm-slide-control',
            nextArrow: '',
            prevArrow: '',
            speed: 300,
            autoplaySpeed: 3000,
            adaptiveHeight: true
        });

        // Slider Animation
        setInterval(function () {
            $('.slider-single-wrapper').each(function () {

                var $speed_ = 'animation-duration';
                if ($(this).hasClass('slick-active')) {
                    $(this).find('.slider-media').addClass($(this).find('.slider-media').data('animation')).css($speed_, $(this).find('.slider-media').data('speed'));
                    $(this).find('.slider-subtitle').addClass($(this).find('.slider-subtitle').data('animation')).css($speed_, $(this).find('.slider-subtitle').data('speed'));
                    $(this).find('.slider-title').addClass($(this).find('.slider-title').data('animation')).css($speed_, $(this).find('.slider-title').data('speed'));
                    $(this).find('.slider-content').addClass($(this).find('.slider-content').data('animation')).css($speed_, $(this).find('.slider-content').data('speed'));
                    $(this).find('.slider-button-1').addClass($(this).find('.slider-button-1').data('animation')).css($speed_, $(this).find('.slider-button-1').data('speed'));
                    $(this).find('.slider-button-2').addClass($(this).find('.slider-button-2').data('animation')).css($speed_, $(this).find('.slider-button-2').data('speed'));
                } else {
                    $(this).find('.slider-media').removeClass($(this).find('.slider-media').data('animation')).css($speed_, $(this).find('.slider-media').data('speed'));
                    $(this).find('.slider-subtitle').removeClass($(this).find('.slider-subtitle').data('animation')).css($speed_, $(this).find('.slider-subtitle').data('speed'));
                    $(this).find('.slider-title').removeClass($(this).find('.slider-title').data('animation')).css($speed_, $(this).find('.slider-title').data('speed'));
                    $(this).find('.slider-content').removeClass($(this).find('.slider-content').data('animation')).css($speed_, $(this).find('.slider-content').data('speed'));
                    $(this).find('.slider-button-1').removeClass($(this).find('.slider-button-1').data('animation')).css($speed_, $(this).find('.slider-button-1').data('speed'));
                    $(this).find('.slider-button-2').removeClass($(this).find('.slider-button-2').data('animation')).css($speed_, $(this).find('.slider-button-2').data('speed'));
                }
            });
        }, 1);

    }


    //  Post Slider
    $(document).on('rendered_addon', function(e, addon){
        if (typeof addon.type !== 'undefined' && ( addon.type === 'addon' || addon.type === 'inner_addon' ) && ( addon.name === 'themeum-slider-two' )){     
            let Container = '';
            let iframe = window.frames['wppb-builder-view'].window.document;
            if( addon.name == 'themeum-slider-two' ){ Container = $( iframe ).find('.slider_content_wrapper'); }
            if( Container.length > 0 ){
                slickProductSliderTwoCallback(Container, 'backend' );
            }
        }
    });
  


    
    // /* --------------------------------------
    // *       2. Magnific Popup Shop
    // *  -------------------------------------- */

     $('.cloud-zoom').magnificPopup({
        type: 'image',
        mainClass: 'product-img-zoomin',
        gallery: { enabled: true },
        zoom: {
            enabled: true, // By default it's false, so don't forget to enable it
            duration: 400, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function
            opener: function(openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });

    /* --------------------------------------
    *       3. search
    *  -------------------------------------- */
    var thmCampItem = $('.themeum-campaign-item');
    thmCampItem.height(thmCampItem.width());
    $(window).on('load', function() {
        thmCampItem.height(thmCampItem.width());
    });
    var searchIcon = $('.search-open-icon, .thm-fullscreen-search .search-overlay'),
        searchForm = $('.thm-fullscreen-search');

    searchIcon.on('click', function(e){
        e.preventDefault();
        searchForm.toggleClass('active');
    });

    $(document).keydown(function(e){
        var code = e.keyCode || e.which;
        if( code == 27 ){
            searchForm.removeClass('active');
        }
    });

    // Social Share.
    $('.social-share-wrap').each(function(){
        //var share_url = $(this).data('url');
        $(this).jsSocials({
            shares: [
                "twitter",
                "facebook",
                "pinterest",
                "linkedin"
            ],
            shareIn: "popup",
            showLabel: false,
            showCount: "inside"
        });
        jsSocials.shares.twitter = {
            label: "Tweet now"
        };
    })

    $('.patrios-bio-social').each(function(){
        //var share_url = $(this).data('url');
        $(this).jsSocials({
            shares: [
                "facebook",
                "twitter",
                "googleplus",
                "linkedin",
                "pinterest",
                // "rss",
            ],

            shareIn: "popup",
            showLabel: false,
            showCount: false
        });

    })

    // Blog and Woocomerce Pagination JS
    if( $('.themeum-pagination').length > 0 ){
        if( !$(".themeum-pagination ul li:first-child a").hasClass('prev') ){
            $(".themeum-pagination ul").prepend('<li class="p-2 first"><span>'+$(".themeum-pagination").data("preview")+'</span></li>');
        }
        if( !$(".themeum-pagination ul li:last-child a").hasClass('next') ){
            $(".themeum-pagination ul").append('<li class="p-2 first"><span>'+$(".themeum-pagination").data("nextview")+'</span></li>');
        }
        $(".themeum-pagination ul li:last-child").addClass("ml-auto");
        $(".themeum-pagination ul").addClass("d-flex justify-content-start").find('li').addClass('p-2').eq(1).addClass('ml-auto');
    }
    // End Pagination

    /* --------------------------------------
    *       3. Sticky Nav
    *  -------------------------------------- */
    jQuery(window).on('scroll', function(){'use strict';
        if ( jQuery(window).scrollTop() > 66 ) {
            jQuery('#masthead').addClass('sticky');
        } else {
            jQuery('#masthead').removeClass('sticky');
        }
    });


    // Vedio Popup
    if ($("#videoPlay, #about-video").length > 0) {
        $("#videoPlay, #about-video").magnificPopup({
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 300,
            preloader: false,
            fixedContentPos: false
        });
    }
    /* --------------------------------------
    *       4. Coming Soon Page
    *  -------------------------------------- */
    if (typeof loopCounterTwo !== 'undefined') {
        loopCounterTwo('.counter-class');
    }

    /* --------------------------------------
    *       5. Campaign CountDown
    *  -------------------------------------- */
    if (typeof loopCounterTwo !== 'undefined') {
        loopCounterTwo('.campaign-counter');
    }

    /* --------------------------------------
    *       5. Smooth Scrolling
    *  -------------------------------------- */
    $('a[href*="#patrios_project"]')
    .not('[href="#"]')
    .not('[href="#0"]')
    .click(function(event) {
        if ( location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname ){
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000, function() {
                        var $target = $(target);
                        $target.focus();
                });
            }
        }
    });
    // End


    /* --------------------------------------
    *       6. Perform AJAX Login
    *  -------------------------------------- */
    $('form#login').on('submit', function(e){ 'use strict';
        $('form#login p.status').show().text(ajax_objects.loadingmessage);
        var checked = false;
        if( $('form#login #rememberlogin').is(':checked') ){ checked = true; }
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_objects.ajaxurl,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#login #usernamelogin').val(),
                'password': $('form#login #passwordlogin').val(),
                'remember': checked,
                'security': $('form#login #securitylogin').val() },
            success: function(data){
                console.log( 'working!!!' );
                if (data.loggedin == true){
                    $('form#login div.login-error').removeClass('alert-danger').addClass('alert-success');
                    $('form#login div.login-error').text(data.message);
                    document.location.href = ajax_objects.redirecturl;
                }else{
                    $('form#login div.login-error').removeClass('alert-success').addClass('alert-danger');
                    $('form#login div.login-error').text(data.message);
                }
                if($('form#login .login-error').text() == ''){
                    $('form#login div.login-error').hide();
                }else{
                    $('form#login div.login-error').show();
                }
            }
        });
        e.preventDefault();
    });
    if($('form#login .login-error').text() == ''){
        $('form#login div.login-error').hide();
    }else{
        $('form#login div.login-error').show();
    }



    /* --------------------------------------
    *       7. Register New User
    *  -------------------------------------- */
    $('form#register').on('submit', function(e){ 'use strict';
        $('form#register p.status').show().text(ajax_objects.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_objects.ajaxurl,
            data: {
                'action':   'ajaxregister', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#register #username').val(),
                'email':    $('form#register #email').val(),
                'password': $('form#register #password').val(),
                'security': $('form#register #security').val() },
            success: function(data){

                if (data.loggedin == true){
                    $('form#register div.login-error').removeClass('alert-danger').addClass('alert-success');
                    $('form#register div.login-error').text(data.message);
                    $('form#register')[0].reset();
                }else{
                    $('form#register div.login-error').removeClass('alert-success').addClass('alert-danger');
                    $('form#register div.login-error').text(data.message);
                }
                if($('form#register .login-error').text() == ''){
                    $('form#register div.login-error').hide();
                }else{
                    $('form#register div.login-error').show();
                }
            }
        });
        e.preventDefault();
    });

    if($('form#register .login-error').text() == ''){
        $('form#register div.login-error').hide();
    }else{
        $('form#register div.login-error').show();
    }


    /* --------------------------------------
    *       9. Testimonial & Slider
    *  -------------------------------------- */
    var dir = $("html").attr("dir");
    var rtl = false;
    if( dir == 'rtl' ){
        rtl = true;
    }
    if( $('.testimonial_content_wrapper').length > 0 ){
        $('.testimonial_content_wrapper').slick({
            rtl: rtl,
            nextArrow: '<div class="slick-prev"><i class="fa fa-chevron-left"></i></div>',
            prevArrow: '<div class="slick-next"><i class="fa fa-chevron-right"></i></div>',
        });
    }

    /* --------------------------------------
    *       10. Product Slider
    *  -------------------------------------- */
    var dir = $("html").attr("dir");
    var rtl = false;
    if( dir == 'rtl' ){
      rtl = true;
    }

    //  Post Slider
    $(document).on('rendered_addon', function(e, addon){
        if (typeof addon.type !== 'undefined' && ( addon.type === 'addon' || addon.type === 'inner_addon' ) && ( addon.name === 'themeum-product-slider' )){     
            let Container = '';
            let iframe = window.frames['wppb-builder-view'].window.document;
            if( addon.name == 'themeum-product-slider' ){ Container = $( iframe ).find('.themeum-product-slider'); }
            if( Container.length > 0 ){
                slickProductSliderCallback(Container, 'backend' );
            }
        }
    });
    // Post Slider
    let post_slider = $('.themeum-product-slider');
    if( post_slider.length > 0 ){
        slickProductSliderCallback( post_slider, 'frontend' );
    }
    function slickProductSliderCallback( Container, type ){

        var dir = $("html").attr("dir");
        var rtl = false;
        if( dir == 'rtl' ){
          rtl = true;
        }

        let argument = {
            dots: false,
            rtl: rtl,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
            nextArrow: '<div class="slick-prev"><i class="fa fa-angle-left"></i></div>',
            prevArrow: '<div class="slick-next"><i class="fa fa-angle-right"></i></div>',
        };

        if( type == 'backend' ){
            Container.not('.slick-initialized').slick(argument);
            Container.each(function(){
                if (!$(this).hasClass('slick-initialized')){
                    $(this).slick();
                }
            });
        }
        if( type == 'frontend' ){
            Container.slick(argument);
        }
    }



    /* --------------------------------------
    *       11. Explore category hover & category tab button hover
    *  -------------------------------------- */
    var current = '';
    $('.thm-iconic-category li')
    .on('mouseenter', function() {
        current = $(this).find('a').css("color");
        $(this).find('a').css( 'color', $(this).data('color') );
    })
    .on('mouseleave', function() {
        $(this).find('a').css( 'color', current );
    });

    //category tab button hover
    $('.themeum-tab-category a.thm-btn').on('mouseenter', function() {
        var catBg = $(this).data('catbg');
        $(this).css({
            color: catBg,
            borderColor: catBg,
            background: '#fff'
        });
    }).on('mouseleave',function () {
        var catBg = $(this).data('catbg');
        $(this).css({
            color: '#fff',
            background: catBg
        });
    });


    $('.thm-explore a').on('click', function(event) {
        $('.thm-iconic-category li').collapse('show');
    });
    $('.thm-explore a').on('click', function(event) {
        $('.thm-explore .thm-iconic-category li').collapse('hide');
    });


    /* --------------------------------------
    *       12. Love It Button
    *  -------------------------------------- */
    $('.thm-love-btn').on('click', function(e) {
        e.preventDefault();
        var that = $(this);
        var campaign_id = that.data('campaign');
        var user_id = that.data('user');

       if( user_id != 0 && campaign_id ){
            $.ajax({
                type:"POST",
                url: ajax_objects.ajaxurl,
                data: {'action': 'thm_campaign_action', 'campaign_id': campaign_id},
                success:function(data){
                    data = JSON.parse(data);
                    if (data.success == 1){
                        that.find('.amount').html(data.number);
                        if( data.message == 'love' ){
                            that.addClass( 'active' ).parents('.themeum-campaign-post').find('.themeum-campaign-img').addClass('active');
                        }else{
                            that.removeClass( 'active' ).parents('.themeum-campaign-post').find('.themeum-campaign-img').removeClass('active');
                        }
                    }
                }
            });
        }else{
            $('#myModal').modal('show');
        }
    });


});
