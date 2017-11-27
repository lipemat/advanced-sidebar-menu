<?php
/**
 * @deprecated
 */
class Advanced_Sidebar_Menu_Menu {
	/**
	 * Advanced_Sidebar_Menu_Menu constructor.
	 *
	 * @deprecated
	 */
	public function __construct() {
		_deprecated_constructor( 'Advanced_Sidebar_Menu_Menu', '7.0.0' );
	}


	/**
	 * current
	 *
	 * @static
	 * @var \Advanced_Sidebar_Menu_Menus_Page|\Advanced_Sidebar_Menu_Menus_Category
	 */
	private static $current;


	public static function set_current( $menu ) {
		self::$current = $menu;
	}


	/**
	 *
	 * @deprecated
	 */
	public static function get_current() {
		//_deprecated_function( 'Advanced_Sidebar_Menu_Menu::get_current()', '7.0.0', 'Advanced_Sidebar_Menu_Menus_Page::factory()' );

		return self::$current;
	}
}
