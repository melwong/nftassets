=== WooCommerce Product Add-Ons Ultimate ===
Contributors: Gareth Harris
Tags: add-ons, ecommerce
Requires at least: 4.7
Tested up to: 5.2.3
Stable tag: 3.2.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Allow your users to customise products through additional fields

== Description ==

WooCommerce Product Add Ons Ultimate allows your users to customise products through additional fields.

== Installation ==
1. Upload the `product-extras-for-woocommerce` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start adding Product Add-Ons in your WooCommerce products

== Frequently Asked Questions ==


== Screenshots ==

1.

== To Do List ==
* edit in cart
* fields summary panel
* steps layout for groups - do one, next one appears, continue button, breadcrumb menu
* customizer - enhancements to layout and style options
* quantity repeater
* finalise groups post type
* pricing table
* change product images
* change variation image in single column layout when variation is selected
* table layout for child products
* allow add-ons at cart and checkout
* importing fields via CSV
* include product ID in child product ID attr
* add option to specify image swatch size
* placeholder text
* filter child products (e.g. by tag)
* conditions on groups
* user role specific groups?
* tooltips not showing on new products
* Save Extras button so you don't need to update the product to save
* new pot file
* tidy up validation
* exclude products from globals / list global groups that a product belongs to
* font field
* WPML currency switcher

== Changelog ==

= 3.2.16, 24 September 2019 =
* Added: pewc_hidden_group_types_in_order filter
* Updated: trigger calculations on page load
* Updated: allow calculations without input fields

= 3.2.15, 23 September 2019 =
* Added: $value parameter to pewc_filter_end_add_cart_item_data filter

= 3.2.14, 18 September 2019 =
* Added: $cart_item_data and $quantity parameters to pewc_get_conditional_field_visibility
* Added: conditions based on quantity
* Added: pewc_after_option_params action
* Added: multiple filters for AJAX file upload strings
* Fixed: correctly respect conditions based on products

= 3.2.13, 7 September 2019 =
* Fixed: pewc_filter_end_add_cart_item_data filter
* Fixed: child product checkbox layout respects discounts
* Fixed: strip slashes from text fields

= 3.2.12, 29 August 2019 =
* Added: pewc_filter_end_add_cart_item_data filter
* Fixed: information fields not displaying correctly for Basic licences

= 3.2.11, 20 August 2019 =
* Added: pewc_filter_child_products_method filter
* Fixed: incorrectly validating required upload fields

= 3.2.10, 17 August 2019 =
* Added: pewc_option_price_separator filter
* Added: additional parameters for pewc_filter_minchars_validation_notice and pewc_filter_minchars_validation_notice filters
* Fixed: allow multiple ajax uploads fields per product
* Fixed: min / max char validation only on required fields

= 3.2.9, 2 August 2019 =
* Fixed: JS error on upload fields

= 3.2.8, 1 August 2019 =
* Added: increased number of columns for image swatches
* Added: pewc_total_only_text filter
* Added: pewc_after_create_product_extra action
* Added: additional parameters for pewc_filter_validation_notice
* Fixed: respecting conditions based on products fields
* Fixed: media upload fields in group post types
* Fixed: respecting min and max chars in textareas
* Fixed: show min/max for new checkbox fields

= 3.2.7, 5 July 2019 =
* Fixed: checkbox swatches not toggling class
* Updated: extended pewc_is_group_public filter to all field types with options

= 3.2.6, 5 July 2019 =
* Added: filter to hide prices in options
* Updated: respect percentage setting for select field options
* Updated: greater than and less than operators for numeric field conditions

= 3.2.5, 3 July 2019 =
* Fixed: issues with conditionals for calculation fields

= 3.2.4, 1 July 2019 =
* Fixed: issues with AJAX uploads

= 3.2.3, 28 June 2019 =
* Fixed: Tabs and Accordion layout

= 3.2.2, 28 June 2019 =
* Fixed: JS error when dropzone.js not enqueued
* Fixed: JS error when formula missing in calculation field

= 3.2.1, 28 June 2019 =
* Added: AJAX upload option
* Fixed: allow multiple file uploads
* Fixed: global information fields not saving correctly
* Fixed: default radio button value not set
* Updated: reduce size of image thumb in order email

= 3.2.0, 24 June 2019 =
* Added: swatch option to variable child products
* Added: information field type
* Added: allow multiple uploads setting
* Fixed: escape condition fields with apostrophes
* Fixed: conditional field visibility not correctly evaluating on add to cart
* Updated: conditionally enqueue math.js

= 3.1.2, 13 June 2019 =
* Fixed: checkboxes in global groups not saving correctly

= 3.1.1, 13 June 2019 =
* Added: group layout option
* Fixed: clear product price when no variation set
* Updated: cost and action settings for calculation field
* Updated: exclude upload fields from conditions

= 3.1.0, 10 June 2019 =
* Added: calculation field

= 3.0.2, 8 June 2019 =
* Added: hide groups where all fields are hidden
* Added: option to attach uploaded images to order email
* Fixed: missing select_placeholder parameter
* Fixed: options in global conditions not populating correctly
* Fixed: incorrectly removing uploaded images
* Fixed: duplicated group and field conditions
* Fixed: default values not displaying correctly
* Updated: restored duplicate global groups
* Updated: reinstated allow_multiple parameter
* Updated: don't check character fields for non-text fields
* Updated: timing on initial page load for pewc_update_total_js

= 3.0.1, 4 June 2019 =
* Fixed: global groups not deleting correctly

= 3.0.0, 3 June 2019 =
* Added: allow html in group description
* Added: further front end template filters
* Added: pewc_flat_rate_label filter
* Fixed: checkbox group field values persisting in fields
* Fixed: image swatch prices not added
* Fixed: parse errors in field-item.php
* Fixed: parse error in field description
* Fixed: missing cost value in condition
* Fixed: JS error when setting condition rule fields
* Fixed: condition cost operator not setting correctly
* Fixed: removing conditions incorrectly hiding condition rules
* Fixed: checkbox default value not retained correctly
* Fixed: repeat pewc_update_total_js after running to help quicker browsers
* Updated: Pro fields visible to Basic users
* Updated: populate pewc_product_extra post with order details when customer is not registered
* Updated: CSS for globals page
* Updated: default total for variable products set to 0
* Updated: uploads no longer moved to media folder
* Updated: migrated product extras data to custom post type

= 2.8.6, 29 May 2019 =
* Added: updater upgrade functions

= 2.8.5, 21 May 2019 =
* Added: beta testing option
* Fixed: reinstated child product functions lost due to version control
* Fixed: zero value number field not validating correctly

= 2.8.4, 10 May 2019 =
* Fixed: hidden child products added to cart
* Updated: POT file and Dutch translation

= 2.8.3, 7 May 2019 =
* Fixed: correctly enqueue pewc-variations.js script

= 2.8.2, 6 May 2019 =
* Updated: changed plugin name to WooCommerce Product Add-Ons Ultimate

= 2.8.2, 3 May 2019 =
* Fixed: removed field price from Products field type
* Fixed: spaces and accented characters counted incorrectly
* Updated: deprecated Allow Multiple option from Products field

= 2.8.1, 1 May 2019 =
* Added: pewc_force_update_total_js trigger to JS
* Fixed: inactive variation specific fields updating price on product page
* Fixed: incorrect validation on hidden product fields with min/max products
* Updated: allow separate flat rate charges for variations
* Updated: reduced length of field ID string

= 2.8.0, 18 April 2019 =
* Added: product cost conditions
* Added: filter for multiple file uploads
* Fixed: default values not setting correctly
* Fixed: condition rules not saving correctly

= 2.7.0, 16 April 2019 =
* Added: minimum and maximum quantities for child product fields
* Fixed: variation prices not updating correctly
* Updated: additional methods for pewc-child-quantity-field field updates

= 2.6.1, 11 April 2019 =
* Updated: allow independent child products to be deleted in the cart
* Updated: allow independent child products quantities to be updated in the cart

= 2.6.0, 9 April 2019 =
* Added: column layout for child products
* Added: support for variable child products
* Fixed: parse error in global settings
* Updated: removed AJAX totals updater in pewc.js

= 2.5.1, 5 April 2019 =
* Fixed: mini cart returning zero price for products without extras

= 2.5.0, 4 April 2019 =
* Added: variation-specific fields
* Fixed: restrict per character pricing to text and textarea fields only
* Fixed: update product price in mini cart

= 2.4.12, 28 March 2019 =
* Added: allow conditions on checkbox groups and product fields
* Fixed: duplicate options for conditions

= 2.4.11, 17 March 2019 =
* Added: display upload thumbs in cart and checkout
* Fixed: conditional fields dependent on checkboxes not saving correctly
* Fixed: flat rate input fields not appearing in order confirmation
* Updated: disabled autocomplete for datepicker fields

= 2.4.10, 4 March 2019 =
* Fixed: conditions for radio groups not firing correctly

= 2.4.9, 21 February 2019 =
* Fixed: condition values getting overwritten

= 2.4.8, 19 February 2019 =
* Fixed: parse error when adding variable child product to cart

= 2.4.7, 16 February 2019 =
* Updated: licensing after site migration

= 2.4.6, 13 February 2019 =
* Updated: provide support for non-image uploads

= 2.4.5, 13 February 2019 =
* Added: better sanitisation for fields
* Added: key element for radio fields
* Fixed: remove child product from cart when parent quantity set to 0
* Fixed: new condition fields not retaining action and rule settings
* Fixed: pewc_get_permitted_mimes filter

= 2.4.4, 25 January 2019 =
* Fixed: changed permitted mime element to 'jpg|jpeg|jpe'	=> 'image/jpeg'
* Updated: removed simple products requirement from json_search in Products field

= 2.4.3, 21 January 2019 =
* Added: actions after each field
* Added: checkbox option for swatch field
* Added: pewc_name_your_price_step filter for Name Your Price field
* Fixed: missing checkbox group items in order screens
* Fixed: parse error in functions-conditionals.php
* Fixed: default values overriding submitted values
* Updated: field description now runs off pewc_after_field_template hook
* Updated: changed name of Radio Image to Image Swatch

= 2.4.2, 9 January 2019 =
* Added: pewc_filter_item_start_list filter
* Fixed: re-allow negative values for fields
* Fixed: parse error on missing placeholder in field-item.php
* Fixed: NaN error on child products with zero value

= 2.4.1, 24 December 2018 =
* Fixed: missing <li> tags in checkbox group
* Updated: change hook for creating new product extra to woocommerce_checkout_order_processed

= 2.4.0, 16 December 2018 =
* Added: German translation
* Added: customizer support
* Added: pricing and subtotal labels and options

= 2.3.2, 11 December 2018 =
* Fixed: conditionals dependent on radio groups not adding to cart correctly
* Fixed: undefined variable in global extras
* Fixed: added space between attributes in front end form fields

= 2.3.1, 27 November 2018 =
* Fixed: new global groups not saving correctly
* Fixed: removed esc_html from field names containing formatted prices

= 2.3.0, 22 November 2018 =
* Added: checkbox groups
* Added: products field in global extras
* Fixed: respect tax settings for product prices
* Fixed: respect tax settings for option prices
* Fixed: correctly calculate totals when using percentage fields
* Fixed: conditions dependent on checkboxes now functioning correctly
* Updated: formatted option prices
* Updated: changed pewc_get_price_for_display to pewc_maybe_include_tax
* Updated: percentage values for variations update dynamically
* Updated: removed pewc_filter_field_label filter to display percentage instead of price

= 2.2.3, 20 November 2018 =
* Fixed: global condition not retaining field from other group
* Updated: tweaked styles for default parameter in new fields

= 2.2.2, 13 November 2018 =
* Added: explanatory text in Product Extras page
* Added: explanatory text in Product Add-Ons page
* Fixed: removed escaping characters from field and group titles
* Fixed: global conditions not picking up fields from other groups
* Fixed: PHP error for missing pewc_product_hash
* Fixed: prevent order without Product Add-Ons generating a new product extra post
* Updated: changed dashicon to plus-alt
* Updated: changed post type label to 'Extras by Order'

= 2.2.1, 6 November 2018 =
* Fixed: prevent 'View Product' button displaying for products that don't have extras
* Updated: French, Italian and Spanish translations

= 2.2.0, 1 November 2018 =
* Added: child products (Pro only)
* Added: tooltips
* Fixed: validation for radio and select fields
* Fixed: 0 default values
* Fixed: missing prices for extras in order confirmation
* Fixed: hide flat rate items in product itemisation in order confirmation
* Fixed: min_date_today field not saving correctly
* Updated: improved price formatting for extras
* Updated: extra prices now respect the WooCommerce tax display setting
* Updated: improved UX for conditionals
* Updated: updated UI
* Updated: changed icon to wcicon-plus
* Updated: removed pewc_filter_is_purchasable and replaced with pewc_view_product_button

= 2.1.8, 31 October 2018 =
* Fixed: date field not validating correctly

= 2.1.7, 29 October 2018 =
* Fixed: Name Your Price field not validating correctly

= 2.1.6, 29 October 2018 =
* Fixed: Name Your Price field not validating correctly
* Fixed: select and radio fields not validating correctly

= 2.1.5, 22 October 2018 =
* Fixed: admin styles for select fields

= 2.1.4, 21 October 2018 =
* Added: 'Instruction only' option for select fields
* Fixed: field image in Global Add-Ons
* Fixed: radio button prices not updating correctly in totals

= 2.1.3, 18 October 2018 =
* Added: integration with WooCommerce PDF Invoices & Packing Slips
* Fixed: missing colon in order confirmation and emails
* Fixed: radio image buttons displaying arrays as labels

= 2.1.2, 30 September 2018 =
* Added: Dutch translation
* Fixed: flat rate pricing in radio buttons
* Fixed: retain field values after validation fails
* Updated: allow HTML in Description field

= 2.1.1, 27 September 2018 =
* Added: conditions for global extras
* Fixed: prevent non-object error in functions-order.php for empty $user object
* Fixed: add correct flat rate values for select and radio button fields
* Fixed: values of select fields not getting added to cart
* Updated: improved conditional field population using JS

= 2.1.0, 18 September 2018 =
* Added: allow free characters (Pro only)
* Added: only allow alphanumeric characters (Pro only)
* Added: only charge for alphanumeric characters (Pro only)
* Fixed: duplicated pewc-field-label class
* Fixed: correctly save Price Per Character value for new fields
* Updated: deprecated import feature
* Updated: text and textarea field templates

= 2.0.1, 13 September 2018 =
* Fixed: out of memory error in import-groups.php

= 2.0.0, 10 September 2018 =
* Added: Radio buttons with image backgrounds (Pro only)
* Added: Percentages (Pro only)
* Added: Group toggles and tabs (Pro only)
* Added: French translation
* Added: Italian translation
* Added: Spanish translation
* Added: upgrade action links
* Fixed: incorrect default value in text fields following a select or radio field
* Fixed: new condition field not showing select options
* Updated: better detection of radio button selection
* Updated: admin templates moved to templates/admin
* Updated: created separate template files for all field types on the frontend
* Updated: pewc_field_label returns value instead of echoing
* Updated: pewc_field_description returns value instead of echoing
* Updated: removed pewc-product-extra-group-wrap class in favour of pewc-group-wrap

= 1.7.4, 15 August 2018 =
* Added: Portuguese translation
* Added: WooCommerce Subscriptions support
* Fixed: formatting issue for 'Duplicate' link in Products table
* Updated: ensure pewc_product_extra_fields only runs once
* Updated: displays extra fields on all product types

= 1.7.3, 15 August 2018 =
* Fixed: radio button conditionals triggering duplicated fields
* Updated: add pewc-has-maxchars class correctly to fields

= 1.7.2, 14 August 2018 =
* Added: field images
* Added: filterable classes for group wrap div
* Added: prevent users entering more than the max chars for input fields
* Fixed: parse errors in empty field values
* Updated: .pot file

= 1.7.1, 2 August 2018 =
* Fixed: undefined qty for products without quantity selector

= 1.7.0, 1 August 2018 =
* Added: flat rate extras
* Fixed: total calculation error with right space currency position
* Fixed: global extras not showing on products with no local extras
* Updated: improved totals fields on product page

= 1.6.1, 30 July 2018 =
* Added: multiplier option on number fields
* Fixed: global extra rules

= 1.6.0, 30 July 2018 =
* Added: global extras
* Fixed: remove deleted conditions from front end
* Fixed: display options group for new radio and select fields

= 1.5.3, 21 June 2018 =
* Added: modal image viewer in Product Extras entries
* Added: modal image viewer in Product Add-Ons entries
* Fixed: deleting product extra group data on save
* Updated: set create_posts capability for pewc post type to do_not_allow

= 1.5.2, 14 May 2018 =
* Fixed: prices for multiple fields of the same type not totalling correctly

= 1.5.1, 3 May 2018 =
* Added: support for WooCommerce Print Invoices/Packing Lists

= 1.5.0, 27 April 2018 =
* Added: radio button group
* Added: default values
* Added: span wrapper for prices in cart meta data
* Added: discount pricing - select extras to reduce the product cost
* Fixed: too many parameters for pewc_order_item_name
* Updated: spaces no longer costed in cost per character fields

= 1.4.5, 6 April 2018 =
* Added: filter for Total heading on single product page
* Added: upload URLs in order meta
* Fixed: hidden required uploads forcing validation to fail

= 1.4.4, 6 April 2018 =
* Added: product extra line item meta on edit order screen

= 1.4.3, 4 April 2018 =
* Added: added pewc-description to description fields
* Added: permitted file type at add to cart validation
* Fixed: overwriting line items in Product Extras custom post type
* Fixed: overwriting line items in Product Add-Ons custom post type

= 1.4.2, 15 March 2018 =
* Updated: wrap order item prices in span tags

= 1.4.1, 20 February 2018 =
* Fixed: incorrectly adding variation price to cart
* Fixed: parse error for empty conditional
* Fixed: incorrectly priced file uploads

= 1.4.0, 9 February 2018 =
* Added: support for variable products
* Updated: default pewc_require_log_in set to no
* Updated: moved log in requirement to upload fields, not all fields

= 1.3.3, 22 January 2018 =
* Fixed: set product price in cart via woocommerce_add_cart_item
* Updated: improved integration with Bookings

= 1.3.2, 19 January 2018 =
* Added: added per_unit field for new fields

= 1.3.1, 17 January 2018 =
* Updated: improved Bookings for WooCommerce integration

= 1.3.0, 17 January 2018 =
* Added: support for Bookings for WooCommerce plugin

= 1.2.4, 16 January 2018 =
* Fixed: correctly remove associated conditions when field is deleted
* Updated: product name for updater

= 1.2.3, 22 November 2017 =
* Added: Price per character option for text input and textarea fields
* Updated: subtotal calculated directly in JS, not via AJAX
* Updated: allow Product Extras on simple products only
* Updated: allow Product Add-Ons on simple products only

= 1.2.2, 21 November 2017 =
* Added: Name Your Price field
* Added: min and max attributes for number fields
* Fixed: missing ID attribute in new field type fields

= 1.2.1, 13 November 2017 =
* Added: total field on product page
* Fixed: parse error condition_action
* Fixed: not adding hidden items to cart
* Updated: 'is-not' parameter not allowed for conditions on checkboxes

= 1.2.0, 8 November 2017 =
* Added: group and field duplication
* Updated: icon font to WooCommerce
* Updated: updater class

= 1.1.0, 6 November 2017 =
* Added: conditional fields

= 1.0.1, 14 October 2017 =
* Fixed: removed duplicate updater class

= 1.0.0, 14 October 2017 =
* Initial commit

== Upgrade Notice ==
