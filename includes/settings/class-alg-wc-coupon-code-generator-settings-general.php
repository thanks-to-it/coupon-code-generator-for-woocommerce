<?php
/**
 * Coupon Code Generator for WooCommerce - General Section Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Coupon_Code_Generator_Settings_General' ) ) :

class Alg_WC_Coupon_Code_Generator_Settings_General extends Alg_WC_Coupon_Code_Generator_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'coupon-code-generator-for-woocommerce' );
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
	 * get_customer_emails.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_customer_emails() {
		$options = array();
		if ( $wc_emails = WC_Emails::instance() ) {
			foreach ( $wc_emails->get_emails() as $email_id => $email ) {
				if ( $email->is_customer_email() ) {
					$options[ $email->id ] = $email->get_title();
				}
			}
		}
		return $options;
	}

	/**
	 * get_product_cats.
	 *
	 * @version 1.4.0
	 * @since   1.3.3
	 */
	function get_product_cats() {
		$product_cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
		return (
			! empty( $product_cats ) && ! is_wp_error( $product_cats ) ?
			wp_list_pluck( $product_cats, 'name', 'term_id' ) :
			array()
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (v2.0.0) separate into sections?
	 * @todo    (dev) `alg_wc_ccg_order_coupon[email_template]`: no `<p>` in default value? (and then maybe apply `wp_autop()`)?
	 * @todo    (dev) `alg_wc_ccg_order_coupon[email_template]`: better default value?
	 * @todo    (desc) `alg_wc_ccg_order_coupon[code_template]`: shortcodes
	 * @todo    (desc) `alg_wc_ccg_order_coupon[emails]`
	 * @todo    (desc) `alg_wc_ccg_order_coupon[order_status]`: better desc?
	 */
	function get_settings() {

		// Order coupon
		$order_coupon_settings = array(
			array(
				'title'             => __( 'Order Coupon Options', 'coupon-code-generator-for-woocommerce' ),
				'desc'              => (
					__( 'This will generate coupon automatically for the selected order status updates.', 'coupon-code-generator-for-woocommerce' ) . ' ' .
					__( 'Coupon code will be automatically attached to the selected customer\'s emails.', 'coupon-code-generator-for-woocommerce' )
				),
				'type'              => 'title',
				'id'                => 'alg_wc_ccg_order_coupon_options',
			),
			array(
				'title'             => __( 'Order coupon', 'coupon-code-generator-for-woocommerce' ),
				'desc'              => '<strong>' . __( 'Enable section', 'coupon-code-generator-for-woocommerce' ) . '</strong>',
				'id'                => 'alg_wc_ccg_order_coupon_enabled',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Order status', 'coupon-code-generator-for-woocommerce' ),
				'desc_tip'          => __( 'Select order status(es) on which coupon code should be created. If you select multiple statuses, coupon will be created only once, on whichever status change occurs first.', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[order_status]',
				'default'           => array( 'wc-completed' ),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => wc_get_order_statuses(),
			),
			array(
				'title'             => __( 'Minimum order amount', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[minimum_order_amount]',
				'desc_tip'          => (
					__( 'Minimum order amount for the coupon to be generated.', 'coupon-code-generator-for-woocommerce' ) . ' ' .
					__( 'Ignored if empty or zero.', 'coupon-code-generator-for-woocommerce' )
				),
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Coupon code template', 'coupon-code-generator-for-woocommerce' ),
				'desc'              => sprintf(
					/* Translators: %s: Placeholder list. */
					__( 'Available placeholders: %s.', 'coupon-code-generator-for-woocommerce' ),
					'<code>' . implode( '</code>, <code>', array(
						'%order_id%',
						'%order_number%',
						'%order_billing_first_name%',
						'%order_billing_first_last%',
						'%order_billing_email%',
						'%order_customer_id%',
					) ) . '</code>'
				),
				'id'                => 'alg_wc_ccg_order_coupon[code_template]',
				'default'           => '%order_billing_email%-%order_id%',
				'type'              => 'text',
				'css'               => 'width:100%;',
			),
			array(
				'title'             => __( 'Coupon type', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[discount_type]',
				'default'           => 'percent',
				'type'              => 'select',
				'class'             => 'chosen_select',
				'options'           => array(
					'percent'       => __( 'Percentage discount', 'coupon-code-generator-for-woocommerce' ),
					'fixed_cart'    => __( 'Fixed cart discount', 'coupon-code-generator-for-woocommerce' ),
					'fixed_product' => __( 'Fixed product discount', 'coupon-code-generator-for-woocommerce' ),
				),
			),
			array(
				'title'             => __( 'Coupon amount', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[coupon_amount]',
				'default'           => 10,
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Individual use only', 'coupon-code-generator-for-woocommerce' ),
				'desc'              => __( 'Enable', 'coupon-code-generator-for-woocommerce' ),
				'desc_tip'          => __( 'Coupon cannot be used in conjunction with other coupons.', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[individual_use]',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Product categories', 'coupon-code-generator-for-woocommerce' ),
				'desc_tip'          => (
					__( 'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'coupon-code-generator-for-woocommerce' ) . ' ' .
					__( 'Ignored if empty.', 'coupon-code-generator-for-woocommerce' )
				),
				'id'                => 'alg_wc_ccg_order_coupon[product_categories]',
				'default'           => array(),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => $this->get_product_cats(),
			),
			array(
				'title'             => __( 'Usage limit per coupon', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[usage_limit]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 1 ),
			),
			array(
				'title'             => __( 'Usage limit per user', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[usage_limit_per_user]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 1 ),
			),
			array(
				'title'             => __( 'Allowed emails', 'coupon-code-generator-for-woocommerce' ),
				'desc'              => __( 'Enable', 'coupon-code-generator-for-woocommerce' ),
				'desc_tip'          => __( 'Set coupon\'s "Allowed emails" option to order\'s billing email.', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[allowed_emails]',
				'default'           => 'yes',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Minimum spend', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[minimum_amount]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Maximum spend', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[maximum_amount]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Emails', 'coupon-code-generator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[emails]',
				'default'           => array( 'customer_completed_order' ),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => $this->get_customer_emails(),
			),
			array(
				'desc'              => (
					__( 'Email template', 'coupon-code-generator-for-woocommerce' ) . '<br>' .
					sprintf(
						/* Translators: %s: Placeholder list. */
						__( 'Available placeholders: %s.', 'coupon-code-generator-for-woocommerce' ),
						'<code>' . implode( '</code>, <code>', array(
							'%coupon_code%',
						) ) . '</code>'
					)
				),
				'id'                => 'alg_wc_ccg_order_coupon[email_template]',
				'default'           => (
					'<p>' .
						sprintf(
							/* Translators: %s: Coupon code. */
							__( 'Here is a coupon for your next purchase: %s', 'coupon-code-generator-for-woocommerce' ),
							'<code>%coupon_code%</code>'
						) .
					'</p>'
				),
				'type'              => 'textarea',
				'css'               => 'width:100%;',
			),
			array(
				'type'              => 'sectionend',
				'id'                => 'alg_wc_ccg_order_coupon_options',
			),
		);

		// Automatic coupon code
		$auto_coupon_code_settings = array(
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
				'desc_tip'                        => __( 'Length value will be ignored if set above the maximum length for selected algorithm. Set to zero to use full length for selected algorithm.', 'coupon-code-generator-for-woocommerce' ),
				'id'                              => 'alg_wc_ccg_auto_coupon_code[length]',
				'default'                         => 0,
				'type'                            => 'number',
			),
			array(
				'type'                            => 'sectionend',
				'id'                              => 'alg_wc_ccg_auto_coupon_code_options',
			),
		);

		return array_merge(
			$order_coupon_settings,
			$auto_coupon_code_settings
		);
	}

}

endif;

return new Alg_WC_Coupon_Code_Generator_Settings_General();
