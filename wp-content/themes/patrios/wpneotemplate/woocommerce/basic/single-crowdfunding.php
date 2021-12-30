<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; # Exit if accessed directly
}
get_header('shop');
do_action( 'wpneo_before_crowdfunding_single_campaign' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}

$enable_shop_sub_header  = get_theme_mod( 'enable_shop_sub_header' ); ?>

<?php if ($enable_shop_sub_header): ?>
    <?php get_template_part('lib/sub-header'); ?> 
<?php endif ?>

<div class="wpneo-wrapper">
    <div class="wpneocf-container">
        <div class="content-area">
            <div id="content" class="site-content" role="main">
                <div class="wpneo-list-details">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php do_action( 'wpneo_before_main_content' ); ?>
                        <div itemscope itemtype="http://schema.org/ItemList" id="campaign-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php do_action( 'wpneo_before_crowdfunding_single_campaign_summery' ); ?>
                            <div class="wpneo-campaign-summary progressbar-content-wrapper">
                                <div class="wpneo-campaign-summary-inner thm-progress-bar" itemscope itemtype="http://schema.org/DonateAction">
                                    
                                    <?php if ($enable_shop_sub_header == false): ?>
                                        <?php 
                                            global $post;
                                            $terms = wp_get_post_terms( $post->ID, 'product_cat' );
                                            if ($terms) {      
                                                echo '<ul class="thm-single-category">'; 
                                                foreach ( $terms as $term ) { 
                                                    $color = get_option('product_cat_color_custom_order_'.$term->term_taxonomy_id);
                                                    $term_link = get_term_link( $term, 'product_cat' );
                                                    echo '<li>';
                                                        echo '<a href="'.$term_link.'" style="color:'.$color.';">';
                                                            echo '<span>'.$term->name.'</span>';
                                                        echo '</a>';
                                                    echo '</li>';
                                                }
                                                echo '</ul>'; 
                                            }
                                        ?>
                                    <?php endif ?>

                                    <div></div>
                                    <?php do_action( 'wpneo_crowdfunding_single_campaign_summery' ); ?>
                                </div><!-- .wpneo-campaign-summary-inner -->
                            </div><!-- .wpneo-campaign-summary -->
                            <?php do_action( 'wpneo_after_crowdfunding_single_campaign_summery' ); ?>
                            <meta itemprop="url" content="<?php the_permalink(); ?>" />
                        </div><!-- #campaign-<?php the_ID(); ?> -->
                        <?php do_action( 'wpneo_after_crowdfunding_single_campaign' ); ?>
                        <?php do_action( 'wpneo_after_main_content' ); ?>
                    <?php endwhile; ?>
                </div>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div>
</div>

<?php

$tabs = apply_filters( 'wpneo_crowdfunding_default_single_campaign_tabs', array() );
if ( ! empty( $tabs ) ) : ?>
<div class="wpneo-wrapper patrios-tabs">
    <div class="wpneocf-container container">
        <div class="wpneo-tabs">
            <ul class="wpneo-tabs-menu">
                <?php $i = 0;
                foreach ( $tabs as $key => $tab ) :
                    $i++;
                    $current = $i === 1 ? 'wpneo-current' : ''; ?>
                    <li class="<?php echo esc_attr($current).' '.esc_attr( $key ); ?>_tab">
                        <a href="#wpneo-tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'wpneo_campaign_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                    </li> 
                <?php endforeach; ?>   
            </ul>
        </div>
    </div>
</div>

<div class="wpneo-wrapper ">
    <div class="wpneocf-container container">
        <div class="content-area">
            <div class="site-content" role="main">
                <div class="wpneo-list-details">
                    <div class="wpneo-tab">
                        <?php foreach ( $tabs as $key => $tab ) :?>
                            <div id="wpneo-tab-<?php echo esc_attr( $key ); ?>" class="wpneo-tab-content">
                                <?php call_user_func( $tab['callback'], $key, $tab ); ?> 
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="clear-float"></div>
                </div>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div>
</div>

<?php endif; ?>

<?php
    if ( is_singular( 'product' ) ){
        $count_post = esc_attr( get_post_meta( $post->ID, '_post_views_count', true) );
        if( $count_post == ''){
            $count_post = 1;
            add_post_meta( $post->ID, '_post_views_count', $count_post);
        }else{
            $count_post = (int)$count_post + 1;
            update_post_meta( $post->ID, '_post_views_count', $count_post);
        }
    }
?>

<?php get_footer('shop'); ?>