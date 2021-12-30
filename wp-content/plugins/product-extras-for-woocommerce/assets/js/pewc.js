(function($) {

	var total_updated = false;


	if( window.Dropzone == true ) {
		Dropzone.autoDiscover = false;
	}

	$( document ).ready( function() {
		$( '.dz-default.dz-message span' ).text( pewc_vars.drop_files_message );
	});

	$('.require-depends li:first input').on('change',function(){
		// Display asterisk on dependent required fields
		if( $(this).val() != '' ) {
			$(this).closest('.pewc-group').addClass('show-required');
		} else {
			$(this).closest('.pewc-group').removeClass('show-required');
		}
	});
	$('.pewc-file-upload').on('change',function(){
		readURL( this, $(this).attr('id') );
	});
	$('.pewc-remove-image').on('click',function(e){
		e.preventDefault();
		id = $(this).attr('data-id');
		$('#'+id).val("");
		$('#'+id+'-placeholder').css('display','none');
		$('#'+id+'-placeholder img').attr('src', '#');
		$('#'+id+'-wrapper').removeClass('image-loaded');
	});
	function readURL(input,id) {
		if( input.files && input.files[0] ) {
			var i = input.files.length;
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#'+id+'-wrapper').addClass('image-loaded');
				$('#'+id+'-placeholder').fadeIn();
				$('#'+id+'-placeholder img').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	$('body').on('change input','.pewc-has-maxchars input, .pewc-has-maxchars textarea',function(){
		var maxchars = $(this).attr('data-maxchars');
		var str_ex_spaces = $(this).val();
		// Don't cost spaces
		str_ex_spaces = str_ex_spaces.replace(/\s/g, "");
		var str_len = str_ex_spaces.length;
		if(str_len>maxchars){
			var new_str = str_ex_spaces.substring(0, maxchars);
			$(this).val(new_str);
		}
	});
	$('body').on('change input','.pewc-form-field',function(){
		$form = $(this).closest( 'form' );
		// pewc_update_total( $form );
		pewc_update_total_js();
	});
	$('body').on('click','.pewc-remove-image',function(){
		$form = $(this).closest('form');
		// pewc_update_total( $form );
		pewc_update_total_js();
	});
	// Bind to the show_variation event
	// Update pewc-product-price field with variation price
	$( document ).bind( 'hide_variation', function( event, variation, purchasable ) {
		$('#pewc-product-price').val( 0 );
		pewc_update_total_js();
	});
	$( document ).bind( 'show_variation', function( event, variation, purchasable ) {
		var var_price = variation.display_price;
		$( '#pewc_variation_price' ).val( var_price );
		$('#pewc-product-price').val(var_price);
		// Update percentage prices
		$('.pewc-percentage').each(function(){
			var new_price = ( var_price / 100 ) * $( this ).attr( 'data-percentage' );
			$(this).attr( 'data-price', new_price );
			new_price = pewc_wc_price( new_price.toFixed(pewc_vars.decimals) );
			$(this).find('.pewc-field-price').html(new_price);
			// Find any options in this field - checkboxes
			$(this).find( '.pewc-option-has-percentage' ).each(function(){
				var option_price = ( var_price / 100 ) * $(this).attr('data-option-percentage');
				$(this).attr('data-option-cost',option_price.toFixed(pewc_vars.decimals));
				option_price = pewc_wc_price( option_price.toFixed(pewc_vars.decimals) );
				$(this).closest('.pewc-checkbox-form-label').find('.pewc-option-cost-label').html(option_price);
			});
		});
		// Find any select options with percentage prices - these might not have a field price
		$( this ).find( '.pewc-percentage.pewc-item-select select' ).each( function() {
			pewc_update_select_percentage_field( $( this ), var_price );
		});
		// Check for variation dependent fields
		var variation_id = variation.variation_id;
		$('.pewc-variation-dependent').each(function(){
			var ids = $(this).attr('data-variations');
			ids = ids.split( ',' );
			ids = ids.map( function( x ) {
				return parseInt( x, 10 );
			});
			if( ids.indexOf( variation_id ) != -1 ) {
				$(this).addClass( 'active' );
			} else {
				$(this).removeClass( 'active' );
			}
		});
		// Trigger recalculation
		$( 'body' ).trigger( 'pewc_trigger_calculations' );
		pewc_update_total_js();
	});
	$( 'body' ).on( 'update change', '.pewc-percentage.pewc-item-select select', function( e ) {
		pewc_update_select_percentage_field( $( this ), $( '#pewc_variation_price' ).val() );
	});
	function pewc_update_select_percentage_field( select, var_price ) {
		// var var_price = $( '#pewc_variation_price' ).val();
		var selected = $( select ).children( 'option:selected' );
		var option_price = ( var_price / 100 ) * $( selected ).attr( 'data-option-percentage' );
		var item = $( select ).closest( '.pewc-item' ).attr( 'data-selected-option-price', option_price );
		$( selected ).attr( 'data-option-cost', option_price.toFixed( pewc_vars.decimals ) );
		option_price = pewc_wc_price( option_price.toFixed( pewc_vars.decimals ) );
		var data_price = $( select ).closest( '.pewc-item' ).attr( 'data-price' );
		if( isNaN( data_price ) ) {
			$( select ).closest( '.pewc-item' ).attr( 'data-price', 0 );
		}
		// Trigger recalculation
		$( 'body' ).trigger( 'pewc_trigger_calculations' );
		pewc_update_total_js();
	}
	function pewc_update_total_js( $update=0 ) {
		var flat_rate_total = 0;
		var product_price = parseFloat( $('#pewc-product-price').val() );

		total_price = 0; // Options running total
		$('.pewc-form-field').each(function() {
			var field_wrapper = $(this).closest('.pewc-group');
			// Ignore hidden variation dependent fields
			if( ! $( field_wrapper ).hasClass( 'pewc-variation-dependent' ) || ( $( field_wrapper ).hasClass( 'pewc-variation-dependent' ) && $( field_wrapper ).hasClass( 'active' ) ) ) {
				// Check that the extra field is not flat rate
				if( ! $(field_wrapper).hasClass('pewc-flatrate') ) {
					if( $(field_wrapper).hasClass('pewc-group-checkbox') && ! $(field_wrapper).hasClass('pewc-hidden-field') ) {
						if( $(field_wrapper).hasClass('pewc-per-unit-pricing') && $(this).prop('checked') ) {
							// Bookings for WooCommerce
							// Multiply option cost by number of booked units
							total_price += parseFloat( $('#num_units_int').val() ) * parseFloat( $(field_wrapper).attr('data-price') );
						} else if( $(this).prop('checked') ) {
							total_price += parseFloat( $(field_wrapper).attr('data-price') );
						}
					} else if( $(field_wrapper).hasClass('pewc-group-select') && ! $(field_wrapper).hasClass('pewc-hidden-field') ) {
						// Add cost of selected option
						total_price += parseFloat( $(this).find(':selected').attr('data-option-cost') );
						// Add cost of select field
						total_price += parseFloat( $(field_wrapper).attr('data-price') );
					} else if( $(this).val() && ! $(field_wrapper).hasClass('pewc-hidden-field') ) {
						if( $(field_wrapper).hasClass('pewc-per-character-pricing') && ( $(field_wrapper).hasClass('pewc-item-text') || $(field_wrapper).hasClass('pewc-item-textarea') ) ) {
							var str_len = pewc_get_text_str_len( $(this).val(), field_wrapper );
							total_price += str_len * parseFloat( $(field_wrapper).attr('data-price') );
						} else if( $(field_wrapper).hasClass('pewc-multiply-pricing') ) {
							var num_value = $(this).val();
							total_price += num_value * parseFloat( $(field_wrapper).attr('data-price') );
						} else if( $(field_wrapper).hasClass('pewc-group-name_price') ) {
							total_price += parseFloat( $(this).val() );
						} else {
							total_price += parseFloat( $(field_wrapper).attr('data-price') );
						}
					}
				} else {
					// Flat rate item
					if( $(field_wrapper).hasClass('pewc-group-checkbox') && ! $(field_wrapper).hasClass('pewc-hidden-field') ) {
						if( $(field_wrapper).hasClass('pewc-per-unit-pricing') && $(this).prop('checked') ) {
							// Bookings for WooCommerce
							// Multiply option cost by number of booked units
							flat_rate_total += parseFloat( $('#num_units_int').val() ) * parseFloat( $(field_wrapper).attr('data-price') );
						} else if( $(this).prop('checked') ) {
							flat_rate_total += parseFloat( $(field_wrapper).attr('data-price') );
						}
					} else if( $(field_wrapper).hasClass('pewc-group-select') && ! $(field_wrapper).hasClass('pewc-hidden-field') ) {
						// Add cost of selected option
						flat_rate_total += parseFloat( $(this).find(':selected').attr('data-option-cost') );
						// Add cost of select field
						flat_rate_total += parseFloat( $(field_wrapper).attr('data-price') );
					} else if( $(this).val() && ! $(field_wrapper).hasClass('pewc-hidden-field') ) {
						if( $(field_wrapper).hasClass('pewc-per-character-pricing') ) {
							var str_len = pewc_get_text_str_len( $(this).val(), field_wrapper );
							flat_rate_total += str_len * parseFloat( $(field_wrapper).attr('data-price') );
						} else if( $(field_wrapper).hasClass('pewc-multiply-pricing') ) {
							var num_value = $(this).val();
							flat_rate_total += num_value * parseFloat( $(field_wrapper).attr('data-price') );
						} else if( $(field_wrapper).hasClass('pewc-group-name_price') ) {
							flat_rate_total += parseFloat( $(this).val() );
						} else {
							flat_rate_total += parseFloat( $(field_wrapper).attr('data-price') );
						}
					}
				}
			}
		});
		$('.pewc-item-radio, .pewc-item-image_swatch').each(function() {
			var radio_group_id = $(this).attr( 'data-id' );
			if( ! $(this).hasClass('pewc-hidden-field') ) {
				if( ! $(this).hasClass('pewc-flatrate') ) {
					if( $('.'+radio_group_id).find( $('input[type=radio]:checked')).attr('data-option-cost') ) {
						total_price += parseFloat( $(this).attr('data-price') );
						var selected_option_price = $('.'+radio_group_id).find( $('input[type=radio]:checked') ).attr('data-option-cost');
						$( this ).attr( 'data-selected-option-price', selected_option_price );
						total_price += parseFloat( selected_option_price );
					}
				} else {
					if( $('.'+radio_group_id).find( $('input[type=radio]:checked')).attr('data-option-cost') ) {
						flat_rate_total += parseFloat( $(this).attr('data-price') );
						var selected_option_price = $('.'+radio_group_id).find( $('input[type=radio]:checked') ).attr('data-option-cost');
						$( this ).attr( 'data-selected-option-price', selected_option_price );
						flat_rate_total += parseFloat( selected_option_price );
					}
				}
			}
		});
		$('.pewc-item-select').each(function() {
			var selected_option_price = $( this ).find( 'option:selected' ).attr( 'data-option-cost' );
			$( this ).attr( 'data-selected-option-price', selected_option_price );
		});
		$('.pewc-item-checkbox_group').each(function() {
			var checkbox_group_id = $(this).attr( 'data-id' );
			var checkbox_group_price = 0;
			if( ! $(this).hasClass('pewc-hidden-field') ) {
				if( ! $(this).hasClass('pewc-flatrate') ) {
					// Get the field price
					if( $("input[name='" + checkbox_group_id + "[]']:checked" ).val() ) {
						checkbox_group_price += parseFloat( $(this).attr('data-price') );
					}
					$('.'+checkbox_group_id).find( $('input[type=checkbox]:checked') ).each( function(){
						checkbox_group_price += parseFloat( $(this).attr('data-option-cost') );
					});
					total_price += checkbox_group_price;
				} else {
					// Flat rate
					if( $("input[name='" + checkbox_group_id + "[]']:checked" ).val() ) {
						flat_rate_total += parseFloat( $(this).attr('data-price') );
					}
					$('.'+checkbox_group_id).find( $('input[type=checkbox]:checked') ).each( function(){
						flat_rate_total += parseFloat( $(this).attr('data-option-cost') );
					});
				}
			}
		});
		$('.pewc-item-image-swatch-checkbox').each(function() {
			var checkbox_group_id = $(this).attr( 'data-id' );
			var checkbox_group_price = 0;
			if( ! $(this).hasClass('pewc-hidden-field') ) {
				if( ! $(this).hasClass('pewc-flatrate') ) {
					// Get the field price
					if( $("input[name='" + checkbox_group_id + "[]']:checked" ).val() ) {
						checkbox_group_price += parseFloat( $(this).attr('data-price') );
					}
					$('.'+checkbox_group_id).find( $('input[type=checkbox]:checked') ).each( function(){
						checkbox_group_price += parseFloat( $(this).attr('data-option-cost') );
					});
					total_price += checkbox_group_price;
				} else {
					// Flat rate
					if( $("input[name='" + checkbox_group_id + "[]']:checked" ).val() ) {
						flat_rate_total += parseFloat( $(this).attr('data-price') );
					}
					$('.'+checkbox_group_id).find( $('input[type=checkbox]:checked') ).each( function(){
						flat_rate_total += parseFloat( $(this).attr('data-option-cost') );
					});
				}
			}
		});
		// Check child products with select layout
		var child_products_total = 0;
		$('.pewc-child-select-field').each(function() {
			var is_hidden = $( this ).closest( 'li.pewc-item' ).hasClass( 'pewc-hidden-field' );
			if( ! is_hidden ) {
				// If the select field has a value
				if( $(this).val() && $(this).val() != '' ) {
					var child_product_price = $(this).find(':selected').data( 'option-cost' );
					var qty = 0;
					if( child_product_price > 0 ) {
						var wrapper = $(this).closest('.child-product-wrapper');
						var quantities = $(wrapper).data('products-quantities');
						// Get the quantity
						if( quantities == 'linked' ) {
							qty = $('.quantity .qty').val();
						} else if( quantities == 'independent' ) {
							// Find the child_quantity field
							qty = $(wrapper).find('.pewc-child-quantity-field').val();
						} else if( quantities == 'one-only' ) {
							qty = 1;
						}
					}
					child_products_total += parseFloat( child_product_price ) * parseFloat( qty );
				}
			}
		});
		$('.pewc-radio-images-wrapper.child-product-wrapper').each(function() {
			var is_hidden = $( this ).closest( 'li.pewc-item' ).hasClass( 'pewc-hidden-field' );
			if( ! is_hidden ) {
				var quantities = $(this).data('products-quantities');
				var radio_val = $(this).find('.pewc-radio-form-field:checked').val();
				if( radio_val && radio_val != undefined ) {
					var child_product_price = $(this).find('.pewc-radio-form-field:checked').data('option-cost');
					var qty = 0;
					if( child_product_price > 0 ) {
						// Get the quantity
						if( quantities == 'linked' ) {
							qty = $('.quantity .qty').val();
						} else if( quantities == 'independent' ) {
							// Find the child_quantity field
							// $(this).closest('.pewc-item-field-wrapper').css('background','red');
							qty = $(this).closest('.pewc-item-field-wrapper').find('.pewc-child-quantity-field').val();
						} else if( quantities == 'one-only' ) {
							qty = 1;
						}
					}
					child_products_total += parseFloat( child_product_price ) * parseFloat( qty );
				}
			}
		});
		$('.pewc-checkboxes-images-wrapper.child-product-wrapper').each(function() {
			var is_hidden = $( this ).closest( 'li.pewc-item' ).hasClass( 'pewc-hidden-field' );
			if( ! is_hidden ) {
				var quantities = $(this).data('products-quantities');
				// Run through each selected checkbox
				$(this).find('.pewc-checkbox-form-field:checkbox:checked').each(function(){
					var child_product_price = $(this).data('option-cost');
					var qty = 0;
					if( child_product_price > 0 ) {
						// Get the quantity
						if( quantities == 'linked' ) {
							qty = $('.quantity .qty').val();
						} else if( quantities == 'independent' ) {
							qty = $(this).closest('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').val();
						} else if( quantities == 'one-only' ) {
							qty = 1;
						}
					}
					child_products_total += parseFloat( child_product_price ) * parseFloat( qty );
				});
			}

		});
		$('.pewc-column-wrapper .pewc-variable-child-product-wrapper.checked').each(function() {
			var is_hidden = $( this ).closest( 'li.pewc-item' ).hasClass( 'pewc-hidden-field' );
			if( ! is_hidden ) {
				var quantities = $(this).closest( '.pewc-column-wrapper' ).data('products-quantities');
				// Run through each selected checkbox for variable child products
				$(this).find('.pewc-variable-child-select').each(function(){
					var child_product_price = $(this).find(':selected').data('option-cost');
					var qty = 0;
					if( child_product_price > 0 ) {
						// Get the quantity
						if( quantities == 'linked' ) {
							qty = $('.quantity .qty').val();
						} else if( quantities == 'independent' ) {
							qty = $(this).closest('.pewc-checkbox-image-wrapper').find('.pewc-child-quantity-field').val();
						} else if( quantities == 'one-only' ) {
							qty = 1;
						}
					}
					child_products_total += parseFloat( child_product_price ) * parseFloat( qty );
				});
			}
		});
		$('.pewc-column-wrapper .pewc-simple-child-product-wrapper.checked').each(function() {
			var is_hidden = $( this ).closest( 'li.pewc-item' ).hasClass( 'pewc-hidden-field' );
			if( ! is_hidden ) {
				var quantities = $(this).closest( '.pewc-column-wrapper' ).data('products-quantities');
				// Run through each selected checkbox
				$(this).find('.pewc-checkbox-form-field:checkbox:checked').each(function(){
					var child_product_price = $(this).data('option-cost');
					var qty = 0;
					if( child_product_price > 0 ) {
						// Get the quantity
						if( quantities == 'linked' ) {
							qty = $('.quantity .qty').val();
						} else if( quantities == 'independent' ) {
							qty = $(this).closest('.pewc-simple-child-product-wrapper').find('.pewc-child-quantity-field').val();
						} else if( quantities == 'one-only' ) {
							qty = 1;
						}
					}
					child_products_total += parseFloat( child_product_price ) * parseFloat( qty );
				});
			}
		});
		$('.pewc-swatches-wrapper .pewc-child-variation-main.checked').each(function() {

			var is_hidden = $( this ).closest( 'li.pewc-item' ).hasClass( 'pewc-hidden-field' );
			if( ! is_hidden ) {

				var quantities = $(this).closest( '.pewc-swatches-wrapper' ).data('products-quantities');

				// Run through each selected variation product
				$(this).find('.pewc-child-name input').each(function() {
					var child_product_price = parseFloat( $(this).data( 'option-cost' ) );
					var qty = 0;
					if( child_product_price > 0 ) {
						// Get the quantity
						if( quantities == 'linked' ) {
							qty = $('.quantity .qty').val();
						} else if( quantities == 'independent' ) {
							qty = $(this).closest('.pewc-child-variation-main').find('.pewc-child-quantity-field').val();
						} else if( quantities == 'one-only' ) {
							qty = 1;
						}
					}
					child_products_total += parseFloat( child_product_price ) * parseFloat( qty );
				});

			}

		});

		if( product_price < 0 ) product_price = 0;
		var qty = 1;
		if($('.qty').val()) {
			qty = $('.qty').val();
		}
		var product_price = qty * product_price;
		var grand_total = product_price;
		product_price = product_price.toFixed(pewc_vars.decimals);
		product_price = pewc_wc_price( product_price );
		$('#pewc-per-product-total').html(product_price);

		total_price = qty * total_price;
		total_price += child_products_total;
		grand_total += total_price;
		total_price = total_price.toFixed(pewc_vars.decimals);
		total_price = pewc_wc_price( total_price );
		$('#pewc-options-total').html(total_price);

		if( flat_rate_total < 0 ) flat_rate_total = 0;
		grand_total += flat_rate_total;
		flat_rate_total = flat_rate_total.toFixed(pewc_vars.decimals);
		flat_rate_total = pewc_wc_price( flat_rate_total );
		$('#pewc-flat-rate-total').html(flat_rate_total);

		grand_total = grand_total.toFixed(pewc_vars.decimals);
		grand_total = pewc_wc_price( grand_total );
		$('#pewc-grand-total').html(grand_total);

		// Re-run this because some browsers are too quick
		if( $update == 0 ) {
			var interval = setTimeout( function() {
				pewc_update_total_js( 1 );
				if( ! total_updated ) {
					// Check any calculations before input fields are updated
					$( 'body' ).trigger( 'pewc_trigger_calculations' );
					total_updated = true;
				}
			},
			250 );
		}
	}
	function pewc_wc_price( price ) {
		var return_html, price_html, formatted_price;
		if( pewc_vars.currency_pos == 'left' ) {
			formatted_price = pewc_vars.currency_symbol + '&#x200e;' + price;
		} else if( pewc_vars.currency_pos == 'right' ) {
			formatted_price = price + pewc_vars.currency_symbol + '&#x200f;';
		} else if( pewc_vars.currency_pos == 'left_space' ) {
			formatted_price = pewc_vars.currency_symbol + '&#x200e;&nbsp;' + price;
		} else if( pewc_vars.currency_pos == 'right_space' ) {
			formatted_price = price + '&nbsp;' + pewc_vars.currency_symbol + '&#x200f;';
		}
		formatted_price = formatted_price.replace('.',pewc_vars.decimal_separator);
		price_html = '<span class="woocommerce-Price-currencySymbol">' + formatted_price + '</span>';
		return_html = '<span class="woocommerce-Price-amount amount">' + price_html + '</span>';

		$('#pewc_total_calc_price').val( price ); // Used in Bookings for WooCommerce

		return return_html;
	}
	// var interval = setInterval(function(){
	// 	pewc_update_total_js();
	// },
	// 500);
	$('form.cart').on('keyup input change paste', 'input, select, textarea', function(){
    pewc_update_total_js();
		$( 'body' ).trigger( 'pewc_updated_total_js' );
	});
	var interval = setTimeout( function() {
		pewc_update_total_js();
	},
	250 );
	$( 'body' ).on( 'pewc_add_button_clicked', function() {
		pewc_update_total_js();
	});
	$( 'body' ).on( 'pewc_force_update_total_js', function() {
		pewc_update_total_js();
	});
	// Accordion and tabs
	$('.pewc-groups-accordion h3').on('click',function(e){
		$(this).closest('.pewc-group-wrap').toggleClass('group-active');
	});
	$('.first-group').addClass('group-active');
	$('.pewc-tab').on('click',function(e){
		var tab_id = $(this).attr('data-group-id');
		$('.pewc-tab').removeClass('active-tab');
		$(this).addClass('active-tab');
		$('.pewc-group-wrap').removeClass('group-active');
		$('.pewc-group-wrap-'+tab_id).addClass('group-active');
	});

	function pewc_get_text_str_len( str, wrapper ) {
		var new_str;
		var field = $(wrapper).find('.pewc-form-field');
		// Don't include spaces
		var str_ex_spaces = str.replace(/\s/g, "");
		var str_len = str_ex_spaces.length;
		// Exclude alphanumerics if selected
		if( $(field).attr('data-alphanumeric') == 1 ) {
			str_ex_spaces = str_ex_spaces.replace(/\W/g, '');
			$(field).val(str_ex_spaces);
			str_len = str_ex_spaces.length;
		}
		// Allow alphanumerics but don't charge if selected
		if( $(field).attr('data-alphanumeric-charge') == 1 ) {
			str_ex_spaces = str_ex_spaces.replace(/\W/g, '');
			// $(field).val(str_ex_spaces);
			str_len = str_ex_spaces.length;
		}
		// If free characters are allowed
		var freechars = $(field).attr('data-freechars');
		str_len -= freechars;
		str_len = Math.max(0,str_len);
		return str_len;
	}
	$('.woocommerce-cart-form__cart-item.pewc-child-product').each(function() {
		$(this).find('.product-quantity input').attr('disabled',true);
	});
	// If child product is selected, manage allowable purchase quantity
	// Applies to radio and select
	$('body').on('change','.products-quantities-independent .pewc-child-select-field',function(){
		var number_field = $(this).closest('.child-product-wrapper').find('.pewc-child-quantity-field');
		if( $(number_field).val() == 0 ) {
			// Automatically enter a quantity when a product is selected
			$(number_field).val(1);
		};
		var available_stock = $(this).find(':selected').data('stock');
		if( available_stock ) {
			var number = $(number_field).attr('max',available_stock);
			if( $(number).val() > available_stock ) {
				$(number_field).val(available_stock);
			}
		} else {
			$(number_field).removeAttr('max');
		}
	});
	$('body').on('change input keyup paste','.products-quantities-independent .pewc-child-quantity-field',function(){
		// Ensure this child product is selected
		if( $(this).val() > 0 ) {
			var checkbox = $(this).closest('.pewc-checkbox-image-wrapper').find('input[type=checkbox]').attr('checked',true);
		} else {
			var checkbox = $(this).closest('.pewc-checkbox-image-wrapper').find('input[type=checkbox]').removeAttr('checked');
		}
		var available_stock = $(this).find(':selected').data('stock');
		$( 'body' ).trigger( 'pewc_update_child_quantity', [ $(this).closest('.pewc-checkbox-image-wrapper').find('input[type=checkbox]') ] );
	});
	$('body').on('click','.products-quantities-independent .pewc-checkbox-form-field',function(){
		if($(this).is(':checked')){
			var number = $(this).closest('.pewc-checkbox-image-wrapper').find('input[type=number]').val();
			if(number==0) {
				$(this).closest('.pewc-checkbox-image-wrapper').find('input[type=number]').val(1);
			}
		} else {
			var number = $(this).closest('.pewc-checkbox-image-wrapper').find('input[type=number]').val(0);
		}
	});
	$( 'body' ).on( 'click', '.pewc-radio-image-wrapper', function( e ) {
		var wrapper = $( this );

		var radio;
		// Remove all checked for radio button
		if( ! $( wrapper ).closest( '.pewc-item' ).hasClass( 'pewc-item-image-swatch-checkbox' ) ) {
			radio = $( wrapper ).find( '.pewc-radio-form-field' ).trigger( 'click' );
			var group = $( wrapper ).closest( '.pewc-radio-images-wrapper' ).find( '.pewc-radio-image-wrapper' ).removeClass( 'checked' );
			$( wrapper ).addClass( 'checked' );
		} else {
			// Checkbox
			radio = $( wrapper ).find( '.pewc-radio-form-field' );
			var checked = $( radio ).prop( 'checked' );
			$( radio ).prop( 'checked', ! checked );
		}
	}).on( 'click', '.pewc-radio-image-wrapper .pewc-radio-form-field', function( e ) {
		var has_class = $( this ).closest( '.pewc-item' ).hasClass( 'pewc-item-image-swatch-checkbox' );
		if( ! has_class ) {
			// Stop propagation for radio buttons
			e.stopPropagation();
		} else {
			$( this ).closest( '.pewc-radio-image-wrapper' ).toggleClass( 'checked' );
		}
	});
	$('body').on('click','.products-quantities-independent .pewc-radio-form-field',function(){
		if($(this).is(':checked')) {
			var number_field = $(this).closest('.pewc-item-field-wrapper').find('input[type=number]');
			var number = $(number_field).val();
			if( number == 0 ) {
				$( number_field ).val( 1 );
			}
			if( $(this).attr('data-stock') > 0 ) {
				// Ensure the quantity field doesn't display more than the available stock
				$(number_field).attr( 'max', $(this).attr('data-stock') );
				if( $(number_field).val() > $(this).attr('data-stock') ) {
					$(number_field).val( $(this).attr('data-stock') );
				}
			}
		} else {
			var number = $(this).closest('.pewc-radio-images-wrapper').find('input[type=number]').val(0);
		}
	});

	var calculations = {

		init: function() {
			$( 'body' ).on( 'keyup input change paste', '.pewc-number-field', this.recalculate );
			$( 'body' ).on( 'keyup input change paste', '.pewc-item, .pewc-form-field', this.recalculate );
			$( 'body' ).on( 'keyup input change paste', '.pewc-number-uploads', this.recalculate );
			$( 'body' ).on( 'pewc_trigger_calculations', this.recalculate );
			$( 'body' ).on( 'pewc_conditions_checked', this.recalculate );
		},

		recalculate: function() {

			var calc_field, price_wrapper, dimensions_wrapper, formula, tags, calc_formula, replace;

			// Find any calculation fields
			$( 'body' ).find( '.pewc-item-calculation' ).not( '.pewc-hidden-field' ).each( function() {

				calc_field = $( this );
				price_wrapper = $( calc_field ).find( '.pewc-calculation-price-wrapper' );
				formula = $( price_wrapper ).find( '.pewc-data-formula' ).val();
				fields = $( price_wrapper ).find( '.pewc-data-fields' ).val();
				tags = $( price_wrapper ).find( '.pewc-data-tag' ).val();
				action = $( price_wrapper ).find( '.pewc-action' ).val();
				round = $( price_wrapper ).find( '.pewc-formula-round' ).val();
				decimals = $( price_wrapper ).find( '.pewc-decimal-places' ).val();

				if( fields ) {
					fields = JSON.parse( fields );
				}

				var result = evaluate_formula( fields, formula, round, decimals );
				if( ! result || ! isNaN( result ) ) {
					$( price_wrapper ).find( 'span' ).html( result );
					if( action == 'cost' ) {
						$( calc_field ).closest( '.pewc-item-calculation' ).attr( 'data-price', result );
						$( price_wrapper ).find( 'span' ).html( pewc_wc_price( result ) );
					} else if( action == 'qty' ) {
						$( 'form.cart' ).find( '.quantity .qty' ).val( result );
					}
					$( price_wrapper ).find( '.pewc-calculation-value' ).val( result ).trigger( 'calculation_field_updated' );
				}

			});

			// Update the totals
			pewc_update_total_js();

		},

	}

	calculations.init();

	function evaluate_formula( fields, formula, round, decimals ) {

		var calc_formula = formula;

		if( fields ) {

			// Replace any field tags with values
			for( var i in fields ) {

				// Look for any price values
				if( fields[i].indexOf( '_option_price' ) > -1 ) {
					var field_id = fields[i].replace( '_option_price', '' );
					// We want the price of the selected option in this field, not its value
					var o_price = parseFloat( $( '.pewc-field-' + field_id ).attr( 'data-selected-option-price' ) );
					replace = new RegExp( '{field_' + fields[i] + '}', 'g' );
					calc_formula = calc_formula.replace( replace, o_price );
				} else if( fields[i].indexOf( '_field_price' ) > -1 ) {
					// We want the price of the field
					var field_id = fields[i].replace( '_field_price', '' );
					var f_price = parseFloat( $( '.pewc-field-' + field_id ).attr( 'data-price' ) );
					replace = new RegExp( '{field_' + fields[i] + '}', 'g' );
					calc_formula = calc_formula.replace( replace, f_price );
				} else if( fields[i].indexOf( '_number_uploads' ) > -1 ) {
					// We want the number of uploads
					var field_id = fields[i].replace( '_number_uploads', '' );
					var num_uploads = parseFloat( $( '.pewc-field-' + field_id ).find( '.pewc-number-uploads' ).val() );
					replace = new RegExp( '{field_' + fields[i] + '}', 'g' );
					calc_formula = calc_formula.replace( replace, num_uploads );
				} else {
					// Look for the value of number fields
					var f_val = parseFloat( $( '.pewc-number-field-' + fields[i] ).val() );
					if( ! isNaN( f_val ) ) {
						replace = new RegExp( '{field_' + fields[i] + '}', 'g' );
						calc_formula = calc_formula.replace( replace, f_val );
					}
				}

			}

		}


		var product_price = parseFloat( $('#pewc-product-price').val() );
		if( formula.includes( "{product_price}" ) && product_price ) {
			calc_formula = calc_formula.replace( /{product_price}/g, parseFloat( product_price ) );
		}
		if( formula.includes( "{variable_1}" ) && pewc_vars.variable_1 ) {
			calc_formula = calc_formula.replace( /{variable_1}/g, parseFloat( pewc_vars.variable_1 ) );
		}
		if( formula.includes( "{variable_2}" ) && pewc_vars.variable_2 ) {
			calc_formula = calc_formula.replace( /{variable_2}/g, parseFloat( pewc_vars.variable_2 ) );
		}
		if( formula.includes( "{variable_3}" ) && pewc_vars.variable_3 ) {
			calc_formula = calc_formula.replace( /{variable_3}/g, parseFloat( pewc_vars.variable_3 ) );
		}

		var result;

		try {
			result = math.eval( calc_formula );
			if( round == 'ceil' ) {
				result = math.ceil( result );
			} else if( round == 'floor' ) {
				result = math.floor( result );
			}
			return result.toFixed( parseFloat( decimals ) );
		} catch( err ) {
			// Check all tags have been replaced
			return 'error';
		}

	}

	var hidden_groups = {

		init: function() {
			$( 'body' ).on( 'pewc_conditions_checked', this.check_group_visibility );
		},

		/**
		 * Check whether to hide or display groups
		 */
		check_group_visibility: function() {

			// Check each group
			$( 'body' ).find( '.pewc-group-wrap' ).each( function() {
				var all_hidden = true;
				var group = $( this );
				$( group ).find( '.pewc-item' ).each( function() {
					if( ! $( this ).hasClass( 'pewc-hidden-field' ) ) {
						all_hidden = false;
					}
				});
				if( all_hidden ) {
					$( group ).addClass( 'pewc-hidden-group' );
				} else {
					$( group ).removeClass( 'pewc-hidden-group' );
				}
			});

		}

	}

	hidden_groups.init();

})(jQuery);
