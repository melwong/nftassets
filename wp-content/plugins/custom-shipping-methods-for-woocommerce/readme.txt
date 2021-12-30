=== Custom Shipping Methods for WooCommerce ===
Contributors: algoritmika, anbinder
Tags: woocommerce, shipping, custom shipping, woo commerce
Requires at least: 4.4
Tested up to: 5.3
Stable tag: 1.5.3
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add custom shipping methods to WooCommerce.

== Description ==

**Custom Shipping Methods for WooCommerce** plugin lets you add custom shipping methods to WooCommerce.

For each custom shipping method you can optionally set **min/max cart cost/weight/volume/quantity** method **availability** options.

In addition to standard WooCommerce **cost calculation** shortcodes (`[qty]`, `[cost]`, `[fee]`) you can also use **`[weight]`** and **`[volume]`** shortcodes.

[Custom Shipping Methods for WooCommerce Pro](https://wpfactory.com/item/custom-shipping-methods-for-woocommerce/) plugin version also has:

* **`[costs_table]`** shortcode for **table rate shipping**,
* **`[distance]`** shortcode for **distance based cost calculation**,
* options to set **free shipping minimum order amount**,
* options to set **min and max cost limits**,
* options to set custom shipping methods' frontend **icons** and **descriptions**.

= Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/custom-shipping-methods-for-woocommerce/).

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Custom Shipping Methods".

== Changelog ==

= 1.5.3 - 11/12/2019 =
* Dev - Shortcodes - `[distance]` - Caching results now.
* Dev - Code refactoring.

= 1.5.2 - 06/12/2019 =
* Dev - Availability - "Min distance" and "Max distance" options added.
* Dev - Advanced - "Custom return URL" option added.
* Dev - Shortcodes - `[costs_table]` - Evaluating math expressions in costs now.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.

= 1.5.1 - 13/11/2019 =
* Dev - Shortcodes - `[distance]` - Returning `default_distance` at once when source or destination address is empty.
* WC tested up to: 3.8.
* Tested up to: 5.3.

= 1.5.0 - 23/10/2019 =
* Feature - Shortcodes - `[distance]` - Rounding attributes added (`rounding` and `rounding_precision`).
* Feature - Shortcodes - `[distance]` - Min and max distance attributes added (`min_distance` and `max_distance`).
* Dev - Code refactoring.

= 1.4.3 - 03/10/2019 =
* Dev - General - Frontend Settings - Add to zero cost - Admin settings descriptions updated.
* WC tested up to: 3.7.

= 1.4.2 - 14/06/2019 =
* Dev - Shipping class costs - "Limits calculation" option added.
* Dev - Admin settings descriptions updated ("Free shipping min amount").
* Dev - Minor code refactoring.

= 1.4.1 - 25/05/2019 =
* Dev - General - Frontend Settings - Trigger checkout update - Now triggering checkout update only on `billing_` and `shipping_` input change.
* Dev - General - Frontend Settings - Add to zero cost - Admin settings descriptions updated.

= 1.4.0 - 24/05/2019 =
* Feature - Shortcodes - `[costs_table]` - `table_format` attribute added (defaults to `min`; other possible value: `range`) (and `default_cost` attribute added).
* Feature - Shortcodes - `[distance]` - Miles (`mi`) option added to the `units` attribute (same applies to `[costs_table prop="distance" ...]`).
* Dev - General - Frontend Settings - "Trigger checkout update" option added.
* Dev - Shortcodes - `[distance]` - `default` attribute renamed to `default_distance`.
* Dev - `WC_Shipping_Alg_Custom` - `alg_wc_custom_shipping_methods_add_rate` filter added.
* Dev - `WC_Shipping_Alg_Custom` - `evaluate_cost()` function visibility changed from `protected` to `public`.
* WC tested up to: 3.6.
* Tested up to: 5.2.

= 1.3.1 - 09/02/2019 =
* Feature - `[costs_table]` - `cost` property added (i.e. `[costs_table prop="cost" ...]`).
* Feature - "Free shipping minimum order amount" option added.
* Dev - `[costs_table]` - Code refactoring.
* Dev - Admin settings restyled.

= 1.3.0 - 03/12/2018 =
* Feature - "Min cost limit" and "Max cost limit" options added.
* Feature - `[distance]` shortcode added.
* Feature - `distance` `prop` added to the `[costs_table]` shortcode (i.e. `[costs_table prop="distance"]`).
* Dev - Method settings descriptions updated.
* Dev - `alg_wc_custom_shipping_methods_evaluate_cost_sum` and `alg_wc_custom_shipping_methods_evaluate_cost_sum_evaluated` filters added.

= 1.2.1 - 14/11/2018 =
* Feature - "Replace zero cost" options added.
* Dev - Code refactoring.

= 1.2.0 - 18/09/2018 =
* Feature - "Method icon" and "Method description" options added.
* Fix - `[costs_table]` shortcode fixed.
* Dev - Admin settings restyled.

= 1.1.0 - 14/09/2018 =
* Feature - Availability - "Min cost", "Max cost", "Min volume", "Max volume", "Min quantity", "Max quantity" options added.
* Fix - Core - Checking if product has dimensions before calling `get_height()`, `get_width()` and `get_length()` in `get_products_volume()`.
* Fix - Core - Checking if product has weight before calling `get_weight()` in `get_products_weight()`.
* Dev - Default "Admin title" and "Method title" values updated.
* Dev - Minor admin settings restyling.
* Dev - Code refactoring.
* Dev - POT file added.
* Dev - Plugin description in readme.txt updated.
* Dev - Plugin URI updated.

= 1.0.0 - 09/05/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
