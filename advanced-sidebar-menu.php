<?php
/**
 * Plugin Name: Advanced Sidebar Menu
 * Plugin URI: https://onpointplugins.com/advanced-sidebar-menu/
 * Description: Creates dynamic menus based on parent/child relationship of your pages or categories.
 * Author: OnPoint Plugins
 * Version: 8.0.3
 * Author URI: https://onpointplugins.com
 * Text Domain: advanced-sidebar-menu
 *
 * @package advanced-sidebar-menu
 */

if ( defined( 'ADVANCED_SIDEBAR_BASIC_VERSION' ) ) {
	return;
}

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '8.0.3' );
define( 'ADVANCED_SIDEBAR_MENU_REQUIRED_PRO_VERSION', '8.0.0' );
define( 'ADVANCED_SIDEBAR_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADVANCED_SIDEBAR_MENU_URL', plugin_dir_url( __FILE__ ) );

use Advanced_Sidebar_Menu\Cache;
use Advanced_Sidebar_Menu\Core;
use Advanced_Sidebar_Menu\Debug;
use Advanced_Sidebar_Menu\List_Pages;
use Advanced_Sidebar_Menu\Menus\Category;
use Advanced_Sidebar_Menu\Menus\Menu_Abstract;
use Advanced_Sidebar_Menu\Menus\Page;
use Advanced_Sidebar_Menu\Scripts;
use Advanced_Sidebar_Menu\Traits\Memoize;
use Advanced_Sidebar_Menu\Traits\Singleton;
use Advanced_Sidebar_Menu\Walkers\Page_Walker;
use Advanced_Sidebar_Menu\Widget\Category as Widget_Category;
use Advanced_Sidebar_Menu\Widget\Page as Widget_Page;
use Advanced_Sidebar_Menu\Widget\Widget_Abstract;

/**
 * Load the plugin
 *
 * @return void
 */
function advanced_sidebar_menu_load() {
	Core::init();
	Cache::init();
	Debug::init();
	Scripts::init();

	if ( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) && version_compare( ADVANCED_SIDEBAR_MENU_REQUIRED_PRO_VERSION, ADVANCED_SIDEBAR_MENU_PRO_VERSION, '>' ) ) {
		add_action( 'admin_notices', 'advanced_sidebar_menu_pro_version_warning' );
		remove_action( 'plugins_loaded', 'advanced_sidebar_menu_pro_init', 11 );
	}
}

add_action( 'plugins_loaded', 'advanced_sidebar_menu_load' );

/**
 * Autoload classes from PSR4 src directory
 * Mirrored after Composer dump-autoload for performance
 *
 * @param string $class - class being loaded.
 *
 * @return void
 */
function advanced_sidebar_menu_autoload( $class ) {
	$classes = [
		// Widgets.
		Widget_Abstract::class => 'Widget/Widget_Abstract.php',
		Widget_Page::class     => 'Widget/Page.php',
		Widget_Category::class => 'Widget/Category.php',

		// Core.
		Cache::class           => 'Cache.php',
		Core::class            => 'Core.php',
		Debug::class           => 'Debug.php',
		List_Pages::class      => 'List_Pages.php',
		Scripts::class         => 'Scripts.php',

		// Menus.
		Category::class        => 'Menus/Category.php',
		Menu_Abstract::class   => 'Menus/Menu_Abstract.php',
		Page::class            => 'Menus/Page.php',

		// Traits.
		Memoize::class         => 'Traits/Memoize.php',
		Singleton::class       => 'Traits/Singleton.php',

		// Walkers.
		Page_Walker::class     => 'Walkers/Page_Walker.php',

	];
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
	$anchor = \Advanced_Sidebar_Menu\Widget\Category::NAME === $widget->id_base ? 'categories-menu' : 'pages-menu';
	?>
	<p style="text-align: right">
		<a
			href="https://onpointplugins.com/advanced-sidebar-menu/#advanced-sidebar-<?php echo esc_attr( $anchor ); ?>"
			target="_blank"
			rel="noopener noreferrer">
			<?php esc_html_e( 'widget documentation', 'advanced-sidebar-menu' ); ?>
		</a>
	</p>
	<?php
}

/**
 * Notify widget users about the PRO options
 *
 * @param array     $instance - widget instance.
 * @param WP_Widget $widget   - widget class.
 *
 * @return void
 */
function advanced_sidebar_menu_upgrade_notice( array $instance, WP_Widget $widget ) {
	if ( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ) {
		if ( version_compare( ADVANCED_SIDEBAR_MENU_REQUIRED_PRO_VERSION, ADVANCED_SIDEBAR_MENU_PRO_VERSION, '>' ) ) {
			?>
			<div class="advanced-sidebar-menu-column-box" style="border-color: red">
				<?php advanced_sidebar_menu_pro_version_warning( true ); ?>
			</div>
			<?php
		}

		return;
	}
	?>
	<div class="advanced-sidebar-menu-column-box">
		<h3><?php esc_html_e( 'Advanced Sidebar Menu PRO!', 'advanced-sidebar-menu' ); ?></h3>
		<p>
			<strong>
				<?php
				/* translators: {<a>}{</a>} links to https://onpointplugins.com/product/advanced-sidebar-menu-pro/ */
				printf( esc_html_x( 'Upgrade to %1$sAdvanced Sidebar Menu PRO%2$s for these features and so much more!', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="blank" href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/">', '</a>' );
				?>
			</strong>
		<ol style="list-style: disc">
			<li><?php esc_html_e( 'Priority support, including access to Members Only Support Area.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Accordion menu support.', 'advanced-sidebar-menu' ); ?></li>
			<li><?php esc_html_e( 'Click and drag menu styling including bullets, colors, sizes, block styles, borders, and border colors.', 'advanced-sidebar-menu' ); ?></li>
			<?php
			if ( \Advanced_Sidebar_Menu\Widget\Page::NAME === $widget->id_base ) {
				?>
				<li><?php esc_html_e( "Ability to customize each page's link text.", 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to exclude a page from all menus using a simple checkbox.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Number of levels of pages to show when always displayed child pages is not checked.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Ability to select and display custom post types.', 'advanced-sidebar-menu' ); ?></li>
				<li><?php esc_html_e( 'Option to display the current page’s parents, grandparents, and children only, as well as siblings options.', 'advanced-sidebar-menu' ); ?></li>
				<?php
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

/**
 * Display a warning if we don't have the required PRO version installed.
 *
 * @param bool $no_banner - Display as "message" banner.
 *
 * @since 8.0.0
 *
 * @return void
 */
function advanced_sidebar_menu_pro_version_warning( $no_banner = false ) {
	?>
	<div class="<?php echo true === $no_banner ? '' : 'error'; ?>">
		<p>
			<?php
			/* translators: {%1$s}[<a>]{%2$s}[</a>] https://wordpress.org/plugins/advanced-sidebar-menu/ */ //phpcs:disable
			printf( esc_html_x( 'Advanced Sidebar Menu requires %1$sAdvanced Sidebar Menu PRO%2$s version %3$s+. Please update or deactivate the PRO version.', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="_blank" rel="noreferrer noopener" href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/">', '</a>', esc_attr( ADVANCED_SIDEBAR_MENU_REQUIRED_PRO_VERSION ) );
			?>
		</p>
	</div>
	<?php
}
