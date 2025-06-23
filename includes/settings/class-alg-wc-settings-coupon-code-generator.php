<?php
/**
 * Coupon Code Generator for WooCommerce - Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Settings_Coupon_Code_Generator' ) ) :

class Alg_WC_Settings_Coupon_Code_Generator extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {

		$this->id    = 'alg_wc_coupon_code_generator';
		$this->label = __( 'Coupon Code Generator', 'coupon-code-generator-for-woocommerce' );
		parent::__construct();

		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'sanitize_as_textarea' ), PHP_INT_MAX, 3 );

		// Sections
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-coupon-code-generator-settings-section.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-coupon-code-generator-settings-order-coupon.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-coupon-code-generator-settings-auto-coupon.php';

	}

	/**
	 * sanitize_as_textarea.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function sanitize_as_textarea( $value, $option, $raw_value ) {
		return (
			! empty( $option['alg_wc_ccg_sanitize_as_textarea'] ) ?
			wp_kses_post( trim( $raw_value ) ) :
			$value
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge(
			apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ),
			array(
				array(
					'title'     => __( 'Reset Settings', 'coupon-code-generator-for-woocommerce' ),
					'type'      => 'title',
					'id'        => $this->id . '_' . $current_section . '_reset_options',
				),
				array(
					'title'     => __( 'Reset section settings', 'coupon-code-generator-for-woocommerce' ),
					'desc'      => '<strong>' . __( 'Reset', 'coupon-code-generator-for-woocommerce' ) . '</strong>',
					'desc_tip'  => __( 'Check the box and save changes to reset.', 'coupon-code-generator-for-woocommerce' ),
					'id'        => $this->id . '_' . $current_section . '_reset',
					'default'   => 'no',
					'type'      => 'checkbox',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_' . $current_section . '_reset_options',
				),
			)
		);
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.4.0
	 * @since   1.1.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			esc_html__( 'Your settings have been reset.', 'coupon-code-generator-for-woocommerce' ) .
		'</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
		do_action( 'alg_wc_ccg_settings_saved' );
	}

}

endif;

return new Alg_WC_Settings_Coupon_Code_Generator();
