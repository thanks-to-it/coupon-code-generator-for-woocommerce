/**
 * alg-wc-coupon-code-generator.js
 *
 * @version 2.0.2
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function () {
	/**
	 * document ready.
	 *
	 * @version 2.0.2
	 * @since   1.0.0
	 */
	if ( '' === jQuery( "#title" ).val() ) {
		var data = {
			'action': 'alg_wc_coupon_code_generator',
		};
		jQuery.ajax( {
			type:    "POST",
			url:     alg_wc_ccg_ajax_object.ajax_url,
			data:    data,
			success: function ( response ) {
				if ( '' !== response && '' === jQuery( "#title" ).val() ) {
					jQuery( "#title" ).val( response );
					jQuery( "#title-prompt-text" ).html( '' );
				}
			},
		} );
	}
} );
