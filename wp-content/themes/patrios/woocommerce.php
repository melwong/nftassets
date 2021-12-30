<?php get_header(); ?>

<?php $col = (is_product()) ? 12 : 8; ?>

<?php get_template_part('lib/sub-header')?>

<section id="main" class="wrappers-content">
    <div class="container">
        <?php if( is_product_category() ) {
            $meta_query  = WC()->query->get_meta_query();
            $shop_post_per_page      = get_theme_mod( 'shop_post_per_page', true );
            $shop_post_order_by      = get_theme_mod( 'shop_post_order_by', true );
            $shop_post_order         = get_theme_mod( 'shop_post_order', true );

            $product_cat = get_query_var( 'product_cat' );
            $args = array( 
                'post_type'           => 'product', 
                'posts_per_page'      => $shop_post_per_page, 
                'product_cat'         => $product_cat, 
                'orderby'             => $shop_post_order_by,
                'order'               => $shop_post_order ,
                'meta_query'          => $meta_query,
            );
            $data = new WP_Query( $args ); 
        ?>

        <?php if( function_exists( 'wpneo_crowdfunding_price' ) ){ ?>

            <!-- Feature Post Start -->
            <?php if (get_theme_mod( 'enable_feature_section', true )): ?>   
                <?php if ( $data->have_posts() ) : ?>
                <div class="row thm-category-featured">


                    <?php while ( $data->have_posts() ) : $data->the_post(); ?>
                        <div class="col-12 col-sm-12 col-lg-6">
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
                        <div class="themeum-campaign-time-exp thm-grid-list clearfix">
                            <?php if ( has_post_thumbnail() ){ ?>
                                <div class="themeum-campaign-thumb" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
                                    <?php if ($active) { ?>
                                        <a href="#" class="thm-love-btn <?php echo esc_attr($active); ?>" data-campaign="<?php echo get_the_ID(); ?>" data-user="<?php echo get_current_user_id(); ?>">
                                            <i class="fa fa-heart"></i>
                                        </a>
                                    <?php }else { ?>
                                        <a href="#" class="thm-love-btn <?php echo esc_attr($active); ?>" data-campaign="<?php echo get_the_ID(); ?>" data-user="<?php echo get_current_user_id(); ?>">
                                            <i class="fa fa-heart-o"></i>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php } ?>                                    
                            
                            <div class="themeum-campaign-exp-content">
                                <span class="entry-category"><?php echo get_the_term_list( get_the_ID(), 'product_cat', ' ', ', ', '' ); ?></span>
                                <h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
                                    <div class="themeum-campaign-author style-2">
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
                                                <span><?php _e('Funded','patrios')?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php wp_reset_query(); ?>
            </div><!--/.row-->
            <?php endif; ?>
        <?php endif ?>
        <!-- End Featured Post -->
    <?php } } ?>

    <!-- Latest Post -->
    <?php if ( get_theme_mod( 'enable_latest_post_section', true ) ): ?>
        <div class="row ">
            <?php $layout = get_theme_mod( 'shop_sidebar', 'fullwidth' ); ?>
            <?php if( $layout == 'left_sidebar' ): ?>
                <div id="shop" class="col-3" role="complementary">
                    <aside class="widget-area">
                        <?php 
                            if ( is_active_sidebar( 'shop' ) ) {
                                dynamic_sidebar('shop');
                            }
                         ?>
                    </aside>
                </div>
            <?php endif; ?>
            <div id="content" class="col-<?php echo ( $layout == 'fullwidth' )? '12':'9'; ?>" role="main">
                <div class="site-content category-post">
                    <?php if (get_theme_mod( 'enable_latest_post_title', true )): ?>
                        <div class="patrios-title-content-wrapper col-12 text-center">
                            <h2 class="thm-heading-title"><?php echo  esc_html(get_theme_mod( 'latest_post_title_text', esc_html__('Latest Projects', 'patrios') )); ?></h2>
                            <p class="sub-title-content"><?php echo  esc_html(get_theme_mod( 'latest_post_subtitle_text', esc_html__('Discover what’s possible when a community creates together', 'patrios') )); ?></p>    
                        </div>  
                    <?php endif ?>
                    <?php woocommerce_content(); ?>
                </div>
            </div>
            <?php if( $layout == 'right_sidebar' ): ?>
            <div id="shop" class="col-3 patrios_wooshop_widgets" role="complementary">
                <aside class="widget-area">
                    <?php 
                        if ( is_active_sidebar( 'shop' ) ) {
                            dynamic_sidebar('shop');
                        }
                     ?>
                </aside>
            </div>
            <?php endif; ?>
        </div> <!-- .row -->
    <?php endif ?>
    <!-- End Latest Post -->
    </div> <!-- .container -->
</section>

<?php get_footer(); ?>