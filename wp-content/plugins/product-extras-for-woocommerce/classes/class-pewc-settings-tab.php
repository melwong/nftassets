<?php
/**
 * Class to create Product Add-Ons tab in Settings
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'PEWC_Settings_Tab' ) ) {

	class PEWC_Settings_Tab {

		public function __construct() {
		}

		public function init() {
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
			add_action( 'woocommerce_settings_tabs_pewc', array( $this, 'settings_tab' ) );
			add_action( 'woocommerce_update_options_pewc', array( $this, 'update_settings' ) );

			add_action( 'woocommerce_admin_field_pewc_license_key', array( $this, 'licence_key' ) );
			// Action links
			add_filter( 'plugin_action_links_product-extras-for-woocommerce/product-extras-for-woocommerce.php', array( $this, 'action_links' ) );
			add_action( 'admin_menu', array( $this, 'upgrade_submenu' ) );
		}

		public static function add_settings_tab( $settings_tabs ) {
			$settings_tabs['pewc'] = __( 'Product Add-Ons', 'pewc' );
			return $settings_tabs;
		}

		public function settings_tab() {
			woocommerce_admin_fields( $this->get_settings() );
		}

		public function update_settings() {
			woocommerce_update_options( $this->get_settings() );
		}

		public function get_settings() {
			$settings = array(
				'section_title' => array(
					'name'     => __( 'WooCommerce Product Add-Ons Ultimate', 'pewc' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'pewc_settings_title'
				),
				'pewc_license_key' => array(
					'name'			=> __( 'License key', 'pewc' ),
					'type'			=> 'pewc_license_key',
					'desc_tip'	=> true,
					'desc'			=> __( 'Enter your license key here. You should have received a key with the email containing the plugin download link.', 'pewc' ),
					'id'				=> 'pewc_license_key',
					'default'		=> '',
				),
				'section_end' => array(
					'type' => 'sectionend',
					'id' => 'pewc_settings_title'
				),

				'general_section_title' => array(
					'name'     => __( 'General', 'pewc' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'pewc_general_title'
				),
				'pewc_require_log_in' => array(
					'name'		=> __( 'Users must be logged in to upload', 'pewc' ),
					'type'		=> 'checkbox',
					'desc_tip'	=> true,
					'desc'		=> __( 'For security reasons, it is strongly recommended that you require users to be logged in before allowing them to upload files.', 'pewc' ),
					'id'		=> 'pewc_require_log_in',
					'default'	=> 'no',
					'std'		=> 'no'
				),
				'pewc_max_upload' => array(
					'name'		=> __( 'Max file size (MB)', 'pewc' ),
					'type'		=> 'number',
					'desc_tip'	=> true,
					'desc'		=> __( 'The max file size for uploads (in MB)', 'pewc' ),
					'id'		=> 'pewc_max_upload',
					'default'	=> '1',
				),
				'pewc_email_images' => array(
					'name'		=> __( 'Attach uploads to emails', 'pewc' ),
					'type'		=> 'checkbox',
					'desc_tip'	=> true,
					'desc'		=> __( 'Add uploaded images to new order emails.', 'pewc' ),
					'id'		=> 'pewc_email_images',
					'default'	=> 'no',
					'std'		=> 'no'
				),
				'pewc_enable_dropzonejs' => array(
					'name'		=> __( 'Enable AJAX uploader', 'pewc' ),
					'type'		=> 'checkbox',
					'desc_tip'	=> true,
					'desc'		=> __( 'Add uploaded images via AJAX.', 'pewc' ),
					'id'		=> 'pewc_enable_dropzonejs',
					'default'	=> 'no',
					'std'		=> 'no'
				),
				'pewc_hide_zero' => array(
					'name'			=> __( 'Hide zero prices', 'pewc' ),
					'type'			=> 'checkbox',
					'desc_tip'	=> true,
					'desc'			=> __( 'Hide prices in the cart for extras which don\'t have a cost.', 'pewc' ),
					'id'				=> 'pewc_hide_zero',
					'default'		=> 'no',
					'std'				=> 'no'
				),
				'pewc_ignore_tax' => array(
					'name'			=> __( 'Ignore tax setting', 'pewc' ),
					'type'			=> 'checkbox',
					'desc_tip'	=> true,
					'desc'			=> __( 'Ignore the WooCommerce "Display prices in the shop" setting which determines whether prices are displaying including or excluding tax.', 'pewc' ),
					'id'				=> 'pewc_ignore_tax',
					'default'		=> 'no',
					'std'				=> 'no'
				),
				'general_section_end' => array(
					'type' => 'sectionend',
					'id' => 'pewc_general_title'
				),

				'labels_section_title' => array(
					'name'     => __( 'Labels', 'pewc' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'pewc_labels_title'
				),
				// Price labelling
				'pewc_price_label' => array(
					'name'			=> __( 'Price label', 'pewc' ),
					'type'			=> 'text',
					'desc_tip'	=> true,
					'desc'			=> __( 'Additional or replacement text for the price', 'pewc' ),
					'id'				=> 'pewc_price_label'
				),
				'pewc_price_display' => array(
					'name'			=> __( 'Price label display', 'pewc' ),
					'type'			=> 'select',
					'desc_tip'	=> true,
					'desc'			=> __( 'Decide where to display the label', 'pewc' ),
					'id'				=> 'pewc_price_display',
					'default'		=> 'before',
					'std'				=> 'before',
					'options'     => array(
						'before'			=> __( 'Before price', 'pewc' ),
						'after'				=> __( 'After price', 'pewc' ),
						'hide'				=> __( 'Hide price', 'pewc' )
					)
				),
				// Subtotals
				'pewc_show_totals' => array(
					'name'			=> __( 'Display totals fields', 'pewc' ),
					'type'			=> 'select',
					'desc_tip'	=> true,
					'desc'			=> __( 'Decide how to display totals fields on product pages', 'pewc' ),
					'id'				=> 'pewc_show_totals',
					'default'		=> 'all',
					'std'				=> 'all',
					'options'     => array(
		        'all'           => __( 'Show totals', 'woocommerce' ),
		        'none'          => __( 'Hide totals', 'woocommerce' ),
		        'total'    			=> __( 'Total only', 'woocommerce' ),
		      ),
				),
				'pewc_product_total_label' => array(
					'name'			=> __( 'Product total label', 'pewc' ),
					'type'			=> 'text',
					'desc_tip'	=> true,
					'desc'			=> __( 'The label for the Product total', 'pewc' ),
					'id'				=> 'pewc_product_total_label',
					'default'		=> __( 'Product total', 'pewc' ),
				),
				'pewc_options_total_label' => array(
					'name'			=> __( 'Options total label', 'pewc' ),
					'type'			=> 'text',
					'desc_tip'	=> true,
					'desc'			=> __( 'The label for the Options total', 'pewc' ),
					'id'				=> 'pewc_options_total_label',
					'default'		=> __( 'Options total', 'pewc' ),
				),
				'pewc_flatrate_total_label' => array(
					'name'			=> __( 'Flat rate total label', 'pewc' ),
					'type'			=> 'text',
					'desc_tip'	=> true,
					'desc'			=> __( 'The label for the Flat rate total', 'pewc' ),
					'id'				=> 'pewc_flatrate_total_label',
					'default'		=> __( 'Flat rate total', 'pewc' ),
				),
				'pewc_grand_total_label' => array(
					'name'			=> __( 'Grand total label', 'pewc' ),
					'type'			=> 'text',
					'desc_tip'	=> true,
					'desc'			=> __( 'The label for the Grand total', 'pewc' ),
					'id'				=> 'pewc_grand_total_label',
					'default'		=> __( 'Grand total', 'pewc' ),
				),
				'labels_section_end' => array(
					'type' => 'sectionend',
					'id' => 'pewc_labels_title'
				),

				'calculations_section_title' => array(
					'name'     => __( 'Calculations', 'pewc' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'pewc_calculations_title'
				),
				'pewc_variable_1' => array(
					'name'			=> __( 'Variable 1', 'pewc' ),
					'type'			=> 'number',
					'desc_tip'	=> true,
					'desc'			=> __( 'Enter a value for variable_1 that will be used in calculations', 'pewc' ),
					'id'				=> 'pewc_variable_1',
					'custom_attributes'	=> array(
						'step'		=> 0.01
					),
					'default'		=> '',
				),
				'pewc_variable_2' => array(
					'name'			=> __( 'Variable 2', 'pewc' ),
					'type'			=> 'number',
					'desc_tip'	=> true,
					'desc'			=> __( 'Enter a value for variable_2 that will be used in calculations', 'pewc' ),
					'id'				=> 'pewc_variable_2',
					'default'		=> '',
				),
				'pewc_variable_3' => array(
					'name'			=> __( 'Variable 3', 'pewc' ),
					'type'			=> 'number',
					'desc_tip'	=> true,
					'desc'			=> __( 'Enter a value for variable_3 that will be used in calculations', 'pewc' ),
					'id'				=> 'pewc_variable_3',
					'default'		=> '',
				),
				'calculations_section_end' => array(
					'type' => 'sectionend',
					'id' => 'pewc_calculations_title'
				),

				'beta_section_title' => array(
					'name'     => __( 'Beta', 'pewc' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => 'pewc_beta_title'
				),
				'pewc_beta_testing' => array(
					'name'			=> __( 'Beta testing', 'pewc' ),
					'type'			=> 'checkbox',
					'desc_tip'	=> true,
					'desc'			=> __( 'Opt in to beta testing the plugin. You should only choose this option on a staging or development site - don\'t enable this on your live site.', 'pewc' ),
					'id'				=> 'pewc_beta_testing',
					'default'		=> 'no',
					'std'				=> 'no'
				),
				'beta_section_end' => array(
					'type' => 'sectionend',
					'id' => 'pewc_beta_title'
				),

			);
			return apply_filters( 'pewc_filter_settings', $settings );
		}

		public function action_links( $links ) {
			if( ! pewc_is_pro() ) {
				$url = pewc_get_upgrade_url();
				$links['upgrade'] = sprintf(
					'<a target="_blank" href="%s">%s</a>',
					esc_url( $url ),
					__( 'Upgrade', 'pewc' )
				);
			}
			return $links;
		}

		public function upgrade_submenu() {
	    global $submenu;
	    $submenu['edit.php?post_type=pewc_product_extra'][] = array( __( 'Upgrade', 'pewc' ), 'manage_plugins', pewc_get_upgrade_url() );
		}

		/**
		 * Custom setting for EDD SL licence key
		 */
		public function licence_key() {
			$key = get_option( 'pewc_license_key' ); ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Licence key', 'pewc' ); ?>
				</th>
				<td class="forminp forminp-text">
					<input name="pewc_license_key" id="pewc_license_key" type="text" style="" value="<?php echo $key; ?>" class="" placeholder="">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Status', 'pewc' ); ?>
				</th>
				<td class="forminp forminp-text">
					<?php $status = ( false !== get_option( 'pewc_license_status' ) ) ? get_option( 'pewc_license_status' ) : 'invalid';
					if( $status == 'valid' ) {
						echo '<span class="dashicons dashicons-yes"></span>&nbsp;';
					} else {
						echo '<span class="dashicons dashicons-no-alt"></span>&nbsp;';
					}
					echo ucfirst( $status ); ?>
				</td>
				<?php if( $status == 'valid' ) { ?>
					<tr>
						<th scope="row" class="titledesc">
							<?php _e( 'Action', 'pewc' ); ?>
						</th>
						<td class="forminp forminp-text">
							<?php printf(
								'<p><button type="submit" name="pewc_deactivate_license_key" class="button button-secondary">%s</button></p>',
								__( 'Deactivate this licence', 'pewc' )
							); ?>
						</td>
					</tr>
				<?php } else if( $status == 'deactivated' ) { ?>
					<tr>
						<th scope="row" class="titledesc">
							<?php _e( 'Action', 'pewc' ); ?>
						</th>
						<td class="forminp forminp-text">
							<?php printf(
								'<p><button type="submit" name="pewc_activate_license_key" class="button button-secondary">%s</button></p>',
								__( 'Activate this licence', 'pewc' )
							); ?>
						</td>
					</tr>
				<?php } ?>
			</tr>
			<?php
			wp_nonce_field( 'pewc_license_key_nonce', 'pewc_license_key_nonce' );
		}

	}
	$PEWC_Settings_Tab = new PEWC_Settings_Tab;
	$PEWC_Settings_Tab->init();
}
