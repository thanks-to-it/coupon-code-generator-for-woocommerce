=== ZILI Coupon Code Generator for WooCommerce ===
Contributors: algoritmika, thankstoit, anbinder, karzin
Tags: woocommerce, coupon, woo commerce
Requires at least: 4.4
Tested up to: 6.8
Stable tag: 2.0.3
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Generate coupons in WooCommerce. Beautifully.

== Description ==

The **ZILI Coupon Code Generator for WooCommerce** plugin lets you enable automatic coupon code generation in WooCommerce.

### ✅ Main Features ###

* **Order Coupon** - generate coupon automatically for the selected order status updates. Coupon code will be automatically attached to the selected customer's emails.
* **Automatic Coupon Code** - generate coupon code automatically when adding new coupon in "Marketing > Coupons > Add coupon".

### 🚀 Automatic Order-Based Coupon Generator (in Emails) for WooCommerce ###

The "ZILI Coupon Code Generator for WooCommerce" plugin is a dynamic tool designed to enhance your WooCommerce store's marketing and customer retention strategies.

This plugin streamlines the process of creating and distributing coupons by automating it based on order statuses.

Tailored to offer a personalized experience, it generates coupons as fixed amounts or percentages, catering to various customer interactions and purchase behaviors.

### 🚀 Automated Coupon Creation Based on Order Status ###

Automatically create coupons triggered by specific order statuses, such as "Order Completed".

This feature allows you to reward customers post-purchase or encourage further engagement, enhancing customer loyalty and satisfaction.

### 🚀 Select Which Emails to Include Coupon In ###

Incorporate coupons into specific customer emails, such as "Order Completed" notifications. This targeted delivery ensures that your coupons are seen and used at critical points in the customer journey, maximizing their impact.

### 🚀 Customizable Coupon Templates - Personalized Design with Dynamic Placeholders ###

Personalize your coupons with a range of placeholders like `%order_id%`, `%order_billing_first_name%`, and `%order_customer_id%`.

This customization ensures that each coupon feels personal to the recipient, increasing its perceived value and effectiveness.

### 🚀 Restrict Coupon Usage by Email ###

Secure your promotional strategies by limiting coupon usage to the email address associated with the billing information of the order. By doing this, you guarantee that your coupons are exclusively used by the intended recipients, enhancing the effectiveness of your campaigns and preventing misusing coupons (or selling them).

### 🚀 Set Spending Limits for Coupon Eligibility ###

Implement minimum and maximum spending thresholds to be able to use the coupons. This way, you can only make coupons work when customers have at at least $100 in cart (or not exceeding specific amount), to ensure the profitability of your promotions.

### 🚀 Advanced Automatic Coupon Code Generator ###

Other than order statuses, you can facilitate effortless coupon creation with advanced options for automatic coupon code generation.

You can automate creating the coupon when adding new coupon in "Marketing > Coupons > Add coupon" page without manual code entry.

Customize the code template using placeholders like `%date_MM%` or `%user_id%`, and define the code style (hash, random letters, numbers, mix) and length.

### 🗘 Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Head to the plugin [GitHub Repository](https://github.com/thanks-to-it/coupon-code-generator-for-woocommerce) to find out how you can pitch in.

### ℹ More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > ZILI Coupon Code Generator".

== Changelog ==

= 2.0.3 - 13/11/2025 =
* Dev - Plugin renamed.

= 2.0.2 - 04/11/2025 =
* Dev - Prefix added to the JavaScript object name (`alg_wc_ccg_ajax_object`).
* Dev - Plugin renamed.
* WC tested up to: 10.3.

= 2.0.1 - 23/10/2025 =
* Dev - Plugin renamed.
* WC tested up to: 10.2.

= 2.0.0 - 23/06/2025 =
* Fix - Emails - Coupon code was not added to email.
* Fix - Translation loading fixed.
* Dev - Automatic Coupon - Algorithm - Moved to the free plugin version.
* Dev - Automatic Coupon - Length - Moved to the free plugin version.
* Dev - Order Coupon - "Create coupons for all orders" tool added.
* Dev - Security - Shortcodes - Output escaped.
* Dev - Admin settings split into sections.
* Dev - Plugin settings moved back to the "WooCommerce > Settings" menu.
* Dev - "Recommendations" removed.
* Dev - "Key Manager" removed.
* Dev - Code refactoring.
* WC tested up to: 9.9.
* Tested up to: 6.8.

= 1.4.2 - 27/12/2024 =
* Dev - Composer - `autoloader-suffix` param added.

= 1.4.1 - 23/12/2024 =
* Fix - Emails - Coupon code was not added to email.

= 1.4.0 - 22/12/2024 =
* Fix - "High-Performance Order Storage (HPOS)" compatibility.
* Fix - Minimum order amount - Checking for equal amounts.
* Dev - Order Coupon - General - Coupon type - "Fixed product discount" option added.
* Dev - Security - Output escaped.
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev - Plugin settings moved to the "WPFactory" menu.
* Dev - "Recommendations" added.
* Dev - "Key Manager" added.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.
* Dev - Coding standards improved.
* WC tested up to: 9.5.
* Tested up to: 6.7.
* Plugin name updated.

= 1.3.4 - 11/09/2024 =
* Dev - Order Coupon - General - "Minimum order amount" option added.

= 1.3.3 - 09/09/2024 =
* Dev - "High-Performance Order Storage (HPOS)" compatibility.
* Dev - Order Coupon - General - "Individual use only" option added.
* Dev - Order Coupon - General - "Product categories" option added.
* Dev - Order Coupon - General - "Limit usage per coupon" option added.
* Dev - Order Coupon - General - "Limit usage per user" option added.
* WC tested up to: 9.2.

= 1.3.2 - 31/07/2024 =
* WC tested up to: 9.1.
* Tested up to: 6.6.
* WooCommerce added to the "Requires Plugins" (plugin header).

= 1.3.1 - 19/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 1.3.0 - 24/11/2022 =
* Dev - The plugin is initialized on the `plugins_loaded` now.
* Dev - Code refactoring.
* WC tested up to: 7.1.
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 1.2.0 - 01/03/2021 =
* Dev - "Order Coupon" section added.
* Dev - Automatic Coupon Code - "Enable section" option added (defaults to `no`).
* Dev - Localisation - `load_plugin_textdomain()` function moved to the `init` action.
* Dev - JS file minified.
* Dev - Admin settings descriptions updated.
* Dev - Code refactoring.
* WC tested up to: 5.0.
* Tested up to: 5.6.

= 1.1.0 - 14/01/2020 =
* Dev - "Template" option added (with `%code%`, `%user_id%`, `%date_YY%`, `%date_MM%`, `%date_DD%` placeholders).
* Dev - Code refactoring.
* Dev - Admin settings descriptions updated.
* Plugin URI updated.
* POT file uploaded.
* WC tested up to: 3.8.
* Tested up to: 5.3.

= 1.0.0 - 01/05/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
