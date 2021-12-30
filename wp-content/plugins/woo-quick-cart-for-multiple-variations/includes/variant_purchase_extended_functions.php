<?php
/**
 * Function to check variant is present in cart or not.
 *
 * @param $variation_id
 * @return bool
 */
function wqcmv_is_variant_in_cart($variation_id) {

    $in_cart = false;
    $cart = WC()->cart->get_cart_contents();
    foreach ($cart as $cart_item) {
        $product_in_cart = $cart_item['variation_id'];
        if (0 !== $product_in_cart && $product_in_cart === $variation_id) {
            $in_cart = $cart_item['quantity'];
        }
    }

    return $in_cart;

}

/**
 * Function to fetch html of variants when it requires.
 *
 * @param int $variation_id
 *
 * @return false|string
 */
function wqcmv_fetch_product_block_html($variation_id = 0, $changed_variations = array()) {

    if (0 === $variation_id) {
        return '';
    }
    ob_start();

    $variation_all_data = wc_get_product($variation_id);
    $variation_title = $variation_all_data->get_formatted_name();
    $variation = get_post($variation_id);
    $unavailable_variants_option = get_option('vpe_allow_unavailable_variants');
    $enable_price_visibility_option = get_option('vpe_enable_price_visibility_for_nonloggedin_customer');
    $enable_stock_visibility_option = get_option('vpe_enable_stock_visibility');
    $show_out_of_stock_variants = ('yes' === $unavailable_variants_option) ? 'yes' : 'no';
    $woo_currency = get_woocommerce_currency_symbol(get_woocommerce_currency());
    $sku = get_post_meta($variation_id, '_sku', true);

    // Variant image
    $variation_thumbnail_id = get_post_thumbnail_id($variation_id);
    $variation_thumbnail = wc_placeholder_img_src();
    if ($variation_thumbnail_id != '') {
        $variation_thumbnail_url = wp_get_attachment_image_src($variation_thumbnail_id, 'thumbnail');
        $variation_full_url = wp_get_attachment_image_src($variation_thumbnail_id, 'medium');
        /*$variation_thumbnail = wp_get_attachment_url($variation_thumbnail_id);*/
        if (empty($variation_thumbnail_url)) {
            $variation_thumbnail = wc_placeholder_img_src();
        } else {
            $variation_thumbnail = $variation_thumbnail_url[0];
            $variation_full = $variation_full_url[0];
        }

    } else {
        if (0 !== $variation->post_parent) {
            $variation_thumbnail_id = get_post_thumbnail_id($variation->post_parent);
            if ($variation_thumbnail_id !== '') {
                $variation_thumbnail_url = wp_get_attachment_image_src($variation_thumbnail_id, 'thumbnail');
                $variation_full_url = wp_get_attachment_image_src($variation_thumbnail_id, 'medium');
                if (empty($variation_thumbnail_url)) {
                    $variation_thumbnail = wc_placeholder_img_src();
                } else {
                    $variation_thumbnail = $variation_thumbnail_url[0];
                    $variation_full = $variation_full_url[0];
                }
            }
        }
    }


    // Fetch variant price
    $reg_price = get_post_meta($variation_id, '_regular_price', true);
    $reg_price = empty($reg_price) ? 0.00 : $reg_price;
    $sale_price = get_post_meta($variation_id, '_sale_price', true);
    $v_price = (float)!empty($sale_price) ? $sale_price : $reg_price;
    $formatted_v_price = $woo_currency . number_format($v_price, 2);
    $manage_stock = get_post_meta($variation_id, '_manage_stock', true);
    // Checking stock status
    $prod_stock_status = get_post_meta($variation_id, '_stock_status', true);

    if (!empty($prod_stock_status) && ('instock' == $prod_stock_status || 'onbackorder' == $prod_stock_status)) {
        $avalilable_quantity = wqcmv_is_variant_in_cart($variation_id);
        $prod_stock = get_post_meta($variation_id, '_stock', true);
        if (false !== $avalilable_quantity) {
            $prod_stock = (int)($prod_stock - $avalilable_quantity);
        }


        if (null !== $prod_stock) {
            $prod_stock_class = 'product-not-in-stock';
            $prod_backorders = get_post_meta($variation_id, '_backorders', true);
            if (0 === $prod_stock) {
                $prod_stock_class = 'product-not-in-stock';
                if (!empty($prod_backorders) && 'no' != $prod_backorders) {
                    $prod_stock_class .= ' backorders-allowed';
                } else {
                    $prod_stock_class .= ' backorders-not-allowed';
                }
            } elseif (0 < $prod_stock) {
                $prod_stock_class = 'product-in-stock';
                if (!empty($prod_backorders) && 'no' != $prod_backorders) {
                    $prod_stock_class .= ' backorders-allowed';
                } else {
                    $prod_stock_class .= ' backorders-not-allowed';
                }
            } else {
                if ('yes' === $manage_stock) {
                    if (0 >= $prod_stock) {
                        $prod_stock_class = 'product-not-in-stock';
                        if (!empty($prod_backorders) && 'no' != $prod_backorders) {
                            $prod_stock_class .= ' backorders-allowed';
                        } else {
                            $prod_stock_class .= ' backorders-not-allowed';
                        }
                    }
                } else {
                    $prod_stock_class = 'product-in-stock';
                    if (!empty($prod_backorders) && 'onbackorder' != $prod_backorders) {
                        $prod_stock_class .= ' backorders-allowed';
                    } else {
                        $prod_stock_class .= ' backorders-not-allowed';
                    }
                }
            }
        } else {
            $prod_stock_class = 'product-in-stock';
        }
    } else {
        $prod_stock_class = 'product-not-in-stock';
    }
    $stock_available_str = sprintf(esc_html__('%1$d Available', 'variant-purchase-extended'), $prod_stock);
    if ('yes' === $manage_stock) {
        $stock_status = sprintf(esc_html__('In Stock %1$s', 'variant-purchase-extended'), '<span class="vpe_small_stock">(' . $stock_available_str . ')</span>');
    } else {
        $stock_status = __('In Stock', 'variant-purchase-extended');
    }
    if ('product-not-in-stock backorders-allowed' === $prod_stock_class) {
        $stock_status = esc_html__('Not In Stock (BO Allowed)', 'variant-purchase-extended');
    } elseif ('product-not-in-stock' === $prod_stock_class || 'product-not-in-stock backorders-not-allowed' === $prod_stock_class) {
        $stock_status = __('Not In Stock', 'variant-purchase-extended');
    } elseif ('product-in-stock backorders-allowed' === $prod_stock_class) {
        $stock_status = esc_html__('In Stock (BO Allowed)', 'variant-purchase-extended');
    }
    $vid_key = array_search($variation_id, array_column($changed_variations, 'vid'));
    $vid_qty = (false !== $vid_key && null !== $vid_key) ? $changed_variations[$vid_key]['qty'] : 0;
    if ('yes' === $show_out_of_stock_variants) { ?>
        <tr class="vpe_row variant-<?php echo $variation_id; ?>"
            data-tobeshown="<?php echo $show_out_of_stock_variants; ?>">
            <td data-title="<?php esc_html_e('Variant', 'variant-purchase-extended'); ?>"
                id="vpe-img-td-<?php echo $variation_id; ?>" class="vpe-img-td"><a href="javascript:;"
                                                                                   id="variation-image-id"
                                                                                   data-image-source="<?php echo $variation_full; ?>"><img
                            src="<?php echo $variation_thumbnail; ?>" class="variation-image"></a>
                <span><?php echo $variation_title; ?></span></td>
            <td data-title="<?php esc_html_e('SKU', 'variant-purchase-extended'); ?>"><?php echo $sku; ?></td>
            <?php if ('yes' === $enable_stock_visibility_option) { ?>
                <td id="vpe-stock-td-<?php echo $variation_id; ?>" class="vpe-stock-td"
                    data-title="<?php esc_html_e('In Stock', 'variant-purchase-extended'); ?>"
                    data-stockclass="<?php echo $prod_stock_class; ?>"><?php echo $stock_status; ?>
                </td>
            <?php } ?>
            <td data-title="<?php esc_html_e('Price', 'variant-purchase-extended'); ?>"><?php
                echo $formatted_v_price;
                ?></td>
            <td id="vpe-qty-td-<?php echo $variation_id; ?>" class="vpe-qty-td"
                data-title="<?php esc_html_e('Quantity', 'variant-purchase-extended'); ?>">

                <?php if ($prod_stock_class === 'product-not-in-stock' || $prod_stock_class === 'product-not-in-stock backorders-not-allowed') { ?>
                    <div class="out_stock_qty variant-qty-input"
                         data-image-source="<?php echo $variation_thumbnail; ?>"><?php esc_html_e('N/A', 'variant-purchase-extended'); ?></div>
                <?php } else { ?>
                    <input type="number" name="qty" id="qty" min="1" value="<?php echo $vid_qty; ?>"
                           class="variant-qty-input" data-variation_id="<?php echo $variation_id; ?>"
                           data-stock-quantity="<?php echo $prod_stock; ?>"
                           data-stock-quantity="<?php echo $prod_stock; ?>"
                           data-variable_name="<?php echo $variation->post_title; ?>"
                           data-stockclass="<?php echo $prod_stock_class; ?>"
                           data-manage-stock="<?php echo $manage_stock; ?>"
                           data-backorders="<?php echo $prod_backorders; ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 ||
    event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <=
    57" onpaste="return false" placeholder="e.g.: 1,2...">
                <?php } ?>
            </td>
        </tr>
    <?php } else {
        if ('instock' === $prod_stock_status || 'onbackorder' === $prod_stock_status) { ?>
            <tr class="vpe_row" data-tobeshown="<?php echo $show_out_of_stock_variants; ?>">
                <td data-title="<?php esc_html_e('Title', 'variant-purchase-extended'); ?>"
                    id="vpe-img-td-<?php echo $variation_id; ?>" class="vpe-img-td"><a href="javascript:;"
                                                                                       id="variation-image-id"
                                                                                       data-image-source="<?php echo $variation_full; ?>"><img
                                src="<?php echo $variation_thumbnail; ?>" class="variation-image"></a>
                    <span><?php echo $variation_title; ?></span></td>
                <td data-title="<?php esc_html_e('SKU', 'variant-purchase-extended'); ?>"><?php echo $sku; ?></td>
                <?php if ('yes' === $enable_stock_visibility_option) { ?>
                    <td id="vpe-stock-td-<?php echo $variation_id; ?>" class="vpe-stock-td"
                        data-title="<?php esc_html_e('In Stock', 'variant-purchase-extended'); ?>"
                        data-stockclass="<?php echo $prod_stock_class; ?>"><?php echo $stock_status; ?></td>
                <?php } ?>
                <td data-title="<?php esc_html_e('Price', 'variant-purchase-extended'); ?>"><?php
                    echo $formatted_v_price;
                    ?></td>
                <td id="vpe-qty-td-<?php echo $variation_id; ?>" class="vpe-qty-td"
                    data-title="<?php esc_html_e('Quantity', 'variant-purchase-extended'); ?>">
                    <?php if ('product-not-in-stock' === $prod_stock_class || $prod_stock_class === 'product-not-in-stock backorders-not-allowed') { ?>
                        <div class="out_stock_qty variant-qty-input"
                             data-image-source="<?php echo $variation_thumbnail; ?>"><?php esc_html_e('N/A', 'variant-purchase-extended'); ?></div>
                    <?php } else { ?>
                        <input type="number" name="qty" id="qty" min="1" value="<?php echo $vid_qty; ?>"
                               class="variant-qty-input" data-variation_id="<?php echo $variation_id; ?>"
                               data-stock-quantity="<?php echo $prod_stock; ?>"
                               data-stock-quantity="<?php echo $prod_stock; ?>"
                               data-variable_name="<?php echo $variation->post_title; ?>"
                               data-stockclass="<?php echo $prod_stock_class; ?>"
                               data-manage-stock="<?php echo $manage_stock; ?>"
                               data-backorders="<?php echo $prod_backorders; ?>" onkeypress="return (event.charCode == 8 || event.charCode == 0 ||
    event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <=
    57" onpaste="return false" placeholder="e.g.: 1,2...">
                    <?php } ?>
                </td>
            </tr>
        <?php }
    }
    $html = ob_get_clean();

    return $html;

}

/**
 *
 *
 * @param $product_id
 * @return int[]|WP_Post[]
 */
function wqcmv_conditional_logic_variation($product_id, $variations_per_page = -1) {

    $variation_args = array(
        'post_type' => array('product_variation'),
        'posts_per_page' => $variations_per_page,
        'post_status' => 'publish',
//        'orderby' => 'name',
//        'order' => 'ASC',
        'post_parent' => $product_id,
        'fields' => 'ids'
    );

    $unavailable_variants_option = get_option('vpe_allow_unavailable_variants');
    if ('no' === $unavailable_variants_option) {
        $variation_args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'value' => 'instock'
            ),
            array(
                'value' => 'onbackorder'
            )
        );
    }

    $variation_pids = get_posts($variation_args);
    return $variation_pids;
}