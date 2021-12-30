<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package myticket
 */

?>

                    <div class="blog-post-content">
                        <!--Blog Post-->
                        <div class="blog-post post-content " >
                            <?php if ( has_post_thumbnail() ) : ?>
                            <div class="area-img">
                               <a href="<?php echo get_the_permalink(); ?>"><?php the_post_thumbnail('myticket-blog', array('class' => 'img-responsive')); ?></a>
                            </div>
                            <?php endif; ?>
                            <div class="area-content">
                                <h2 class="text-regular text-uppercase"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
                                <div class="blog-stats">
                                    <span class="clock">
                                        <i class="fa fa-calendar stats-item"></i>
                                        <span class="text-center text-light stats-item"><a href="<?php echo get_the_permalink(); ?>">
                                        <?php echo date_i18n( get_option( 'date_format' ), get_post_time( 'U', true ) ); ?></a></span>
                                    </span>
                                    <span class="comment">
                                        <i class="fa fa-comment stats-item"></i>
                                        <span class="text-center text-light stats-item"><?php echo comments_number( esc_html__( 'no comments', 'myticket' ), esc_html__( 'one comment', 'myticket' ), '% ' . esc_html__( 'comments', 'myticket' ) ); ?></span>
                                    </span>
                                    <span class="user">
                                        <i class="fa fa-user stats-item"></i>
                                        <span class="text-content text-light stats-item"><?php echo get_the_author(); ?></span>
                                    </span>
                                </div>
                                <div class="clearfix" ></div>
                                <div class="post-content-body">
                                <?php
                                the_content( sprintf(
                                        /* translators: %s: Name of current post. */
                                        wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'myticket' ), array( 'span' => array( 'class' => array() ) ) ),
                                        the_title( '<span class="screen-reader-text">"', '"</span>', false )
                                ) );
                                ?>
                                </div>
                                <?php    
                                //pagination
                                wp_link_pages( array(
                                                     'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'myticket' ) . '</span>',
                                                     'after'       => '</div>',
                                                     'link_before' => '<span>',
                                                     'link_after'  => '</span>',
                                                     'pagelink'    => '<span class="screen-reader-text">%</span>',
                                                     'separator'   => '',
                                                     ) );
                              
                                ?>

                            </div>
                        </div>

                        <!--Share-->
                        <?php 
                        if ( 1 == get_theme_mod('social_blog', '') )
                            myticket_sharing( $type = 'blog' );

                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif; ?>
                    </div>
