<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package myticket
 */

?>
    <div class="clearfix"></div>
    <footer id="colophon" class="site-footer">
        <div class="top-footer">
            <div class="container">
                <div class="row footer-logo">
                    <?php $imgurl = (get_theme_mod( 'myticket_logo', '' ));
                        if(empty($imgurl) || '' == $imgurl){

                            $imgurl = get_template_directory_uri() . '/images/logo.svg';
                        }
                    ?>
                    <div class="col-md-8">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src='<?php echo esc_url( $imgurl ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'></a>
                    </div>
                    <div class="col-md-4">
                    
                    <p><?php echo esc_attr( get_theme_mod( 'myticket_footnote',  '&copy; 2017 MYTICKET.COM. ALL RIGHTS RESERVED' ) ); ?></p>
                    </div>
                </div>  
            </div>
        </div>
        <?php if ( has_nav_menu( 'footer' ) || has_nav_menu( 'footer_right' ) ) : ?>
            <div class="main-footer">
                <div class="container">
                    <div class="row">
                        <div class="footer-1 col-md-9">
                            <?php if ( has_nav_menu( 'footer' ) ) {
                                    wp_nav_menu(array(
                                          'theme_location' => 'footer',
                                          'container'       => false,
                                          'items_wrap'      => '%3$s',
                                          'depth' => 2,
                                          'walker' => new myticket_footer_walker_nav_menu
                                          ));
                            } ?>

                            <div class="social clearfix">
                                <h3><?php echo esc_attr( get_theme_mod( 'myticket_footsocialnote',  '' ) ); ?></h3>
                                <ul>
                                    <?php if ( get_theme_mod( 'facebook' ) ){ echo '<li class="facebook"><a href="' .esc_url( get_theme_mod( 'facebook' ) ). '"><i class="fa fa-facebook hvr-wobble-top " aria-hidden="true"></i>Facebook</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'twitter' ) ){ echo '<li class="twitter"><a href="' .esc_url( get_theme_mod( 'twitter' ) ). '"><i class="fa fa-twitter hvr-wobble-top " aria-hidden="true"></i>Twitter</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'youtube' ) ){ echo '<li class="youtube"><a href="' .esc_url( get_theme_mod( 'youtube' ) ). '"><i class="fa fa-youtube hvr-wobble-top " aria-hidden="true"></i>Youtube</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'linkedin' ) ){ echo '<li class="linkedin"><a href="' .esc_url( get_theme_mod( 'linkedin' ) ). '"><i class="fa fa-linkedin hvr-wobble-top " aria-hidden="true"></i>Linkedin</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'pinterest' ) ){ echo '<li class="pinterest"><a href="' .esc_url( get_theme_mod( 'pinterest' ) ). '"><i class="fa fa-pinterest hvr-wobble-top " aria-hidden="true"></i>Pinterest</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'google' ) ){ echo '<li class="google"><a href="' .esc_url( get_theme_mod( 'google' ) ). '"><i class="fa fa-google hvr-wobble-top " aria-hidden="true"></i>Google</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'tumblr' ) ){ echo '<li class="tumblr"><a href="' .esc_url( get_theme_mod( 'tumblr' ) ). '"><i class="fa fa-tumblr hvr-wobble-top " aria-hidden="true"></i>Tumblr</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'instagram' ) ){ echo '<li class="instagram"><a href="' .esc_url( get_theme_mod( 'instagram' ) ). '"><i class="fa fa-instagram  hvr-wobble-top" aria-hidden="true"></i>Instagram</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'vimeo' ) ){ echo '<li class="vimeo"><a href="' .esc_url( get_theme_mod( 'vimeo' ) ). '"><i class="fa fa-vimeo hvr-wobble-top " aria-hidden="true"></i>Vimeo</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'vk' ) ){ echo '<li class="vk"><a href="' .esc_url( get_theme_mod( 'vk' ) ). '"><i class="fa fa-vk hvr-wobble-top " aria-hidden="true"></i>Vkontakte</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'disqus' ) ){ echo '<li class="disqus"><a href="' .esc_url( get_theme_mod( 'disqus' ) ). '"><i class="fa fa-disqus hvr-wobble-top " aria-hidden="true"></i>Disqus</a></li>'; } ?>
                                    <?php if ( get_theme_mod( 'kenzap' ) ){ echo '<li class="kenzap"><a target="blank" title="'.esc_html__( 'Create website', 'myticket' ).'" href="' .esc_url( get_theme_mod( 'kenzap' ) ). '"><i class="fa fa-cloud hvr-wobble-top txcolor" aria-hidden="true"></i>Kenzap</a></li>'; } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="footer-2 col-md-3">

                            <?php if ( has_nav_menu( 'footer_right' ) ) {
                                    wp_nav_menu(array(
                                          'theme_location' => 'footer_right',
                                          'container'       => false,
                                          'items_wrap'      => '%3$s',
                                          'depth' => 2,
                                          'walker' => new myticket_footer_right_walker_nav_menu
                                          ));
                            } ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
