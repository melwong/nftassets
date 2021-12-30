<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $post, $woocommerce, $product;
?>
<div class="wpneo-campaign-single-left-info">

    <div class="wpneo-post-img">

        <?php

            $wpneo_funding_video = get_post_meta( $post->ID, 'wpneo_funding_video', true );
            if (has_post_thumbnail()) {
                $image_caption = get_post(get_post_thumbnail_id())->post_excerpt;
                $image_link = wp_get_attachment_url(get_post_thumbnail_id());
                $image = get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                    'title' => get_the_title(get_post_thumbnail_id())
                ));

                if($image_link != "" ){
                    $image_style = 'style = "background-image: url('.esc_url($image_link).'); background-repeat:no-repeat; background-size:cover; width:100%; height:390px; margin-bottom:30px; "';
                }

                /**
                 * WooCommerce deprecated support since @var 3.0
                 */
                if (wpneo_wc_version_check()) {
                    $attachment_count = $product->get_gallery_image_ids();
                }else{
                    $attachment_count = count($product->get_gallery_attachment_ids());
                }


                if ($attachment_count > 0) {
                    $gallery = '[product-gallery]';
                } else {
                    $gallery = '';
                }

                if ($wpneo_funding_video) {
                    echo '<div class="video-container pull-left" '.$image_style.'>';
                    echo '<a href="'.$wpneo_funding_video.'" id="videoPlay" class="pup-up-video" data-rel="prettyPhoto"><i class="back-play-button"></i></a>';
                    echo '</div>';
                } else {
                    echo '<div class="video-container pull-left" '.$image_style.'>';
                    echo '</div>';
                }

            } else {
                echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), ''), $post->ID);
            }

        ?>
    </div>
    <?php do_action( 'woocommerce_product_thumbnails' ); ?>
    <?php do_action( 'wpneo_crowdfunding_after_feature_img' ); ?>
</div>
