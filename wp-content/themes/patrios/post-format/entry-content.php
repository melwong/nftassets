<div class="entry-summary clearfix">
    <?php if ( is_single() ) {
        the_content();
    } else {
        if ( get_theme_mod( 'blog_intro_en', true ) ) { 
            if ( get_theme_mod( 'blog_post_text_limit', 280 ) ) {
                $textlimit = get_theme_mod( 'blog_post_text_limit', 280 );
                echo patrios_excerpt_max_charlength($textlimit);
            } else {
                the_content();
            }
            if ( get_theme_mod( 'blog_continue_en', true ) ) { 
                if ( get_theme_mod( 'blog_continue', 'Read More' ) ) {
                    $continue = esc_html( get_theme_mod( 'blog_continue', 'Read More' ) );
                    echo '<p class="wrap-btn-style"><a class="thm-btn" href="'.get_permalink().'">'. $continue .'</a></p>';

                } 
            }
        }
    } 
    ?>

</div> <!-- //.entry-summary -->