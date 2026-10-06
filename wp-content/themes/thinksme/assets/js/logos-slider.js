/**
 * Client logos carousel (template-parts/logos-slider.php). Loop + autoplay,
 * settings read from data-* attributes so Appearance > Customize controls
 * (see functions.php, thinksme_customize_register()) take effect without a
 * code change.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	if ( typeof Swiper === 'undefined' ) {
		return;
	}

	document.querySelectorAll( '.logos-swiper' ).forEach( function ( el ) {
		// The logo marquees loop for everyone, on the client's instruction: they are the
		// site's proof strips and reading as a dead row of icons under a reduced-motion
		// OS setting was read as broken. (Other sliders still honour reduced-motion.)
		var autoplayEnabled = 'true' === el.dataset.autoplay;
		var pauseOnHover = 'true' === el.dataset.pauseOnHover;
		var slidesDesktop = parseInt( el.dataset.slidesDesktop, 10 ) || 5;
		// The homepage strips run the full content width and space their logos 64px
		// apart; the Business Loan hero's runs inside a 642px column beside a label
		// (Figma 119:1737), where 64px reads as four logos adrift rather than one row.
		// So the desktop gap is a per-strip attribute with the homepage's own value as
		// the default.
		var spaceDesktop = parseInt( el.dataset.spaceDesktop, 10 ) || 64;
		// Mobile gap for auto-width strips; defaults to the desktop gap so a strip
		// without the attribute (the Business Loan bank strip) keeps one fixed gap.
		var spaceMobile = parseInt( el.dataset.spaceMobile, 10 ) || spaceDesktop;
		// The bank strip fills its own loop from the template; the homepage strips
		// don't, so only they get the auto-mode clone fill below.
		var isBanks = el.classList.contains( 'ci-hero__banks' );
		// `data-slides-desktop="auto"` sizes each slide to its own logo instead of to an
		// equal share of the track. The homepage strips want equal columns; the Business
		// Loan hero's wants Figma's row — logos at their natural widths, a fixed gap
		// apart — and with equal columns a narrow mark sits marooned in the middle of
		// its cell.
		var autoWidth = 'auto' === el.dataset.slidesDesktop;
		// Transition duration for one slide-advance — with autoplay delay pinned
		// to ~0 below, this is what makes the strip read as a continuous crawl
		// instead of a slide-then-pause carousel. Higher = slower/smoother.
		var scrollSpeed = parseInt( el.dataset.autoplayDelay, 10 ) || 4000;

		// Swiper needs roughly twice as many slides as are visible at once before
		// it will loop cleanly; short of that it pads the loop with blank slots,
		// which is what made the certifications strip (6 logos, slidesPerView 5)
		// read as a few icons adrift with uneven gaps instead of an even row.
		// wrapper/slide classes are already in the markup here, unlike
		// ci-why-slider.js, so only the clone loop is needed.
		var wrapper = el.querySelector( '.swiper-wrapper' );
		var realSlides = Array.prototype.slice.call( el.querySelectorAll( '.swiper-slide' ) );

		if ( ! autoWidth ) {
			// Equal-column strips: clone until ~2x the visible count exists.
			var target = 2 * Math.max( 3, 4, slidesDesktop );

			if ( wrapper && realSlides.length && realSlides.length < target ) {
				var i = 0;
				while ( wrapper.querySelectorAll( '.swiper-slide' ).length < target ) {
					var copy = realSlides[ i % realSlides.length ].cloneNode( true );
					copy.setAttribute( 'aria-hidden', 'true' );
					wrapper.appendChild( copy );
					i++;
				}
			}
		} else if ( ! isBanks && wrapper && realSlides.length ) {
			// Auto-width homepage strips: the template outputs each logo once, which
			// isn't enough track to loop seamlessly (Swiper would leave a blank gap at
			// the seam). Clone the whole set until the slides — at their natural widths
			// plus the gap — span at least 2.5x the container, so the crawl and the loop
			// seam stay gap-even. Slides carry width/height attributes, so their box is
			// measurable here before the images finish downloading. The bank strip is
			// excluded: it already duplicates its own logos in the template.
			var need = el.getBoundingClientRect().width * 2.5;
			var total = realSlides.reduce( function ( sum, s ) {
				return sum + s.getBoundingClientRect().width + spaceDesktop;
			}, 0 );
			var j = 0;
			var guard = 0;
			while ( total < need && guard < 300 ) {
				var src = realSlides[ j % realSlides.length ];
				var clone = src.cloneNode( true );
				clone.setAttribute( 'aria-hidden', 'true' );
				wrapper.appendChild( clone );
				total += src.getBoundingClientRect().width + spaceDesktop;
				j++;
				guard++;
			}
		}

		new Swiper( el, {
			loop: true,
			slidesPerView: autoWidth ? 'auto' : 3,
			spaceBetween: autoWidth ? spaceDesktop : 24,
			speed: scrollSpeed,
			grabCursor: true,
			allowTouchMove: true,
			breakpoints: autoWidth ? {
				// Auto-width: only the gap is responsive (a smaller gap on phones).
				// A strip with no data-space-mobile keeps one gap at every width.
				0: {
					spaceBetween: spaceMobile,
				},
				1024: {
					spaceBetween: spaceDesktop,
				},
			} : {
				640: {
					slidesPerView: 4,
					spaceBetween: 32,
				},
				1024: {
					slidesPerView: slidesDesktop,
					spaceBetween: spaceDesktop,
				},
			},
			autoplay: autoplayEnabled ? {
				delay: 1,
				disableOnInteraction: false,
				pauseOnMouseEnter: pauseOnHover,
			} : false,
		} );
	} );
} );
