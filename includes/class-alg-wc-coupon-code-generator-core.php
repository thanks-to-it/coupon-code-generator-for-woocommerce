<?php
/**
 * Order Coupon Automator for WooCommerce - Core Class
 *
 * @version 2.0.2
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Coupon_Code_Generator_Core' ) ) :

class Alg_WC_Coupon_Code_Generator_Core {

	/**
	 * auto_coupon_code_options.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $auto_coupon_code_options;

	/**
	 * order_coupon_options.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $order_coupon_options;

	/**
	 * shortcodes.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	public $shortcodes;

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * init.
	 *
	 * @version 2.0.2
	 * @since   2.0.0
	 *
	 * @todo    (v2.0.0) replace `alg_wc_coupon_code_generator...` with `alg_wc_ccg...` everywhere?
	 * @todo    (v2.0.0) separate into smaller classes/files, e.g., `Alg_WC_Coupon_Code_Generator_Email`, etc.?
	 * @todo    (feature) Order coupon: meta box: "delete order coupon", (maybe) "(re)generate order coupon", "(re)send order coupon", etc.
	 * @todo    (feature) Order coupon: tool: "delete all order coupons" (i.e., delete 2 order metas)
	 * @todo    (feature) automatic coupon in cart
	 * @todo    (dev) Automatic Coupon Code: add option to generate code only on button (in meta box) pressed?
	 * @todo    (dev) Automatic Coupon Code: `wp_ajax_nopriv_` ?
	 */
	function init() {

		// Automatic coupon code
		if ( 'yes' === get_option( 'alg_wc_ccg_auto_coupon_code_enabled', 'no' ) ) {

			// Options
			$auto_coupon_code_options_default_options = array(
				'template'  => '%code%',
				'algorithm' => 'crc32',
				'length'    => 0,
			);
			$this->auto_coupon_code_options = array_merge(
				$auto_coupon_code_options_default_options,
				get_option( 'alg_wc_ccg_auto_coupon_code', array() )
			);

			// Hooks
			add_action(
				'wp_ajax_' . 'alg_wc_coupon_code_generator',
				array( $this, 'ajax_generate_coupon_code' )
			);
			add_action(
				'admin_enqueue_scripts',
				array( $this, 'enqueue_generate_coupon_code_script' )
			);

		}

		// Order coupon
		if ( 'yes' === get_option( 'alg_wc_ccg_order_coupon_enabled', 'no' ) ) {

			// Options
			$order_coupon_default_options = array(
				'order_status'         => array( 'wc-completed' ),
				'code_template'        => '%order_billing_email%-%order_id%',
				'discount_type'        => 'percent',
				'coupon_amount'        => 10,
				'minimum_order_amount' => '',
				'allowed_emails'       => 'yes',
				'minimum_amount'       => '',
				'maximum_amount'       => '',
				'usage_limit_per_user' => 0,
				'usage_limit'          => 0,
				'individual_use'       => 'no',
				'product_categories'   => array(),
				'emails'               => array( 'customer_completed_order' ),
				'email_template'       => '<p>' . sprintf(
					/* Translators: %s: Coupon code. */
					__( 'Here is a coupon for your next purchase: %s', 'order-coupon-automator-for-woocommerce' ),
					'<code>%coupon_code%</code>'
				) . '</p>',
			);
			$this->order_coupon_options = array_merge(
				$order_coupon_default_options,
				get_option( 'alg_wc_ccg_order_coupon', array() )
			);

			// Hooks: Create coupon
			foreach ( $this->order_coupon_options['order_status'] as $order_status ) {
				add_action(
					'woocommerce_order_status_' . substr( $order_status, 3 ),
					array( $this, 'create_order_coupon' )
				);
			}

			// Hooks: Email coupon
			foreach ( $this->order_coupon_options['emails'] as $email ) {
				add_filter(
					'woocommerce_email_additional_content_' . $email,
					array( $this, 'add_coupon_to_order_email' ),
					10,
					3
				);
			}

			// Shortcodes
			$this->shortcodes = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-ccg-shortcodes.php';

			// Tools
			add_action( 'alg_wc_ccg_settings_saved', array( $this, 'create_coupons_for_all_orders' ) );
			add_action( 'admin_notices', array( $this, 'tools_admin_notices' ) );

		}

	}

	/**
	 * tools_admin_notices.
	 *
	 * @version 2.0.2
	 * @since   2.0.0
	 */
	function tools_admin_notices() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['alg_wc_ccg_order_coupon_tool_all_orders'] ) ) {
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php printf(
					/* Translators: %d: Number of coupons. */
					esc_html__( '%d coupon(s) created.', 'order-coupon-automator-for-woocommerce' ),
					intval( $_GET['alg_wc_ccg_order_coupon_tool_all_orders'] )
				); ?></p>
			</div>
			<?php
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * create_coupons_for_all_orders.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query
	 *
	 * @todo    (feature) send via email?
	 * @todo    (feature) add `delete_coupons_for_all_orders` tool (`_alg_wc_ccg_order_coupon_code`, etc.)
	 */
	function create_coupons_for_all_orders() {

		if ( 'no' === get_option( 'alg_wc_ccg_order_coupon_tool_all_orders', 'no' ) ) {
			return;
		} else {
			update_option( 'alg_wc_ccg_order_coupon_tool_all_orders', 'no' );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$counter = 0;
		$args    = array(
			'type'   => 'shop_order',
			'limit'  => -1,
			'status' => $this->order_coupon_options['order_status'],
			'return' => 'ids',
		);
		foreach ( wc_get_orders( $args ) as $order_id ) {
			if ( $this->create_order_coupon( $order_id ) ) {
				$counter++;
			}
		}

		wp_safe_redirect(
			add_query_arg(
				'alg_wc_ccg_order_coupon_tool_all_orders',
				$counter
			)
		);
		exit;

	}

	/**
	 * generate_order_coupon_code.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) more placeholders
	 */
	function generate_order_coupon_code( $order ) {
		$placeholders = array(
			'%order_id%'                 => $order->get_id(),
			'%order_number%'             => $order->get_order_number(),
			'%order_billing_first_name%' => $order->get_billing_first_name(),
			'%order_billing_first_last%' => $order->get_billing_last_name(),
			'%order_billing_email%'      => $order->get_billing_email(),
			'%order_customer_id%'        => $order->get_customer_id(),
		);
		return str_replace(
			array_keys( $placeholders ),
			$placeholders,
			$this->shortcodes->do_shortcode(
				$this->order_coupon_options['code_template'],
				array( 'order' => $order )
			)
		);
	}

	/**
	 * add_coupon_to_order_email.
	 *
	 * @version 2.0.0
	 * @since   1.2.0
	 *
	 * @todo    (feature) customizable position, i.e., before or after the default content?
	 */
	function add_coupon_to_order_email( $content, $order, $email ) {
		if ( is_a( $order, 'WC_Order' ) ) {

			// Make sure coupon exists
			$this->create_order_coupon( $order->get_id() );

			// Add coupon text
			if (
				( $_order = wc_get_order( $order->get_id() ) ) && // if this is not done, there will be no `_alg_wc_ccg_order_coupon_code` meta
				( $code = $_order->get_meta( '_alg_wc_ccg_order_coupon_code' ) ) &&
				wc_get_coupon_id_by_code( $code )
			) {
				return (
					str_replace(
						'%coupon_code%',
						$code,
						$this->order_coupon_options['email_template']
					) .
					PHP_EOL .
					$content
				);
			}

		}
		return $content;
	}

	/**
	 * create_order_coupon.
	 *
	 * @version 2.0.2
	 * @since   1.2.0
	 *
	 * @see     https://woocommerce.github.io/code-reference/classes/WC-Coupon.html
	 *
	 * @todo    (feature) `$data`: add more options, e.g., "Coupon expiry date", "Exclude sale items", etc.
	 */
	function create_order_coupon( $order_id ) {
		if (
			( $order = wc_get_order( $order_id ) ) &&
			! ( $_coupon_id = $order->get_meta( '_alg_wc_ccg_order_coupon_id' ) ) &&
			(
				empty( $this->order_coupon_options['minimum_order_amount'] ) ||
				$order->get_total( 'edit' ) >= floatval( $this->order_coupon_options['minimum_order_amount'] )
			) &&
			( $code = $this->generate_order_coupon_code( $order ) ) &&
			! wc_get_coupon_id_by_code( $code )
		) {

			$coupon = array(
				'post_title'   => $code,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'shop_coupon',
				'post_excerpt' => sprintf(
					/* Translators: %1$s: Plugin name, %2$s: Order ID. */
					__( 'Created by "%1$s" plugin for order #%2$s', 'order-coupon-automator-for-woocommerce' ),
					__( 'Order Coupon Automator for WooCommerce', 'order-coupon-automator-for-woocommerce' ),
					$order->get_id()
				),
			);
			$coupon_id = wp_insert_post( $coupon );

			if ( $coupon_id ) {

				// Coupon data
				$data = array(
					'coupon_amount'        => $this->order_coupon_options['coupon_amount'],
					'discount_type'        => $this->order_coupon_options['discount_type'],
					'customer_email'       => ( 'yes' === $this->order_coupon_options['allowed_emails'] ? $order->get_billing_email() : '' ),
					'minimum_amount'       => $this->order_coupon_options['minimum_amount'],
					'maximum_amount'       => $this->order_coupon_options['maximum_amount'],
					'usage_limit_per_user' => $this->order_coupon_options['usage_limit_per_user'],
					'usage_limit'          => $this->order_coupon_options['usage_limit'],
					'individual_use'       => $this->order_coupon_options['individual_use'],
					'product_categories'   => $this->order_coupon_options['product_categories'],
				);
				foreach ( $data as $key => $value ) {
					update_post_meta( $coupon_id, $key, $value );
				}

				// Update order
				$order->add_order_note(
					sprintf(
						/* Translators: %1$s: Plugin name, %2$s: Coupon code. */
						__( '"%1$s" plugin generated <code>%2$s</code> coupon for this order.', 'order-coupon-automator-for-woocommerce' ),
						__( 'Order Coupon Automator for WooCommerce', 'order-coupon-automator-for-woocommerce' ),
						$code
					)
				);
				$order->update_meta_data( '_alg_wc_ccg_order_coupon_code', $code );
				$order->update_meta_data( '_alg_wc_ccg_order_coupon_id',   $coupon_id );
				$order->save();

				return true;
			}

		}
		return false;
	}

	/**
	 * enqueue_generate_coupon_code_script.
	 *
	 * @version 2.0.2
	 * @since   1.0.0
	 *
	 * @todo    (v2.0.0) use `get_current_screen()` ([action] => add, [id] => shop_coupon)?
	 */
	function enqueue_generate_coupon_code_script() {
		global $pagenow;
		if (
			'post-new.php' === $pagenow &&
			isset( $_GET['post_type'] ) && // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'shop_coupon' === $_GET['post_type'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		) {
			$file = (
				'alg-wc-coupon-code-generator' .
				( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? '' : '.min' ) .
				'.js'
			);
			wp_enqueue_script(
				'alg-wc-coupon-code-generator',
				alg_wc_ccg()->plugin_url() . '/includes/js/' . $file,
				array( 'jquery' ),
				alg_wc_ccg()->version,
				true
			);
			wp_localize_script(
				'alg-wc-coupon-code-generator',
				'alg_wc_ccg_ajax_object',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
			);
		}
	}

	/**
	 * generate_coupon_code.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) add more placeholders (e.g., %user_name% etc.)
	 */
	function generate_coupon_code( $str = '', $algorithm = '', $length = '' ) {

		$time = current_time( 'timestamp' );

		if ( '' === $str ) {
			$str = $time;
		}
		$code = $this->get_coupon_code( $str, $algorithm, $length );
		$code = apply_filters( 'alg_wc_coupon_code_generator', $code, $str, $algorithm, $length );

		$template = $this->auto_coupon_code_options['template'];
		if ( '' === $template ) {
			$template = '%code%';
		}

		$placeholders = array(
			'%code%'    => $code,
			'%user_id%' => get_current_user_id(),
			'%date_YY%' => date( 'Y', $time ), // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			'%date_MM%' => date( 'm', $time ), // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
			'%date_DD%' => date( 'd', $time ), // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
		);

		return str_replace( array_keys( $placeholders ), $placeholders, $template );
	}

	/**
	 * random_string.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	 */
	function random_string( $length = 32, $characters = 'abcdefghijklmnopqrstuvwxyz' ) {
		$characters_length = strlen( $characters );
		$random_string     = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$random_string .= $characters[ rand( 0, $characters_length - 1 ) ]; // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		}
		return $random_string;
	}

	/**
	 * get_coupon_code.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 *
	 * @todo    (v2.0.0) rename the function?
	 * @todo    (feature) more algorithms?
	 */
	function get_coupon_code( $str, $algorithm, $length ) {

		if ( '' === $algorithm ) {
			$algorithm = $this->auto_coupon_code_options['algorithm'];
		}
		switch ( $algorithm ) {
			case 'random_letters_and_numbers':
				$code = $this->random_string( 32, '0123456789abcdefghijklmnopqrstuvwxyz' );
				break;
			case 'random_letters':
				$code = $this->random_string( 32, 'abcdefghijklmnopqrstuvwxyz' );
				break;
			case 'random_numbers':
				$code = $this->random_string( 32, '0123456789' );
				break;
			case 'md5':
				$code = md5( $str );
				break;
			case 'sha1':
				$code = sha1( $str );
				break;
			default: // 'crc32'
				$code = sprintf( '%08x', crc32( $str ) );
		}

		if ( '' === $length ) {
			$length = $this->auto_coupon_code_options['length'];
		}
		if ( $length > 0 && strlen( $code ) > $length ) {
			$code = substr( $code, 0, $length );
		}

		return $code;
	}

	/**
	 * ajax_generate_coupon_code.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) optionally generate some description for coupon (e.g., "Automatically generated coupon [YYYY-MM-DD]")
	 */
	function ajax_generate_coupon_code() {
		$attempts = 0;
		while ( true ) {
			$coupon_code = $this->generate_coupon_code();
			$coupon      = new WC_Coupon( $coupon_code );
			if ( ! $coupon->get_id() ) {
				echo esc_html( $coupon_code );
				die();
			}
			$attempts++;
			if ( $attempts > 100 ) { // shouldn't happen, but just in case...
				echo '';
				die();
			}
		}
	}

}

endif;

return new Alg_WC_Coupon_Code_Generator_Core();
