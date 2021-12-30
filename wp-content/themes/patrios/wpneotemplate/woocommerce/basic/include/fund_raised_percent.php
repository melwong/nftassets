<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$raised_percent = WPNEOCF()->getFundRaisedPercentFormat(); ?>

<div class="wpneo-raised-percent">
    <div class="wpneo-meta-name"><?php _e('Raised Percent', 'patrios'); ?> :</div>
    <div class="wpneo-meta-desc" ><?php echo wp_kses_post($raised_percent); ?></div>
</div>

<div class="progress">
    <?php 
        $css_width = WPNEOCF()->getFundRaisedPercent(); 
    ?>
    <div class="progress-bar" style="width: <?php echo esc_attr($css_width); ?>%"></div> <br>
</div>

<?php $wpneo_campaign_end_method = get_post_meta(get_the_ID(), 'wpneo_campaign_end_method', true); ?>

<div class="lead">
    <?php if ($wpneo_campaign_end_method != 'never_end'){ ?>
        <span class="thm-Price-amount">
            <span class="woocommerce-Price-amount amount"><?php echo WPNEOCF()->dateRemaining(); ?></span>
        </span> 
        <span class="thm-raise-sp"><?php _e( 'Days to go','patrios' ); ?></span>
    <?php } ?>
    <div class="thm-meta-desc pull-right text-right">
        <span class="thm-Price-amount">
            <span class="woocommerce-Price-amount amount">
                <?php echo esc_attr($css_width).'%'; ?></span>
        </span>
        <span class="thm-raise-sp">
            <?php _e('Funded', 'patrios'); ?>
        </span>
    </div>
</div>