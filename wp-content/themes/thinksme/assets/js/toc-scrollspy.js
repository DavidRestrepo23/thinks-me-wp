/**
 * Table-of-contents scrollspy — template-parts/legal-toc.php (Figma 130:2165,
 * numbered list) and template-parts/blog-toc.php (Figma 134:133/134:137, the
 * rail-with-fill skin on single blog posts).
 *
 * Moves `data-active` between the sidebar links (`[data-toc-link]`) as their
 * matching sections (`[data-toc-section]`) cross a line near the top of the
 * viewport, via IntersectionObserver rather than a scroll handler. The first
 * link starts `data-active="true"` in markup so the design's static look holds
 * before this runs or if it never loads (no JS, IntersectionObserver
 * unsupported); this only ever re-targets that same attribute, never adds a
 * class the CSS doesn't already key off (see `.legal-toc a[data-active]` /
 * `.blog-toc__link[data-active]` in src/base.css).
 *
 * blog-toc.php additionally carries a `[data-toc-fill]` bar that tracks the
 * active link's position down a rail (`[data-toc-rail]`), Figma's yellow
 * marker against the pale track. Every active-link change repositions it by
 * reading the link's own offsetTop/offsetHeight — both are direct children of
 * the same `[data-toc-scrollspy]` flex row, so no extra bookkeeping is needed
 * to keep them lined up. legal-toc.php's group has no `[data-toc-fill]`, so
 * that step is just skipped there rather than needing a second script.
 *
 * Every group on the page is initialised, not just the first — the same
 * convention expand-cards.js and tabs.js follow.
 */
( function () {
	if ( typeof IntersectionObserver === 'undefined' ) {
		return;
	}

	var groups = document.querySelectorAll( '[data-toc-scrollspy]' );

	for ( var g = 0; g < groups.length; g++ ) {
		init( groups[ g ] );
	}

	function init( root ) {
		var sections = root.querySelectorAll( '[data-toc-section]' );
		var links = root.querySelectorAll( '[data-toc-link]' );

		if ( ! sections.length || ! links.length ) {
			return;
		}

		var linkById = {};
		for ( var i = 0; i < links.length; i++ ) {
			linkById[ links[ i ].getAttribute( 'data-toc-link' ) ] = links[ i ];
		}

		var fill = root.querySelector( '[data-toc-fill]' );

		var visible = {};

		var observer = new IntersectionObserver(
			function ( entries ) {
				for ( var e = 0; e < entries.length; e++ ) {
					visible[ entries[ e ].target.id ] = entries[ e ].isIntersecting;
				}

				var activeId = null;
				for ( var s = 0; s < sections.length; s++ ) {
					if ( visible[ sections[ s ].id ] ) {
						activeId = sections[ s ].id;
						break;
					}
				}

				if ( ! activeId ) {
					return;
				}

				for ( var id in linkById ) {
					if ( Object.prototype.hasOwnProperty.call( linkById, id ) ) {
						linkById[ id ].setAttribute( 'data-active', id === activeId ? 'true' : 'false' );
					}
				}

				if ( fill && linkById[ activeId ] ) {
					fill.style.top = linkById[ activeId ].offsetTop + 'px';
					fill.style.height = linkById[ activeId ].offsetHeight + 'px';
				}
			},
			{ rootMargin: '-100px 0px -70% 0px' }
		);

		for ( var s2 = 0; s2 < sections.length; s2++ ) {
			observer.observe( sections[ s2 ] );
		}
	}
} )();
