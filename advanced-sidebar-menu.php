<?php
/*
Plugin Name: Advanced Sidebar Menu
Plugin URI: https://matlipe.com/advanced-sidebar-menu/
Description: Creates dynamic menus based on parent/child relationship of your pages or categories.
Author: Mat Lipe
Version: 7.1.2
Author URI: https://matlipe.com
Text Domain: advanced-sidebar-menu
*/


if ( defined( 'ADVANCED_SIDEBAR_BASIC_VERSION' ) ) {
	return;
}

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '7.1.2' );
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


add_action( 'advanced_sidebar_menu_after_widget_form', 'advanced_sidebar_menu_upgrade_notice', 1, 2 );
/**
 * @todo translate these features
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
				printf( esc_html_x( 'Upgrade to %1$sAdvanced Sidebar Menu Pro%2$s for these features:', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="blank" href="https://matlipe.com/product/advanced-sidebar-menu-pro/">', '</a>' ); ?>
			</strong>
		<ol>
			<li>Priority support.</li>
			<?php
			//page widget options
			if ( 'advanced_sidebar_menu' === $widget->id_base ) {
				?>
				<li>Ability to customize each page's link text.</li>
				<li>Ability to exclude a page from all menus using a simple checkbox.</li>
				<li>Number of levels of pages to show when always displayed child pages is not checked.</li>
				<li>Ability to select and display custom post types.</li>
				<li>Option to display the current page’s parents and grandparents only</li>
				<li>Option to display child page siblings when on a child page. <strong> NEW</strong></li>
				<li>Option to display child page siblings when on a child page with no grandchild pages.<strong>
						NEW</strong></li>
				<li>Accordion menu support for pages.</li>
				<?php
				//category widget options
			} else {
				?>
				<li>Link ordering for the category widget.</li>
				<li>Ability to select and display custom taxonomies.</li>
				<li>Accordion menu support for categories.</li>
				<?php
			}
			?>
			<li>Accordion icon selection from 4 styles of icons.</li>
			<li>Accordion icon color selection.</li>
			<li>Accordion option to keep all sections closed until clicked.</li>
			<li>Accordion option to include highest level parent in accordion.</li>
			<li>Click and drag styling for both the page and category widgets.</li>
			<li>Styling options for links including color, background color, size, and font weight.</li>
			<li>Styling options for different levels of links.</li>
			<li>Styling options for the current page or category.</li>
			<li>Styling options for the parent of the current page or category.</li>
			<li>Block styling options including borders and border colors.</li>
			<li>Bullet style selection from 7 styles or select none to have no bullets.</li>
			<li>Ability to display the widgets everywhere the sidebar display. <strong> NEW</strong></li>
			<li>Ability to select the parent page/category when using the display widget everywhere option.<strong>
					NEW</strong></li>
			<li>Access to members only support area.</li>
		</ol>
		<p>
	</div>
	<?php
}




