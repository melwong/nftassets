<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package myticket
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="story-single">
        <?php
        if ( is_single() ) {
            $meta = get_post_meta( get_the_ID() );
        ?>
        <div class="cover-img">
            <div class="area-img">
                <?php the_post_thumbnail('myticket-story-large', array('class' => 'img-responsive')); ?>
            </div>
            <div class="area-title">
                <div class="container">
                <h2 class="heading-light"><?php if ( isset( $meta['_name'] ) ) { echo esc_attr( $meta['_name'][0] ); }else{ echo '<br>'; } ?></h2>
                    <h1 class="heading-regular"><?php echo the_title(); ?></h1>
                </div>
            </div>
        </div>
        <?php
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
        ?>
        <div class="entry-content story-content">
            <div class="container">
            <?php
                the_content( sprintf(
                    /* translators: %s: Name of current post. */
                    wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'myticket' ), array( 'span' => array( 'class' => array() ) ) ),
                    the_title( '<span class="screen-reader-text">"', '"</span>', false )
                ) );

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'myticket' ),
                    'after'  => '</div>',
                ) );
            ?>
            <?php
            if ( 'post' === get_post_type() ) : ?>
            <?php myticket_posted_on(); ?>
            <?php endif; ?>
            </div><!-- .entry-content -->
            </div>
            <?php 
            if ( 1 == get_theme_mod('social_blog', '') )
                myticket_sharing(); ?>
            <?php myticket_content_nav( 'nav-below' ); ?>
            </div>
    </div><!-- .entry-header -->

</div><!-- #post-## -->
