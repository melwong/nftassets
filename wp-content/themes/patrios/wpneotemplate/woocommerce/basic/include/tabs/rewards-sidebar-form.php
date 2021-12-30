<!-- Quantity Box WooCommerce -->
<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; # Exit if accessed directly
    }
    global $post, $woocommerce, $product;
    $currency = '$';
    if ($product->get_type() == 'crowdfunding') {
        if (WPNEOCF()->campaignValid()) {
            $recomanded_price = get_post_meta($post->ID, 'wpneo_funding_recommended_price', true);
            $min_price = get_post_meta($post->ID, 'wpneo_funding_minimum_price', true);
            $max_price = get_post_meta($post->ID, 'wpneo_funding_maximum_price', true);
            if(function_exists( 'get_woocommerce_currency_symbol' )){
                $currency = get_woocommerce_currency_symbol();
            }

            if (! empty($_GET['reward_min_amount'])){
                $recomanded_price = (int) esc_html($_GET['reward_min_amount']);
            } ?>

            <?php 
            $_nf_duration_end = get_post_meta(get_the_ID(), '_nf_duration_end', true);
            $end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);

            $today = strtotime((new DateTime())->format('d-m-Y'));
            $expiry = strtotime((new DateTime($_nf_duration_end))->format('d-m-Y'));
            $fundraised = WPNEOCF()->getFundRaisedPercent(); 

            # Campaign Valid.   

            if ( ($end_method == 'target_goal' && $fundraised >= '100') || ($end_method == 'target_date' && $today > $expiry) || ($end_method == 'target_goal_and_date' && ($fundraised >= '100' || $today > $expiry) ) ) {  ?>
                <div class="overlay until-date ">
                    <div>
                        <div>
                            <span class="info-text"><?php _e( 'Campaign Over','wp-crowdfunding' ); ?></span>
                        </div>
                    </div>
                </div>
            <?php } else { ?>

                <div id="patrios_project" class="quantity_box">
                    <span><?php _e('Make a pledge without a reward', 'patrios'); ?> </span>
                    <span class="wpneo-tooltip">
                        <span class="wpneo-tooltip-min"><?php _e('Minimum amount is ','patrios'); echo esc_attr($currency.$min_price); ?></span>
                        <span class="wpneo-tooltip-max"><?php _e('Maximum amount is ','patrios'); echo esc_attr($currency.$max_price); ?></span>
                    </span>
                    <form enctype="multipart/form-data" method="post" class="cart">
                        <?php do_action('before_wpneo_donate_field'); ?>
                        <input type="number" min="0" placeholder="" name="wpneo_donate_amount_field" class="input-text amount wpneo_donate_amount_field text" value="<?php echo esc_attr($recomanded_price); ?>" data-min-price="<?php echo esc_attr($min_price) ?>" data-max-price="<?php echo esc_attr($max_price) ?>" >
                        <?php do_action('after_wpneo_donate_field'); ?>
                        <input type="hidden" value="<?php echo esc_attr($post->ID); ?>" name="add-to-cart">
                        <button type="submit" class="q<?php echo apply_filters('add_to_donate_button_class', 'wpneo_donate_button'); ?>"><i class="fa fa-long-arrow-right"></i></button>
                    </form>
                </div>

            <?php } ?>

            
        <?php } 
    }
?>
<!-- Quantity Box End -->
<br>
<span id="patrios_project_demo" class="patrios-rewards">
    <span><?php _e('Rewards', 'patrios'); ?></span>
</span>

<?php
    global $post;
    $campaign_rewards   = get_post_meta($post->ID, 'wpneo_reward', true);
    $campaign_rewards   = stripslashes($campaign_rewards);
    $campaign_rewards_a = json_decode($campaign_rewards, true);
    if (is_array($campaign_rewards_a)) {
        if (count($campaign_rewards_a) > 0) {

            $i      = 0;
            $amount = array();

            foreach ($campaign_rewards_a as $key => $row) {
                $amount[$key] = $row['wpneo_rewards_pladge_amount'];
            }
            array_multisort($amount, SORT_ASC, $campaign_rewards_a);
            
            foreach ($campaign_rewards_a as $key => $value) {
                $key++;
                $i++;
                $quantity = '';
                
                $post_id    = get_the_ID();
                $min_data   = $value['wpneo_rewards_pladge_amount'];
                $max_data   = '';
                $orders     = 0;
                ( ! empty($campaign_rewards_a[$i]['wpneo_rewards_pladge_amount']))? ( $max_data = $campaign_rewards_a[$i]['wpneo_rewards_pladge_amount'] - 1 ) : ($max_data = 9000000000);
                if( $min_data != '' ){
                    $orders = wpneo_campaign_order_number_data( $min_data,$max_data,$post_id );
                }
                if( $value['wpneo_rewards_item_limit'] ){
                    $quantity = 0;
                    if( $value['wpneo_rewards_item_limit'] >= $orders ){
                        $quantity = $value['wpneo_rewards_item_limit'] - $orders;
                    }
                }
            ?>

            <div class="tab-rewards-wrapper<?php echo ( $quantity === 0 ) ? ' disable' : ''; ?>">
                <?php if( $value['wpneo_rewards_image_field'] ){ ?>
                    <div class="wpneo-rewards-image">
                        <?php echo '<img src="'.wp_get_attachment_url( $value["wpneo_rewards_image_field"] ).'"/>'; ?>
                    </div>
                <?php } ?>
                <div class="patrios-reward-cont">
                    <h3>
                        <?php echo get_woocommerce_currency_symbol(). $value['wpneo_rewards_pladge_amount'];
                            if( 'true' != get_option('wpneo_reward_fixed_price','') ){
                                echo ( ! empty($campaign_rewards_a[$i]['wpneo_rewards_pladge_amount']))? ' - '. get_woocommerce_currency_symbol(). ($campaign_rewards_a[$i]['wpneo_rewards_pladge_amount'] - 1) : ' - more';    
                            }
                        ?>
                    </h3>
                    <p><?php echo esc_html($value['wpneo_rewards_description']); ?></p>
                    <?php if( $min_data != '' ){
                        echo '<p>'.$orders.' '.__('Backers','patrios').'</p>'; } ?>


                        <?php 

                        $est_delivery = ucfirst(date_i18n("M", strtotime($value['wpneo_rewards_endmonth']))).', '.$value['wpneo_rewards_endyear'];
                        if ( ! empty($value['wpneo_rewards_endmonth']) || ! empty($value['wpneo_rewards_endyear'])){ ?>
                            <div class="patrios-estimate-date">
                                <?php
                                    echo '<span>'.__('Estimated Delivery: ', 'patrios').'</span>';
                                    echo "<span>{$est_delivery}</span>";
                                ?>
                            </div>
                    <?php } ?>



                <?php 
                 # Metabox Call
                    $_nf_duration_end = get_post_meta(get_the_ID(), '_nf_duration_end', true);
                    $end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true);
                    $today = strtotime((new DateTime())->format('d-m-Y'));
                    $expiry = strtotime((new DateTime($_nf_duration_end))->format('d-m-Y'));
                    $fundraised = WPNEOCF()->getFundRaisedPercent(); 
                    
                    # Campaign Valid.                       
                    if ( ($end_method == 'target_goal' && $fundraised >= '100') || ($end_method == 'target_date' && $today > $expiry) || ($end_method == 'target_goal_and_date' && ($fundraised >= '100' || $today > $expiry) ) ) {  ?>
                        <div class="overlay until-date ">
                            <div>
                                <div>
                                    <span class="info-text"><?php _e( 'Campaign Over','wp-crowdfunding' ); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <?php if (WPNEOCF()->campaignValid()) { ?>
                            <?php if (WPNEOCF()->is_campaign_started()) {
                                if (get_option('wpneo_single_page_reward_design') == 1) { ?>
                                    <div class="overlay">
                                        <div>
                                            <div>
                                                <?php if( $quantity === 0 ){ ?>
                                                    <span class="wpneo-error"><?php _e( 'Reward no longer available.', 'wp-crowdfunding' ); ?></span>
                                                <?php } else { ?>
                                                    <form enctype="multipart/form-data" method="post" class="cart">
                                                        <input type="hidden" value="<?php echo $value['wpneo_rewards_pladge_amount']; ?>" name="wpneo_donate_amount_field" />
                                                        <input type="hidden" value="<?php echo esc_attr(serialize($value)); ?>" name="wpneo_selected_rewards_checkout" />
                                                        <input type="hidden" value="<?php echo $key; ?>" name="wpneo_rewards_index" />
                                                        <input type="hidden" value="<?php echo esc_attr($post->post_author); ?>" name="_cf_product_author_id">
                                                        <input type="hidden" value="<?php echo esc_attr($post->ID); ?>" name="add-to-cart">
                                                        <button type="submit" class="select_rewards_button"><?php _e('Select Reward','wp-crowdfunding'); ?></button>
                                                    </form>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else if (get_option('wpneo_single_page_reward_design') == 2){ ?>
                                    <div class="tab-rewards-submit-form-style1">
                                        <?php if( $quantity === 0 ): ?>
                                            <span class="wpneo-error"><?php _e( 'Reward no longer available.', 'wp-crowdfunding' ); ?></span>
                                        <?php else: ?>
                                            <form enctype="multipart/form-data" method="post" class="cart">
                                                <input type="hidden" value="<?php echo $value['wpneo_rewards_pladge_amount']; ?>" name="wpneo_donate_amount_field" />
                                                <input type="hidden" value="<?php echo esc_attr(serialize($value)); ?>" name="wpneo_selected_rewards_checkout" />
                                                <input type="hidden" value="<?php echo $key; ?>" name="wpneo_rewards_index" />
                                                <input type="hidden" value="<?php echo esc_attr($post->post_author); ?>" name="_cf_product_author_id">
                                                <input type="hidden" value="<?php echo esc_attr($post->ID); ?>" name="add-to-cart">
                                                <button type="submit" class="select_rewards_button"><?php _e('Select Reward','wp-crowdfunding'); ?></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php }else { ?>
                            <div class="overlay until-date">
                                <div>
                                    <div>
                                        <p class="funding-amount"><?php echo WPNEOCF()->days_until_launch(); ?></p>
                                        <span class="info-text"><?php _e( 'Days Until Launch','wp-crowdfunding' ); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <!-- Campaign Over -->


                </div>
            </div>
            <?php
            }
        }
    }
?>
<div style="clear: both"></div>