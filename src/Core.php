<?php

/**
 * Advanced_Sidebar_Menu
 *
 * @author OnPoint Plugins
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Core {
	use \Advanced_Sidebar_Menu\Traits\Singleton;

	/**
	 * Actions
	 */
	protected function hook() {
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );
	}


	/**
	 * Register the page and category widgets.
	 *
	 * @return void
	 */
	public function register_widgets() {
		register_widget( \Advanced_Sidebar_Menu_Widget_Page::class );
		register_widget( \Advanced_Sidebar_Menu_Widget_Category::class );
	}


	/**
	 * The plugin styles are universal
	 * This ensures that we only include them once on a single request
	 *
	 * @return void
	 */
	public function include_plugin_styles() {
		?>
		<style>
			<?php include_once $this->get_template_part( 'sidebar-menu.css' ); ?>
		</style>
		<?php
	}


	/**
	 * Retrieve a template file from either the theme's 'advanced-sidebar-menu' directory
	 * or this plugins views folder if one does not exist
	 *
	 * @param string $file_name
	 *
	 * @since 6.0.0
	 *
	 * @return string
	 */
	public function get_template_part( $file_name ) {
		$file = locate_template( 'advanced-sidebar-menu/' . $file_name );
		if ( empty( $file ) ) {
			$file = ADVANCED_SIDEBAR_DIR . 'views/' . $file_name;
		}

		$file = apply_filters( 'advanced_sidebar_menu_template_part', $file, $file_name, $this );

		return $file;
	}
}
