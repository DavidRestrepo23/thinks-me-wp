/**
 * Free-tools tabs (template-parts/ci-tools.php).
 *
 * This file only moves `data-active` / `aria-selected` between the tabs and
 * toggles [hidden] on the panels — every pixel of the open/closed geometry is
 * CSS (src/base.css). If this script never loads, the panel the template marked
 * active stays visible and the section still reads as the Figma state; the other
 * tabs simply don't switch.
 *
 * Roving tabindex plus arrow/Home/End keys, per the WAI-ARIA tabs pattern: only
 * the selected tab is in the tab order, and the arrow keys move between them.
 * The tools themselves have no behaviour yet — this is switching only.
 */
( function () {
	var group = document.querySelector( '.ci-tools' );

	if ( ! group ) {
		return;
	}

	var tabs = group.querySelectorAll( '.ci-tools__tab' );

	if ( tabs.length < 2 ) {
		// A lone tab is already open and has nothing to switch with; leave it be
		// rather than letting a click hide the only panel.
		return;
	}

	function panelFor( tab ) {
		return document.getElementById( tab.getAttribute( 'aria-controls' ) );
	}

	// Below lg the strip is hidden and this <select> is the control instead
	// (src/base.css, Figma 100:10). It drives the same tabs, and it is kept in
	// step when the strip is what moved, so a resize across the breakpoint never
	// shows a dropdown naming a tab that isn't open.
	var dropdown = group.querySelector( '.ci-tools__select-field' );

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
} )();
