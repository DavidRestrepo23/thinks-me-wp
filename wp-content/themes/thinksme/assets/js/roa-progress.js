/**
 * Scroll-driven progress rail (template-parts/roa-why.php).
 *
 * The section is pinned while you scroll past it and its connectors fill as you
 * go, reaching full on the last step — after which the sticky child releases
 * and the page scrolls on normally. Nothing here touches the scroll itself:
 * there is no preventDefault, no scrollTo, no wheel handler. The pin is
 * `position: sticky` and this file only (a) gives the section a scroll runway
 * tall enough to scrub through, and (b) writes --roa-progress, a 0..1 number
 * the CSS turns into connector fill heights.
 *
 * It opts out — leaving the section as a plain block with every connector
 * already filled, which is the CSS default — when any of these hold:
 *
 *  - the viewport is narrower than the breakpoint the design's two columns need;
 *  - the visitor asked for reduced motion (hijacking their scroll rhythm to
 *    animate something is exactly what that preference is about);
 *  - the pinned content is taller than the viewport, where pinning would park
 *    its bottom off screen with no way to reach it.
 *
 * The last two are re-evaluated on resize, so rotating a tablet or opening dev
 * tools switches the section between pinned and static rather than stranding it.
 */
( function () {
	var section = document.querySelector( '.roa-why' );

	if ( ! section ) {
		return;
	}

	var pin = section.querySelector( '.roa-why__pin' );

	if ( ! pin ) {
		return;
	}

	// Matches the `lg` breakpoint the two-column layout starts at.
	var wide = window.matchMedia( '(min-width: 1024px)' );
	var still = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	// Scroll distance, in viewport heights, spent filling one connector.
	var RUNWAY_PER_LINE = 0.5;

	var pinned = false;
	var ticking = false;

	function lines() {
		var declared = parseInt(
			getComputedStyle( section ).getPropertyValue( '--roa-lines' ),
			10
		);

		return declared > 0 ? declared : 1;
	}

	function update() {
		ticking = false;

		if ( ! pinned ) {
			return;
		}

		var runway = section.offsetHeight - pin.offsetHeight;

		if ( runway <= 0 ) {
			section.style.setProperty( '--roa-progress', '1' );
			return;
		}

		// section.getBoundingClientRect().top is 0 the moment the pin latches and
		// -runway when it lets go, so negating it gives the 0..1 scrub directly.
		var progress = -section.getBoundingClientRect().top / runway;

		section.style.setProperty(
			'--roa-progress',
			String( Math.min( 1, Math.max( 0, progress ) ) )
		);
	}

	function onScroll() {
		if ( ticking ) {
			return;
		}

		ticking = true;
		window.requestAnimationFrame( update );
	}

	function unpin() {
		pinned = false;
		section.removeAttribute( 'data-pinned' );
		section.style.removeProperty( 'height' );
		section.style.removeProperty( '--roa-progress' );
	}

	function measure() {
		if ( ! wide.matches || still.matches ) {
			unpin();
			return;
		}

		// Measured unpinned, so the pin is at its natural height rather than the
		// 100svh the pinned state gives it.
		unpin();

		if ( pin.offsetHeight > window.innerHeight ) {
			return;
		}

		pinned = true;
		section.setAttribute( 'data-pinned', 'true' );
		section.style.height =
			'calc(100svh + ' + lines() * RUNWAY_PER_LINE * 100 + 'svh)';

		update();
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', measure );

	if ( wide.addEventListener ) {
		wide.addEventListener( 'change', measure );
		still.addEventListener( 'change', measure );
	}

	// Web fonts land after first paint and change how tall the rail is, so the
	// fit test above has to be redone once they're in.
	if ( document.fonts && document.fonts.ready ) {
		document.fonts.ready.then( measure );
	}

	measure();
} )();
