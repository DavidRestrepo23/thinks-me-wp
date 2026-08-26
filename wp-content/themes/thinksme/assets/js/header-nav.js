/**
 * Mobile nav panel (header.php). Desktop needs no JS — the menu is always
 * visible and submenus open on hover/focus via CSS.
 *
 * State lives in two attributes: data-nav-open on the header (whole panel
 * open/closed) and an is-drilled class on <nav> (root list vs. a tapped
 * item's submenu), both of which src/base.css keys off. aria-expanded on the
 * toggle is kept in sync for screen readers.
 */
document.addEventListener( 'DOMContentLoaded', function () {
	var header = document.querySelector( '.site-header' );
	if ( ! header ) {
		return;
	}

	var toggle = header.querySelector( '.site-header__toggle' );
	var nav    = header.querySelector( '.site-header__nav' );
	if ( ! toggle || ! nav ) {
		return;
	}

	var menu         = nav.querySelector( '.site-header__menu' );
	var submenuSlot  = nav.querySelector( '.site-header__submenu-slot' );
	var subnav       = nav.querySelector( '.site-header__subnav' );
	var subnavTitle  = subnav ? subnav.querySelector( '.site-header__subnav-title' ) : null;
	var backButton   = subnav ? subnav.querySelector( '.site-header__back' ) : null;
	var mobileQuery  = window.matchMedia( '(max-width: 1023.98px)' );

	// activeItem tracks the <li> a moved .megamenu came from, so "back" can put
	// it back exactly where display_element() rendered it — required for the
	// desktop hover CSS (li.menu-item-has-children:hover > .megamenu), which
	// only matches a directly-nested panel.
	var activeItem = null;

	function updateHeaderHeight() {
		document.documentElement.style.setProperty( '--site-header-height', header.offsetHeight + 'px' );
	}

	updateHeaderHeight();
	window.addEventListener( 'resize', updateHeaderHeight );

	function closeSubmenu() {
		if ( activeItem ) {
			activeItem.appendChild( activeItem.thinksmeMegamenu );
			activeItem.thinksmeMegamenu.classList.remove( 'is-active' );
			activeItem = null;
		}

		nav.classList.remove( 'is-drilled' );

		if ( subnav ) {
			subnav.hidden = true;
		}
	}

	function openSubmenu( item ) {
		var megamenu = item.querySelector( ':scope > .megamenu' );
		var link     = item.querySelector( ':scope > a' );

		if ( ! megamenu || ! link || ! submenuSlot || ! subnav || ! subnavTitle ) {
			return;
		}

		if ( activeItem && activeItem !== item ) {
			closeSubmenu();
		}

		activeItem                    = item;
		activeItem.thinksmeMegamenu   = megamenu;

		submenuSlot.appendChild( megamenu );
		megamenu.classList.add( 'is-active' );
		subnavTitle.textContent = link.textContent.trim();
		subnav.hidden            = false;
		nav.classList.add( 'is-drilled' );
		nav.querySelector( '.site-header__nav-scroll' ).scrollTop = 0;
	}

	function setOpen( open ) {
		header.dataset.navOpen = open ? 'true' : 'false';
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

		if ( ! open ) {
			closeSubmenu();
		}
	}

	setOpen( false );

	toggle.addEventListener( 'click', function () {
		setOpen( 'true' !== header.dataset.navOpen );
	} );

	if ( menu ) {
		menu.addEventListener( 'click', function ( event ) {
			if ( ! mobileQuery.matches ) {
				return;
			}

			var link = event.target.closest( '.site-header__menu > li.menu-item-has-children > a' );
			if ( ! link ) {
				return;
			}

			event.preventDefault();
			openSubmenu( link.closest( 'li' ) );
		} );
	}

	if ( backButton ) {
		backButton.addEventListener( 'click', closeSubmenu );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' !== event.key || 'true' !== header.dataset.navOpen ) {
			return;
		}

		if ( nav.classList.contains( 'is-drilled' ) ) {
			closeSubmenu();
		} else {
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
	// state (and any drilled-into submenu, whose .megamenu has to be back under
	// its <li> for the desktop hover CSS to find it) so it isn't still "open"
	// when the viewport goes back down.
	window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( event ) {
		if ( event.matches ) {
			setOpen( false );
		}
	} );
} );
