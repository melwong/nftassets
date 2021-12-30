=== Shipping Icons and Descriptions for WooCommerce ===
Contributors: wpdesignduo
Tags: woocommerce, shipping, shipping icons, shipping descriptions, shipping icon, shipping description
Requires at least: 4.4
Tested up to: 5.2
Stable tag: 2.1.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add frontend icons and/or descriptions for WooCommerce shipping methods.

== Description ==

**Shipping Icons and Descriptions for WooCommerce** plugin lets you add **icons** (i.e. images) and/or **descriptions** (simple text or HTML) to WooCommerce shipping methods on frontend.

Icons and descriptions can be added to both **default (i.e. standard) and custom** WooCommerce shipping methods.

You can choose descriptions and icons **positions on frontend** (before or after label).

For shipping icons you can also set icon **HTML style and class** etc.

Shipping Icons and Descriptions for WooCommerce plugin is **WPML** compatible (i.e. you can set different descriptions and/or icons for different languages). We suggest using included `[alg_wc_sid_translate]` shortcode.

= Pro Version =

[Shipping Icons and Descriptions for WooCommerce Pro](https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/) allows you to use to use **shipping methods instances** instead of shipping methods. For example if you need to set different descriptions for different instances of Flat rate (or any other) shipping method (either in different or in same shipping zone).

Pro version also allows you to set descriptions and/or icons **site visibility** (on both cart and checkout pages, on cart page only, on checkout page only).

= Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Please visit [Shipping Icons and Descriptions for WooCommerce plugin page](https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/).

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Shipping Icons and Descriptions".

== Changelog ==

= 2.1.0 - 08/11/2019 =
* Dev - Code refactoring.
* Plugin author changed.
* WC tested up to: 3.8.

= 2.0.4 - 25/07/2019 =
* Dev - Descriptions - "Order details" option added.
* Dev - Code refactoring.

= 2.0.3 - 20/06/2019 =
* WC tested up to: 3.6.
* Tested up to: 5.2

= 2.0.2 - 04/02/2019 =
* Dev - `alg_wc_shipping_icons_descs_get_value` filter added.
* Dev - `alg_wc_shipping_icons_descs_shipping_methods` filter added.

= 2.0.1 - 29/01/2019 =
* Dev - Admin settings descriptions updated.

= 2.0.0 - 28/10/2018 =
* Dev - "Apply shortcodes" options added (and `[alg_wc_sid_translate]` shortcode added).
* Dev - "Use shipping instances" options added.
* Fix - Icons - ID attribute in `<img>` tag now includes shipping method's instance ID.
* Dev - Icons - "Icon HTML class" option added.
* Dev - Icons - "Separator" option added.
* Dev - Descriptions - "Description position" option added.
* Dev - Major admin settings restyling (split into separate "Icons" and "Descriptions" sections; "General" section removed; settings descriptions updated etc.).
* Dev - Major code refactoring (`version_updated()` function added; settings array saved as main class property; `is_visible()` function refactored etc.).
* Dev - Plugin URI updated.

= 1.1.1 - 17/11/2017 =
* Dev - WooCommerce v3.2.0 compatibility - Admin settings - `select` type options display fixed (by adding `wc-enhanced-select` class).
* Dev - Admin settings - Minor restyling.

= 1.1.0 - 18/06/2017 =
* Dev - Autoloading plugin's options.
* Dev - Minor code refactoring.
* Dev - Using `custom_textarea` instead of `textarea` in plugin's settings.
* Dev - Plugin settings descriptions updated.
* Dev - Plugin header ("Text Domain" etc.) updated.
* Dev - Plugin link updated from <a href="http://coder.fm">http://coder.fm</a> to <a href="https://wpcodefactory.com">https://wpcodefactory.com</a>.

= 1.0.0 - 20/02/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
