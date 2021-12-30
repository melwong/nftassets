<?php 
    if ( ! defined( 'ABSPATH' ) ) {
        exit; # Exit if accessed directly
    }

    $predefined_price = get_post_meta( get_the_ID(), 'wpcf_predefined_pledge_amount', true );
    if (!empty($predefined_price)){
        $predefined_price = apply_filters('wpcf_predefined_pledge_amount_array_a', explode(',', $predefined_price));
    }

    # Backer List Item.
    $baker_list = WPNEOCF()->getCustomersByProduct();
    $total_item =count($baker_list);
?>

<!-- Review Section Start -->
<div class="lead patrios-review-cont">
    <span class="thm-Price-amount">
        <span class="woocommerce-Price-amount amount"><i class="back-dove"></i> <?php echo $total_item; ?></span>
        <?php echo ($total_item == '1') ? '<span class="thm-raise-sp">'._e('Backer', 'patrios').'</span>' : '<span class="thm-raise-sp">'._e('Backers', 'patrios').'</span>'; ?>
    </span> 

    <span class="thm-Price-amount text-center">
        <span class="thm-love-btn"  data-campaign="<?php echo get_the_ID(); ?>" data-user="<?php echo get_current_user_id(); ?>">
            <i class="back-heart"></i>
            <span class="woocommerce-Price-amount amount latest-price">
                <?php
                    $love_count = get_post_meta( get_the_ID(),'loved_campaign_ids', true );
                    echo ($love_count) ? $love_count : 0;
                ?>
            </span>
            <span class="thm-raise-sp"><?php _e('Love it', 'patrios') ?></span>
        </span>
    </span> 

    <!-- Review Count -->
    <span class="thm-Price-amount pull-right text-right">      
        <?php        
        global $product;
        if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
            return;
        }
        $review_count = $product->get_review_count();
        if ( $review_count >= 0 ) : ?>
            <?php if ( comments_open() ): ?>
            <span class="woocommerce-Price-amount amount">
                <i class="back-favorites"></i>
                <?php echo '<span class="count">' . esc_html( $review_count ) . '</span>'; ?>
            </span>
            <span class="thm-raise-sp"><?php _e('Reviews', 'patrios') ?></span>
            <?php endif ?>
        <?php endif; ?>
    </span>  
    <!-- Review Count End -->
</div>
<!-- Review End -->

<?php $wpneo_campaign_end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true); ?>

<div class="lead">
    <?php if (is_array($predefined_price) && count($predefined_price)){
        echo '<ul class="wpcf_predefined_pledge_amount">';
        foreach ($predefined_price as $price){
            $price = trim($price);
            $wooPrice = wc_price($price);
            echo " <li><a href='javascript:;' data-predefined-price='{$price}'> {$wooPrice}</a> </li> ";
        }
        echo "</ul>";
    } ?>

    <span class="thm-Price-amount">
        <span class="woocommerce-Price-amount amount"><?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_fund_raised_by_campaign()); ?></span>
    </span> 
    <span class="thm-raise-sp"><?php _e('Raised', 'patrios') ?></span>

    <div class="thm-meta-desc pull-right text-right">
        <span class="thm-Price-amount">
            <span class="woocommerce-Price-amount amount"><?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_goal_by_campaign(get_the_ID())); ?></span>
        </span>
        <span class="thm-raise-sp">
            <?php _e(' Goal', 'patrios') ?>
        </span>
    </div>
</div>

