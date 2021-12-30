<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $post;
if ( ! $post->post_excerpt ) {
    return;
}
?>