<?php
/*
Plugin Name: Advanced Sidebar Menu
Plugin URI: https://matlipe.com/advanced-sidebar-menu/
Description: Creates dynamic menus based on parent/child relationship of your pages or categories.
Author: Mat Lipe
Version: 7.1.1
Author URI: https://matlipe.com
Text Domain: advanced-sidebar-menu
*/


if ( defined( 'ADVANCED_SIDEBAR_BASIC_VERSION' ) ) {
	return;
}

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '7.1.1' );
define( 'ADVANCED_SIDEBAR_DIR', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'advanced_sidebar_menu_load' ) ) {
	function advanced_sidebar_menu_load() {
		Advanced_Sidebar_Menu_Core::init();
		Advanced_Sidebar_Menu_Cache::init();
		Advanced_Sidebar_Menu_Debug::init();
	}

	add_action( 'plugins_loaded', 'advanced_sidebar_menu_load' );
}

/**
 * Autoload classes from PSR4 src directory
 * Mirrored after Composer dump-autoload for performance
 *
 * @param string $class
 *
 * @return void
 */
function advanced_sidebar_menu_autoload( $class ) {
	$classes = array(
		//widgets
		'Advanced_Sidebar_Menu_Widget_Page'     => 'Widget/Page.php',
		'Advanced_Sidebar_Menu_Widget_Category' => 'Widget/Category.php',

		//core
		'Advanced_Sidebar_Menu_Cache'           => 'Cache.php',
		'Advanced_Sidebar_Menu_Core'            => 'Core.php',
		'Advanced_Sidebar_Menu_Debug'           => 'Debug.php',
		'Advanced_Sidebar_Menu_List_Pages'      => 'List_Pages.php',
		'Advanced_Sidebar_Menu_Menu'            => 'Menu.php',
		'Advanced_Sidebar_Menu_Page_Walker'     => 'Page_Walker.php',

		//menus
		'Advanced_Sidebar_Menu_Menus_Category'  => 'Menus/Category.php',
		'Advanced_Sidebar_Menu_Menus_Abstract'  => 'Menus/Abstract.php',
		'Advanced_Sidebar_Menu_Menus_Page'      => 'Menus/Page.php',
	);
	if ( isset( $classes[ $class ] ) ) {
		require dirname( __FILE__ ) . '/src/' . $classes[ $class ];
	}
}

spl_autoload_register( 'advanced_sidebar_menu_autoload' );


#-- Translate
add_action( 'plugins_loaded', 'advanced_sidebar_menu_translate' );
function advanced_sidebar_menu_translate() {
	load_plugin_textdomain( 'advanced-sidebar-menu', false, 'advanced-sidebar-menu/languages' );
}

add_action( 'admin_print_scripts', 'advanced_sidebar_menu_script' );
function advanced_sidebar_menu_script() {
	wp_enqueue_script(
		apply_filters( 'asm_script', 'advanced-sidebar-menu-script' ),
		plugins_url( 'resources/js/advanced-sidebar-menu.js', __FILE__ ),
		array( 'jquery' ),
		ADVANCED_SIDEBAR_BASIC_VERSION
	);
}

#-- Let know about new Pro Version
add_action( 'advanced_sidebar_menu_after_widget_form', 'advanced_sidebar_menu_pro_notice' );
function advanced_sidebar_menu_pro_notice() {
	if ( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ) {
		return;
	}
	?>
	<fieldset style="border: 1px solid black; border-radius: 10px; padding: 10px;">
		<legend style="font-size: 14px; font-weight: bold;">
			<?php esc_html_e( 'Checkout Advanced Sidebar Menu Pro!', 'advanced-sidebar-menu' ); ?>
		</legend>
		<p>
			<?php
			/* translators: {<a>}{</a>} links to https://matlipe.com/product/advanced-sidebar-menu-pro/ */
			printf( esc_html_x( 'Upgrade to %1$sAdvanced Sidebar Menu Pro%2$s for Priority Support, Styles, Custom Link Text, Accordions, Custom Post Types, and so much more!', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="blank" href="https://matlipe.com/product/advanced-sidebar-menu-pro/">', '</a>' ); ?>
		<p>
	</fieldset>
	<?php
}




