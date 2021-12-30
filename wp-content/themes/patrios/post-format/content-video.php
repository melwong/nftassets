<article id="post-<?php the_ID(); ?>" <?php if( !is_single()) post_class('patrios-post themeum-grid-post'); ?> <?php if(is_single()){ post_class('patrios-post themeum-grid-post single-post');} ?>>
    <?php if(function_exists('rwmb_meta')){ ?>
    <?php  if ( get_post_meta( get_the_ID(), 'themeum_video',true ) ) { ?>
    <div class="entry-video embed-responsive embed-responsive-16by9">
        <?php $video_source = esc_attr(get_post_meta( get_the_ID(), 'themeum_video_source',true )); ?>
        <?php $video = get_post_meta( get_the_ID(), 'themeum_video',true ); ?>
        <?php if($video_source == 1): ?>
        <?php echo get_post_meta( get_the_ID(), 'themeum_video',true ); ?>
        <?php elseif ($video_source == 2): ?>
        <?php echo '<iframe width="100%" height="350" src="http://www.youtube.com/embed/'.esc_attr($video).'?rel=0&showinfo=0&modestbranding=1&hd=1&autohide=1&color=white"  allowfullscreen></iframe>'; ?>
        <?php elseif ($video_source == 3): ?>
        <?php echo '<div class="embed-responsive embed-responsive-16by9"><iframe src="http://player.vimeo.com/video/'.esc_attr($video).'?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>'; ?>
        <?php endif; ?>
    </div>
    <?php } ?>
    <?php } ?>
    <div class="themeum-post-grid-title clearfix">

        <?php if(!is_single()) : ?>
        <div class="patrios-date-meta">
            <div class="patrios-date-meta">
                <time datetime="<?php echo get_the_date('Y-m-d') ?>"><?php echo get_the_date(); ?></time>
            </div>
        </div>
        <?php endif; ?>
        <?php if(is_single()){ ?>
        <div class="thm-post-meta">
            <div class="comments">
                <i class="fa fa-comments"></i>
                <?php comments_number( '0', '1', '%' ); ?>
            </div>
            <div class="date">
                <i class="fa fa-clock-o"></i>
                <?php echo get_the_date().' '; echo get_the_time(); ?>
            </div>   
            <div class="cat-list">
                <?php if(get_the_category_list()) : ?>
                    <i class="fa fa-folder-open-o"></i>
                <?php endif; ?>
                <?php printf(esc_html__('%s', 'patrios'), get_the_category_list(', ')); ?>
            </div>       
            <div class="tag-list">
                <?php if(get_the_tag_list()) : ?>
                    <i class="fa fa-tags"></i>
                <?php endif; ?>
                <?php echo get_the_tag_list('',', ',''); ?>
            </div>
        </div>
        <?php } ?>
        <h2 class="content-item-title">
            <?php if(!is_single()) : ?>
            <a href="<?php esc_url(the_permalink()); ?>">
                <?php endif; ?>
                <?php the_title(); ?>
                <?php if(!is_single()) : ?>
            </a>
            <?php endif; ?>
        </h2>
    </div>
    <div class="entry-blog">
        <?php if( !is_single() ){ ?>
            <?php get_template_part( 'post-format/entry-content' ); ?> 
        <?php } else {  ?>
        <div class="thm-single-post-content">
            <div class="thm-single-post-content-inner">
                <?php the_content(); ?>
            </div>
            <?php include( get_parent_theme_file_path('post-format/social-buttons.php') ); ?>
        </div>
        <?php } ?>
    </div> 
</article>