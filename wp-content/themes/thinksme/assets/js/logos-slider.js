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
		// Transition duration for one slide-advance — with autoplay delay pinned
		// to ~0 below, this is what makes the strip read as a continuous crawl
		// instead of a slide-then-pause carousel. Higher = slower/smoother.
		var scrollSpeed = parseInt( el.dataset.autoplayDelay, 10 ) || 4000;

		new Swiper( el, {
			loop: true,
			slidesPerView: 3,
			spaceBetween: 24,
			speed: scrollSpeed,
			grabCursor: true,
			allowTouchMove: true,
			breakpoints: {
				640: {
					slidesPerView: 4,
					spaceBetween: 32,
				},
				1024: {
					slidesPerView: slidesDesktop,
					spaceBetween: 64,
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
