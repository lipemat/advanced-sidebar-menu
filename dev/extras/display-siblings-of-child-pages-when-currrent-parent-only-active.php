<?php
/**
 * Only works with pro version with "Display current page parent only" checked
 *
 */
add_filter( 'advanced_sidebar_menu_widget_pre_render', 'asm_show_child_siblings', 100 );
function asm_show_child_siblings( $menu ) {
	/**
	 * @var \Advanced_Sidebar_Menu_Menus_Page $menu
	 */
	if( is_page() && get_post()->post_parent === $menu->get_top_parent_id() ){
		remove_all_filters( 'advanced_sidebar_menu_child_pages' );
	}
}