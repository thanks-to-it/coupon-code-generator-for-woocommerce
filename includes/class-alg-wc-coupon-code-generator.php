<?php
/**
 * Coupon Code Generator for WooCommerce - Main Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Coupon_Code_Generator' ) ) :

final class Alg_WC_Coupon_Code_Generator {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WC_COUPON_CODE_GENERATOR_VERSION;

	/**
	 * core.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $core;

	/**
	 * @var   Alg_WC_Coupon_Code_Generator The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Coupon_Code_Generator Instance.
	 *
	 * Ensures only one instance of Alg_WC_Coupon_Code_Generator is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_Coupon_Code_Generator - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Coupon_Code_Generator Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @access  public
	 *
	 * @todo    (v2.0.0) rename the plugin to e.g., "Automatic Order-Based Coupon Generator for WooCommerce"?
	 */
	function __construct() {

		// Check for the active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'coupon-code-generator-for-woocommerce-pro.php' === basename( ALG_WC_COUPON_CODE_GENERATOR_FILE ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'pro/class-alg-wc-coupon-code-generator-pro.php';
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.3.0
	 * @since   1.2.0
	 */
	function localize() {
		load_plugin_textdomain(
			'coupon-code-generator-for-woocommerce',
			false,
			dirname( plugin_basename( ALG_WC_COUPON_CODE_GENERATOR_FILE ) ) . '/langs/'
		);
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 1.4.0
	 * @since   1.3.3
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = (
				defined( 'ALG_WC_COUPON_CODE_GENERATOR_FILE_FREE' ) ?
				array( ALG_WC_COUPON_CODE_GENERATOR_FILE, ALG_WC_COUPON_CODE_GENERATOR_FILE_FREE ) :
				array( ALG_WC_COUPON_CODE_GENERATOR_FILE )
			);
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
					'custom_order_tables',
					$file,
					true
				);
			}
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-coupon-code-generator-core.php';
	}

	/**
	 * admin.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function admin() {

		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_COUPON_CODE_GENERATOR_FILE ), array( $this, 'action_links' ) );

		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

		// Version update
		if ( get_option( 'alg_wc_ccg_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();

		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_coupon_code_generator' ) . '">' .
			__( 'Settings', 'coupon-code-generator-for-woocommerce' ) .
		'</a>';

		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Coupon Code Generator settings tab to WooCommerce settings.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-settings-coupon-code-generator.php';
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( 'alg_wc_ccg_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_COUPON_CODE_GENERATOR_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_COUPON_CODE_GENERATOR_FILE ) );
	}

}

endif;
