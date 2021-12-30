<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; # Exit if accessed directly
}

global $product;
# Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$column 			= get_theme_mod( 'shop_column', 3 );
$enable_shop_desc 	= get_theme_mod( 'enable_shop_desc' );
$enable_shop_admin 	= get_theme_mod( 'enable_shop_admin' );
$product_cat_list 	= get_theme_mod( 'product_cat_list' ); ?>

    <?php if( $product->get_type() == 'crowdfunding' ): ?>
    <div class="col-12 col-sm-6 thm-post-grid-col col-lg-<?php echo esc_attr($column); ?>">
        <div class="themeum-campaign-post d-flex flex-wrap">
            <div class="clearfix">
                <?php if ( has_post_thumbnail() ){ ?>
                <div class="themeum-campaign-img">
                    <a class="review-item-image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('patrios-medium', array('class' => 'img-fluid')); ?></a>
                    <div class="thm-camp-hvr">
                        <div class="thm-ch-icon">
                            <i class="fa fa-heart-o"></i>
                        </div>
                        <h4><?php _e('Project You Love','patrios'); ?></h4>
                    </div>
                </div>
                <?php } ?>
                <div class="themeum-campaign-post-content clearfix">
                    <?php
                        $active = '';
                        if ( is_user_logged_in() ) {
                            $campaign_id = get_user_meta( get_current_user_id() , 'loved_campaign_ids', true);
                            if( $campaign_id ){
                                $campaign_id = json_decode( $campaign_id, true );
                                if (in_array( get_the_ID() , $campaign_id )){
                                    $active = 'active';
                                }
                            }
                        }
                    ?>
                    <a href="#" class="thm-love-btn <?php echo esc_attr($active); ?>" data-campaign="<?php echo get_the_ID(); ?>" data-user="<?php echo get_current_user_id(); ?>">
                        <i class="fa fa-heart-o"></i>
                    </a>
                    <span class="entry-category"><?php echo get_the_term_list( get_the_ID(), 'product_cat', '', ', ' ); ?></span>
                    <h3 class="entry-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <!-- <p><?php //echo patrios_excerpt_max_charlength( $textlimit ); ?></p> -->
                </div>
            </div>
            <div class="clearfix w-100 align-self-end">
                <div class="progressbar-content-wrapper">
                    <div class="thm-progress-bar">
                        <div class="lead">
                            <span class="thm-Price-amount">
                                <?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_fund_raised_by_campaign()); ?>
                            </span>
                            <span class="thm-raise-sp">
                                <?php _e('Raised','patrios'); ?> 
                            </span>
                            <div class="thm-meta-desc pull-right text-right">
                                <span class="thm-Price-amount">
                                    <?php echo wpneo_crowdfunding_price(wpneo_crowdfunding_get_total_goal_by_campaign(get_the_ID())); ?> 
                                </span>
                                <span class="thm-raise-sp">
                                    <?php _e('Goal','patrios'); ?> 
                                </span>
                            </div>
                        </div>
                        <div class="progress">
                            <?php $css_width = WPNEOCF()->getFundRaisedPercent(); if( $css_width >= 100 ){ $css_width = 100; } ?>
                            <div class="progress-bar progress-bar-primary six-sec-ease-in-out" role="progressbar" data-valuetransitiongoal="<?php echo esc_attr($css_width); ?>" style="width: <?php echo esc_attr($css_width); ?>%;"></div>
                        </div>
                    </div>
                </div>

                <div class="themeum-campaign-author">
                    <div class="themeum-camp-author clearfix">
                        <div class="themeum-author-img float-left">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ) , 40 ); ?>
                        </div>
                        <div class="themeum-author-dsc pull-left">
                            <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                                <?php echo get_the_author_meta('display_name'); ?>
                            </a>
                            <span><?php echo get_post_meta(get_the_ID(), '_nf_location', true); ?></span>
                        </div>
                        <div class="themeum-author-funded pull-right">
                            <h6>
                                <?php echo esc_attr($css_width).'%'; ?>
                            </h6>
                            <span><?php _e('Funded','patrios'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div <?php post_class( 'col-sm-6 col-md-6 col-lg-'.$column ); ?>>
        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
        <div class="product-thumbnail-outer">
            <div class="product-thumbnail-outer-inner">
                <?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
                <div class="addtocart-btn">
                    <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
                </div>
            </div>
            <div class="product-content-wrapper">
                <a href="<?php the_permalink(); ?>">
                    <?php do_action( 'woocommerce_shop_loop_item_title' ); ?>
                </a>
                <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
