<?php
    $permalink = get_permalink(get_the_ID());
    if(get_theme_mod('blog_share', false)) : ?>
    <div class="social-share-wrap" data-url="<?php echo esc_url($permalink); ?>"></div>
<?php endif; ?>
