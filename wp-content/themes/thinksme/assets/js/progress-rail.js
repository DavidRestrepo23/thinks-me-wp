/**
 * Scroll-driven progress rails.
 *
 * A rail is a column of steps joined by connectors that fill as you scroll past
 * the section. Two sections use one: template-parts/roa-why.php (Registered
 * Office Address) and template-parts/ci-requirements.php (Accounting &
 * Bookkeeping). Both opt in from markup rather than by class name — the section
 * carries `data-progress-rail` and the block to pin carries `data-progress-pin`
 * — so a third section needs no change here.
 *
 * The section is pinned while you scroll past it and its connectors fill as you
 * go, reaching full on the last step — after which the sticky child releases
 * and the page scrolls on normally. Nothing here touches the scroll itself:
 * there is no preventDefault, no scrollTo, no wheel handler. The pin is
 * `position: sticky` and this file only (a) gives the section a scroll runway
 * tall enough to scrub through, and (b) writes --rail-progress, a 0..1 number
 * the CSS turns into connector fill heights.
 *
 * It opts out — leaving the section as a plain block with every connector
 * already filled, which is the CSS default — when any of these hold:
 *
 *  - the viewport is narrower than the breakpoint the design's layout needs;
 *  - the visitor asked for reduced motion (hijacking their scroll rhythm to
 *    animate something is exactly what that preference is about);
 *  - the pinned content is taller than the viewport, where pinning would park
 *    its bottom off screen with no way to reach it.
 *
 * The last two are re-evaluated on resize, so rotating a tablet or opening dev
 * tools switches a section between pinned and static rather than stranding it.
 */
( function () {
	var sections = document.querySelectorAll( '[data-progress-rail]' );

	if ( ! sections.length ) {
		return;
	}

	// Matches the `lg` breakpoint the pinned layouts start at.
	var wide = window.matchMedia( '(min-width: 1024px)' );
	var still = window.matchMedia( '(prefers-reduced-motion: reduce)' );

	// Scroll distance, in viewport heights, spent filling one connector.
	var RUNWAY_PER_LINE = 0.5;

	var rails = [];

	function Rail( section, pin ) {
		this.section = section;
		this.pin = pin;
		this.pinned = false;
	}

	Rail.prototype.lines = function () {
		var declared = parseInt(
			getComputedStyle( this.section ).getPropertyValue( '--rail-lines' ),
			10
		);

		return declared > 0 ? declared : 1;
	};

	Rail.prototype.update = function () {
		if ( ! this.pinned ) {
			return;
		}

		var runway = this.section.offsetHeight - this.pin.offsetHeight;

		if ( runway <= 0 ) {
			this.section.style.setProperty( '--rail-progress', '1' );
			return;
		}

		// section.getBoundingClientRect().top is 0 the moment the pin latches and
		// -runway when it lets go, so negating it gives the 0..1 scrub directly.
		var progress = -this.section.getBoundingClientRect().top / runway;

		this.section.style.setProperty(
			'--rail-progress',
			String( Math.min( 1, Math.max( 0, progress ) ) )
		);
	};

	Rail.prototype.unpin = function () {
		this.pinned = false;
		this.section.removeAttribute( 'data-pinned' );
		this.section.style.removeProperty( 'height' );
		this.section.style.removeProperty( '--rail-progress' );
	};

	Rail.prototype.measure = function () {
		if ( ! wide.matches || still.matches ) {
			this.unpin();
			return;
		}

		// Measured unpinned, so the pin is at its natural height rather than the
		// 100svh the pinned state gives it.
		this.unpin();

		if ( this.pin.offsetHeight > window.innerHeight ) {
			return;
		}

		this.pinned = true;
		this.section.setAttribute( 'data-pinned', 'true' );
		this.section.style.height =
			'calc(100svh + ' + this.lines() * RUNWAY_PER_LINE * 100 + 'svh)';

		this.update();
	};

	Array.prototype.forEach.call( sections, function ( section ) {
		var pin = section.querySelector( '[data-progress-pin]' );

		if ( pin ) {
			rails.push( new Rail( section, pin ) );
		}
	} );

	if ( ! rails.length ) {
		return;
	}

	var ticking = false;

	function update() {
		ticking = false;

		rails.forEach( function ( rail ) {
			rail.update();
		} );
	}

	function onScroll() {
		if ( ticking ) {
			return;
		}

		ticking = true;
		window.requestAnimationFrame( update );
	}

	function measure() {
		rails.forEach( function ( rail ) {
			rail.measure();
		} );
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', measure );

	if ( wide.addEventListener ) {
		wide.addEventListener( 'change', measure );
		still.addEventListener( 'change', measure );
	}

	// Web fonts land after first paint and change how tall a rail is, so the fit
	// test above has to be redone once they're in.
	if ( document.fonts && document.fonts.ready ) {
		document.fonts.ready.then( measure );
	}

	measure();
} )();
