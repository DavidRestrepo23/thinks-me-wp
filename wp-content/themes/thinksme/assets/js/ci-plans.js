/**
 * Pricing slider (template-parts/ci-pricing.php).
 *
 * Mobile only: below lg the three plan cards loop and autoplay, opening on the
 * highlighted one. Above lg they are the Figma row of three and Swiper must not
 * exist at all — its container clips overflow, which would cut off the "MOST
 * POPULAR" tab hanging above the middle card.
 *
 * Swiper's class names are stamped on here rather than written into the
 * template for the same reason: `.swiper`/`.swiper-wrapper` carry layout of
 * their own in swiper-bundle.css, so on desktop they'd apply with nothing
 * driving them. The markup ships as a plain flex row, base.css turns it into a
 * scroll-snap rail below lg, and this script swaps that rail for Swiper. Each
 * layer degrades to the one under it: no JS is still a swipeable rail, and no
 * Swiper (or reduced motion) is still a slider, just without the autoplay.
 *
 * The breakpoint is duplicated from base.css because Swiper has to be built and
 * destroyed on the crossing, not restyled — Swiper's own `breakpoints` can only
 * change options on an instance that already exists.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var container = document.querySelector( '.ci-plans' );

	if ( ! container || typeof Swiper === 'undefined' ) {
		return;
	}

	var track = container.querySelector( '.ci-plans__track' );
	var slides = container.querySelectorAll( '.ci-plan' );

	if ( ! track || slides.length < 2 ) {
		// One card has nothing to loop through; leave it as the rail it is.
		return;
	}

	var mobile = window.matchMedia( '(max-width: 1023.98px)' );
	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var featured = container.querySelector( '.ci-plan[data-featured="true"]' );
	var initialSlide = featured ? Array.prototype.indexOf.call( slides, featured ) : 0;
	var swiper = null;
	var observer = null;

	// Swiper wants roughly twice as many slides as are on screen before it will
	// loop, and centred slides at nearly full width put that at about four. The
	// pages that price two packages are under it, so Swiper was disabling the loop
	// and saying so in the console — and loop + autoplay is the client's rule for
	// every carousel in the theme. So the set is duplicated, which is what Swiper's
	// own warning asks for, and the copies are aria-hidden: they are the same cards
	// again and a screen reader should hear them once.
	//
	// Unlike ci-why-slider.js the copies cannot stay in the DOM: above lg this
	// section is a plain Figma row, where a duplicate card is simply a wrong extra
	// column. They are made in build() and removed in teardown(), which is also why
	// nothing here holds on to the original NodeList.
	function currentSlides() {
		return track.querySelectorAll( '.ci-plan' );
	}

	function duplicate() {
		var original = Array.prototype.slice.call( currentSlides() );

		while ( currentSlides().length < 4 ) {
			for ( var c = 0; c < original.length; c++ ) {
				var copy = original[ c ].cloneNode( true );

				copy.setAttribute( 'aria-hidden', 'true' );
				copy.setAttribute( 'data-duplicate', 'true' );
				track.appendChild( copy );
			}
		}
	}

	function build() {
		if ( swiper ) {
			return;
		}

		duplicate();

		container.classList.add( 'swiper' );
		track.classList.add( 'swiper-wrapper' );
		Array.prototype.forEach.call( currentSlides(), function ( slide ) {
			slide.classList.add( 'swiper-slide' );
		} );

		swiper = new Swiper( container, {
			// Widths come from CSS (`.ci-plans.swiper .ci-plan`), so the peek is
			// defined in one place with the un-enhanced rail's padding.
			slidesPerView: 'auto',
			centeredSlides: true,
			spaceBetween: 16,
			loop: true,
			initialSlide: initialSlide,
			grabCursor: true,
			speed: 500,
			// Same bargain as the reviews carousel: a swipe doesn't kill autoplay,
			// but reduced motion does — the cards are still swipeable then.
			autoplay: prefersReducedMotion ? false : {
				delay: 5000,
				disableOnInteraction: false,
				pauseOnMouseEnter: true,
			},
			keyboard: {
				enabled: true,
				onlyInViewport: true,
			},
			a11y: {
				containerMessage: 'Incorporation packages',
			},
		} );

		// Don't animate a carousel nobody is looking at. The observer is kept so
		// teardown can disconnect it: this Swiper is destroyed every time the
		// viewport crosses lg, and an observer still pointing at the destroyed
		// instance throws on its next callback.
		if ( swiper.autoplay && 'IntersectionObserver' in window ) {
			observer = new IntersectionObserver(
				function ( entries ) {
					if ( ! swiper || ! swiper.autoplay ) {
						return;
					}

					if ( entries[ 0 ].isIntersecting ) {
						swiper.autoplay.start();
					} else {
						swiper.autoplay.stop();
					}
				},
				{ threshold: 0.2 }
			);

			observer.observe( container );
		}
	}

	function teardown() {
		if ( ! swiper ) {
			return;
		}

		if ( observer ) {
			observer.disconnect();
			observer = null;
		}

		swiper.destroy( true, true );
		swiper = null;

		// destroy() leaves this one behind, and it is Swiper's, not ours.
		container.classList.remove( 'swiper', 'swiper-backface-hidden' );
		track.classList.remove( 'swiper-wrapper' );

		// The copies go with it: the desktop row is the design's own three (or two)
		// cards, and a duplicate there is an extra column nobody drew.
		var copies = track.querySelectorAll( '.ci-plan[data-duplicate]' );

		for ( var i = 0; i < copies.length; i++ ) {
			copies[ i ].parentNode.removeChild( copies[ i ] );
		}

		Array.prototype.forEach.call( currentSlides(), function ( slide ) {
			slide.classList.remove( 'swiper-slide' );
		} );
	}

	function sync( event ) {
		if ( event.matches ) {
			build();
		} else {
			teardown();
		}
	}

	sync( mobile );
	mobile.addEventListener( 'change', sync );
} );
