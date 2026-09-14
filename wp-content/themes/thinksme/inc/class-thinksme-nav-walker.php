<?php
/**
 * Primary-nav walker: turns a WordPress menu into the desktop megamenu.
 * Figma: node 63:294 ("Desktop Megamenu V1"), file "Untitled" (vzdpOnH1U36oXcFcugiyE5).
 *
 * Why a walker and not hand-built markup: staying inside wp_nav_menu() keeps
 * everything WordPress gives a menu for free — current-page classes, the menu
 * item ID/classes the client can set in wp-admin, translations, and any plugin
 * filters — while still emitting the panel markup the design needs, which
 * `sub-menu` <ul>s can't express.
 *
 * Menu depth is what picks the layout, so the client controls it from
 * Appearance > Menus with no code change:
 *
 *   - Top level        → a nav item. With children it opens the panel.
 *   - 2nd level with   → a column: its title becomes the column heading and its
 *     children           own children become the cards. This is the design.
 *   - 2nd level without→ a card of its own, one per column cell. A two-level
 *     children           menu therefore renders as a row of cards rather than
 *                        breaking.
 *
 * Per-card content: the icon is an ACF field on the menu item (`menu_icon`, see
 * acf-json/group_thinksme_menu_item.json) and the grey line under the title is
 * the menu item's own native Description field — the client has to switch
 * "Description" on once under Screen Options in Appearance > Menus. An item with
 * no description renders the title alone rather than placeholder copy.
 *
 * Layout/colour all live in src/base.css (`.megamenu*`), same reasoning as the
 * rest of the header: markup here, styling there.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thinksme_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Stamp each element with what its subtree looks like before it renders.
	 * The walker only ever sees one item at a time, but $children_elements holds
	 * the whole tree, so this is the one place that can tell a column (a 2nd-level
	 * item with children) from a card (one without).
	 *
	 * @param object $element            Menu item.
	 * @param array  $children_elements  Children keyed by parent ID.
	 * @param int    $max_depth          Max depth.
	 * @param int    $depth              Current depth.
	 * @param array  $args               wp_nav_menu args.
	 * @param string $output             Accumulated markup.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( $element ) {
			$children = isset( $children_elements[ $element->ID ] ) ? $children_elements[ $element->ID ] : array();

			$element->thinksme_has_children = ! empty( $children );

			if ( 0 === $depth ) {
				$element->thinksme_is_dropdown = $this->is_dropdown( $children, $children_elements );
			}
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/**
	 * Whether a top-level item's panel is the narrow dropdown rather than the
	 * viewport-wide megamenu. Figma: node 172:148, same file.
	 *
	 * Two conditions, and the second is the one that matters: the item has
	 * between two and five children, and *none* of them has children of its
	 * own. A panel whose 2nd-level items are columns (Figma's megamenu, node
	 * 63:294) needs the full width to hold its headings and their card lists,
	 * and "Corporate Service" is exactly three of those — so counting alone
	 * would turn the design's own megamenu into a 544px dropdown. What makes a
	 * panel a dropdown is that it is a plain list of cards; the count only says
	 * it is short enough to read as one.
	 *
	 * Six or more cards go back to the wide panel's grid, where they wrap into
	 * rows instead of running past the fold as one column.
	 *
	 * @param array $children          The item's own children.
	 * @param array $children_elements Children keyed by parent ID.
	 * @return bool
	 */
	private function is_dropdown( $children, $children_elements ) {
		$count = count( $children );

		if ( $count < 2 || $count > 5 ) {
			return false;
		}

		foreach ( $children as $child ) {
			if ( ! empty( $children_elements[ $child->ID ] ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Opening a submenu. Depth 0 is the panel itself: an outer positioning box
	 * (which also paints the blurred backdrop over the page, see base.css) around
	 * the card grid, because the grid has to clip its own rounded corners and
	 * clipping the backdrop with them would cut it off.
	 *
	 * @param string   $output Accumulated markup.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   wp_nav_menu args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '<div class="megamenu"><div class="megamenu__grid">';
			return;
		}

		if ( 1 === $depth ) {
			$output .= '<ul class="megamenu__list">';
			return;
		}

		// Nothing in the design goes deeper; fall back to the plain flyout the
		// header already styles rather than dropping the items.
		$output .= '<ul class="sub-menu">';
	}

	/**
	 * @param string   $output Accumulated markup.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   wp_nav_menu args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div></div>';
			return;
		}

		$output .= '</ul>';
	}

	/**
	 * @param string   $output Accumulated markup.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   wp_nav_menu args.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( 1 === $depth ) {
			$has_children = ! empty( $item->thinksme_has_children );

			$output .= '<div class="megamenu__col' . ( $has_children ? '' : ' megamenu__col--card' ) . '">';

			if ( $has_children ) {
				$output .= $this->column_heading( $item );
			} else {
				$output .= $this->card( $item );
			}

			return;
		}

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Which of the two panels this item opens. A class on the <li> rather
		// than on the panel itself: start_lvl() is what emits .megamenu and it
		// is handed no element to ask, and the CSS has to reach the <li>
		// anyway — the dropdown is positioned against its nav item where the
		// megamenu is positioned against the whole header.
		if ( 0 === $depth && ! empty( $item->thinksme_is_dropdown ) ) {
			$classes[] = 'menu-item--dropdown';
		}

		if ( 2 === $depth ) {
			$classes[] = 'megamenu__item';
		}

		$class_names = implode( ' ', array_filter( array_unique( $classes ) ) );
		$output     .= '<li class="' . esc_attr( $class_names ) . '">';

		if ( 2 === $depth ) {
			$output .= $this->card( $item );
			return;
		}

		$output .= '<a' . $this->link_attributes( $item ) . '>' . esc_html( $item->title ) . '</a>';
	}

	/**
	 * @param string   $output Accumulated markup.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   wp_nav_menu args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= 1 === $depth ? '</div>' : '</li>';
	}

	/**
	 * Column heading. Plain text unless the item actually points somewhere — a
	 * heading linking to "#" would be a keyboard stop that does nothing.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	private function column_heading( $item ) {
		$title = esc_html( $item->title );

		if ( $this->is_real_link( $item ) ) {
			return '<p class="megamenu__heading"><a' . $this->link_attributes( $item ) . '>' . $title . '</a></p>';
		}

		return '<p class="megamenu__heading">' . $title . '</p>';
	}

	/**
	 * One card: yellow icon tile, title, optional description, caret.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	private function card( $item ) {
		$icons_uri   = get_template_directory_uri() . '/assets/images/icons';
		$icon_url    = thinksme_menu_icon_url( thinksme_field( 'menu_icon', $item->ID ) );
		$description = trim( (string) $item->description );

		$card  = '<a class="megamenu__card"' . $this->link_attributes( $item ) . '>';
		$card .= '<span class="megamenu__icon"><img src="' . esc_url( $icon_url ) . '" alt="" width="24" height="24"></span>';
		$card .= '<span class="megamenu__text">';
		$card .= '<span class="megamenu__title">' . esc_html( $item->title ) . '</span>';

		if ( '' !== $description ) {
			$card .= '<span class="megamenu__desc">' . esc_html( $description ) . '</span>';
		}

		$card .= '</span>';
		$card .= '<img class="megamenu__caret" src="' . esc_url( "$icons_uri/caret-right.svg" ) . '" alt="" aria-hidden="true">';
		$card .= '</a>';

		return $card;
	}

	/**
	 * href/target/rel/title for a menu item, from the fields wp-admin exposes.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	private function link_attributes( $item ) {
		$attributes = ' href="' . esc_url( $item->url ? $item->url : '#' ) . '"';

		if ( ! empty( $item->target ) ) {
			$attributes .= ' target="' . esc_attr( $item->target ) . '"';
			// A target of _blank without this hands the new tab a window.opener
			// reference back to the site.
			$attributes .= ' rel="' . esc_attr( trim( $item->xfn . ' noopener' ) ) . '"';
		} elseif ( ! empty( $item->xfn ) ) {
			$attributes .= ' rel="' . esc_attr( $item->xfn ) . '"';
		}

		if ( ! empty( $item->attr_title ) ) {
			$attributes .= ' title="' . esc_attr( $item->attr_title ) . '"';
		}

		return $attributes;
	}

	/**
	 * Whether the item points at something other than the placeholder "#".
	 *
	 * @param WP_Post $item Menu item.
	 * @return bool
	 */
	private function is_real_link( $item ) {
		$url = trim( (string) $item->url );

		return '' !== $url && '#' !== $url;
	}
}
