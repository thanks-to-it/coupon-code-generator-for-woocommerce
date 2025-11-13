<?php
/*
Plugin Name: ZILI Coupon Code Generator for WooCommerce
Plugin URI: https://wordpress.org/plugins/zili-coupon-code-generator-for-woocommerce/
Description: Generate coupons in WooCommerce. Beautifully.
Version: 2.0.3
Author: Algoritmika Ltd
Author URI: https://algoritmika.com
Requires at least: 4.4
Text Domain: zili-coupon-code-generator-for-woocommerce
Domain Path: /langs
WC tested up to: 10.3
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

if ( 'coupon-code-generator-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 */
	$plugin = 'coupon-code-generator-for-woocommerce-pro/coupon-code-generator-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		(
			is_multisite() &&
			array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
		)
	) {
		defined( 'ALG_WC_COUPON_CODE_GENERATOR_FILE_FREE' ) || define( 'ALG_WC_COUPON_CODE_GENERATOR_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_COUPON_CODE_GENERATOR_VERSION' ) || define( 'ALG_WC_COUPON_CODE_GENERATOR_VERSION', '2.0.3' );

defined( 'ALG_WC_COUPON_CODE_GENERATOR_FILE' ) || define( 'ALG_WC_COUPON_CODE_GENERATOR_FILE', __FILE__ );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-coupon-code-generator.php';

if ( ! function_exists( 'alg_wc_ccg' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Coupon_Code_Generator to prevent the need to use globals.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function alg_wc_ccg() {
		return Alg_WC_Coupon_Code_Generator::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_ccg' );
