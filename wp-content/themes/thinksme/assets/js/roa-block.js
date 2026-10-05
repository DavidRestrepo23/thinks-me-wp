/**
 * Service cards expand on click (template-parts/roa-block.php).
 *
 * This file only moves `data-active` between the cards and keeps aria-expanded
 * in step — every pixel of the open/closed geometry is CSS (src/base.css), so
 * if this script never loads the section still renders with the card the
 * template marked active already open, which is the Figma state.
 *
 * Every card is a button, including ones with no description yet — those simply
 * have no aria-expanded to keep in step, since they disclose nothing.
 */
( function () {
	var group = document.querySelector( '.roa-cards' );

	if ( ! group ) {
		return;
	}

	var cards = group.querySelectorAll( '.roa-card' );

	if ( cards.length < 2 ) {
		// A lone card is already open and has nothing to switch with; leave it be
		// rather than letting a click collapse the whole section.
		return;
	}

	function select( card ) {
		for ( var i = 0; i < cards.length; i++ ) {
			var isActive = cards[ i ] === card ? 'true' : 'false';

			cards[ i ].setAttribute( 'data-active', isActive );

			// Absent on cards with no description — see template-parts/roa-block.php.
			if ( cards[ i ].hasAttribute( 'aria-expanded' ) ) {
				cards[ i ].setAttribute( 'aria-expanded', isActive );
			}
		}
	}

	for ( var i = 0; i < cards.length; i++ ) {
		cards[ i ].addEventListener( 'click', function () {
			select( this );
		} );
	}
} )();
