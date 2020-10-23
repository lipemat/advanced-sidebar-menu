<?php

namespace Advanced_Sidebar_Menu;

use Advanced_Sidebar_Menu\Traits\Singleton;
use Advanced_Sidebar_Menu\Widget\Category;

/**
 * Various notice handling for the admin and widgets.
 *
 * @author OnPoint Plugins
 * @since  8.1.0
 */
class Notice {
	use Singleton;

	/**
	 * Actions and filters.
	 */
	public function hook() {
		add_action( 'advanced-sidebar-menu/widget/page/before-columns', [ $this, 'preview' ], 1, 2 );
		add_action( 'advanced-sidebar-menu/widget/category/before-columns', [ $this, 'preview' ], 1, 2 );
	}


	/**
	 * Display a preview image which covers the widget when the "Preview"
	 * button is clicked.
	 *
	 * @param array      $instance - Widgets settings.
	 * @param \WP_Widget $widget   - Widget class.
	 */
	public function preview( array $instance, \WP_Widget $widget ) {
		$src = 'pages-widget.png';
		if ( Category::NAME === $widget->id_base ) {
			$src = 'category-widget.png';
		}
		?>
		<div
			data-js="advanced-sidebar-menu/pro/preview/<?php echo esc_attr( $widget->id ); ?>"
			class="advanced-sidebar-desktop-only advanced-sidebar-menu-full-width">
			<div class="dashicons dashicons-no-alt advanced-sidebar-menu-close-icon"></div>
			<img
				class="advanced-sidebar-menu-preview-image"
				src="<?php echo esc_url( ADVANCED_SIDEBAR_MENU_URL . 'resources/img/' . $src ); ?>"
				alt="PRO version widget options" />
		</div>
		<?php
	}

}
