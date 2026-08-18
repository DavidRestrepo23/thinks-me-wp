/**
 * A row of cards where clicking one expands it — template-parts/roa-block.php
 * (Figma 67:211) and template-parts/ci-steps.php (Figma 119:1796).
 *
 * This was roa-block.js, and it is now driven by `data-expand-cards` /
 * `data-expand-card` rather than one section's class names — the same
 * generalisation ci-tools.js → tabs.js and roa-progress.js → progress-rail.js
 * got, and for the same reason: the Business Loan frame draws the interaction on
 * a palette that looks nothing like ROA's. The `.roa-card*` / `.ci-step*` classes
 * stay exactly what they were, styling hooks in src/base.css.
 *
 * It only moves `data-active` between the cards and keeps aria-expanded in step —
 * every pixel of the open/closed geometry is CSS, so if this script never loads
 * the section still renders with the card the template marked active already
 * open, which is the Figma state.
 *
 * Every card is a button, including ones the client has written no description
 * for — those simply have no aria-expanded to keep in step, since they disclose
 * nothing.
 *
 * Every group on the page is initialised, not just the first: a page is free to
 * draw two of these.
 */
( function () {
	var groups = document.querySelectorAll( '[data-expand-cards]' );

	for ( var g = 0; g < groups.length; g++ ) {
		init( groups[ g ] );
	}

	function init( group ) {
		var cards = group.querySelectorAll( '[data-expand-card]' );

		if ( cards.length < 2 ) {
			// A lone card is already open and has nothing to switch with; leave it be
			// rather than letting a click collapse the whole section.
			return;
		}

		function select( card ) {
			for ( var i = 0; i < cards.length; i++ ) {
				var isActive = cards[ i ] === card ? 'true' : 'false';

				cards[ i ].setAttribute( 'data-active', isActive );

				// Absent on cards with no description — see the templates.
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
	}
} )();
