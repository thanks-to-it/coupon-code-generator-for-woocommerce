<?php
/**
 * Smart Coupon Generator for WooCommerce - Shortcodes Class
 *
 * @version 2.0.0
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Coupon_Code_Generator_Shortcodes' ) ) :

class Alg_WC_Coupon_Code_Generator_Shortcodes {

	/**
	 * order.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $order;

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) add more shortcodes, e.g., `[alg_wc_ccg_order_date]`
	 * @todo    (feature) add "general" shortcodes, i.e., `[alg_wc_ccg_order_func]` and `[alg_wc_ccg_order_meta]`
	 */
	function __construct() {
		add_shortcode( 'alg_wc_ccg_order_id',                 array( $this, 'order_id' ) );
		add_shortcode( 'alg_wc_ccg_order_number',             array( $this, 'order_number' ) );
		add_shortcode( 'alg_wc_ccg_order_billing_first_name', array( $this, 'order_billing_first_name' ) );
		add_shortcode( 'alg_wc_ccg_order_billing_last_name',  array( $this, 'order_billing_last_name' ) );
		add_shortcode( 'alg_wc_ccg_order_billing_email',      array( $this, 'order_billing_email' ) );
		add_shortcode( 'alg_wc_ccg_order_customer_id',        array( $this, 'order_customer_id' ) );
	}

	/**
	 * order_customer_id.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function order_customer_id( $atts, $content = '' ) {
		return ( $this->order ? $this->output( $this->order->get_customer_id(), $atts ) : '' );
	}

	/**
	 * order_billing_email.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function order_billing_email( $atts, $content = '' ) {
		return ( $this->order ? $this->output( $this->order->get_billing_email(), $atts ) : '' );
	}

	/**
	 * order_billing_last_name.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function order_billing_last_name( $atts, $content = '' ) {
		return ( $this->order ? $this->output( $this->order->get_billing_last_name(), $atts ) : '' );
	}

	/**
	 * order_billing_first_name.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function order_billing_first_name( $atts, $content = '' ) {
		return ( $this->order ? $this->output( $this->order->get_billing_first_name(), $atts ) : '' );
	}

	/**
	 * order_number.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function order_number( $atts, $content = '' ) {
		return ( $this->order ? $this->output( $this->order->get_order_number(), $atts ) : '' );
	}

	/**
	 * order_id.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function order_id( $atts, $content = '' ) {
		return ( $this->order ? $this->output( $this->order->get_id(), $atts ) : '' );
	}

	/**
	 * output.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 */
	function output( $content, $atts ) {
		return (
			$content ?
			(
				( isset( $atts['before'] ) ? wp_kses_post( $atts['before'] ) : '' ) .
				$content .
				( isset( $atts['after'] )  ? wp_kses_post( $atts['after'] )  : '' )
			) :
			''
		);
	}

	/**
	 * do_shortcode.
	 *
	 * @version 1.4.0
	 * @since   1.2.0
	 */
	function do_shortcode( $content, $args = array() ) {
		$this->order = ( $args['order'] ?? false );
		$content = do_shortcode( $content );
		$this->order = false;
		return $content;
	}

}

endif;

return new Alg_WC_Coupon_Code_Generator_Shortcodes();
