<?php
/*
Plugin Name: Advanced Sidebar Menu
Plugin URI: https://matlipe.com/advanced-sidebar-menu/
Description: Creates dynamic menus based on parent/child relationship of your pages or categories.
Author: Mat Lipe
Version: 7.2.5
Author URI: https://matlipe.com
Text Domain: advanced-sidebar-menu
*/


if ( defined( 'ADVANCED_SIDEBAR_BASIC_VERSION' ) ) {
	return;
}

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '7.2.5' );
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
		'Advanced_Sidebar_Menu__Widget__Widget' => 'Widget/Widget.php',
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

	wp_enqueue_style(
		apply_filters( 'asm_style', 'advanced-sidebar-menu-style' ),
		plugins_url( 'resources/css/advanced-sidebar-menu.css', __FILE__ ),
		array(),
		ADVANCED_SIDEBAR_BASIC_VERSION
	);
}


add_action( 'advanced-sidebar-menu/widget/category/right-column', 'advanced_sidebar_menu_upgrade_notice', 1, 2 );
add_action( 'advanced-sidebar-menu/widget/page/right-column', 'advanced_sidebar_menu_upgrade_notice', 1, 2 );


/**
 * Notify widget users about the PRO options
 *
 * @param array     $instance
 * @param WP_Widget $widget
 *
 * @return void
 */
function advanced_sidebar_menu_upgrade_notice( array $instance, WP_Widget $widget ) {
	if ( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ) {
		return;
	}
	?>
	<div class="advanced-sidebar-menu-column-box">
		<h3><?php esc_html_e( 'Checkout Advanced Sidebar Menu Pro!', 'advanced-sidebar-menu' ); ?></h3>
		<p>
			<strong>
				<?php
				/* translators: {<a>}{</a>} links to https://matlipe.com/product/advanced-sidebar-menu-pro/ */
				printf( esc_html_x( 'Upgrade to %1$sAdvanced Sidebar Menu Pro%2$s for these features and so much more!', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="blank" href="https://matlipe.com/product/advanced-sidebar-menu-pro/">', '</a>' ); ?>
			</strong>
		<ol style="list-style: disc">
			<li><?php esc_html_e( 'Priority support, including access to Members Only Support Area.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Accordion menu support.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Click and drag menu styling including bullets, colors, sizes, block styles, borders, and border colors.', 'advanced-sidebar-menu' ); ?></li>
			<?php
			//page widget options
			if ( 'advanced_sidebar_menu' === $widget->id_base ) {
				?>
				<li><?php esc_html_e( "Ability to customize each page's link text.", 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to exclude a page from all menus using a simple checkbox.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Number of levels of pages to show when always displayed child pages is not checked.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to select and display custom post types.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Option to display the current page’s parents and grandparents only, as well as siblings options.', 'advanced-sidebar-menu' ); ?></li>
				<?php
				//category widget options
			} else {
				?>
				<li><?php esc_html_e( 'Link ordering for the category widget.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to select and display custom taxonomies.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to display assigned posts or custom post types under categories.', 'advanced-sidebar-menu' ); ?><strong> NEW</strong></li>
				<?php
			}
			?>
			<li><?php esc_html_e( 'Ability to display the widgets everywhere the sidebar display.', 'advanced-sidebar-menu' ); ?>
				<strong> NEW</strong></li>
			<li><?php esc_html_e( 'Support for custom navigation menus from Appearance -> Menus.', 'advanced-sidebar-menu' ); ?><strong> NEW</strong></li>
		</ol>
		<p>
	</div>
	<?php
}
