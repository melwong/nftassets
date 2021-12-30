/**
 * MyTicket created by Kenzap on 28/05/2017.
 */
//$.fn.reverse = [].reverse;

(function ($) {
 "use strict";
 	
 	var price_range = $("#price-range"); 

	$( ".event-map" ).on('mouseleave', function( event ){
	  $('.event-map iframe').css("pointer-events", "none"); 
	});


	//counters counter
	var flagscroll = true;
	$(window).scroll(function(){

		//sticky header
		if($(".add-sticky-header").length){
			$('body').toggleClass('sticky-header', $(this).scrollTop() > $('.top-header').outerHeight());
		    $('.header-empty').css("height", $('.main-header').outerHeight());
		}
		var y = $(window).scrollTop();
		if(  y > 2000 && flagscroll==true ) {
			flagscroll=false;
			$('.count').each(function () {
				$(this).prop('Counter',0).animate({
					Counter: $(this).text()
					}, {
					duration: 2000,
					easing: 'swing',
					step: function (now) {
						$(this).text(Math.ceil(now).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					}
				});
			});
		}
	});

	var $thank_you = $(".thank-you"); 
	if ($thank_you.length) {

		//perform ajax request
		$.ajax({
			type: 'POST',
			dataType: 'html',
			url: screenReaderText.ajaxurl,
			data: {
				'orderid': $thank_you.data('orderid'),
				'action': 'myticket_send_emails_ajax'
			},
			beforeSend : function () {

			},
			success: function (data) {
				console.log(data);
			},
			error : function (jqXHR, textStatus, errorThrown) {
				
			},
		});
	}

	//myticket schedule generator
	var date_prev = ''; var tab_left_cont = ''; var tab_right_cont = ''; var ee = 0;
	function generateSchedule(){ 

		if( $(".section-full-events-schedule") ){
			$('.tab_left').each(function (index, value) { 

				if ( date_prev != $(this).data('date') ) {

					if ( tab_left_cont != '' ) {

						// assign tab html
						tab_left_cont += '</ul></div>';
						tab_right_cont += '</div></div>';
						$("#tab"+date_prev).html(tab_left_cont+tab_right_cont);
						tab_left_cont = '';
						tab_right_cont = '';
					}
					tab_left_cont += '<div class="row"><div class="col-sm-4 col-md-3"><ul class="nav" role="tablist">';
					tab_right_cont += '<div class="col-sm-8 col-md-9"><div class="tab-content">';
					date_prev = $(this).data('date');
					ee=0;
				}
				ee++;
				tab_left_cont += $(this).html();
				tab_right_cont += $("#tab_right_"+date_prev+"_"+ee).html();
			});

			// assign tab html
			tab_left_cont += '</ul></div>';
			tab_right_cont += '</div></div></div>';
			$("#tab"+date_prev).html(tab_left_cont+tab_right_cont);
			tab_left_cont = '';
			tab_right_cont = '';
			date_prev = '';
			ee = 0;
		}
	}
	
	// Textbox Clear
	$( ".hasclear" ).on('keyup', function( event ){
	  var t = $(this);
	  t.next('span').toggle(Boolean(t.val()));
	});

	$(".clearer").hide($(this).prev('input').val());

	// Price Range Slider
	var price_range_first = Math.round(+new Date()/1000);
	if ( price_range.length ){
		price_range.slider({
			// tooltip: 'always',
			// tooltip_split: true,
			formatter: function(value) {
				if ( (Math.round(+new Date()/1000)-price_range_first) > 1)
					refreshProductPricing();
				return '$ ' + value;
			},
		});
	}	

	// setTimeout( 
	// 	function() {   	
	// 		$('body').on('DOMSubtreeModified', ".tooltip-inner", function( event ){

	// 			var t = $(this).html();
	// 			$(this).html(t+"%");
	// 			return;

	// 			console.log($(this).html());
	// 		});
	// }, 5000);

	$(activate);
	function activate() {
		$('.event-tabs')
			.scrollingTabs({
			  scrollToTabEdge: true		  
			})
			.on('ready.scrtabs', function() {
			$('.tab-content').show();
		});
	}
	generateSchedule();
	 
	$('.hero-2 .count-down').countdown('2017/04/26').on('update.countdown', function(event) {
	  var $this = $(this).html(event.strftime('<li>%D <span>day%!d</span></li>'
		+ '<li>%H <span>hours</span></li>'
		+ '<li>%M <span>minutes</span></li>'
		+ '<li>%S <span>seconds</span></li>'));
	});
	
	// The slider being synced must be initialized first
	$('#carousel').flexslider({
		animation: "slide",
		controlNav: false,
		animationLoop: false,
		slideshow: false,
		itemWidth: 160,
		itemMargin: 5,
		asNavFor: '#slider'
	});
	 
	$('#slider').flexslider({
		animation: "slide",
		controlNav: false,
		directionNav: false,
		animationLoop: false,
		slideshow: false,
		sync: "#carousel"
	}); 
	
	$("input.cart_pers").on("focus",function(){
		$(this).parent().parent().find(".update").fadeIn('fast');
	});

	$("a,section,div,span,li,input[type='text'],input[type='submit'],input[type='button'],input[type='checkbox'],tr,button").on("click", function(){
		
		if ($(this).hasClass("event-map")) { 
			$('.event-map iframe').css("pointer-events", "auto");
		}
		
		if ($(this).hasClass("select-seat")) { 
			$(this).siblings().removeClass("selected");
			$(this).addClass('selected');
		}
		
		if ($(this).hasClass("clearer")) { 
			$(this).prev('input').val('').focus();
			$(this).hide();
		}
		
		if ($(this).hasClass("myticket-search-btn")) { 
		
			loadFilteredProducts(true);
			return false;
		}

		if ($(this).hasClass("myticket-schedule-btn")) { 
		
			loadFilteredSchedule(true);
			return false;
		}

		if ($(this).hasClass("add_to_cart_button")) {
		      refreshCart();
		}else if ( $(this).hasClass("remove") ) {
		      refreshCart();
		}

		if ($(this).hasClass("qty-btn")) { 
			var $button = $(this);
			var oldValue = $button.closest('.qty-select').find("input.quantity-input").val();

			if ($button.text() === "+") {
				var newVal = parseFloat(oldValue) + 1;
			} else {
				// Don't allow decrementing below zero
				if (oldValue > 1) {
					var newVal = parseFloat(oldValue) - 1;
				} else {
					newVal = 1;
				}
			}
			$button.closest('.qty-select').find("input.quantity-input").val(newVal);
			return false;
		}
		
		if ($(this).hasClass("closecanvas")) { 
			$("body").removeClass("offcanvas-stop-scrolling");
		}

		if ($(this).hasClass("play-youtube")) {

			window.setTimeout( 
	        function() {   
	          
	          $("iframe.featherlight-inner").css("height",(parseInt($("iframe.featherlight-inner").width())/16*8+60)+"px");
					}, 2500);
					
			window.setTimeout( 
				function() {   
					
					$("iframe.featherlight-inner").css("height",(parseInt($("iframe.featherlight-inner").width())/16*8+60)+"px");
				}, 5500);
		} 

		if ($(this).hasClass("myticket-widget-category-checkbox")) {

			loadFilteredProducts(true);
		} 
	});

	//fix menu cart new line problem
	if ( $("#menu-main-menu").outerHeight() > 60 ) {

		var liItems = $("#menu-main-menu > .menu-item").get();
		//iterate through this array in reverse order    
		for(var i = liItems.length - 1; i >= 0; --i){

		  	//do Something
		  	if ( $("#menu-main-menu").outerHeight() < 60 ){
			
			}else{	
				$("#"+liItems[i].id).remove();
			}	
		}
	}

	//myticket ajax filter
	var product_list = "list", per_page = 10, product_category = "", product_category_list = "", product_category2 = "", product_tag = "", product_calories_low = 0, product_calories_high = 100000000000000,  product_pricing_low = 0, product_pricing_high = 100000000000000, inRequest = false, product_columns = 4, product_order = "", pagenum_link = "";// product_cat = "";
 	if( $("#myticket-sorting").length > 0 ){

      	product_order = $("#myticket-sorting").data('active');
  	}

  	//select event binding
  	$("select, button").on('change',function(e){

	    if ( $(this).is("#myticket-sorting") ) {
	      product_order = $(this).val();
	      loadFilteredProducts(true);
	    }else if ( $(this).is("#product-records") ) {
	      per_page = parseInt($(this).val());
	      loadFilteredProducts(true);
	    }else if ( $(this).is("#myticket-location") ) {
	      loadFilteredProducts(true);
	    }else if ( $(this).is("#myticket-time") ) {
	      loadFilteredProducts(true);
	    }
	});
	   
	var priceTimeout = null;
	function refreshProductPricing(){

	      if( priceTimeout!= null ){
	        clearTimeout( priceTimeout );
	      }
	      clearTimeout( priceTimeout );
	      priceTimeout = window.setTimeout( 
	        function() {   
	          loadFilteredProducts(true);

	        }, 1000);
	}

	function loadFilteredSchedule(clear_contents){

	    inRequest = true;
	    var $loader = $('.schedule-result-cont');
	    $loader.fadeTo( "normal", 0.33 );
	    createCookie("search_value", $('.myticket-search-value').val(), 1);
	    createCookie("search_location", $("#myticket-location").val(), 1);

	    //perform ajax request
	    $.ajax({
	        type: 'POST',
	        dataType: 'html',
	        url: screenReaderText.ajaxurl,
	        data: {
	          'cat': product_category,
	          'events_per_page': $loader.data('events_per_page'),
	          'show_header': $loader.data('show_header'),
	          'type': $loader.data('type'),
	          'search_value': $('.myticket-search-value').val(),
	          'search_location': $("#myticket-location").val(),
	          'search_month': $("#myticket-month").val(),
	          'action': 'myticket_filter_schedule_ajax'
	        },
	        beforeSend : function () {

	        },
	        success: function (data) {
	          var $data = $(data);
	          if(clear_contents) {
	              $loader.empty();
	          }
	          if ($data.length) {
	            var $newElements = $data.css({ opacity: 0 });
	            $loader.append($newElements);
	            $newElements.animate({ opacity: 1 });
 				generateSchedule(); 
 				activate();
	          } else {
	            $loader.html('<div style="text-align:center;letter-spacing:0.1em;margin-top:20px;">'+screenReaderText.noposts+'</div>');
	          }
	          inRequest = false;
	          $loader.fadeTo( "fast", 1 );
	        },
	        error : function (jqXHR, textStatus, errorThrown) {
	          inRequest = false;
	          $loader.fadeTo( "fast", 1 );
	        },
	    });
	    return false;
	}

	function loadFilteredProducts(clear_contents){

	    inRequest = true;
	    var $content = $('.myticket-content');
	    var $loader = $('.search-result-cont');
	    var ppp = per_page, color = 'red', size = 'S', price_low = '10', price_high = '1000';
	    var offset = $('.grid-product-content .row').find('.item').length;
	    pagenum_link = $content.data('pagenum_link');
	    var pagination = $content.data('pagination');
	    product_list = $content.data('list_style');
	    $content.fadeTo( "normal", 0.33 );

		if ( price_range.length ){
			var temp = price_range.val().split(',');
			product_pricing_low = temp[0];
			product_pricing_high = temp[1];
		}

		var product_category_list = '';
		$( ".myticket-widget-category-checkbox" ).each(function( index ) {
			if ($(this).is(':checked'))
				product_category_list += $( this ).data('category')+",";
		});

		if (typeof $content.data('category') != 'undefined')
			if ( $content.data('category').length > 0 )
				product_category_list += $content.data('category')+",";

	    //cache settings if page will be refreshed
	    createCookie("product_category_list", product_category_list, 1);
	    createCookie("events_per_page", $content.data('events_per_page'), 1);
	    createCookie("events_relation", $content.data('relation'), 1);
	    createCookie("offset", offset, 1);
	    createCookie("product_list", product_list, 1);
	    createCookie("product_order", product_order, 1);
	    createCookie("search_value", $('.myticket-search-value').val(), 1);
	    createCookie("search_location", $("#myticket-location").val(), 1);
	    createCookie("product_tag", product_tag, 1);
	    createCookie("product_calories_low", product_calories_low, 1);
	    createCookie("product_calories_high", product_calories_high, 1);
	    createCookie("product_pricing_low", product_pricing_low, 1);
	    createCookie("product_pricing_high", product_pricing_high, 1);
	    createCookie("product_columns", product_columns, 1);
	    createCookie("pagenum_link", pagenum_link, 1);

	    //perform ajax request
	    $.ajax({
	        type: 'POST',
	        dataType: 'html',
	        url: screenReaderText.ajaxurl,
	        data: {
	          'cat': product_category,
	          'product_category_list': product_category_list,
	          'offset': offset,
	          'product_order': product_order,
	          'product_type': $content.data('type'),
	          'events_per_page': $content.data('events_per_page'),
	          'events_relation': $content.data('relation'),
	          'search_value': $('.myticket-search-value').val(),
	          'search_location': $("#myticket-location").val(),
	          'search_time': $("#myticket-time").val(),
	          'product_list': product_list,
	          'product_tag': product_tag,
	          'product_pricing_low': product_pricing_low,
	          'product_pricing_high': product_pricing_high,
	          'product_columns': product_columns,
	          'pagenum_link': pagenum_link,
	          'pagination': $content.data('pagination'),
	          'action': 'myticket_filter_list_ajax'
	        },
	        beforeSend : function () {

	        },
	        success: function (data) {
	          var $data = $(data);
	          if(clear_contents) {
	              $loader.empty();
	          }
	          if ($data.length) {
	            var $newElements = $data.css({ opacity: 0 });
	            $loader.append($newElements);
	            $newElements.animate({ opacity: 1 });
	            $('.load-more-loader').hide(); 
	            $('.btn-load-more').show(); 
	          } else {
	            $loader.html('<div style="text-align:center;letter-spacing:0.1em;margin-top:20px;">'+screenReaderText.noposts+'</div>');
	            $('.load-more-loader').hide(); 
	          }
	          inRequest = false;
	          $content.fadeTo( "fast", 1 );
	          refresh_rp_counters();
	        },
	        error : function (jqXHR, textStatus, errorThrown) {
	          $('.load-more-loader').hide(); 
	          $('.btn-load-more').show();
	          inRequest = false;
	          $content.fadeTo( "fast", 1 );
	        },
	    });
	    return false;
	}

	function refreshCart(){
	    window.setTimeout( 
	        function() {   
	           loadCart();
	        }, 1500);
	    window.setTimeout( 
	        function() {   
	           loadCart();
	        }, 5000);
	}

	function loadCart(){

	    $.ajax({
	        type: 'POST',
	        dataType: 'html',
	        url: screenReaderText.ajaxurl,
	        data: {
	          'action': 'myticket_cart_data_ajax'
	        },
	        beforeSend : function () {

	        },
	        success: function (data) {
	          if (data.length){
	            var cart = $.parseJSON(data);
	            $(".cart-count").html(cart.cart_contents_count);
	          } 
	          inRequest = false;
	        },
	        error : function (jqXHR, textStatus, errorThrown) {
	          inRequest = false;
	        },
	      });
	      return false;
	}

	refresh_rp_counters();
	function refresh_rp_counters(){
		
		// refresh header counters	          
	    if( $("#myticket_post_count").length > 0 ){

	      	$("#myticket_pcr").html($("#myticket_post_count").val());  	
	      	var to = parseInt($("#myticket_current_page").val()) * parseInt($("#myticket_max_page_records").val());
	      	var from = to - parseInt($("#myticket_max_page_records").val()) + 1;
	      	if ( to > $("#myticket_post_count").val() )
	      		to = ( $("#myticket_post_count").val() );
	      	$("#myticket_prr").html( from + ' - ' + to );  
	    }

	    //refresh header text
	    if( $("#myticket-sri-cont").length > 0 ){

	    	if( $(".myticket-search-value").length > 0 ){

	    		var q = $(".myticket-search-value").val();
	    		var l = $("#myticket-location").val();
	    		$("#myticket-sri-cont").html( $("#myticket-sri-cont").data('search') + ' ' + q + ' ' + l );
	    		if ( q == '' && l == '' ){
	    			$("#myticket-sri-cont").html( $("#myticket-sri-cont").data('all') );
	    		}
	    	}else{
	    		$("#myticket-sri-cont").html( $("#myticket-sri-cont").data('all') );
	    	}
	    }

	    if( $("#myticket_post_count").length == 0 ){

	    	$("#myticket_prr").html( '0' );  
	    	$("#myticket_pcr").html( '0' );  
	    }
	}

  	function createCookie(name, value, days) {
      var expires;
      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toGMTString();
      } else {
          expires = "";
      }
      document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
  	}

  	function readCookie(name) {
      var nameEQ = encodeURIComponent(name) + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) === ' ') c = c.substring(1, c.length);
          if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
      }
      return null;
  	}

  	initMap();

})(jQuery);

function initMap() {
  var mapCanvas = document.getElementById("map");
  if(mapCanvas == null) 
    return;
  var myCenter = new google.maps.LatLng(mapCanvas.dataset.latitude, mapCanvas.dataset.longitude);
  var mapOptions = {
    center: myCenter,
    zoom: ((mapCanvas.dataset.zoom=='')?15:parseInt(mapCanvas.dataset.zoom)),
    disableDoubleClickZoom: true,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    zoomControl: false,
    scrollwheel: false,
    styles: [
    {
      featureType: 'all',
      stylers: [
      { saturation: ((mapCanvas.dataset.saturation=='')?(-80):parseInt(mapCanvas.dataset.saturation)) },
      { hue: ((mapCanvas.dataset.hue=='')?'#ccc':mapCanvas.dataset.hue) },
      ]
    }, {
      featureType: 'road.arterial',
      elementType: 'geometry',
      stylers: [
      { hue: '#654ef4' },
      { saturation: 50 }
      ]
    }, {
      featureType: 'poi.business',
      elementType: 'labels',
      stylers: [
      { visibility: 'off' }
      ]
    }
    ]
  };
  var map = new google.maps.Map(mapCanvas, mapOptions);
  var marker = new google.maps.Marker({});
  if(mapCanvas.dataset.pointer=='pointer'){

    marker = new google.maps.Marker({
      position: myCenter,
      map: map,
      title: mapCanvas.dataset.balloon
    });
  }else{
    var infowindow = new google.maps.InfoWindow({
      position: myCenter,
      content: mapCanvas.dataset.balloon
    });
    infowindow.open(map, marker);
  }
}
