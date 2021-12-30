<?php
/**
 * Custom Shipping Methods for WooCommerce - Custom Shipping Class
 *
 * @version 1.5.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Shipping_Alg_Custom' ) ) :

class WC_Shipping_Alg_Custom extends WC_Shipping_Method {

	/** @var string cost passed to [fee] shortcode */
	protected $fee_cost = '';

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   int $instance_id
	 * @todo    [feature] add free shipping **coupon** functionality
	 */
	function __construct( $instance_id = 0 ) {
		$this->id                    = 'alg_wc_shipping';
		$this->instance_id           = absint( $instance_id );
		$this->method_title          = get_option( 'alg_wc_custom_shipping_methods_admin_title', __( 'Custom shipping', 'custom-shipping-methods-for-woocommerce' ) );
		$this->method_description    = __( 'Custom shipping method.', 'custom-shipping-methods-for-woocommerce' );
		$this->supports              = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->init();

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * init user set variables.
	 *
	 * @version 1.5.2
	 * @since   1.0.0
	 * @todo    [feature] customizable admin title and description (i.e. per method instance)
	 */
	function init() {
		$this->instance_form_fields     = include( 'settings/settings-custom-shipping.php' );
		$this->title                    = $this->get_option( 'title' );
		$this->alg_wc_csm_icon          = $this->get_option( 'alg_wc_csm_icon' );
		$this->alg_wc_csm_desc          = $this->get_option( 'alg_wc_csm_desc' );
		$this->tax_status               = $this->get_option( 'tax_status' );
		$this->cost                     = $this->get_option( 'cost' );
		$this->min_cost_limit           = $this->get_option( 'min_cost_limit' );
		$this->max_cost_limit           = $this->get_option( 'max_cost_limit' );
		$this->free_shipping_min_amount = $this->get_option( 'free_shipping_min_amount', 0 );
		$this->type                     = $this->get_option( 'type', 'class' );
		$this->min_cost                 = $this->get_option( 'min_cost' );
		$this->max_cost                 = $this->get_option( 'max_cost' );
		$this->min_weight               = $this->get_option( 'min_weight' );
		$this->max_weight               = $this->get_option( 'max_weight' );
		$this->min_volume               = $this->get_option( 'min_volume' );
		$this->max_volume               = $this->get_option( 'max_volume' );
		$this->min_qty                  = $this->get_option( 'min_qty' );
		$this->max_qty                  = $this->get_option( 'max_qty' );
		$this->min_distance             = $this->get_option( 'min_distance' );
		$this->max_distance             = $this->get_option( 'max_distance' );
		$this->distance_calculation     = $this->get_option( 'distance_calculation' );
		$this->limit_calc               = $this->get_option( 'limit_calc', 'class' );
		$this->return_url               = $this->get_option( 'return_url', '' );
	}

	/**
	 * is this method available?
	 *
	 * @version 1.5.2
	 * @since   1.0.0
	 * @param   array $package
	 * @return  bool
	 */
	function is_available( $package ) {
		$available = parent::is_available( $package );
		if ( $available ) {
			$conditions = array( 'cost', 'weight', 'volume', 'qty', 'distance' );
			foreach ( $conditions as $condition ) {
				$min = 'min_' . $condition;
				$max = 'max_' . $condition;
				if ( 0 != $this->{$min} || 0 != $this->{$max} ) {
					switch ( $condition ) {
						case 'cost':
							$total = $package['contents_cost'];
							break;
						case 'weight':
							$total = alg_wc_custom_shipping_methods()->core->get_package_item_weight( $package );
							break;
						case 'volume':
							$total = alg_wc_custom_shipping_methods()->core->get_package_item_volume( $package );
							break;
						case 'qty':
							$total = $this->get_package_item_qty( $package );
							break;
						case 'distance':
							add_shortcode( 'distance', array( alg_wc_custom_shipping_methods()->core, 'distance' ) );
							$total = do_shortcode( $this->distance_calculation );
							break;
						default:
							$total = 0;
					}
					if ( ( 0 != $this->{$min} && $total < $this->{$min} ) || ( 0 != $this->{$max} && $total > $this->{$max} ) ) {
						return false;
					}
				}
			}
		}
		return $available;
	}

	/**
	 * evaluate a cost from a sum/string.
	 *
	 * @version 1.4.2
	 * @since   1.0.0
	 * @param   string $sum
	 * @param   array  $args
	 * @return  string
	 */
	function evaluate_cost( $sum, $args = array() ) {
		include_once( WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php' );

		// Allow 3rd parties to process shipping cost arguments
		$args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
		$this->fee_cost = $args['cost'];

		do_action( 'alg_wc_custom_shipping_methods_evaluate_cost_args', $args );

		// Expand shortcodes
		add_shortcode( 'fee', array( $this, 'fee' ) );

		foreach ( apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_shortcodes', array() ) as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}

		$replaced_values = apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_replace', array(
			'[qty]'  => $args['qty'],
			'[cost]' => $args['cost'],
		), $args );

		$sum = do_shortcode( str_replace( array_keys( $replaced_values ), $replaced_values, $sum ) );

		remove_shortcode( 'fee', array( $this, 'fee' ) );

		foreach ( apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_shortcodes', array() ) as $shortcode => $function ) {
			remove_shortcode( $shortcode, $function );
		}

		// Remove whitespace from string
		$sum = preg_replace( '/\s+/', '', $sum );

		// Remove locale from string
		$sum = str_replace( $decimals, '.', $sum );

		// Trim invalid start/end characters
		$sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

		// Filter
		$sum = apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_sum', $sum );

		// Do the math
		if ( $sum ) {

			$sum = apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_sum_evaluated', WC_Eval_Math::evaluate( $sum ) );

			// Limits
			if ( in_array( $this->limit_calc, array( 'class', 'all' ) ) ) {
				$sum = apply_filters( 'alg_wc_custom_shipping_methods_option', $sum, 'min_max_limits',
					array( 'min' => $this->min_cost_limit, 'max' => $this->max_cost_limit ) );
			}

			// Free shipping
			$sum = apply_filters( 'alg_wc_custom_shipping_methods_option', $sum, 'free_shipping_min_amount',
				array( 'free_shipping_min_amount' => $this->free_shipping_min_amount, 'package_cost' => $args['cost'] ) );
		}

		// Return
		return $sum ? $sum : 0;
	}

	/**
	 * work out fee (shortcode).
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  string
	 */
	function fee( $atts ) {
		$atts = shortcode_atts( array(
			'percent' => '',
			'min_fee' => '',
			'max_fee' => '',
		), $atts, 'fee' );

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}

	/**
	 * calculate_shipping function.
	 *
	 * @version 1.4.2
	 * @since   1.0.0
	 * @param   array $package (default: array())
	 * @todo    [feature] add "Free shipping calculation" option: "per class" (i.e. per package, as it is now), "per order" (i.e. total sum) and maybe "all"
	 */
	function calculate_shipping( $package = array() ) {
		$rate = array(
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => 0,
			'package' => $package,
		);

		// Calculate the costs
		$has_costs = false; // True when a cost is set. False if all costs are blank strings.
		$cost      = $this->get_option( 'cost' );

		if ( '' !== $cost ) {
			$has_costs    = true;
			$rate['cost'] = $this->evaluate_cost( $cost, apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_args_package', array(
				'qty'  => $this->get_package_item_qty( $package ),
				'cost' => $package['contents_cost'],
			), $package ) );
		}

		// Add shipping class costs.
		$shipping_classes = WC()->shipping->get_shipping_classes();

		if ( ! empty( $shipping_classes ) ) {
			$found_shipping_classes = $this->find_shipping_classes( $package );
			$highest_class_cost     = 0;

			foreach ( $found_shipping_classes as $shipping_class => $products ) {
				// Also handles BW compatibility when slugs were used instead of ids
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

				if ( '' === $class_cost_string ) {
					continue;
				}

				$has_costs  = true;
				$class_cost = $this->evaluate_cost( $class_cost_string, apply_filters( 'alg_wc_custom_shipping_methods_evaluate_cost_args_class', array(
					'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
					'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
				), $products ) );

				if ( 'class' === $this->type ) {
					$rate['cost'] += $class_cost;
				} else {
					$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
				}
			}

			if ( 'order' === $this->type && $highest_class_cost ) {
				$rate['cost'] += $highest_class_cost;
			}
		}

		// Limits
		if ( in_array( $this->limit_calc, array( 'order', 'all' ) ) ) {
			$rate['cost'] = apply_filters( 'alg_wc_custom_shipping_methods_option', $rate['cost'], 'min_max_limits',
				array( 'min' => $this->min_cost_limit, 'max' => $this->max_cost_limit ) );
		}

		// Add the rate
		if ( $has_costs ) {
			$this->add_rate( apply_filters( 'alg_wc_custom_shipping_methods_add_rate', $rate, $package, $this ) );
		}

		/**
		 * Developers can add additional rates based on this one via this action
		 *
		 * This example shows how you can add an extra rate based on this rate via custom function:
		 *
		 * 		add_action( 'woocommerce_alg_wc_shipping_shipping_add_rate', 'add_another_custom_rate', 10, 2 );
		 *
		 * 		function add_another_custom_rate( $method, $rate ) {
		 * 			$new_rate          = $rate;
		 * 			$new_rate['id']    .= ':' . 'custom_rate_name'; // Append a custom ID.
		 * 			$new_rate['label'] = 'Rushed Shipping'; // Rename to 'Rushed Shipping'.
		 * 			$new_rate['cost']  += 2; // Add $2 to the cost.
		 *
		 * 			// Add it to WC.
		 * 			$method->add_rate( $new_rate );
		 * 		}.
		 */
		do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate );
	}

	/**
	 * get items in package.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $package
	 * @return  int
	 */
	function get_package_item_qty( $package ) {
		$total_quantity = 0;
		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$total_quantity += $values['quantity'];
			}
		}
		return $total_quantity;
	}

	/**
	 * finds and returns shipping classes and the products with said class.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   mixed $package
	 * @return  array
	 */
	function find_shipping_classes( $package ) {
		$found_shipping_classes = array();

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = array();
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

}

endif;
