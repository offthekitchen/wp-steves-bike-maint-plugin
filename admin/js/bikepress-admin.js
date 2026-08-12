(function( $ ) {
	'use strict';

	$( function() {
		$( document ).on( 'click', '.bikepress-bike-edit-link', function( event ) {
			var $link = $( this );
			var selectSelector = $link.data( 'bikepress-select' );
			var editUrl = $link.data( 'bikepress-edit-url' );
			var listUrl = $link.data( 'bikepress-list-url' );
			var bikeId = 0;

			if ( selectSelector ) {
				bikeId = parseInt( $( selectSelector ).val(), 10 ) || 0;
			}

			event.preventDefault();

			if ( bikeId > 0 && editUrl ) {
				window.location.href = editUrl + bikeId;
				return;
			}

			window.location.href = listUrl || $link.attr( 'href' );
		} );
	} );

})( jQuery );
