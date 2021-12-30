<?php
/*
*Template Name: 404 Page Template
*/
get_header('alternative');
?>
<div class="patrios-error-wrapper" style="background-image: url(<?php echo esc_url( get_theme_mod('errorlogo', '')); ?>)">
    <div class="container">
        <div class="info-wrapper">
            <h2 class="error-message-title">
                <?php  echo esc_html(get_theme_mod( '404_title', esc_html__('404', 'patrios') )); ?>
            </h2>
            <p class="error-message">
                <?php  echo esc_html(get_theme_mod( '404_description', esc_html__('Oops! you’ve encountered an error!', 'patrios') )); ?>
            </p>
            <a class="btn btn-patrios white" href="<?php echo esc_url( home_url('/') ); ?>" title="<?php  esc_html_e( 'HOME', 'patrios' ); ?>">
                <?php  echo esc_html(get_theme_mod( '404_btn_text', esc_html__('Go Home', 'patrios') )); ?>
            </a>
        </div>
    </div>
</div>
<?php get_footer('alternative'); ?>
