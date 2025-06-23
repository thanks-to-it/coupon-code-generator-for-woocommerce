<?php
/**
 * Coupon Code Generator for WooCommerce - Auto Coupon Section Settings
 *
 * @version 2.0.0
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Coupon_Code_Generator_Settings_Auto_Coupon' ) ) :

class Alg_WC_Coupon_Code_Generator_Settings_Auto_Coupon extends Alg_WC_Coupon_Code_Generator_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id   = 'auto_coupon';
		$this->desc = __( 'Auto Coupon', 'coupon-code-generator-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_length_desc.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_length_desc( $length ) {
		return ' (' . sprintf(
			/* Translators: %d: Length. */
			__( 'length %d', 'coupon-code-generator-for-woocommerce' ),
			$length
		) . ')';
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_settings() {
		return array(
			array(
				'title'                           => __( 'Automatic Coupon Code Options', 'coupon-code-generator-for-woocommerce' ),
				'desc'                            => sprintf(
					/* Translators: %s: Add coupon link. */
					__( 'This will generate coupon code automatically when adding new coupon in %s.', 'coupon-code-generator-for-woocommerce' ),
					'<a href="' . admin_url( 'edit.php?post_type=shop_coupon' ) . '">' .
						__( 'Marketing > Coupons > Add coupon', 'coupon-code-generator-for-woocommerce' ) .
					'</a>'
				),
				'type'                            => 'title',
				'id'                              => 'alg_wc_ccg_auto_coupon_code_options',
			),
			array(
				'title'                           => __( 'Automatic coupon code', 'coupon-code-generator-for-woocommerce' ),
				'desc'                            => '<strong>' . __( 'Enable section', 'coupon-code-generator-for-woocommerce' ) . '</strong>',
				'id'                              => 'alg_wc_ccg_auto_coupon_code_enabled',
				'default'                         => 'no',
				'type'                            => 'checkbox',
			),
			array(
				'title'                           => __( 'Template', 'coupon-code-generator-for-woocommerce' ),
				'desc'                            => sprintf(
					/* Translators: %s: Placeholder list. */
					__( 'Placeholders: %s.', 'coupon-code-generator-for-woocommerce' ),
					'<code>' . implode( '</code>, <code>', array(
						'%code%',
						'%user_id%',
						'%date_YY%',
						'%date_MM%',
						'%date_DD%',
					) ) . '</code>'
				),
				'id'                              => 'alg_wc_ccg_auto_coupon_code[template]',
				'default'                         => '%code%',
				'type'                            => 'text',
				'css'                             => 'width:100%;',
				'alg_wc_ccg_sanitize_as_textarea' => true,
			),
			array(
				'title'                           => __( 'Algorithm', 'coupon-code-generator-for-woocommerce' ),
				'id'                              => 'alg_wc_ccg_auto_coupon_code[algorithm]',
				'default'                         => 'crc32',
				'type'                            => 'select',
				'class'                           => 'chosen_select',
				'options'                         => array(
					'crc32'                      => __( 'Hash', 'coupon-code-generator-for-woocommerce' ) . ': ' . 'crc32'      . $this->get_length_desc( 8 ),
					'md5'                        => __( 'Hash', 'coupon-code-generator-for-woocommerce' ) . ': ' . 'md5'        . $this->get_length_desc( 32 ),
					'sha1'                       => __( 'Hash', 'coupon-code-generator-for-woocommerce' ) . ': ' . 'sha1'       . $this->get_length_desc( 40 ),
					'random_letters_and_numbers' => __( 'Random letters and numbers', 'coupon-code-generator-for-woocommerce' ) . $this->get_length_desc( 32 ),
					'random_letters'             => __( 'Random letters', 'coupon-code-generator-for-woocommerce' )             . $this->get_length_desc( 32 ),
					'random_numbers'             => __( 'Random numbers', 'coupon-code-generator-for-woocommerce' )             . $this->get_length_desc( 32 ),
				),
			),
			array(
				'title'                           => __( 'Length', 'coupon-code-generator-for-woocommerce' ),
				'desc_tip'                        => __( 'The length value will be ignored if it exceeds the maximum length for the selected algorithm. Set to zero to use the full length for the selected algorithm.', 'coupon-code-generator-for-woocommerce' ),
				'id'                              => 'alg_wc_ccg_auto_coupon_code[length]',
				'default'                         => 0,
				'type'                            => 'number',
			),
			array(
				'type'                            => 'sectionend',
				'id'                              => 'alg_wc_ccg_auto_coupon_code_options',
			),
		);
	}

}

endif;

return new Alg_WC_Coupon_Code_Generator_Settings_Auto_Coupon();
