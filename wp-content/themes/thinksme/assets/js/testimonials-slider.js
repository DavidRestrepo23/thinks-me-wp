/**
 * Google Reviews slider (template-parts/testimonials.php).
 *
 * Centered Swiper carousel. The only thing this writes to the DOM is data-pos
 * on each slide — 0 for the centered card, negative to its left, positive to
 * its right, clamped to ±2 — and src/base.css turns that into the Figma
 * palette and tilt. Doing it by position (rather than per review) keeps the
 * navy highlight in the middle as the carousel moves.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	if ( typeof Swiper === 'undefined' ) {
		return;
	}

	var MAX_POS = 2;
	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	document.querySelectorAll( '.testimonials-slider' ).forEach( function ( el ) {
		var section = el.closest( '.testimonials' ) || document;
		var autoplayDelay = parseInt( el.dataset.autoplayDelay, 10 ) || 5000;

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

			for ( i = 0; i < slides.length; i++ ) {
				var offset = i - activeIndex;
				if ( offset < -MAX_POS ) {
					offset = -MAX_POS;
				} else if ( offset > MAX_POS ) {
					offset = MAX_POS;
				}
				slides[ i ].dataset.pos = offset;
			}
		}

		var swiper = new Swiper( el, {
			slidesPerView: 'auto',
			centeredSlides: true,
			spaceBetween: 16,
			loop: true,
			grabCursor: true,
			speed: 500,
			// Autoplay keeps running after a drag or an arrow click
			// (disableOnInteraction: false) but yields while the pointer is over
			// the carousel, so a review being read doesn't slide away. Off
			// entirely under prefers-reduced-motion — the arrows still work.
			autoplay: prefersReducedMotion ? false : {
				delay: autoplayDelay,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			},
			breakpoints: {
				1024: {
					spaceBetween: 28,
				},
			},
			navigation: {
				prevEl: section.querySelector( '.testimonials-nav--prev' ),
				nextEl: section.querySelector( '.testimonials-nav--next' ),
			},
			keyboard: {
				enabled: true,
				onlyInViewport: true,
			},
			on: {
				// Covers init, arrow clicks, drags and the loop's slide
				// re-ordering alike — every one of them ends in a transition.
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
