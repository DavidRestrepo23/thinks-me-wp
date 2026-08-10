/**
 * "Three Ways" rotating card deck (template-parts/cards-deck.php).
 *
 * Order lives here as an array of card elements, front first; the only thing
 * written to the DOM is data-depth (0 = front). All positioning/animation is
 * CSS keyed off that attribute — see src/base.css.
 *
 * Auto-advance interval comes from data-autoplay-delay (4000ms). It pauses on
 * hover/focus and while the deck is off screen, and is disabled entirely for
 * users who asked for reduced motion — for them the deck is click-only.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.querySelectorAll( '.cards-deck' ).forEach( function ( deck ) {
		var order = Array.prototype.slice.call( deck.querySelectorAll( '.cards-deck__card' ) );
		if ( order.length < 2 ) {
			return;
		}

		var delay = parseInt( deck.dataset.autoplayDelay, 10 ) || 4000;
		var timer = null;
		var paused = false;
		var visible = true;

		function render() {
			order.forEach( function ( card, depth ) {
				card.dataset.depth = depth;

				// Content of a back card is fully covered by the front card —
				// keep it out of the tab order and off the pointer until it
				// is promoted.
				var content = card.querySelector( '.cards-deck__content' );
				if ( content ) {
					if ( 0 === depth ) {
						content.removeAttribute( 'inert' );
					} else {
						content.setAttribute( 'inert', '' );
					}
				}
			} );
		}

		function stop() {
			if ( timer ) {
				window.clearTimeout( timer );
				timer = null;
			}
		}

		function schedule() {
			stop();
			if ( prefersReducedMotion || paused || ! visible ) {
				return;
			}
			timer = window.setTimeout( function () {
				order.push( order.shift() );
				render();
				schedule();
			}, delay );
		}

		// Rotate the array until `card` is front, so the cards behind it keep
		// their relative order instead of shuffling.
		function bringToFront( card ) {
			var index = order.indexOf( card );
			if ( index < 1 ) {
				return;
			}
			order = order.slice( index ).concat( order.slice( 0, index ) );
			render();
			schedule();
		}

		deck.querySelectorAll( '.cards-deck__promote' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				bringToFront( button.closest( '.cards-deck__card' ) );
			} );
		} );

		deck.addEventListener( 'mouseenter', function () {
			paused = true;
			stop();
		} );

		deck.addEventListener( 'mouseleave', function () {
			paused = false;
			schedule();
		} );

		deck.addEventListener( 'focusin', function () {
			paused = true;
			stop();
		} );

		deck.addEventListener( 'focusout', function () {
			paused = false;
			schedule();
		} );

		if ( 'IntersectionObserver' in window ) {
			new IntersectionObserver(
				function ( entries ) {
					visible = entries[ 0 ].isIntersecting;
					if ( visible ) {
						schedule();
					} else {
						stop();
					}
				},
				{ threshold: 0.2 }
			).observe( deck );
		}

		render();
		schedule();
	} );
} );
