<?php

namespace Advanced_Sidebar_Menu\Walkers;

/**
 * This walker's only purpose is to allow us to close our menus only when needed.
 */
class Page_Walker extends \Walker_Page {
	//phpcs:disable
	function end_el( &$output, $page, $depth = 0, $args = [] ) {
		/** Do Nothing */
	}
}
