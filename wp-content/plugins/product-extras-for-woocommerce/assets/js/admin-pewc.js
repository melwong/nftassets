(function($) {
	$('.pewc-date-field').datepicker();
	update_field_names_object();
	$('body').on('click','.add_new_group',function(e){
		e.preventDefault();
		var last_row = $('.product-extra-group-data').find('.group-row').last();
		var count = $(last_row).attr('data-group-count');
		count = parseFloat( count ) + 1;
		if( isNaN( count ) ) {
			count = 0;
		}
		var clone_row = $('.new-group-row').clone().appendTo('#product_extra_groups');
		var new_group_id = pewc_get_id_code();
		$(clone_row).removeClass('new-group-row');
		$(clone_row).attr('data-group-count',new_group_id);
		$(clone_row).attr('data-group-id',new_group_id);
		$(clone_row).attr('id','group-' + new_group_id);
		$(clone_row).find('.pewc-group-title').attr('name','_product_extra_groups['+ new_group_id +'][meta][group_title]');
		$(clone_row).find('.pewc-group-required').attr('name','_product_extra_groups['+ new_group_id +'][meta][group_required]');
		$(clone_row).find('.pewc-group-description').attr('name','_product_extra_groups['+ new_group_id +'][meta][group_description]');
	});
	$('body').on('click','.add_new_field',function(e){
		e.preventDefault();
		var group_id = $(this).closest('.group-row').attr('data-group-id');
		var last_item = $('#group-' + group_id + ' ul.field-list').find('li.field-item').last();
		var item_count = 0;
		if( last_item ) {
			item_count = $(last_item).attr('data-size-count');
			item_count = parseFloat( item_count ) + 1;
		} else {
			item_count = 0;
		}
		if( isNaN( item_count ) ) {
			item_count = 0;
		}

		var clone_item = $('.new-field-item').clone().appendTo('#group-' + group_id + ' ul.field-list');
		var new_item_id = pewc_get_id_code();
		$(clone_item).removeClass('new-field-item');
		$(clone_item).attr('id','pewc_group_' + group_id + '_' + new_item_id);
		$(clone_item).attr('data-size-count',new_item_id);
		$(clone_item).attr('data-item-id',new_item_id);
		$(clone_item)
			.find('.pewc-id')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][id]')
			.val('pewc_group_' + group_id + '_' + new_item_id);

		$(clone_item)
			.find('.pewc-group-id')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][group_id]')
			.val(group_id);

		$(clone_item)
			.find('.pewc-field-id')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][field_id]')
			.val(new_item_id);

		$(clone_item)
			.find('.pewc-field-type')
			.attr('id','field_type_' + group_id + '_' + new_item_id );

		$(clone_item)
			.find('.pewc-option-fields')
			.attr('id','pewc_option_' + group_id + '_' + new_item_id );

		$(clone_item)
			.find('.pewc-upload-button')
			.attr('data-item-id',new_item_id );

		$(clone_item)
			.find('.pewc-image-attachment-id')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][field_image]');

		$(clone_item)
			.find('.pewc-field-image')
			.addClass('pewc-field-image-'+new_item_id);

		$(clone_item)
			.addClass('field-type-checkbox');

		$(clone_item)
			.find('.pewc-number-columns')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][number_columns]');

		$(clone_item)
			.find('.pewc-min-child-products')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][min_products]');

		$(clone_item)
			.find('.pewc-max-child-products')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][max_products]');

		$(clone_item)
			.find('.pewc-hide-labels')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][hide_labels]');

		$(clone_item)
			.find('.pewc-allow-multiple')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][allow_multiple]');

		$(clone_item)
			.find('.pewc-field-per-character')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][per_character]');

		$(clone_item)
			.find('.pewc-field-alphanumeric-charge')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][field_alphanumeric_charge]');

		$(clone_item)
			.find('.pewc-first-field-empty')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][first_field_empty]');

		$(clone_item)
			.find('.pewc-field-default-hidden')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][field_default_hidden]');

		var replace_fields = [ 'child_products', 'products_layout', 'products_quantities', 'select_placeholder', 'allow_none', 'min_date_today' ];
		$(replace_fields).each( function(i,v) {
			$(clone_item).find('.pewc-field-' + v).attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][' + v + ']');
		});

		fields = [ 'label', 'type', 'price', 'required', 'per_unit', 'flatrate', 'percentage', 'description', 'minchars', 'maxchars', 'minchecks', 'maxchecks', 'minval', 'maxval', 'freechars', 'alphanumeric', 'mindate', 'maxdate', 'default' ];
		$(fields).each( function( i, v ) {
			$(clone_item).find('.pewc-field-' + v).attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][field_' + v + ']');
		});

		// Check action and match names are populated
		var condition_fields = $(clone_item).closest('.pewc-fields-conditionals');
		$(clone_item).find('.pewc-condition-action').attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][condition_action]');
		$(clone_item).find('.pewc-condition-condition').attr('name','_product_extra_groups[' + group_id + '][items][' + new_item_id + '][condition_match]');

		update_field_names_object(); // Update list of fields

	});
	$('body').on('click','.add_new_option',function(e){
		e.preventDefault();
		var group_id = $(this).closest('.group-row').attr('data-group-id');
		var item_id = $(this).closest('.field-item').attr('data-item-id');
		var option_fields = $(this).closest('.pewc-option-fields');
		var last_option = $(option_fields).find('.product-extra-option-wrapper').last();
		var option_count = 0;
		if( last_option ) {
			option_count = parseFloat( $(last_option).attr('data-option-count') );
			option_count++;
		}
		if( isNaN( option_count ) ) {
			option_count = 0;
		}

		// appendTo(option_fields);
		var clone_option = $('.new-option .product-extra-option-wrapper').clone().insertBefore( $(this).parent() );

		$(clone_option).attr('data-option-count',option_count);
		$(clone_option)
			.find('.pewc-field-option-value')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][field_options][' + option_count + '][value]')
			.val('');

		$(clone_option)
			.find('.pewc-field-option-price')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][field_options][' + option_count + '][price]')
			.val('');

		$(clone_option)
			.find('.pewc-image-attachment-id')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][field_options][' + option_count + '][image]');

		$(clone_option)
			.find('.pewc-field-image')
			.addClass('pewc-field-image-' + item_id + '_' + option_count);

		$(clone_option)
			.find('.pewc-upload-option-image')
			.attr('data-item-id',item_id+'_'+option_count);

	});

	$('body').on('click','.remove',function(e){
		e.preventDefault;
		var r = confirm( pewc_obj.delete_group );
		if( r == true ) {
			var group = $(this).closest('.group-row');
			$(group).fadeOut(
				150,
				function(){
					$(this).remove();
				}
			);
		}
	});
	$('body').on('click','.remove-field',function(e){
		e.preventDefault;
		var r = confirm( pewc_obj.delete_field );
		if( r == true ) {
			var group_id = $(this).closest('.group-row').attr('data-group-id');
			$(this).closest('.field-item').fadeOut(
				150,
				function(){
					$(this).remove();
					var item_id = $(this).attr('data-item-id');
					pewc_remove_associated_conditions( group_id, item_id );
				}
			);
		}
	});
	$('body').on('click','.remove-option',function(e){
		e.preventDefault;
		var r = confirm( pewc_obj.delete_option );
		if( r == true ) {
			var field_item = $(this).closest('.field-item');
			$(this).closest('.product-extra-option-wrapper').fadeOut(
				150,
				function(){
					$(this).remove();
					// Remove this option from any conditions
					set_options_data( field_item );
				}
			);
		}
	});
	$('body').on('click','.pewc-group-meta-heading',function(e){
		e.preventDefault;
		$(this).closest('.field-table').toggleClass('collapse-panel');
	});
	$('body').on('click','.pewc-field-meta-heading',function(e){
		e.preventDefault;
		$(this).closest('.field-item').toggleClass('collapsed-field');
	});
	$('body').on('keyup','.pewc-group-title',function(){
		var title = $(this).val();
		var heading = $(this).closest('.group-row').find('.pewc-display-title').text(title);
	});
	$('body').on('keyup','.pewc-field-label',function(){
		var title = $(this).val();
		var heading = $(this).closest('.field-item').find('.pewc-display-field-title').text(title);
	});

	$('body').on('change','.pewc-field-type',function(e) {
		e.preventDefault;
		var field_type = $(this).val();
		// Check if there are any conditionals associated with this field
		pewc_check_field_has_conditions( $(this).attr('id'), field_type );
		var wrapper = $(this).closest('.field-item');
		$(wrapper).removeClass (function (index, className) {
			return (className.match (/(^|\s)field-type-\S+/g) || []).join(' ');
		});
		$(wrapper).addClass('field-item field-type-'+field_type);
	});
	$('body').on('change','.pewc-field-products_layout',function(e) {
		e.preventDefault;
		var layout = $(this).val();
		var wrapper = $(this).closest('.field-item');
		$(wrapper).removeClass (function (index, className) {
			return (className.match (/(^|\s)products-layout-\S+/g) || []).join(' ');
		});
		$(wrapper).addClass('products-layout-'+layout);
		// Set allow_none to enabled if layout is checkboxes
		$(wrapper).find('.pewc-field-allow_none').attr('disabled',false);
		if( layout=='checkboxes' || layout=='column' ) {
			// $(wrapper).find('.pewc-field-allow_none').attr('checked',true);
			$(wrapper).find('.pewc-field-allow_none').attr('disabled',true);
		}
	});
	$('body').on('change','.pewc-field-products_quantities',function(e) {
		e.preventDefault;
		var quantities = $(this).val();
		var wrapper = $(this).closest('.field-item');
		$(wrapper).removeClass (function (index, className) {
			return (className.match (/(^|\s)products-quantities-\S+/g) || []).join(' ');
		});
		$(wrapper).addClass('products-quantities-'+quantities);
	});
	$('#product_extra_groups').sortable();
	$('.field-list').sortable();
	$('.pewc-field-options-wrapper, .pewc-global-set-wrap').sortable();

	$('body').on('click','.pewc-field-per-character',function(e){
		e.preventDefault;
		var wrapper = $(this).closest('.field-item').toggleClass('per-char-selected');
	});
	$( 'body' ).on( 'change', '.pewc-field-default', function( e ) {
		$( this ).closest( '.pewc-default-fields' ).find( '.pewc-field-default-hidden' ).val( $( this).val() );
	});

	// Conditionals
	$('body').on('change','.pewc-condition-field',function() {
		// Display a value field if both selects have a legitimate value, i.e not 'not-selected'
		var select = $(this);
		var group_id = $(this).attr( 'data-group-id' );
		var item_id = $(this).attr( 'data-item-id' );
		var condition_id = $(this).attr( 'data-condition-id' );
		var condition_field = $( '#condition_field_' + group_id + '_' + item_id + '_' + condition_id ).val();
		var condition_rule = $( '#condition_rule_' + group_id + '_' + item_id + '_' + condition_id ).val();
		if( condition_field != 'not-selected' && condition_rule != 'not-selected' ) {
			// Show the value field
			var value_field;
			// Find the field type of the selected field
			var field_id = condition_field.replace( 'pewc_group_', 'field_type_' );
			var field_type = $('#' + field_id ).val();
			if( field_type == undefined ) {
				// Catch 'cost'
				field_type = $( select ).find(':selected').attr( 'data-type' );
			}
			var value_field = pewc_get_value_field_type( field_type );

			pewc_add_value_field( select, field_id, field_type, value_field, '' );
			pewc_set_rule_field( select, field_type );

		} else {
			// Hide the value field
		}

	});
	$('body').on('click','.add_new_condition',function(e){
		e.preventDefault();
		var group_id = $(this).closest('.group-row').attr('data-group-id');
		var item_id = $(this).closest('.field-item').attr('data-item-id');
		var condition_fields = $(this).closest('.pewc-fields-conditionals');
		var last_condition = $(condition_fields).find('.product-extra-conditional-row').last();
		var condition_count = 0;
		if( last_condition ) {
			condition_count = parseFloat( $(last_condition).attr('data-condition-count') );
			condition_count++;
		}
		if( isNaN( condition_count ) ) {
			condition_count = 0;
			$(this).closest('.pewc-fields-conditionals').find('.product-extra-action-match-row').fadeIn();
		}

		var clone_condition = $('.new-conditional-row').clone().insertBefore( $(this).parent() );
		$(clone_condition).removeClass('new-conditional-row');

		$(clone_condition).attr('data-condition-count',condition_count);
		$(clone_condition)
			.find('.pewc-condition-field')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][condition_field][' + condition_count + ']')
			.attr('id','condition_field_' + group_id + '_' + item_id + '_' + condition_count )
			.attr('data-group-id', group_id)
			.attr('data-item-id', item_id)
			.attr('data-condition-id', condition_count)
			.val('');

		// If we're in global, just get fields from current group

		// Remove the current field from the list of fields
		var select = $(clone_condition).find('.pewc-condition-field').attr('id');
		var select_id = '#condition_field_' + group_id + '_' + item_id + '_' + condition_count;
		var option_value = 'pewc_group_' + group_id + '_' + item_id;
		$(select_id + ' option[value="' + option_value + '"]').remove();

		$(clone_condition)
			.find('.pewc-condition-rule')
			.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][condition_rule][' + condition_count + ']')
			.attr('id','condition_rule_' + group_id + '_' + item_id + '_' + condition_count )
			.attr('data-group-id', group_id)
			.attr('data-item-id', item_id)
			.attr('data-condition-id', condition_count);
	});
	//
	// $('body').on('click','.add_new_variation',function(e){
	// 	e.preventDefault();
	// 	var group_id = $(this).closest('.group-row').attr('data-group-id');
	// 	var item_id = $(this).closest('.field-item').attr('data-item-id');
	// 	var variation_fields = $(this).closest('.pewc-fields-variations');
	// 	var last_variation = $(variation_fields).find('.product-extra-variation-row').last();
	// 	var variation_count = 0;
	// 	if( last_variation ) {
	// 		variation_count = parseFloat( $(last_variation).attr('data-variation-count') );
	// 		variation_count++;
	// 	}
	// 	if( isNaN( variation_count ) ) {
	// 		variation_count = 0;
	// 		$(this).closest('.pewc-fields-variations').find('.product-extra-action-match-row').fadeIn();
	// 	}
	//
	// 	var clone_variation = $('.new-variation-row').clone().insertBefore( $(this).parent() );
	// 	$(clone_variation).removeClass('new-variation-row');
	//
	// 	console.log(clone_variation);
	//
	// 	$(clone_variation).attr('data-variation-count',variation_count);
	// 	$(clone_variation)
	// 		.find('.pewc-variation-field')
	// 		.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][variation_field][' + variation_count + ']')
	// 		.attr('id','variation_field_' + group_id + '_' + item_id + '_' + variation_count )
	// 		.attr('data-group-id', group_id)
	// 		.attr('data-item-id', item_id)
	// 		.attr('data-variation-id', variation_count);
	//
	// 	// If we're in global, just get fields from current group
	//
	// 	// Remove the current field from the list of fields
	// 	var select = $(clone_variation).find('.pewc-variation-field').attr('id');
	// 	var select_id = '#variation_field_' + group_id + '_' + item_id + '_' + variation_count;
	// 	var option_value = 'pewc_group_' + group_id + '_' + item_id;
	// 	$(select_id + ' option[value="' + option_value + '"]').remove();
	//
	// 	$(clone_variation)
	// 		.find('.pewc-variation-rule')
	// 		.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][variation_rule][' + variation_count + ']')
	// 		.attr('id','variation_rule_' + group_id + '_' + item_id + '_' + variation_count )
	// 		.attr('data-group-id', group_id)
	// 		.attr('data-item-id', item_id)
	// 		.attr('data-variation-id', variation_count);
	// });

	$('body').on('click','.remove-condition',function(e){
		e.preventDefault;
		var r = confirm( 'Delete this condition?' );
		if( r == true ) {
			$(this).closest('.product-extra-conditional-row').fadeOut(
				150,
				function(){
					if( $(this).attr( 'data-condition-count' ) == '0' ) {
						// Last condition removed so hide actions and set to null
						$(this).parent().find('.product-extra-action-match-row').fadeOut();
						$(this).closest('.pewc-fields-conditionals').find('select option:selected').removeAttr("selected");
					}
					$(this).remove();
				}
			);
		}
	});

	// Remove associated conditions when field is deleted
	function pewc_remove_associated_conditions( group_id, item_id ) {
		// Look for each condition where this field is selected
		var field_id = 'pewc_group_' + group_id + '_' + item_id;
		$('.pewc-condition-select').each(function(i,v){
			var select_id = $(this).attr('id');
			$('#' + select_id).find('option:selected').each(function(){
				var option_value = $(this).val();
				if( option_value == field_id ) {
					var conditions_wrapper = $(this).closest('.pewc-fields-conditionals');
					$('#' + select_id).closest('.product-extra-conditional-rule').remove();
					// Have we removed the last condition?
					if( $(conditions_wrapper).find('.product-extra-conditional-rule').length == 0 ) {
						$(conditions_wrapper).find('.product-extra-action-match-row').fadeOut();
					}
				}
			});
		});
	}
	// Return the type of value field based on the condition field selected
	function pewc_get_value_field_type( field_type ) {
		if( field_type == 'number' || field_type == 'cost' ) {
			return 'pewc-input-number';
		} else if( field_type == 'text' ) {
			return 'pewc-input-text';
		} else if( field_type == 'select' || field_type == 'radio' || field_type == 'image_swatch' || field_type == 'products' || field_type == 'checkbox_group' ) {
			return 'pewc-value-select';
		} else if( field_type == 'checkbox' ) {
			return 'pewc-value-checkbox';
		}
		return false;
	}
	// Populate a dynamically added select field
	function pewc_populate_select_value_field( condition_field ) {
		var option_field = condition_field.replace( 'pewc_group', 'pewc_option' );
		var data = $('body').find('#' + option_field + ' .pewc-data-options').attr( 'data-options' );
		if( data == undefined ) {
			data = '[]';
		}
		data = JSON.parse( data );
		return data;
	}
	function pewc_add_value_field( field, field_id, field_type, value_field, val ) {
		if( val == '__checked__' ) {
			val = '';
		}
		$(field).closest('.product-extra-conditional-row').find('.pewc-checked-placeholder').remove();
		var wrapper = $(field).parent().parent();
		$(wrapper).find('.pewc-condition-value-field .pewc-condition-value').remove();
		var group_id = $(field).closest('.group-row').attr('data-group-id');
		var item_id = $(field).closest('.field-item').attr('data-item-id');
		var condition_id = $(field).attr( 'data-condition-id' );

		var clone_value = $('.new-condition-value-field .' + value_field).clone().appendTo( $(wrapper).find('.pewc-condition-value-field') );
		$(clone_value)
			.attr('name','_product_extra_groups[' + group_id + '][items][' + item_id + '][condition_value][' + condition_id + ']')
			.attr('id','condition_value_' + group_id + '_' + item_id + '_' + condition_id )
			.attr('data-group-id', group_id)
			.attr('data-item-id', item_id)
			.attr('data-condition-id', condition_id)
			.val(val);

		if( field_type == 'select' || field_type == 'radio' || field_type == 'image_swatch' || field_type == 'products' || field_type == 'checkbox_group' ) {
			options = pewc_populate_select_value_field( $(field).val() );
			for(var i=0; i < options.length; i++ ) {
				$(clone_value).append($('<option>', {
					value: options[i],
					text: options[i]
				}));
			}
		}
		if( field_type == 'checkbox' ) {
			$(wrapper).find('.pewc-checked-placeholder').remove();
			var clone_span = $('.new-condition-value-field .pewc-checked-placeholder').clone().appendTo( $(wrapper).find('.pewc-condition-value-field') );
			$( clone_value ).addClass('pewc-condition-set-value');
			$( clone_value ).val( '__checked__' );
		}
		if( field_type == 'cost' ) {

		}
	}
	function pewc_get_id_code() {
		var result = [];
	    strLength = 7;
	    charSet = 'abcdef0123456789';
	    while (--strLength) {
				result.push(charSet.charAt(Math.floor(Math.random() * charSet.length)));
	    }
	    return result.join('');
	}
	function pewc_check_field_has_conditions( id, field_type ) {
		var field_type = $('#' + id).val();
		var field_id = id.replace( 'field_type_', 'pewc_group_');
		$('.pewc-condition-select').each(function(i,v){
			var select_id = $(this).attr('id');
			$('#' + select_id).find('option:selected').each(function(){
				var option_value = $(this).val();
				if( option_value == field_id ) {
					var r = confirm( pewc_obj.condition_continue );
					if( r == true ) {
						// Iterate through each instance of this field in conditions, check the value field if required
						var value_field_type = pewc_get_value_field_type( field_type );
						// Try to retain the condition value if field types permit it
						var condition_value = $('#' + select_id).closest('.product-extra-conditional-row').find('.pewc-condition-value').val();
						pewc_add_value_field( $('#' + select_id), field_id, field_type, value_field_type, condition_value );
					}
				}
			});
		});
	}
	// Duplicate groups
	$('body').on('click', '.pewc-group-meta-actions .duplicate', function(e) {
		e.preventDefault();
		var clone_group = $(this).closest('.group-row').clone().appendTo('#product_extra_groups');
		var old_group_id = $(clone_group).attr('data-group-id');
		var new_group_id = pewc_get_id_code();
		$(clone_group)
			.attr('data-group-id', new_group_id)
			.attr('id', 'group-'+new_group_id);

		var group_title = $(clone_group).find('.pewc-group-title').val();
		$(clone_group).find('.pewc-group-title').val( group_title + ' [' + pewc_obj.copy_label + ']');

		pewc_update_duplicated_ids( clone_group, old_group_id, new_group_id );

		// Conditions
		$(clone_group).find('.pewc-condition-field, .pewc-condition-rule, .pewc-condition-value').each(function(){
			if( $(this).attr('data-group-id') != undefined ) {
				var old_group_id = $(this).attr('data-group-id');
				var new_group_id = old_group_id.replace(old_group_id,new_group_id);
				$(this).attr('data-group-id',new_group_id);
			}
		});

	});
	// Duplicate fields
	$('body').on('click', '.pewc-field-actions .duplicate', function(e) {
		e.preventDefault();
		var list = $(this).closest('.field-list');
		var field = $(this).closest('.field-item');
		var clone_field = $(field).clone().insertAfter($(field));
		var old_field_id = $(clone_field).attr('data-item-id');
		var new_field_id = pewc_get_id_code();
		$(clone_field)
			.attr('data-item-id', new_field_id)
			.attr('data-size-count', new_field_id)
			.attr('id', 'group-'+new_field_id);

		var field_title = $(clone_field).find('.pewc-field-label').val();
		$(clone_field).find('.pewc-field-label').val( field_title + ' [' + pewc_obj.copy_label + ']');

		pewc_update_duplicated_ids( clone_field, old_field_id, new_field_id );

	});
	function pewc_update_duplicated_ids( item, old_id, new_id ) {
		// Update form names to new ID
		$( item ).find('[name]').each(function(){
			var old_name = $(this).attr('name');
			var new_name = old_name.replace( old_id, new_id );
			$(this).attr('name',new_name);
		});
		// Update field IDs to new ID
		$( item ).find('.pewc-fields-wrapper, .pewc-field-type').each(function(){
			if( $(this).attr('id') != undefined ) {
				var old_field_id = $(this).attr('id');
				var new_field_id = old_field_id.replace( old_id, new_id );
				$(this).attr('id',new_field_id);
			}
		});
		$( item ).find('.pewc-hidden-id-field').each(function(){
			if( $(this).val() ) {
				var old_field_val = $(this).val();
				var new_field_val = old_field_val.replace( old_id, new_id );
				$(this).val(new_field_val);
			}
		});
		$( item ).find('.pewc-condition-field, .pewc-condition-rule, .pewc-condition-value').each(function(){
			if( $(this).attr('id') != undefined ) {
				var old_field_id = $(this).attr('id');
				var new_field_id = old_field_id.replace( old_id, new_id );
				$(this).attr('id',new_field_id);
			}
		});
		// Update options
		$( item ).find('option').each(function(i,v){
			var old_field_val = $(this).val();
			var new_field_val = old_field_val.replace( old_id, new_id );
			$(this).val(new_field_val);
		});
	}
	// Imports
	$('body').on('click','.import_groups',function(e){
		e.preventDefault();
		var button = $(this);
		$('.pewc-loading').fadeIn();
		$(button).attr('disabled','true');
		var import_id = $('#pewc_import_groups').val();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'pewc_import_groups',
				import_id: import_id,
				pewc_import_nonce: $('#pewc_import_nonce').val()
			},
			success: function( response ) {
				$(button).removeAttr('disabled');
				$('.pewc-loading').fadeOut();
				if(response.content) {
					$(response.content).appendTo('#product_extra_groups');
				}
			}
		});
	});
	// Global Add-Ons
	$('body').on('click','#pewc_add_global_set', function(e) {
		e.preventDefault();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'pewc_add_global_set',
				security: $('#pewc_global_set').val()
			},
			success: function(response) {
				console.log(response);
			}
		});
	});
	$('.pewc-view-image').on('click',function(e){
		e.preventDefault();
		$(this).closest('.pewc-image-modal-wrapper').addClass('active');
	});
	$('.pewc-image-close, .pewc-image-inner').on('click',function(){
		$(this).closest('.pewc-image-modal-wrapper').removeClass('active');
	});
	$('.pewc-global-set-wrap .pewc-rule-select').select2();
	$('body').on('click','#pewc_add_global_set',function(e){
		e.preventDefault();
		var last_row = $('.pewc-global-set-wrap').find('.group-row').last();
		var count = $(last_row).attr('data-group-count');
		count = parseFloat( count ) + 1;
		if( isNaN( count ) ) {
			count = 0;
		}
		var clone_row = $('.new-group-row').clone().appendTo('.pewc-global-set-wrap');
		var new_group_id = pewc_get_id_code();
		$(clone_row).removeClass('new-group-row');
		$(clone_row).attr('data-group-count',count);
		$(clone_row).attr('data-group-id',new_group_id);
		$(clone_row).attr('id','group-' + new_group_id);
		$(clone_row).find('.pewc-group-title').attr('name','_product_extra_groups['+ new_group_id +'][meta][group_title]');
		// $(clone_row).find('.pewc-group-required').attr('name','_product_extra_groups['+ new_group_id +'][meta][group_required]');
		$(clone_row).find('.pewc-rule-field').each(function(){
			if($(this).attr('data-name')) {
				var data_name = $(this).attr('data-name');
				data_name = data_name.replace('GROUP_KEY',new_group_id);
				$(this).attr('name',data_name);
			}
		});
		$(clone_row).find('.pewc-rule-select').select2();
	});

	$('body').on('click','#pewc_save_globals', function(e) {
		e.preventDefault();
		var button = $(this);
		$(button).attr('disabled','true');
		$(button).parent().find('.spinner').css('visibility','visible');
		var form = $('#pewc_global_settings_form').serialize();
		$.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'pewc_save_globals',
				form: form,
				security: $('#pewc_global_set').val()
			},
			success: function(response) {
				$(button).removeAttr('disabled');
				$(button).parent().find('.spinner').css('visibility','hidden');
			}
		});
	});
	// Media uploader
	var meta_image_frame;
	// Runs when the image button is clicked.
	$('body').on('click','.pewc-upload-button',function(e){
		e.preventDefault();
		var item_id = $(this).data('item-id');
		var image = $('.pewc-field-image-'+item_id+' .pewc-field-image');
		// Removing or adding the image?
		if( $(this).hasClass('remove-image') ) {
			// Remove
			$(image).removeClass('has-image');
			$(this).removeClass('remove-image');
			$('.pewc-field-image-'+item_id+' .pewc-image-attachment-id').val('');
			var placeholder = $('.pewc-field-image-'+item_id+' .pewc-upload-button img').attr('data-placeholder');
			$('.pewc-field-image-'+item_id+' .pewc-upload-button img').attr( 'src', placeholder );
		} else {
			// Sets up the media library frame
			meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
				library: { type: 'image' }
			});
			$('.pewc-field-image-'+item_id+' .pewc-field-image').addClass('has-image');
			$(this).addClass('remove-image');
			// Runs when an image is selected.
			meta_image_frame.on('select', function(){
				// Grabs the attachment selection and creates a JSON representation of the model.
				var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
				// Sends the attachment URL to our custom image input field.
				$('.pewc-field-image-'+item_id+' .pewc-image-attachment-id').val(media_attachment.id);
				$('.pewc-field-image-'+item_id+' .pewc-upload-button img').attr( 'src', media_attachment.url );
			});
			// Opens the media library frame.
			meta_image_frame.open();
		}
	});
	// Update list of field names for conditions
	$('body').on('focusout','.pewc-field-label',update_field_names_object);
	$('body').on('change','.pewc-field-type',update_field_names_object);
	$('body').on('focusout','.product-extra-option-wrapper input',update_field_names_object);
	function update_field_names_object() {
		if( $('body').hasClass('post-type-product') || $('body').hasClass('pewc_product_extra_page_global') ) {
			var all_fields = {};
			$('body').find('.field-item').not('.new-field-item').find('.pewc-field-label').each(function(){
				var group_id = $(this).closest('.group-row').attr('data-group-id');
				var field_id = $(this).closest('li.field-item').attr('data-item-id');
				var label = '[no label]';
				if( $(this).val() != '' ) {
					label = $(this).val();
				}
				var type = $('#field_type_'+group_id+'_'+field_id).val();
				if( ! all_fields[group_id] ) {
					all_fields[group_id] = {};
				}
				all_fields[group_id][field_id] = {'label': label, 'type': type};
				if( type=='select' || type=='radio' || type=='image_swatch' || type=='checkbox_group' ) {
					// Update data-options
					var option_fields = $(this).closest('li.field-item').find('.pewc-option-fields');
					var options = [];
					$(option_fields).find('.pewc-field-option-value').each(function(i,v){
						options.push($(this).val());
					});
					$('#pewc_option_'+group_id+'_'+field_id).find('.pewc-data-options').attr('data-options',JSON.stringify(options));
					// Get all possible values for the select field
					all_fields[group_id][field_id] = {'label': label, 'type': type, 'options': options};
				} else if( type=='products' ) {
					// Update data-options
					var selected_products = $(this).closest('li.field-item').find('.pewc-field-child_products').val();
					$(this).closest('li.field-item').find('.pewc-data-options').attr('data-options',JSON.stringify(selected_products));
					// Get all possible values for the select field
					all_fields[group_id][field_id] = {'label': label, 'type': type, 'options': selected_products};
				}
			});
			$('.product-extra-group-data').attr('data-fields',JSON.stringify(all_fields));
			update_conditional_fields();
			update_conditional_value_fields();
		}
	}

	function update_conditional_fields() {
		if( $('.product-extra-group-data').attr('data-fields') ) {
			var all_fields = JSON.parse( $('.product-extra-group-data').attr('data-fields') );
		} else {
			return;
		}

		// If we're in a product, get fields from all groups
		// If we're on the global page, only get fields belonging to the specific group
		// Changed in 2.2.2 so that all fields are available in global
		var page = 'product';
		if( $('body').hasClass('pewc_product_extra_page_global') ) {
			page = 'global';
		}

		// Save options by group for global
		var options_by_group = [];
		// Get the first option
		// var option_value = $('.new-conditional-row .pewc-condition-field.pewc-condition-select').find('option:first-of-type').html();
		var options = '<option value="not-selected">'+pewc_obj.select_text+'</option>';
		// Remove all current options except the first one
		$('.new-conditional-row .pewc-condition-field.pewc-condition-select option').remove();
		$('.new-conditional-row .pewc-condition-field.pewc-condition-select optgroup').remove();
		// Read new set of options from object
		for( var group in all_fields ) {
			var group_name = $('#group-' + group + ' .pewc-group-title').val();
			if( ! group_name ) group_name = '[No group title]';
			options_by_group[group] = '<option value="not-selected">'+pewc_obj.select_text+'</option>';
			var items = all_fields[group];
			options += '<optgroup label="' + group_name + '">';
			for( var field in items ) {
				if( ! items.hasOwnProperty(field) ) continue;
				options_by_group[group] += '<option data-type="' + items[field]['type'] + '" value="pewc_group_'+group+'_'+field+'">'+items[field]['label']+'</option>';
				options += '<option data-type="' + items[field]['type'] + '" value="pewc_group_'+group+'_'+field+'">'+items[field]['label']+'</option>';
			}
			options += '</optgroup>';
		}
		options += '<optgroup label="Product Cost">';
		options += '<option data-type="cost" value="cost">Cost</option>';
		options += '</optgroup>';
		// Update the new condition select field
		$('.new-conditional-row .pewc-condition-field.pewc-condition-select').append( options );
		// Now update all the condition select fields in use
		$('body').find('.group-row .pewc-condition-field.pewc-condition-select').each(function(){
			// Update the field with the new options
			var group_id = $(this).closest('.group-row').attr('data-group-id');
			var field_id = $(this).closest('li.field-item').attr('data-item-id');
			// Retain the currently selected option
			var selected = $(this).find(':selected').val();
			$(this).children().remove('optgroup');
			$(this).find('option').remove();
			// if( page == 'product' ) {
			// 	$(this).append(options);
			// } else {
			// 	$(this).append(options_by_group[group_id]);
			// }
			$(this).append(options);

			// Ensure that a field can't be a condition of itself
			$(this).find('option[value="pewc_group_' + group_id + '_' + field_id + '"]').remove();
			// Set correct option to selected
			$(this).val(selected);

		});

	}

	function update_conditional_value_fields() {
		// Update all the options with any newly added option
		$('body').find('.field-item').not('.new-field-item').find('.pewc-field-options-wrapper').each(function(i,v){
			var option_id = $(this).closest('.pewc-option-fields').attr('id');
			option_id = option_id.replace('pewc_option','pewc_group');
			var options = $(this).attr('data-options');
			if( options != undefined ) {
				var options = JSON.parse( $(this).attr('data-options') );
				if( $(this).closest('.pewc-option-fields').attr('id') != undefined ) {
					$('.pewc-condition-select').each(function(i,v){
						if( $(this).val() == option_id ) {
							// Using .pewc-condition-set-value to ensure we don't overwrite values that have already been set
							var condition_value_field = $(this).closest('.product-extra-conditional-row').find('.pewc-condition-value').not('.pewc-condition-set-value');
							// if( options != undefined ) {
								// Remove existing options and replace with updated set
								var selected = $(condition_value_field).find(':selected').val();
								$(condition_value_field).find('option').remove();
								for(var i=0; i < options.length; i++ ) {
									$(condition_value_field).append($('<option>', {
										value: options[i],
										text: options[i]
									}));
								}
								$(condition_value_field).val(selected);
							// }
							// Replace Is/Not Is for fields that allow multiple selections
							$('.pewc-condition-rule').each(function(i,v) {
								var has_multiple = $(this).hasClass('pewc-has-multiple');
								pewc_set_rules( $(this), has_multiple, $(this).val() );
							});
						}
					});
				}
			}
		});
	}

	function pewc_set_rule_field( select, field_type ) {
		// Decide whether to show is/is not or contains/does not contain
		var row = $(select).closest('.product-extra-conditional-row');
		var rule = $(row).find('.pewc-condition-rule');
		var has_multiple = pewc_has_multiple( select );
		pewc_set_rules( rule, has_multiple, $( select ).val() );
	}

	function pewc_set_rules( field, has_multiple, is_cost ) {
		$(field).find('option[value="is"]').attr('disabled', has_multiple);
		$(field).find('option[value="is-not"]').attr('disabled', has_multiple);
		$(field).find('option[value="contains"]').attr('disabled', ! has_multiple);
		$(field).find('option[value="does-not-contain"]').attr('disabled', ! has_multiple);
		$(field).find('option[value="cost-equals"]').attr('disabled', true );
		$(field).find('option[value="cost-greater"]').attr('disabled', true );
		$(field).find('option[value="cost-less"]').attr('disabled', true );
		// Ensure an enabled option is selected
		var current_val = $( field ).val();
		if( current_val == null ) current_val = '';
		if( has_multiple && current_val.indexOf( 'contain' ) == -1 ) {
			$(field).val( 'contains' );
			$(field).addClass( 'pewc-has-multiple' );
		} else if( is_cost != 'cost' && current_val.indexOf( 'is' ) == -1 ) {
			$(field).val( 'is' );
			$(field).removeClass('pewc-has-multiple');
		} else if( is_cost == 'cost' ) {
			$(field).find('option[value="is"]').attr( 'disabled', true );
			$(field).find('option[value="is-not"]').attr( 'disabled', true );
			$(field).find('option[value="contains"]').attr( 'disabled', true );
			$(field).find('option[value="does-not-contain"]').attr( 'disabled', true );
			$(field).find('option[value="cost-equals"]').attr( 'disabled', false );
			$(field).find('option[value="cost-greater"]').attr('disabled', false );
			$(field).find('option[value="cost-less"]').attr('disabled', false );
			if( current_val.indexOf( 'cost' ) == -1 ) {
				$(field).val( 'cost-equals' );
			}
			$(field).removeClass( 'pewc-has-multiple' );
		}
	}

	// Check if our field type allows multiple selections
	function pewc_has_multiple( field ) {
		var parent_field_id = $(field).val(); // The id of the field that we are dependent on
		var parent_field_type = $('#' + parent_field_id).find('.pewc-field-type').val();
		if( parent_field_type == 'products' || parent_field_type == 'checkbox_group' ) {
			return true;
		} else if( parent_field_type == 'image_swatch' ) {
			if( $('#' + parent_field_id).find('.pewc-allow-multiple').attr('checked') ) {
				return true;
			}
		}
		return false;
	}

	function set_options_data( option_wrapper ) {
		if( $(option_wrapper).length > 0 ) {
			var options = [];
			$(option_wrapper).find('.pewc-field-option-value').each(function(i,v){
				options.push($(this).val());
			});
			$(option_wrapper).attr('data-options',JSON.stringify(options));
		}
	}

	$('body').on('click','.pewc-is-dismissible-pewc-notice',function(){
		$.ajax(
			ajaxurl,
			{
				type: 'POST',
				data: {
					action: 'pewc_dismiss_notification',
					option: 'name_notice'
				}
			}
		);
	});
	$('.pewc-variation-field').select2();

})(jQuery);
