/**
 * alg-wc-coupon-code-generator.js
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( document ).ready( function () {
	/**
	 * document ready.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	if ( '' === jQuery( "#title" ).val() ) {
		var data = {
			'action': 'alg_wc_coupon_code_generator',
		};
		jQuery.ajax( {
			type:    "POST",
			url:     ajax_object.ajax_url,
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
