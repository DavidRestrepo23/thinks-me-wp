/**
 * Mobile nav panel (header.php). Desktop needs no JS — the menu is always
 * visible and submenus open on hover/focus via CSS.
 *
 * State lives in one attribute, data-nav-open on the header, which src/base.css
 * keys off; aria-expanded on the button is kept in sync for screen readers.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var header = document.querySelector( '.site-header' );
	if ( ! header ) {
		return;
	}

	var toggle = header.querySelector( '.site-header__toggle' );
	if ( ! toggle ) {
		return;
	}

	function setOpen( open ) {
		header.dataset.navOpen = open ? 'true' : 'false';
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	}

	setOpen( false );

	toggle.addEventListener( 'click', function () {
		setOpen( 'true' !== header.dataset.navOpen );
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && 'true' === header.dataset.navOpen ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	document.addEventListener( 'click', function ( event ) {
		if ( 'true' === header.dataset.navOpen && ! header.contains( event.target ) ) {
			setOpen( false );
		}
	} );

	// Resizing past the breakpoint reveals the desktop nav anyway; drop the open
	// state so it isn't still "open" when the viewport goes back down.
	window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( event ) {
		if ( event.matches ) {
			setOpen( false );
		}
	} );
} );
