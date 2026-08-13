/**
 * "Why founders choose us" carousel (template-parts/ci-why-slider.php).
 *
 * Figma draws the five cards as a 2703px row inside a 1440px frame with a
 * prev/next pair under it (102:2410, 102:2260) — a carousel on the canvas, so a
 * carousel here. Unlike ci-plans.js this one runs at every width: the row is
 * wider than the viewport on a desktop too, which is the whole point of it.
 *
 * Swiper's class names are stamped on here rather than written into the template
 * because `.swiper`/`.swiper-wrapper` carry layout of their own in
 * swiper-bundle.css; the markup ships as a plain flex row that base.css turns
 * into a scroll-snap rail, and this script swaps that rail for Swiper. So no JS
 * (or no Swiper) is still a swipeable rail, just without the arrows — which is
 * why the template ships them hidden and this script reveals them.
 *
 * Slide widths live in CSS (`.ci-why-slider .ci-why-slide`) so the peek is
 * defined in one place with the un-enhanced rail's padding, hence
 * `slidesPerView: 'auto'`.
 *
 * Loops and autoplays, like every other carousel in the theme. The cards carry a
 * paragraph each rather than a glanceable quote, so the delay is longer than the
 * reviews slider's and the autoplay pauses on hover and stops for reduced
 * motion — the same bargain ci-plans.js makes.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var container = document.querySelector( '.ci-why-slider' );

	if ( ! container || typeof Swiper === 'undefined' ) {
		return;
	}

	var track = container.querySelector( '.ci-why-slider__track' );
	var slides = container.querySelectorAll( '.ci-why-slide' );
	var nav = document.querySelector( '.ci-why-slider__nav' );

	if ( ! track || slides.length < 2 ) {
		// One card has nothing to page through; leave it as the rail it is.
		return;
	}

	// Swiper needs roughly twice as many slides as are on screen before it will
	// loop; with `slidesPerView: 'auto'` and cards this wide that is about six, and
	// under it Swiper disables looping and says so in the console. Both pages that
	// run this section are under it — five cards on Corporate Secretary, three on
	// Corporate Tax — so the set is duplicated until there is enough material,
	// which is what Swiper's own warning asks for. The copies are aria-hidden: they
	// are the same cards again, and a screen reader should hear them once. Loop and
	// autoplay are the client's rule for every carousel in the theme, so making the
	// slider silently linear here isn't an option.
	if ( slides.length < 6 ) {
		var original = Array.prototype.slice.call( slides );

		while ( track.querySelectorAll( '.ci-why-slide' ).length < 6 ) {
			for ( var c = 0; c < original.length; c++ ) {
				var copy = original[ c ].cloneNode( true );

				copy.setAttribute( 'aria-hidden', 'true' );
				copy.setAttribute( 'data-duplicate', 'true' );
				track.appendChild( copy );
			}
		}

		slides = track.querySelectorAll( '.ci-why-slide' );
	}

	container.classList.add( 'swiper' );
	track.classList.add( 'swiper-wrapper' );
	Array.prototype.forEach.call( slides, function ( slide ) {
		slide.classList.add( 'swiper-slide' );
	} );

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	var options = {
		slidesPerView: 'auto',
		spaceBetween: 16,
		grabCursor: true,
		speed: 500,
		loop: true,
		// A swipe doesn't kill the autoplay, but reduced motion does — the cards
		// are still swipeable then, they just stop moving on their own.
		autoplay: prefersReducedMotion ? false : {
			delay: 6000,
			disableOnInteraction: false,
			pauseOnMouseEnter: true,
		},
		keyboard: {
			enabled: true,
			onlyInViewport: true,
		},
		a11y: {
			containerMessage: 'Reasons to appoint a corporate secretary',
		},
		breakpoints: {
			1024: {
				spaceBetween: 32,
			},
		},
	};

	if ( nav ) {
		nav.hidden = false;
		options.navigation = {
			prevEl: nav.querySelector( '.ci-why-slider__button--prev' ),
			nextEl: nav.querySelector( '.ci-why-slider__button--next' ),
		};
	}

	var swiper = new Swiper( container, options );

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
		).observe( container );
	}
} );
