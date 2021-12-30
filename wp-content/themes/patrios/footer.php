<?php 
    $col = get_theme_mod( 'bottom_column', 3 );
    $enable_bottom_section  = get_theme_mod( 'enable_bottom_section', true );
    $enable_mailchimp       = get_theme_mod( 'enable_mailchimp', true ); 
?>

<div class="footer-menu">
    <div class="raindrop_style">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 text-left">
                    <?php
                        $default = array( 'theme_location'  => 'footernav',
                        'container'       => '', 
                        'menu_class'      => 'menu-footer-menu',
                        'menu_id'         => 'menu-footer-menu',
                        'fallback_cb'     => 'wp_page_menu',
                        'depth'           => 1
                        );
                        wp_nav_menu($default);
                    ?>
                </div> 
            </div> 
        </div> 
    </div>
</div>


<?php if ($enable_bottom_section == 'true'): ?>
    <div class="bottom footer-wrap">
        <div class="container bottom-footer-cont">
            <div class="row clearfix">
                <!-- Without MailChimp -->
                <?php if ($enable_mailchimp == 'false'){ ?>
                    <?php if (is_active_sidebar('bottom1')):?>
                    <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                        <?php dynamic_sidebar('bottom1'); ?>
                    </div>
                    <?php endif; ?> 
                    <?php if (is_active_sidebar('bottom2')):?>
                        <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                            <?php dynamic_sidebar('bottom2'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('bottom3')):?>
                    <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                        <?php dynamic_sidebar('bottom3'); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('bottom4')):?>
                    <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                        <?php dynamic_sidebar('bottom4'); ?>
                    </div><!-- End -->
                    <?php endif; ?>
                <?php }else{ ?>
                    <!-- mailchil-container start -->
                    <?php if (is_active_sidebar('bottom5')):?>
                        <div class="col-lg-4 mailchimp-inner bottom-wrap">
                            <div class="mailchil-container">
                                
                                <?php dynamic_sidebar('bottom5'); ?>
                                
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- mailchimp end --> 

                    <!-- With MailChimp -->
                    <div class="col-lg-8">
                        <div class="row">
                            <?php if (is_active_sidebar('bottom1')):?>
                                <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                                    <?php dynamic_sidebar('bottom1'); ?> 
                                </div>
                            <?php endif; ?> 
                            <?php if (is_active_sidebar('bottom2')):?>
                                <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                                    <?php dynamic_sidebar('bottom2'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (is_active_sidebar('bottom3')):?>
                                <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                                    <?php dynamic_sidebar('bottom3'); ?>
                                </div>
                            <?php endif; ?>   
                            <?php if (is_active_sidebar('bottom4')):?>
                                <div class="bottom-wrap col-sm-6 col-lg-<?php echo esc_attr($col);?>">
                                    <?php dynamic_sidebar('bottom4'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div><!--/#footer-->
    <?php endif ?>

    <?php if ( get_theme_mod( 'enable_footer_en', true )) { ?>
        <!-- start footer -->
        <footer id="footer"> 
            <div class="container">
                <div class="footer-copyright">
                    <div class="row">  
                        <div class="col-md-6 text-left copy-wrapper">
                            
                            <?php if( get_theme_mod( 'copyright_en', true ) ) { ?>
                                <span><?php echo wp_kses_post( get_theme_mod( 'copyright_text', '© 2018 patrios. All Rights Reserved.') ); ?></span>
                            <?php } ?>
                        </div> <!-- end row -->
                        <div class="col-md-6 text-right copy-wrapper">
                            <?php if( get_theme_mod( 'socialshare_en', true ) ) { ?>
                            <?php get_template_part('lib/social-link')?>
                            <?php } ?>
                        </div> <!-- end row -->
                    </div> <!-- end row -->
                </div> <!-- end row --> 
            </div> <!-- end container -->
        </footer>
        <!-- End footer -->
    <?php } ?>

</div> <!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
