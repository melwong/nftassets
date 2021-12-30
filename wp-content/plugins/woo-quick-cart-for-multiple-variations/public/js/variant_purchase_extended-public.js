jQuery(document).ready(function ($) {
    'use strict';

    var _variations = [], _recent_updated_variations = [];

    lock_add_to_cart_btn(); // Lock the button on every page load.

    $(document).bind('mouseup', '.variant-qty-input', function () {
        vpe_create_required_arrays();
        hide_error();

        /**
         * This code is for the case when backspace key is pressed after entering a valid qty.
         * The button gets enabled which should not if all the values are either 0 or blank.
         */
        check_for_all_quantities();
    });

    $(document).on('keyup input', '.variant-qty-input', function () {
        var _is_pressed_key_verified = verify_the_current_key_pressed();
        if (true === _is_pressed_key_verified) {
            vpe_create_required_arrays();
            hide_error();
        }

        /**
         * This code is for the case when backspace key is pressed after entering a valid qty.
         * The button gets enabled which should not if all the values are either 0 or blank.
         */
        check_for_all_quantities();
    });

    function check_for_all_quantities() {
        var _valid_qty = 0;
        $('.variant-qty-input').each(function () {
            var _input_box = $(this);
            var _current_qty = parseInt(_input_box.val());
            if (0 < _current_qty) {
                _valid_qty++;
            }
        });

        if (0 < _valid_qty) {
            unlock_add_to_cart_btn();
        } else {
            lock_add_to_cart_btn();
        }

    }

    function pass_key_code() {
        var key_pass = event.which || event.keyCode;
        return key_pass;
    }

    function verify_the_current_key_pressed() {
        var keycode_array = [];
        for (var i = 48, j = 96; i < 58, j < 106; i++, j++) {
            keycode_array.push(i, j);
        }
        var enter_key_value = pass_key_code();
        var _available_keycode = $.inArray(enter_key_value, keycode_array);

        if (-1 !== _available_keycode) {
            return true;
        }

        return false;

    }

    function vpe_create_required_arrays() {
        _variations = [];

        /**
         * Traverse the quantity input boxes.
         */
        $('.variant-qty-input').each(function () {
            var _input_box = $(this);
            var _variation_id = _input_box.data('variation_id');
            var _allowed_stock = _input_box.data('stock-quantity');
            var _backorders_allowed = _input_box.data('backorders');
            var _manage_stock = _input_box.data('manage-stock');
            var _current_qty = parseInt(_input_box.val());
            var _variant_name = _input_box.data('variable_name');

            if (0 < _current_qty) {
                if ('no' === _manage_stock) {
                    push_entry_into_variants_array(_variations, _variation_id, _current_qty, _allowed_stock, _backorders_allowed, _manage_stock, _recent_updated_variations);
                } else {
                    if ('no' === _backorders_allowed) {
                        push_entry_into_variants_array(_variations, _variation_id, _current_qty, _allowed_stock, _backorders_allowed, _manage_stock, _recent_updated_variations);

                    } else {
                        push_entry_into_variants_array(_variations, _variation_id, _current_qty, _allowed_stock, _backorders_allowed, _manage_stock, _recent_updated_variations);
                    }

                }
            }

            /**
             * Lock the button based on whether there is any valid variant to be added to cart.
             */
            if (0 < _recent_updated_variations.length) {
                unlock_add_to_cart_btn();
            } else {
                lock_add_to_cart_btn();
            }
        });
    }

    function lock_add_to_cart_btn() {
        $('.vpe_single_add_to_cart_button').attr('disabled', 'disabled');
        return false;
    }

    function unlock_add_to_cart_btn() {
        $('.vpe_single_add_to_cart_button').removeAttr('disabled');
    }

    function lock_variants_container() {
        $('.vpe-ajax-loader-message').text('Adding variants to cart...');
        $('.vpe-ajax-loader').css('display', 'block');
    }

    function unlock_variants_container() {
        $('.vpe-ajax-loader').css('display', 'none');
    }

    function push_entry_into_variants_array(_variations, _variation_id, _current_qty, _allowed_stock, _backorders_allowed, _manage_stock, _recent_updated_variations) {
        var temp_arr = {};
        temp_arr['vid'] = _variation_id;
        temp_arr['qty'] = _current_qty;
        temp_arr['variant_name'] = _current_qty;
        temp_arr['stock_quantity'] = _allowed_stock;
        if ('yes' === _manage_stock) {
            if ('no' === _backorders_allowed) {
                if (_allowed_stock < _current_qty) {
                    temp_arr['is_valid'] = "No";
                } else {
                    temp_arr['is_valid'] = "Yes";
                }
            }

        } else {
            temp_arr['is_valid'] = "Yes";
        }
        var result = _variations.filter(function (v, i) {
            if (v['vid'] === _variation_id) {
                _variations.splice(i, 1)
            }
        });
        _variations.push(temp_arr);


        var result = _recent_updated_variations.filter(function (v, i) {
            if (v['vid'] === _variation_id) {
                _recent_updated_variations.splice(i, 1)
            }
        });
        _recent_updated_variations.push(temp_arr);

    }

    function hide_error() {
        $('.error-message-blk').hide();
    }


    function show_view_cart_button() {
        $('.vpe-view-cart').css('display', 'inline-block');
    }

    function show_success_message() {
        $('.vpe_container_btn').after('<p class="success-message" style="color:green">Variations added successfully</p>');
    }

    function hide_success_message() {
        $('.success-message').fadeOut(3000);
    }

    function bring_data_as_fresh() {
        $('.variant-qty-input').val(0);
        lock_add_to_cart_btn();
    }

    function merge_variations() {

        if (0 < _recent_updated_variations.length && 0 < _variations.length) {
            for (var i in _recent_updated_variations) {
                var variation = _recent_updated_variations[i];
                var variation_id = variation['vid'];

                /**
                 * Check if the traversing variation exists in the _variations array.
                 * If not, extract it from the _recent_updated_variations and insert into _variations
                 */

                var result = _variations.filter(function (v, i) {
                    if (v['vid'] === variation_id) {
                        _variations.splice(i, 1)
                    }
                });

                _variations.push(variation);
            }
        }
        return _variations;
    }

    function process_add_to_cart(_action, _parent_product_id, _merged_variations) {

        var _data = {
            action: _action,
            variations: _merged_variations,
            parent_product_id: _parent_product_id,
        };
        $.ajax({
            type: 'POST',
            url: WQCMV_Public_JS_Obj.ajaxurl,
            data: _data,
            success: function (response) {
                if ('vpe-product-added-to-cart-prac' === response.data.message) {
                    $('.vpe_container_btn').css('display', 'inline-block');
                    /**
                     * Updating the minicart.
                     * setInterval is just to make the user understand that some process is happening behind.
                     */
                    $('.vpe-ajax-loader-message').text('Updating the mini cart...');
                    setTimeout(function () {
                        $(".cart-icon-wrap .cart-wrap span").html(response.data.cart_count);
                        $(".cart-contents span.count").html(response.data.cart_count);
                        $(".cart-item-number").html(response.data.cart_count);
                        $(".cart-contents span.woocommerce-Price-amount").replaceWith(response.data.cart_total);
                        $.post(
                            WQCMV_Public_JS_Obj.ajaxurl,
                            {'action': 'wqcmv_update_mini_cart',},
                            function (response) {
                                $('.widget_shopping_cart_content').html(response);
                            },
                        );

                        $('.vpe-ajax-loader-message').text('Updating the buffer stock as items have been added to cart now...');
                        setTimeout(function () {
                            /**
                             * Updating the buffer stock.
                             */
                            $('table.vpe_table tr td.vpe-qty-td input').each(function () {
                                var _qty_input = $(this);
                                var stock = _qty_input.data('stock-quantity');
                                var variation_id = _qty_input.data('variation_id');
                                if ('' !== stock) {
                                    var _arr_found = _variations.filter(function (vid) {
                                        return vid.vid == variation_id
                                    });
                                    if (0 !== _arr_found.length) {
                                        var vid_found_qty = _arr_found[0]['qty'];
                                        var new_stock_qty = stock - vid_found_qty;
                                        _qty_input.data('stock-quantity', new_stock_qty);


                                    }
                                }
                            });

                            /**
                             * Update the stock column data.
                             */
                            $('table.vpe_table tr td.vpe-stock-td').each(function () {
                                var _current_td = $(this);
                                var _element_class = _current_td.attr('class');
                                var _element_id = _current_td.attr('id');
                                var _variation_id = _element_id.replace(_element_class + '-', '');
                                var _remaining_stock = parseInt($('#vpe-qty-td-' + _variation_id + ' input').data('stock-quantity'));
                                var _manage_stock = $('#vpe-qty-td-' + _variation_id + ' input').data('manage-stock');
                                var _backorders_allowed = $('#vpe-qty-td-' + _variation_id + ' input').data('backorders');
                                var _arr_found = _variations.filter(function (vid) {
                                    return vid.vid == _variation_id
                                });


                                if (0 !== _arr_found.length) {
                                    var _if_bo_allowed = $('#vpe-qty-td-' + _variation_id + ' input').data('backorders');
                                    var _stock_html = '';
                                    if ('yes' === _manage_stock) {
                                        if ('no' === _backorders_allowed) {
                                            if (0 === _remaining_stock) {
                                                /**
                                                 * We need to check for the backorders.
                                                 */
                                                if ('yes' === _if_bo_allowed) {
                                                    /**
                                                     * If you're here, means the product can still be ordered.
                                                     */
                                                    _stock_html = 'Not In Stock (BO Allowed)';
                                                } else {
                                                    /**
                                                     * If you're here, means the product has gone out of stock.
                                                     */
                                                    var _qty_input_html = '<div class="out_stock_qty">N/A</div>';
                                                    _stock_html = 'Not In Stock';
                                                    $('#vpe-qty-td-' + _variation_id).html(_qty_input_html);
                                                }
                                                _current_td.html(_stock_html);
                                            } else if (0 <= _remaining_stock) {
                                                /**
                                                 * Simple update the span text in the td.
                                                 */
                                                _stock_html = 'In Stock <span class="vpe_small_stock">( ' + _remaining_stock + ' Available )</span>';
                                                if ('yes' === _if_bo_allowed) {
                                                    _stock_html += ' (BO Allowed)';
                                                }
                                                _current_td.html(_stock_html);
                                            }
                                        } else {
                                            if (0 >= _remaining_stock) {
                                                _stock_html = 'Not In Stock (BO Allowed)';
                                            } else {
                                                _stock_html = 'In Stock (BO Allowed)';
                                            }
                                            _current_td.html(_stock_html);
                                        }
                                    }
                                }


                            });
                            if ('yes' === response.data.redirect_to_cart) {
                                window.location.replace(response.data.cart_url);
                            } else {
                                unlock_variants_container();
                            }
                            show_view_cart_button();
                            show_success_message();
                            bring_data_as_fresh();
                            _recent_updated_variations = [];
                            hide_success_message();
                        }, 3000);
                    }, 3000);

                }
            }
        });
    }

    /**
     * Add to cart.
     *
     */
    $(document).on('click', '.vpe_single_add_to_cart_button', function (evt) {
        evt.preventDefault();
        var _merged_variations = merge_variations();
        var _err_html = '';
        $.each(_merged_variations, function () {
            if ('No' === this.is_valid) {
                _err_html += '<li class="warning-message" style="color:red;">You cannot add that amount of ' + this.variant_name + ' to the cart because there is not enough stock (' + this.stock_quantity + ' remaining)</li>';
            }
        });

        if ('' !== _err_html) {
            _err_html = '<ul>' + _err_html + '</ul>';
            $('.error-message-blk').html(_err_html).show();
        } else {
            var _parent_product_id = $('#vpe-parent-product-id').val();
            lock_variants_container();
            process_add_to_cart('wqcmv_woocommerce_ajax_add_to_cart', _parent_product_id, _merged_variations);
        }
    });
    $(document).on('click', '.products-pagination', function () {
        var this_btn = $(this);
        var first_page_chunk_val = $('#vpe-active-chunk').val();

        var active_chunk = parseInt($('#vpe-active-chunk').val());
        var next_chunk = parseInt($('#vpe-next-chunk').val());

        if (this_btn.hasClass('next')) var loadchunk = active_chunk + 1;
        if (this_btn.hasClass('prev')) var loadchunk = active_chunk - 1;

        if (loadchunk < 0) {

            $('.pagination-for-products .prev').prop("disabled", true);
            return false;
        }

        if (loadchunk >= 0) {

            $('.pagination-for-products .prev').prop("disabled", false);
        }
        var parent_product_id = $('#vpe-parent-product-id').val();
        var data = {
            'action': 'wqcmv_products_pagination',
            'loadchunk': loadchunk,
            'parent_product_id': parent_product_id,
            'changed_variations': _recent_updated_variations
        };
        $('.vpe-ajax-loader').show();
        $('.vpe-ajax-loader-message').text('Loading Variants....');
        $.ajax({
            dataType: 'JSON',
            url: WQCMV_Public_JS_Obj.ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {

                if ('vpe-product-pagination' === response.data.message) {

                    unlock_variants_container();

                    if ('no' === response.data.next_chunk_available) {
                        $('.pagination-for-products .next').prop("disabled", true);
                    } else if ('yes' === response.data.next_chunk_available) {
                        $('.pagination-for-products .next').prop("disabled", false);
                        $('.pagination-for-products .prev').prop("disabled", false);
                    }

                    if ('no' === response.data.prev_chunk_available) {
                        $('.pagination-for-products .prev').prop("disabled", true);
                    }
                    // Overwrite the html only when any html is returned.
                    if ('' !== response.data.html) {
                        $('.pagination_row').html(response.data.html);
                        if (this_btn.hasClass('next')) {
                            $('#vpe-next-chunk').val(next_chunk + 1);
                            $('#vpe-active-chunk').val(active_chunk + 1);
                        }
                        if (this_btn.hasClass('prev')) {
                            $('#vpe-next-chunk').val(next_chunk - 1);
                            $('#vpe-active-chunk').val(active_chunk - 1);
                        }
                    }

                }
            },
        });
    });


    /**
     * variation Image Change On click
     */
    $(document).on('click', '.vpe-img-td a', function () {
        var _img_src = $(this).data('image-source');
        $.fancybox.open(_img_src);
    });

});

/*
* Table view scroll in mobile.
* */
const tableMobile = function () {
    var outer_width = jQuery(".vpe_table_responsive").innerWidth();
    if (parseInt(outer_width) < 440) {
        jQuery('.vpe_table_responsive table').addClass('table_mobile');
    } else {
        jQuery('.vpe_table_responsive table').removeClass('table_mobile');
    }
};
tableMobile();

jQuery(window).resize(function () {
    tableMobile();
});