<?php 
$image_url = "";
if ( $instance['img1'] != '' ){
    $image_url1 = wp_get_attachment_image_src($instance['img1'],"myticket-aboutus-small",false);
}
if ( $instance['img1'] != '' ){
    $image_url1_full = wp_get_attachment_image_src($instance['img1'],"full",false);
}
if ( $instance['img2'] != '' ){
    $image_url2 = wp_get_attachment_image_src($instance['img2'],"myticket-aboutus-small",false);
}
if ( $instance['img2'] != '' ){
    $image_url2_full = wp_get_attachment_image_src($instance['img2'],"full",false);
}
if ( $instance['img3'] != '' ){
    $image_url3 = wp_get_attachment_image_src($instance['img3'],"myticket-aboutus-small",false);
}
if ( $instance['img3'] != '' ){
    $image_url3_full = wp_get_attachment_image_src($instance['img3'],"full",false);
}
if ( $instance['img4'] != '' ){
    $image_url4 = wp_get_attachment_image_src($instance['img4'],"myticket-aboutus-small",false);
}
if ( $instance['img4'] != '' ){
    $image_url4_full = wp_get_attachment_image_src($instance['img4'],"full",false);
}
if ( $instance['img5'] != '' ){
    $image_url5 = wp_get_attachment_image_src($instance['img5'],"myticket-aboutus-large",false);
}
if ( $instance['img5'] != '' ){
    $image_url5_full = wp_get_attachment_image_src($instance['img5'],"full",false);
}
echo do_shortcode( '[myticket_aboutus title="'.$instance['title'].'" text="'.$instance['text'].'" text_right="'.$instance['text_right'].'" text_left="'.$instance['text_left'].'" img1="'.$image_url1[0].'" img1_full="'.$image_url1_full[0].'"  img2="'.$image_url2[0].'"  img2_full="'.$image_url2_full[0].'" img3="'.$image_url5[0].'" img3_full="'.$image_url5_full[0].'" img4="'.$image_url3[0].'" img4_full="'.$image_url3_full[0].'" img5="'.$image_url4[0].'" img5_full="'.$image_url4_full[0].'" button_url="'.$instance['button_url'].'" button_text="'.$instance['button_text'].'" ]' ); ?>