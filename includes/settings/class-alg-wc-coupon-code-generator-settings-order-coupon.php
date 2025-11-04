<?php
/**
 * Order Coupon Automator for WooCommerce - Order Coupon Section Settings
 *
 * @version 2.0.2
 * @since   2.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Coupon_Code_Generator_Settings_Order_Coupon' ) ) :

class Alg_WC_Coupon_Code_Generator_Settings_Order_Coupon extends Alg_WC_Coupon_Code_Generator_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.2
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'Order Coupon', 'order-coupon-automator-for-woocommerce' );
		parent::__construct();
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
	 * @version 2.0.2
	 * @since   2.0.0
	 *
	 * @todo    (dev) `alg_wc_ccg_order_coupon[email_template]`: no `<p>` in default value? (and then maybe apply `wp_autop()`)?
	 * @todo    (dev) `alg_wc_ccg_order_coupon[email_template]`: better default value?
	 * @todo    (desc) `alg_wc_ccg_order_coupon[code_template]`: shortcodes
	 * @todo    (desc) `alg_wc_ccg_order_coupon[emails]`
	 * @todo    (desc) `alg_wc_ccg_order_coupon[order_status]`: better desc?
	 */
	function get_settings() {

		$settings = array(
			array(
				'title'             => __( 'Order Coupon Options', 'order-coupon-automator-for-woocommerce' ),
				'desc'              => (
					__( 'This will generate coupon automatically for the selected order status updates.', 'order-coupon-automator-for-woocommerce' ) . ' ' .
					__( 'Coupon code will be automatically attached to the selected customer\'s emails.', 'order-coupon-automator-for-woocommerce' )
				),
				'type'              => 'title',
				'id'                => 'alg_wc_ccg_order_coupon_options',
			),
			array(
				'title'             => __( 'Order coupon', 'order-coupon-automator-for-woocommerce' ),
				'desc'              => '<strong>' . __( 'Enable section', 'order-coupon-automator-for-woocommerce' ) . '</strong>',
				'id'                => 'alg_wc_ccg_order_coupon_enabled',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Order status', 'order-coupon-automator-for-woocommerce' ),
				'desc_tip'          => __( 'Select order status(es) on which coupon code should be created. If you select multiple statuses, coupon will be created only once, on whichever status change occurs first.', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[order_status]',
				'default'           => array( 'wc-completed' ),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => wc_get_order_statuses(),
			),
			array(
				'title'             => __( 'Minimum order amount', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[minimum_order_amount]',
				'desc_tip'          => (
					__( 'Minimum order amount for the coupon to be generated.', 'order-coupon-automator-for-woocommerce' ) . ' ' .
					__( 'Ignored if empty or zero.', 'order-coupon-automator-for-woocommerce' )
				),
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Coupon code template', 'order-coupon-automator-for-woocommerce' ),
				'desc'              => sprintf(
					/* Translators: %s: Placeholder list. */
					__( 'Available placeholders: %s.', 'order-coupon-automator-for-woocommerce' ),
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
				'title'             => __( 'Coupon type', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[discount_type]',
				'default'           => 'percent',
				'type'              => 'select',
				'class'             => 'chosen_select',
				'options'           => array(
					'percent'       => __( 'Percentage discount', 'order-coupon-automator-for-woocommerce' ),
					'fixed_cart'    => __( 'Fixed cart discount', 'order-coupon-automator-for-woocommerce' ),
					'fixed_product' => __( 'Fixed product discount', 'order-coupon-automator-for-woocommerce' ),
				),
			),
			array(
				'title'             => __( 'Coupon amount', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[coupon_amount]',
				'default'           => 10,
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Individual use only', 'order-coupon-automator-for-woocommerce' ),
				'desc'              => __( 'Enable', 'order-coupon-automator-for-woocommerce' ),
				'desc_tip'          => __( 'Coupon cannot be used in conjunction with other coupons.', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[individual_use]',
				'default'           => 'no',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Product categories', 'order-coupon-automator-for-woocommerce' ),
				'desc_tip'          => (
					__( 'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'order-coupon-automator-for-woocommerce' ) . ' ' .
					__( 'Ignored if empty.', 'order-coupon-automator-for-woocommerce' )
				),
				'id'                => 'alg_wc_ccg_order_coupon[product_categories]',
				'default'           => array(),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => $this->get_product_cats(),
			),
			array(
				'title'             => __( 'Usage limit per coupon', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[usage_limit]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 1 ),
			),
			array(
				'title'             => __( 'Usage limit per user', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[usage_limit_per_user]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 1 ),
			),
			array(
				'title'             => __( 'Allowed emails', 'order-coupon-automator-for-woocommerce' ),
				'desc'              => __( 'Enable', 'order-coupon-automator-for-woocommerce' ),
				'desc_tip'          => __( 'Set coupon\'s "Allowed emails" option to order\'s billing email.', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[allowed_emails]',
				'default'           => 'yes',
				'type'              => 'checkbox',
			),
			array(
				'title'             => __( 'Minimum spend', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[minimum_amount]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Maximum spend', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[maximum_amount]',
				'default'           => '',
				'type'              => 'number',
				'custom_attributes' => array( 'min' => 0, 'step' => 0.000001 ),
			),
			array(
				'title'             => __( 'Emails', 'order-coupon-automator-for-woocommerce' ),
				'id'                => 'alg_wc_ccg_order_coupon[emails]',
				'default'           => array( 'customer_completed_order' ),
				'type'              => 'multiselect',
				'class'             => 'chosen_select',
				'options'           => $this->get_customer_emails(),
			),
			array(
				'desc'              => (
					__( 'Email template', 'order-coupon-automator-for-woocommerce' ) . '<br>' .
					sprintf(
						/* Translators: %s: Placeholder list. */
						__( 'Available placeholders: %s.', 'order-coupon-automator-for-woocommerce' ),
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
							__( 'Here is a coupon for your next purchase: %s', 'order-coupon-automator-for-woocommerce' ),
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

		if ( 'yes' === get_option( 'alg_wc_ccg_order_coupon_enabled', 'no' ) ) {
			$settings = array_merge( $settings, array(
				array(
					'title'    => __( 'Tools', 'order-coupon-automator-for-woocommerce' ),
					'type'     => 'title',
					'id'       => 'alg_wc_ccg_order_coupon_tools',
				),
				array(
					'title'    => __( 'Create coupons for all orders', 'order-coupon-automator-for-woocommerce' ),
					'desc'     => __( 'Create', 'order-coupon-automator-for-woocommerce' ),
					'desc_tip' => __( 'Check the box and save changes to run the tool.', 'order-coupon-automator-for-woocommerce' ),
					'id'       => 'alg_wc_ccg_order_coupon_tool_all_orders',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'alg_wc_ccg_order_coupon_tools',
				),
			) );
		}

		return $settings;
	}

}

endif;

return new Alg_WC_Coupon_Code_Generator_Settings_Order_Coupon();
