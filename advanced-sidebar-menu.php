<?php
/**
 * Plugin Name: Advanced Sidebar Menu
 * Plugin URI: https://onpointplugins.com/advanced-sidebar-menu/
 * Description: Creates dynamic menus based on parent/child relationship of your pages or categories.
 * Author: OnPoint Plugins
 * Version: 7.7.3
 * Author URI: https://onpointplugins.com
 * Text Domain: advanced-sidebar-menu
 *
 * @package advanced-sidebar-menu
 */

use Advanced_Sidebar_Menu\Scripts;

if ( defined( 'ADVANCED_SIDEBAR_BASIC_VERSION' ) ) {
	return;
}

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '7.7.3' );
define( 'ADVANCED_SIDEBAR_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADVANCED_SIDEBAR_MENU_URL', plugin_dir_url( __FILE__ ) );

if ( ! function_exists( 'advanced_sidebar_menu_load' ) ) {
	/**
	 * Load the plugin
	 *
	 * @return void
	 */
	function advanced_sidebar_menu_load() {
		Advanced_Sidebar_Menu_Core::init();
		Advanced_Sidebar_Menu_Cache::init();
		Advanced_Sidebar_Menu_Debug::init();
		Scripts::init();
	}

	add_action( 'plugins_loaded', 'advanced_sidebar_menu_load' );
}

/**
 * Autoload classes from PSR4 src directory
 * Mirrored after Composer dump-autoload for performance
 *
 * @param string $class - class being loaded.
 *
 * @return void
 */
function advanced_sidebar_menu_autoload( $class ) {
	$classes = array(
		// widgets.
		'Advanced_Sidebar_Menu__Widget__Widget' => 'Widget/Widget.php',
		'Advanced_Sidebar_Menu_Widget_Page'     => 'Widget/Page.php',
		'Advanced_Sidebar_Menu_Widget_Category' => 'Widget/Category.php',

		// core.
		'Advanced_Sidebar_Menu_Cache'           => 'Cache.php',
		'Advanced_Sidebar_Menu_Core'            => 'Core.php',
		'Advanced_Sidebar_Menu_Debug'           => 'Debug.php',
		'Advanced_Sidebar_Menu_List_Pages'      => 'List_Pages.php',
		'Advanced_Sidebar_Menu_Menu'            => 'Menu.php',
		'Advanced_Sidebar_Menu_Page_Walker'     => 'Page_Walker.php',
		'Advanced_Sidebar_Menu\Scripts'         => 'Scripts.php',

		// menus.
		'Advanced_Sidebar_Menu_Menus_Category'  => 'Menus/Category.php',
		'Advanced_Sidebar_Menu_Menus_Abstract'  => 'Menus/Abstract.php',
		'Advanced_Sidebar_Menu_Menus_Page'      => 'Menus/Page.php',

		// Traits.
		'Advanced_Sidebar_Menu\Traits\Singleton' => 'Traits/Singleton.php',
	);
	if ( isset( $classes[ $class ] ) ) {
		require __DIR__ . '/src/' . $classes[ $class ];
	}
}

spl_autoload_register( 'advanced_sidebar_menu_autoload' );


add_action( 'plugins_loaded', 'advanced_sidebar_menu_translate' );
/**
 * Load translations
 *
 * @return void
 */
function advanced_sidebar_menu_translate() {
	load_plugin_textdomain( 'advanced-sidebar-menu', false, 'advanced-sidebar-menu/languages' );
}


add_action( 'advanced-sidebar-menu/widget/category/right-column', 'advanced_sidebar_menu_upgrade_notice', 1, 2 );
add_action( 'advanced-sidebar-menu/widget/page/right-column', 'advanced_sidebar_menu_upgrade_notice', 1, 2 );
add_action( 'advanced-sidebar-menu/widget/page/after-form', 'advanced_sidebar_menu_widget_docs', 99, 2 );
add_action( 'advanced-sidebar-menu/widget/category/after-form', 'advanced_sidebar_menu_widget_docs', 99, 2 );

/**
 * Add a link to widget docs inside the widget.
 *
 * @param array     $instance - Widget settings.
 * @param WP_Widget $widget   - Current widget.
 */
function advanced_sidebar_menu_widget_docs( $instance, WP_Widget $widget ) {
	$anchor = 'advanced_sidebar_menu_category' === $widget->id_base ? 'categories-menu' : 'pages-menu';
	?>
	<p style="text-align: right">
		<a href="https://onpointplugins.com/advanced-sidebar-menu/#advanced-sidebar-<?php echo esc_attr( $anchor ); ?>" target="blank">
			<?php esc_html_e( 'widget documentation', 'advanced-sidebar-menu' ); ?>
		</a>
	</p>
	<?php
}

/**
 * Legacy method now deprecated.
 *
 * @deprecated In favor of \Advanced_Sidebar_Menu\Scripts::instance()->admin_scripts()
 */
function advanced_sidebar_menu_script() {
	_deprecated_function( 'advanced_sidebar_menu_script', '7.8.0', '\Advanced_Sidebar_Menu\Scripts::instance()->admin_scripts()' );
	\Advanced_Sidebar_Menu\Scripts::instance()->admin_scripts();
}

/**
 * Notify widget users about the PRO options
 *
 * @param array     $instance - widget instance.
 * @param WP_Widget $widget - widget class.
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
				/* translators: {<a>}{</a>} links to https://onpointplugins.com/product/advanced-sidebar-menu-pro/ */
				printf( esc_html_x( 'Upgrade to %1$sAdvanced Sidebar Menu Pro%2$s for these features and so much more!', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="blank" href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/">', '</a>' );
				?>
			</strong>
		<ol style="list-style: disc">
			<li><?php esc_html_e( 'Priority support, including access to Members Only Support Area.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Accordion menu support.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Click and drag menu styling including bullets, colors, sizes, block styles, borders, and border colors.', 'advanced-sidebar-menu' ); ?></li>
			<?php
			// page widget options.
			if ( 'advanced_sidebar_menu' === $widget->id_base ) {
				?>
				<li><?php esc_html_e( "Ability to customize each page's link text.", 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to exclude a page from all menus using a simple checkbox.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Number of levels of pages to show when always displayed child pages is not checked.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to select and display custom post types.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Option to display the current page’s parents, grandparents, and children only, as well as siblings options.', 'advanced-sidebar-menu' ); ?></li>
				<?php
				// category widget options.
			} else {
				?>
				<li><?php esc_html_e( 'Link ordering for the category widget.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to select and display custom taxonomies.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to display assigned posts or custom post types under categories.', 'advanced-sidebar-menu' ); ?></li>
				<?php
			}
			?>
			<li><?php esc_html_e( 'Ability to display the widgets everywhere the sidebar displays.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Support for custom navigation menus from Appearance -> Menus.', 'advanced-sidebar-menu' ); ?></li>
		</ol>
		<p>
	</div>
	<?php
}
