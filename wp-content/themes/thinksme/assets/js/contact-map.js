/**
 * Office cards drive the Google Maps embed (template-parts/offices-map.php).
 *
 * Each card is a plain link to Google Maps, which is what a visitor gets if this
 * file never loads. Here the click is intercepted instead: the iframe is
 * re-pointed at that office and the card takes over the selected styling
 * (data-active, styled in src/base.css).
 *
 * Opening the full map in a new tab stays available on modifier-click and
 * middle-click — those are the browser's own gestures for "open this link", and
 * swallowing them would break the fallback the markup deliberately keeps.
 */
( function () {
	var section = document.querySelector( '.offices-map' );

	if ( ! section ) {
		return;
	}

	var frame = section.querySelector( '.offices-map__embed' );
	var cards = section.querySelectorAll( '.office-card[data-map-src]' );

	if ( ! frame || ! cards.length ) {
		return;
	}

	function select( card ) {
		for ( var i = 0; i < cards.length; i++ ) {
			cards[ i ].setAttribute( 'data-active', cards[ i ] === card ? 'true' : 'false' );
		}

		frame.setAttribute( 'src', card.getAttribute( 'data-map-src' ) );
	}

	for ( var i = 0; i < cards.length; i++ ) {
		cards[ i ].addEventListener( 'click', function ( event ) {
			if ( event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || 1 === event.button ) {
				return;
			}

			event.preventDefault();
			select( this );
		} );
	}
} )();
