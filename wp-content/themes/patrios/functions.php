<?php

define( 'PATRIOS_CSS', get_parent_theme_file_uri().'/css/' );
define( 'PATRIOS_JS', get_parent_theme_file_uri().'/js/' );
define( 'PATRIOS_DIR', get_template_directory() );
define( 'PATRIOS_URI', trailingslashit(get_template_directory_uri()) );


/* -------------------------------------------
*        Guttenberg for Themeum Themes
* ------------------------------------------- */
add_theme_support( 'align-wide' );
add_theme_support( 'wp-block-styles' );


/*-------------------------------------------*
 *				Patrios Register
 *------------------------------------------*/
include( get_parent_theme_file_path('lib/main-function/patrios-register.php') );

/* -------------------------------------------
*           	Include TGM Plugins
* -------------------------------------------- */
include( get_parent_theme_file_path('lib/class-tgm-plugin-activation.php') );


/*-------------------------------------------*
 *				Register Navigation
 *------------------------------------------*/
register_nav_menus( array(
	'primary' => esc_html__( 'Primary Menu', 'patrios' ),
	'footernav' => esc_html__( 'Footer Menu', 'patrios' ),
) );


/*-------------------------------------------
*          		add font patrios
*--------------------------------------------*/
include( get_parent_theme_file_path('lib/fontpatrios-helper.php') );

/*-------------------------------------------*
 *				navwalker
 *------------------------------------------*/
include( get_parent_theme_file_path('lib/menu/admin-megamenu-walker.php') );
include( get_parent_theme_file_path('lib/menu/meagmenu-walker.php') );
include( get_parent_theme_file_path('lib/menu/mobile-navwalker.php') );
add_filter( 'wp_edit_nav_menu_walker', function( $class, $menu_id ){
	return 'Themeum_Megamenu_Walker';
}, 10, 2 );


/*-------------------------------------------------------
*				Themeum Core
*-------------------------------------------------------*/
include( get_parent_theme_file_path('lib/main-function/patrios-core.php') );


/*-----------------------------------------------------
 * 				Custom Excerpt Length
 *----------------------------------------------------*/
if(!function_exists('patrios_excerpt_max_charlength')):
	function patrios_excerpt_max_charlength($charlength) {
		$excerpt = get_the_excerpt();
		$charlength++;
		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				return mb_substr( $subex, 0, $excut );
			} else {
				return $subex;
			}
		} else {
			return $excerpt;
		}
	}
endif;


add_filter( 'document_title_parts', function( $title ){
    if ( is_search() )
        $title['title'] = sprintf(
            esc_html__( '&#8220;%s&#8221; result page', 'my-theme-domain' ),
            get_search_query()
        );
    return $title;
} );




/* ------------------------------------------ *
 *				woocommerce support
* ------------------------------------------ */
function patrios_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'patrios_woocommerce_support' );

function patrios_loop_columns() {
	return 3;
}
add_filter('loop_shop_columns', 'patrios_loop_columns'); // Set Number of rows in Shop

function patrios_document_title_parts($parts){
	if (is_post_type_archive() && function_exists('is_shop') && is_shop()) {
		$parts['title'] = esc_html__('Shop','patrios');
	}
	return $parts;
}
add_filter( 'document_title_parts', 'patrios_document_title_parts', 99 );


/* -------------------------------------------
 * 				Custom body class
 * ------------------------------------------- */
add_filter( 'body_class', 'patrios_body_class' );
function patrios_body_class( $classes ) {
    $layout = get_theme_mod( 'boxfull_en', 'fullwidth' );
    $classes[] = $layout.'-bg';
	return $classes;
}

/* -------------------------------------------
 * 				Logout Redirect Home
 * ------------------------------------------- */
add_action( 'wp_logout', 'patrios_auto_redirect_external_after_logout');
function patrios_auto_redirect_external_after_logout(){
  wp_redirect( home_url('/') );
  exit();
}


/* -------------------------------------------
 *   Add Custom Field To Category Form
 * ------------------------------------------- */

add_action( 'product_cat_add_form_fields', 'product_cat_form_custom_field_add', 10 );
add_action( 'product_cat_edit_form_fields', 'product_cat_form_custom_field_edit', 10, 2 );

function product_cat_form_custom_field_add( $taxonomy ) { ?>
	<div class="form-field">
		<!--Add Icon-->
		<label for="product_cat_custom_order"><?php _e('Select category icon','patrios');?></label>
		<select  id="product_cat_custom_order" name="product_cat_custom_order">
			<?php
				$iconlist = getBacknowIconsList();
				$op = '<option value="%s"%s>%s</option>';
				foreach ($iconlist as $value ) {
					if ($product_cat_custom_order == $value) {
						printf($op, $value, ' selected="selected"', $value);
					} else {
						printf($op, $value, '', $value);
					}
				}
				?>
		</select>
		<p class="description"><?php _e('Add Category Icon','patrios');?></p>

		<!--Color Picker-->
		<label for="product_cat_color_custom_order"><?php _e('Category Color Option','patrios');?></label>
		<input class="patrios-color-picker" name="product_cat_color_custom_order" id="product_cat_color_custom_order" type="text" value="#c9366f" size="40" aria-required="true" />
		<p class="description"><?php _e('Add Category Color','patrios');?></p>

		<!--Subtitle-->
		<label for="product_cat_subtitle_custom_order"><?php _e('Category Sub Title','patrios');?></label>
		<input name="product_cat_subtitle_custom_order" id="product_cat_subtitle_custom_order" type="text" value="" size="40" aria-required="true" />
		<p class="description"><?php _e('Add sub title','patrios');?></p>
	</div>
<?php }

function product_cat_form_custom_field_edit( $tag, $taxonomy ) { ?>
	<!--Add Icon-->
	<tr class="form-field">
		<th scope="row"><label for="product_cat_custom_order"><?php _e('Select category icon','patrios');?></label></th>
		<td>
			<select  id="product_cat_custom_order" name="product_cat_custom_order">
			<?php
			    $option_name = 'product_cat_custom_order_' . $tag->term_id;
			    $product_cat_custom_order = get_option( $option_name );
			    $iconlist = getBacknowIconsList();

				$op = '<option value="%s"%s>%s</option>';

				foreach ($iconlist as $value ) {

					if ($product_cat_custom_order == $value) {
			            printf($op, $value, ' selected="selected"', $value);
			        } else {
			            printf($op, $value, '', $value);
			        }
			    }
				?>
			</select>
		</td>
	</tr>

	<!--Color Picker-->
	<?php
		$option_name = 'product_cat_color_custom_order_' . $tag->term_id;
		$product_cat_color_custom_order = get_option( $option_name );
	?>
	<tr class="form-field">
	  <th scope="row" valign="top"><label for="product_cat_color_custom_order"><?php _e('Category Color Option','patrios');?></label></th>
	  <td>
	    <input class="patrios-color-picker" type="text" name="product_cat_color_custom_order" id="product_cat_color_custom_order" value="<?php echo esc_attr( $product_cat_color_custom_order ) ? esc_attr( $product_cat_color_custom_order ) : ''; ?>" size="40" aria-required="true" />
	     <p class="description"><?php _e('Category Color Option','patrios');?></p>
	  </td>
	</tr>


	<!--Subtitle-->
	<?php
		$option_name = 'product_cat_subtitle_custom_order_' . $tag->term_id;
		$product_cat_subtitle_custom_order = get_option( $option_name );
	?>
	<tr class="form-field">
	  <th scope="row" valign="top"><label for="product_cat_subtitle_custom_order"><?php _e('Category Sub Title','patrios');?></label></th>
	  <td>
	    <input type="text" name="product_cat_subtitle_custom_order" id="product_cat_subtitle_custom_order" value="<?php echo esc_attr( $product_cat_subtitle_custom_order ) ? esc_attr( $product_cat_subtitle_custom_order ) : ''; ?>" size="40" aria-required="true" />
	     <p class="description"><?php _e('Add sub title','patrios');?></p>
	  </td>
	</tr>


<?php }

/** Save Custom Field Of product_cat Form */
add_action( 'created_product_cat', 'product_cat_form_custom_field_save', 10, 2 );
add_action( 'edited_product_cat', 'product_cat_form_custom_field_save', 10, 2 );
function product_cat_form_custom_field_save( $term_id, $tt_id ) {
    if ( isset( $_POST['product_cat_custom_order'] ) ) {
        $option_name = 'product_cat_custom_order_' . $term_id;
        update_option( $option_name, sanitize_text_field( $_POST['product_cat_custom_order'] ) );
    }
	if ( isset( $_POST['product_cat_subtitle_custom_order'] ) ) {
		$option_name = 'product_cat_subtitle_custom_order_' . $term_id;
		update_option( $option_name, sanitize_text_field( $_POST['product_cat_subtitle_custom_order'] ) );
	}
	if ( isset( $_POST['product_cat_color_custom_order'] ) ) {
		$option_name = 'product_cat_color_custom_order_' . $term_id;
		update_option( $option_name, sanitize_text_field( $_POST['product_cat_color_custom_order'] ) );
	}
}


/* -------------------------------------------
 * 				WooCommerce Product Filter
 * ------------------------------------------- */
add_action( 'woocommerce_product_query', 'limit_show_cf_campaign_in_shop' );
function limit_show_cf_campaign_in_shop($wp_query){
	$type = get_theme_mod( 'shop_product', 'allproduct' );
	if( $type == 'without_crowdfunding' ){
		$tax_query = array(
	        array(
	            'taxonomy' 	=> 'product_type',
	            'field'    	=> 'slug',
	            'terms' 	=> array( 'crowdfunding' ),
	            'operator' => 'NOT IN'
	        )
	    );
	    $wp_query->set( 'tax_query', $tax_query );
	}
	if( $type == 'only_crowdfunding' ){
		$tax_query = array(
	        array(
	            'taxonomy' => 'product_type',
	            'field'    => 'slug',
	            'terms' => array( 'crowdfunding' ),
	            'operator' => 'IN'
	        )
	    );
	    $wp_query->set( 'tax_query', $tax_query );
	}
    return $wp_query;
}


/* -------------------------------------------
 *   Love it Action
 * ------------------------------------------- */
add_action( 'wp_ajax_thm_campaign_action','themeum_campaign_action' );
add_action( 'wp_ajax_nopriv_thm_campaign_action', 'themeum_campaign_action' );
function themeum_campaign_action(){
    if ( ! is_user_logged_in()){
        die(json_encode(array('success'=> 0, 'message' => __('Please Sign In first', 'patrios') )));
    }

    $loved_campaign_ids  = array();
    $user_id             = get_current_user_id();
    $campaign_id         = sanitize_text_field($_POST['campaign_id']);
	$prev_campaign_ids   = get_user_meta($user_id, 'loved_campaign_ids', true);
	$postid 			 = get_post_meta( $campaign_id, 'loved_campaign_ids', true );

    if ($prev_campaign_ids){
        $loved_campaign_ids = json_decode( $prev_campaign_ids, true );
	}

    if (in_array($campaign_id, $loved_campaign_ids)){
        if(($key = array_search($campaign_id, $loved_campaign_ids)) !== false) {
            unset( $loved_campaign_ids[$key] );
        }
        $json_update_campaign_ids = json_encode($loved_campaign_ids);
		update_user_meta($user_id, 'loved_campaign_ids', $json_update_campaign_ids);
		if( $postid ){
			$postid = (int)$postid - 1;
			update_post_meta( $campaign_id, 'loved_campaign_ids', $postid );
		}else{
			$postid = 0;
			update_post_meta( $campaign_id, 'loved_campaign_ids', 0 );
		}
		die(json_encode(array('success'=> 1, 'number' => $postid, 'message' => 'delete' )));
    }else{
        $loved_campaign_ids[] = $campaign_id;
		update_user_meta($user_id, 'loved_campaign_ids', json_encode($loved_campaign_ids) );
		if( $postid ){
			$postid = (int)$postid + 1;
			update_post_meta( $campaign_id, 'loved_campaign_ids', $postid );
		}else{
			$postid = 1;
			update_post_meta( $campaign_id, 'loved_campaign_ids', 1 );
		}
        die(json_encode(array('success'=> 1, 'number' => $postid , 'message' => 'love' )));
    }
}

/* -------------------------------------------
*             License for Patrios Theme
* -------------------------------------------- */
require_once( PATRIOS_DIR . '/lib/license/class.patrios-theme-license.php');

/* -------------------------------------------
*        		Licence Code
* ------------------------------------------- */
add_action('admin_menu', 'patrios_options_menu');
if ( ! function_exists('patrios_options_menu')){
  function patrios_options_menu(){
    $personalblog_option_page = add_menu_page('Patrios Options', 'Patrios Options', 'manage_options', 'patrios-options', 'patrios_option_callback');
    add_action('load-'.$personalblog_option_page, 'patrios_option_page_check');
  }
}

function patrios_option_callback(){}
function patrios_option_page_check(){
	global $current_screen;
	if ($current_screen->id === 'toplevel_page_patrios-options'){
		wp_redirect(admin_url('customize.php'));
	}
}
