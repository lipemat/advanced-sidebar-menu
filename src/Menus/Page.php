<?php


/**
 * Advanced_Sidebar_Menu_Menus_Page
 *
 * @author Mat Lipe
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Menus_Page extends Advanced_Sidebar_Menu_Menus_Abstract {
	/**
	 * post_type
	 *
	 * @var string
	 */
	public $post_type = 'page';

	public static function factory( array $widget_instance, array $widget_args ) {
		return parent::factory( __CLASS__, $widget_instance, $widget_args );
	}
}