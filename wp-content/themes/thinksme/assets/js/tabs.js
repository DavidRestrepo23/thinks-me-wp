/**
 * Tab strips, shared by template-parts/ci-tools.php (the free-tools panel on the
 * three Company Incorporation pages) and template-parts/ci-eligibility.php (the
 * Property Cashout page's two-tab checklist).
 *
 * This used to be `ci-tools.js` and was named after the one section that had a
 * tab strip. The Property Cashout frame draws a second one whose panel looks
 * nothing like the first — a white card with a checklist and a photograph rather
 * than a navy card with an input — so the behaviour was lifted out the same way
 * `roa-progress.js` became `progress-rail.js`: driven by data attributes instead
 * of by one section's class names, and applied to every group on the page rather
 * than the first one found.
 *
 * The markup opts in with `data-tabs` on the group and, inside it, `data-tab` on
 * each button, `data-tab-panel` on each panel and (optionally) `data-tab-select`
 * on a <select> that drives the same tabs where the strip is too wide to be a
 * row. Appearance stays entirely in src/base.css, keyed off `data-active` — so
 * with this file gone the panel the template marked active stays open and each
 * section still reads as its Figma state.
 *
 * Roving tabindex plus arrow/Home/End keys, per the WAI-ARIA tabs pattern: only
 * the selected tab is in the tab order and the arrow keys move between them.
 */
( function () {
	var groups = document.querySelectorAll( '[data-tabs]' );

	for ( var g = 0; g < groups.length; g++ ) {
		init( groups[ g ] );
	}

	function init( group ) {
		var tabs = group.querySelectorAll( '[data-tab]' );

		if ( tabs.length < 2 ) {
			// A lone tab is already open and has nothing to switch with; leave it
			// be rather than letting a click hide the only panel.
			return;
		}

		// Where a strip of long labels can't be a row on a phone, src/base.css
		// hides it and shows this <select> instead. It drives the same tabs, and
		// it is kept in step when the strip is what moved, so a resize across the
		// breakpoint never shows a dropdown naming a tab that isn't open.
		var dropdown = group.querySelector( '[data-tab-select]' );

		function panelFor( tab ) {
			return document.getElementById( tab.getAttribute( 'aria-controls' ) );
		}

		function select( tab, moveFocus ) {
			for ( var i = 0; i < tabs.length; i++ ) {
				var isActive = tabs[ i ] === tab;
				var panel    = panelFor( tabs[ i ] );

				tabs[ i ].setAttribute( 'data-active', isActive ? 'true' : 'false' );
				tabs[ i ].setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
				tabs[ i ].setAttribute( 'tabindex', isActive ? '0' : '-1' );

				if ( panel ) {
					if ( isActive ) {
						panel.removeAttribute( 'hidden' );
					} else {
						panel.setAttribute( 'hidden', '' );
					}
				}
			}

			if ( dropdown && dropdown.value !== tab.id ) {
				dropdown.value = tab.id;
			}

			if ( moveFocus ) {
				tab.focus();
			}
		}

		if ( dropdown ) {
			dropdown.addEventListener( 'change', function () {
				var tab = document.getElementById( this.value );

				if ( tab ) {
					select( tab, false );
				}
			} );
		}

		for ( var i = 0; i < tabs.length; i++ ) {
			tabs[ i ].addEventListener( 'click', function () {
				select( this, false );
			} );

			tabs[ i ].addEventListener( 'keydown', function ( event ) {
				var current = Array.prototype.indexOf.call( tabs, this );
				var next    = -1;

				if ( 'ArrowRight' === event.key || 'ArrowDown' === event.key ) {
					next = ( current + 1 ) % tabs.length;
				} else if ( 'ArrowLeft' === event.key || 'ArrowUp' === event.key ) {
					next = ( current - 1 + tabs.length ) % tabs.length;
				} else if ( 'Home' === event.key ) {
					next = 0;
				} else if ( 'End' === event.key ) {
					next = tabs.length - 1;
				}

				if ( next > -1 ) {
					event.preventDefault();
					select( tabs[ next ], true );
				}
			} );
		}
	}
} )();
