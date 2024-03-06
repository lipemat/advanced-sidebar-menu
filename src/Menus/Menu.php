<?php

namespace Advanced_Sidebar_Menu\Menus;

/**
 * Rules for a Menu class.
 *
 * Previously managed by the being phased out Menu_Abstract.
 *
 * @author OnPoint Plugins
 * @since  9.5.0
 */
interface Menu {
	/**
	 * Is this item excluded from this menu?
	 *
	 * @param int|string $id ID of the object.
	 *
	 * @return bool
	 */
	public function is_excluded( $id ): bool;
}
