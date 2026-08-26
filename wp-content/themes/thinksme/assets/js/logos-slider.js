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

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.querySelectorAll( '.logos-swiper' ).forEach( function ( el ) {
		var autoplayEnabled = 'true' === el.dataset.autoplay && ! prefersReducedMotion;
		var pauseOnHover = 'true' === el.dataset.pauseOnHover;
		var slidesDesktop = parseInt( el.dataset.slidesDesktop, 10 ) || 5;
		// The homepage strips run the full content width and space their logos 64px
		// apart; the Business Loan hero's runs inside a 642px column beside a label
		// (Figma 119:1737), where 64px reads as four logos adrift rather than one row.
		// So the desktop gap is a per-strip attribute with the homepage's own value as
		// the default.
		var spaceDesktop = parseInt( el.dataset.spaceDesktop, 10 ) || 64;
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
		// read as a few icons adrift with uneven gaps instead of an even row. Not
		// needed in `auto` mode: that caller (the Business Loan hero's bank strip)
		// already duplicates its own logos in the template, since it also controls
		// their order. wrapper/slide classes are already in the markup here, unlike
		// ci-why-slider.js, so only the clone loop is needed.
		if ( ! autoWidth ) {
			var wrapper = el.querySelector( '.swiper-wrapper' );
			var realSlides = Array.prototype.slice.call( el.querySelectorAll( '.swiper-slide' ) );
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
		}

		new Swiper( el, {
			loop: true,
			slidesPerView: autoWidth ? 'auto' : 3,
			spaceBetween: autoWidth ? spaceDesktop : 24,
			speed: scrollSpeed,
			grabCursor: true,
			allowTouchMove: true,
			breakpoints: autoWidth ? {} : {
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
