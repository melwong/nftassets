<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; # Exit if accessed directly
}?>
<div class="tab-description tab_col_9 tab-campaign-story-left">
    <?php the_content(); ?>
</div>
<div class="tab-rewards tab_col_3 tab-campaign-story-right">
    <?php do_action('wpneo_campaign_story_right_sidebar'); ?>
    <div style="clear: both"></div>
</div>

<?php 
global $post;
$user_info = get_user_meta($post->post_author);
$creator = get_user_by('id', $post->post_author);

if (is_product()) {
    global $post;
    $product = wc_get_product($post->ID);

    if ($product->get_type() === 'crowdfunding') {

        
        echo "<div class='tab-description-wrap wpneo-clearfix'>";
        echo "<div class='tab-description'>"; ?>
 
        
            <div class="patrios-bio">
                <div class="patrios-info-img">
                    <?php if ( $post->post_author ) {
                        $img_src    = '';
                        $image_id = get_user_meta( $post->post_author,'profile_image_id', true );
                        if( $image_id != '' ){
                            $img_src = wp_get_attachment_image_src( $image_id, 'backer-portfo' )[0];
                        } ?>
                        <?php if( $img_src ){ ?>
                            <img width="80" height="80" src="<?php echo esc_url($img_src); ?>" alt="">
                        <?php } else { ?>
                            <?php  echo get_avatar($post->post_author, 80); ?>
                        <?php } ?>
                    <?php } ?>
                    
                    <a data-toggle="modal" data-target="#bioinfo" href="#" class="wpneo-fund-modal-btn"><?php echo wpneo_crowdfunding_get_author_name(); ?></a> 
                </div>

                <?php
                    $permalink = get_permalink(get_the_ID());
                    $titleget = get_the_title();
                    $media_url ='';
                    if( has_post_thumbnail( get_the_ID() ) ){
                        $thumb_src =  wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' ); 
                        $media_url = $thumb_src[0];
                    }
                ?>

                <!-- User Address -->
                <div class="patrios-share">  
                    <?php $profile_address = get_user_meta( get_current_user_id(), 'profile_address', true ); ?>
                    <?php if ($profile_address ): ?>
                        <div class="patrios-location">  
                            <span class="patrios-meta-desc"><i class="back-placeholder"></i> <?php echo esc_attr($profile_address); ?></span>
                        </div>  
                    <?php endif ?> 
                </div>

                <!-- Social Share -->
                <div class="patrios-follow-us pull-right">
                    <div class="patrios-bio-social" data-url="<?php echo esc_url($permalink); ?>"></div>        
                </div>
            </div>
       

        <?php
        echo '</div>';
        echo '</div>';
    }
} ?>


<!-- Modal Content -->
<div class="modal fade wpneo-modal-wrapper" id="bioinfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="wpneo-modal-content">
                <div class="wpneo-modal-wrapper-head">
                    <h4><?php _e('About the campaign creator','patrios'); ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <div class="wpneo-modal-content-inner">        
                    <div  class="wpneo-profile-left">
                        <?php
                            $img_src = '';
                            $image_id = get_user_meta( get_current_user_id(), 'profile_image_id', true );
                            if( $image_id != '' ){
                                $img_src = wp_get_attachment_image_src( $image_id, 'full' );
                                $img_src = $img_src[0];
                            }
                            if (!empty($img_src)){
                                echo '<img width="105" height="105" class="profile-avatar" src="'.$img_src.'" alt="">';
                            }
                        ?>
                    </div>

                    <div class="wpneo-profile-right">
                        <div class="wpneo-profile-name"><?php echo wpneo_crowdfunding_get_author_name(); ?></div> 
                        <?php $profile_address = get_user_meta( get_current_user_id(), 'profile_address', true ); ?>
                        <?php if ($profile_address ): ?>
                        <div class="wpneo-profile-location patrios-location">  
                            <span class="patrios-meta-desc"><i class="back-placeholder"></i> <?php echo esc_attr($profile_address); ?></span>
                        </div>
                        <?php endif ?>
                        <div class="wpneo-profile-campaigns"><?php echo wpneo_crowdfunding_author_all_campaigns()->post_count.__( " Campaigns" , "patrios" ).' | '. wpneo_loved_campaign_count().__( " Loved campaigns" , "patrios" ); ?></div>
                    </div>

                    <?php
                    if ( ! empty($user_info['profile_about'][0])){
                        echo '<div class="wpneo-profile-about">';
                        echo '<h3>'.__("About","patrios").'</h3>';
                        echo '<p>'.$user_info['profile_about'][0].'</p>';
                        echo '</div>';
                    }

                    if ( ! empty($user_info['profile_portfolio'][0])){
                        echo '<div class="wpneo-profile-about">';
                        echo '<h3>'.__("Portfolio","patrios").'</h3>';
                        echo '<p>'.$user_info['profile_portfolio'][0].'</p>';
                        echo '</div>';
                    }
                    
                    echo '<div class="wpneo-profile-about">';
                        echo '<h3>'.__("Contact Info","patrios").'</h3>';
                        if ( ! empty($user_info['profile_email1'][0])){
                            echo '<p>'.__("Email: ","patrios").$user_info['profile_email1'][0].'</p>';
                        }
                        if ( ! empty($user_info['profile_mobile1'][0])){
                            echo '<p>'.__("Phone: ","patrios").$user_info['profile_mobile1'][0].'</p>';
                        }
                        if ( ! empty($user_info['profile_fax'][0])){
                            echo '<p>'.__("Fax: ","patrios").$user_info['profile_fax'][0].'</p>';
                        }
                        if ( ! empty($user_info['profile_website'][0])){
                            echo '<p>'.__("Website: ","patrios").' <a href="'.wpneo_crowdfunding_add_http($user_info['profile_website'][0]).'"> '.wpneo_crowdfunding_add_http($user_info['profile_website'][0]).' </a></p>';
                        }
                        if ( ! empty($user_info['profile_email1'][0])){
                            echo '<a class="wpneo-profile-button" href="mailto:'.$user_info['profile_email1'][0].'" target="_top">'.__("Contact Me","patrios").'</a>';
                        }
                    echo '</div>';

                    echo '<div class="wpneo-profile-about">';
                        echo '<h3>'.__("Social Link","patrios").'</h3>';
                        if ( ! empty($user_info['profile_facebook'][0])){
                            echo '<a class="wpneo-social-link" href="'.$user_info["profile_facebook"][0].'"><i class="wpneo-icon wpneo-icon-facebook"></i></a>';
                        }
                        if ( ! empty($user_info['profile_twitter'][0])){
                            echo '<a class="wpneo-social-link" href="'.$user_info["profile_twitter"][0].'"><i class="wpneo-icon wpneo-icon-twitter"></i></a>';
                        }
                        if ( ! empty($user_info['profile_plus'][0])){
                            echo '<a class="wpneo-social-link" href="'.$user_info["profile_plus"][0].'"><i class="wpneo-icon wpneo-icon-gplus"></i></a>';
                        }
                        if ( ! empty($user_info['profile_linkedin'][0])){
                            echo '<a class="wpneo-social-link" href="'.$user_info["profile_linkedin"][0].'"><i class="wpneo-icon wpneo-icon-linkedin"></i></a>';
                        }
                        if ( ! empty($user_info['profile_pinterest'][0])){
                            echo '<a class="wpneo-social-link" href="'.$user_info["profile_pinterest"][0].'"><i class="wpneo-icon wpneo-icon-pinterest"></i></a>';
                        }
                    echo '</div>'; ?>

                </div>
            </div>
        </div>
    </div>
</div>