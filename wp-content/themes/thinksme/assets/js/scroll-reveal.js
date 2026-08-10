/**
 * Scroll reveal — each section fades and lifts into place as it scrolls into
 * view. Homepage sections (#main-content > section) and the footer.
 *
 * The hidden state is CSS, gated on `html.has-scroll-reveal`, and this file is
 * what adds that class — synchronously, from the <head>, before the browser
 * paints. Two things follow from that:
 *
 *  1. No flash: the sections are never painted visible and then hidden.
 *  2. If this script fails to load, or IntersectionObserver is missing, or the
 *     visitor asked for reduced motion, the class is never added (or is removed
 *     again) and every section renders normally. The animation can only ever
 *     hide content while the code that reveals it is known to be running.
 *
 * Each section is revealed once, on the way in, and then unobserved — scrolling
 * back up doesn't re-hide it.
 */
( function () {
	var CLASS = 'has-scroll-reveal';
	var root = document.documentElement;

	// Nothing to undo later if we never opt in.
	if (
		! ( 'IntersectionObserver' in window ) ||
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
	) {
		return;
	}

	root.classList.add( CLASS );

	function init() {
		var targets = document.querySelectorAll( '#main-content > section, #site-footer' );

		if ( ! targets.length ) {
			root.classList.remove( CLASS );
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}

					entry.target.classList.add( 'is-revealed' );
					observer.unobserve( entry.target );
				} );
			},
			{
				// Waits until the section is ~12% up from the bottom edge, so it
				// animates while the visitor is looking at it rather than in the
				// sliver below the fold. A section taller than the viewport still
				// triggers on its top edge.
				rootMargin: '0px 0px -12% 0px',
				threshold: 0,
			}
		);

		targets.forEach( function ( target ) {
			observer.observe( target );
		} );
	}

	// This file is enqueued in the <head> so the class lands before first paint,
	// so the DOM is never ready yet — but the readyState check keeps it correct
	// if it's ever moved to the footer or given defer.
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
