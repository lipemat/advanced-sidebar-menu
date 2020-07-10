<?php

namespace Advanced_Sidebar_Menu;

/**
 * This walker's only purpose is to allow us to close our menus only when needed.
 */
class Page_Walker extends \Walker_Page {
	function end_el( &$output, $page, $depth = 0, $args = [] ) {
		/** Do Nothing */
	}
}
