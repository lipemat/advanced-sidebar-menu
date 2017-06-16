<?php
/*
Plugin Name: Advanced Sidebar Menu
Plugin URI: https://matlipe.com/advanced-sidebar-menu/
Description: Creates dynamic menus based on parent/child relationship of your pages or categories.
Author: Mat Lipe
Version: 6.3.0
Author URI: https://matlipe.com
Text Domain: advanced-sidebar-menu
*/

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '6.3.0' );
define( 'ADVANCED_SIDEBAR_DIR', plugin_dir_path( __FILE__ ) );

if( !function_exists( 'advanced_sidebar_menu_load' ) ){
	function advanced_sidebar_menu_load(){
		//widgets
		require( ADVANCED_SIDEBAR_DIR . 'src/widgets/advanced_sidebar_menu_category.php' );
		require( ADVANCED_SIDEBAR_DIR . 'src/widgets/advanced_sidebar_menu_page.php' );

		require( ADVANCED_SIDEBAR_DIR . 'src/Advanced_Sidebar_Menu.php' );
		require( ADVANCED_SIDEBAR_DIR . 'src/Advanced_Sidebar_Menu_Menu.php' );

		require( ADVANCED_SIDEBAR_DIR . 'src/Advanced_Sidebar_Menu_Page_Walker.php' );
		require( ADVANCED_SIDEBAR_DIR . 'src/Advanced_Sidebar_Menu_List_Pages.php' );
		require( ADVANCED_SIDEBAR_DIR . 'src/Advanced_Sidebar_Menu_Cache.php' );

		Advanced_Sidebar_Menu::init();
		Advanced_Sidebar_Menu_Cache::init();
	}

	add_action( 'plugins_loaded', 'advanced_sidebar_menu_load' );

}

#-- Translate
add_action( 'plugins_loaded', 'advanced_sidebar_menu_translate' );
function advanced_sidebar_menu_translate(){
	load_plugin_textdomain( 'advanced-sidebar-menu', false, 'advanced-sidebar-menu/languages' );
}

add_action( 'admin_print_scripts', 'advanced_sidebar_menu_script' );
function advanced_sidebar_menu_script(){
	wp_enqueue_script(
		apply_filters( 'asm_script', 'advanced-sidebar-menu-script' ),
		plugins_url( 'resources/js/advanced-sidebar-menu.js', __FILE__ ),
		array( 'jquery' ),
		ADVANCED_SIDEBAR_BASIC_VERSION
	);
}

#-- Let know about new Pro Version
add_action( 'advanced_sidebar_menu_after_widget_form', 'advanced_sidebar_menu_pro_notice' );
function advanced_sidebar_menu_pro_notice(){
	if( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ){
		return;
	}
	?>
	<fieldset style="border: 1px solid black; border-radius: 10px; padding: 10px;">
		<legend style="font-size: 14px; font-weight: bold;">
            <?php _e( 'Want More Options', 'advanced-sidebar-menu' ); ?>?
		</legend>
		<p>
			<strong>
                <big>
					<a target="blank" href="http://matlipe.com/product/advanced-sidebar-menu-pro/">
                        <?php _e( 'Go Pro', 'advanced-sidebar-menu' ); ?>!
					</a>
				</big>
            </strong>
		<p>
	</fieldset>
	<?php
}




