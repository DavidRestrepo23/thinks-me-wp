/**
 * Hero photo fan (template-parts/hero.php).
 *
 * Same pattern as the Google Reviews slider: the only thing written to the DOM
 * is data-pos on each slide — 0 for the centred card, negative to its left,
 * positive to its right, clamped to ±2 — and src/base.css turns that into
 * Figma's card size, tilt and vertical drop. Loop + autoplay; autoplay pauses on
 * hover and off screen, and is off entirely under prefers-reduced-motion.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	if ( typeof Swiper === 'undefined' ) {
		return;
	}

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.querySelectorAll( '.hero-slider' ).forEach( function ( el ) {
		var autoplayDelay = parseInt( el.dataset.autoplayDelay, 10 ) || 3500;

		function stampPositions( swiper ) {
			var slides = swiper.slides;
			var activeIndex = -1;
			var i;

			for ( i = 0; i < slides.length; i++ ) {
				if ( slides[ i ].classList.contains( 'swiper-slide-active' ) ) {
					activeIndex = i;
					break;
				}
			}

			if ( activeIndex < 0 ) {
				return;
			}

			// Raw offset, not clamped: the fan is exactly five cards, and
			// src/base.css hides anything outside ±2 so no sixth card peeks in
			// at the edges.
			for ( i = 0; i < slides.length; i++ ) {
				slides[ i ].dataset.pos = i - activeIndex;
			}
		}

		var swiper = new Swiper( el, {
			slidesPerView: 'auto',
			centeredSlides: true,
			spaceBetween: 0,
			loop: true,
			// Third slide: the fallback photos are ordered left-to-right as they
			// sit in the Figma fan, so this opens on the one Figma centres.
			initialSlide: 2,
			grabCursor: true,
			speed: 600,
			autoplay: prefersReducedMotion ? false : {
				delay: autoplayDelay,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			},
			on: {
				init: stampPositions,
				slideChangeTransitionStart: stampPositions,
				loopFix: stampPositions,
			},
		} );

		// Don't animate a carousel nobody is looking at.
		if ( swiper.autoplay && 'IntersectionObserver' in window ) {
			new IntersectionObserver(
				function ( entries ) {
					if ( entries[ 0 ].isIntersecting ) {
						swiper.autoplay.start();
					} else {
						swiper.autoplay.stop();
					}
				},
				{ threshold: 0.2 }
			).observe( el );
		}
	} );
} );
