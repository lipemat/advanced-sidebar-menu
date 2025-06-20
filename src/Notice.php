<?php

namespace Advanced_Sidebar_Menu;

use Advanced_Sidebar_Menu\Traits\Singleton;
use Advanced_Sidebar_Menu\Widget\Category;

/**
 * Various notice handlers for the admin and widgets.
 *
 * @author OnPoint Plugins
 * @since  8.1.0
 *
 * @phpstan-import-type PAGE_SETTINGS from Widget\Page
 * @phpstan-import-type CATEGORY_SETTINGS from Widget\Category
 */
class Notice {
	use Singleton;

	/**
	 * Actions and filters.
	 */
	public function hook(): void {
		add_action( 'advanced-sidebar-menu/widget/page/before-columns', [ $this, 'preview' ], 1, 2 );
		add_action( 'advanced-sidebar-menu/widget/category/before-columns', [ $this, 'preview' ], 1, 2 );
		add_action( 'advanced-sidebar-menu/widget/category/right-column', [ $this, 'info_panel' ], 1, 2 );
		add_action( 'advanced-sidebar-menu/widget/page/right-column', [ $this, 'info_panel' ], 1, 2 );

		add_filter( 'plugin_action_links_' . Core::PLUGIN_FILE, [ $this, 'plugin_action_link' ], 11 );

		if ( $this->is_conflicting_pro_version() ) {
			add_action( 'all_admin_notices', [ $this, 'pro_version_warning' ] );
			add_filter( 'advanced-sidebar-menu/scripts/js-config/error', [ $this, 'get_pro_version_warning_message' ] );
		}
	}


	/**
	 * Is PRO active but an unsupported version?
	 *
	 * @return bool
	 */
	public function is_conflicting_pro_version() {
		return null !== $this->get_pro_version() && version_compare( ADVANCED_SIDEBAR_MENU_REQUIRED_PRO_VERSION, $this->get_pro_version(), '>' );
	}


	/**
	 * Is the PRO version installed?
	 *
	 * @since 9.7.0
	 *
	 * @return ?string
	 */
	public function get_pro_version(): ?string {
		return \defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ? ADVANCED_SIDEBAR_MENU_PRO_VERSION : null;
	}


	/**
	 * Display a warning if we don't have the required PRO version installed.
	 *
	 * @param bool $no_banner - Display as a "message" banner.
	 */
	public function pro_version_warning( $no_banner = false ) {
		?>
		<div class="<?php echo true === $no_banner ? '' : 'error'; ?>">
			<p>
				<?php echo $this->get_pro_version_warning_message(); //phpcs:ignore ?>
			</p>
		</div>
		<?php
	}


	/**
	 * Get a message to display in various admin locations if
	 * the PRO version of the plugin is unsupported.
	 *
	 * @return string
	 */
	public function get_pro_version_warning_message() {
		/* translators: Link to PRO plugin {%1$s}[<a href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/">]{%2$s}[</a>] */
		return \sprintf( esc_html_x( 'Advanced Sidebar Menu requires %1$sAdvanced Sidebar Menu PRO%2$s version %3$s+. Please update or deactivate the PRO version.', '{<a>}{</a>}', 'advanced-sidebar-menu' ), '<a target="_blank" rel="noreferrer noopener" href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/">', '</a>', esc_attr( ADVANCED_SIDEBAR_MENU_REQUIRED_PRO_VERSION ) );
	}


	/**
	 * Notify widget users about the PRO options
	 *
	 * @phpstan-param PAGE_SETTINGS|CATEGORY_SETTINGS     $_
	 *
	 * @param array                                       $_      - widget instance.
	 * @param \WP_Widget<PAGE_SETTINGS|CATEGORY_SETTINGS> $widget - widget class.
	 *
	 * @return void
	 */
	public function info_panel( array $_, \WP_Widget $widget ) {
		if ( $this->is_conflicting_pro_version() ) {
			?>
			<div class="advanced-sidebar-menu-column-box" style="border-color: red; font-size: 13px !important;">
				<?php static::instance()->pro_version_warning( true ); ?>
			</div>
			<?php
		}
		if ( null !== $this->get_pro_version() ) {
			return;
		}

		/**
		 * On clicking of the <h3> element add an "open" class to the parent
		 * div element.
		 */
		?>
		<div class="advanced-sidebar-menu-column-box advanced-sidebar-info-panel wp-core-ui">
			<h3 onclick="this.parentNode.classList.toggle('open')">
				<?php esc_html_e( 'Go PRO', 'advanced-sidebar-menu' ); ?>
			</h3>
			<ol>
				<?php
				foreach ( $this->get_features() as $feature ) {
					?>
					<li>
						<?php echo esc_html( $feature ); ?>
					</li>
					<?php
				}
				?>
				<li>
					<?php esc_html_e( 'And so much more…', 'advanced-sidebar-menu' ); ?>
				</li>
			</ol>
			<a
				class="button-primary"
				href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/?utm_source=widget-upgrade&utm_campaign=gopro&utm_medium=wp-dash#buy-now-choices"
				target="_blank"
			>
				<?php esc_html_e( 'Upgrade', 'advanced-sidebar-menu' ); ?>
			</a>
			<div
				data-js="advanced-sidebar-menu/pro/preview/trigger"
				data-target="advanced-sidebar-menu/pro/preview/<?php echo esc_attr( (string) $widget->id ); ?>"
				class="advanced-sidebar-desktop-only advanced-sidebar-preview-trigger"
			>
				<button class="button-secondary">
					<?php esc_html_e( 'Preview', 'advanced-sidebar-menu' ); ?>
				</button>
			</div>
		</div>
		<?php
	}


	/**
	 * Display a preview image, which covers the widget when the "Preview"
	 * button is clicked.
	 *
	 * @phpstan-param PAGE_SETTINGS|CATEGORY_SETTINGS     $instance
	 *
	 * @param array                                       $instance - Widgets settings.
	 * @param \WP_Widget<PAGE_SETTINGS|CATEGORY_SETTINGS> $widget   - Widget class.
	 */
	public function preview( array $instance, \WP_Widget $widget ) {
		if ( null !== $this->get_pro_version() ) {
			return;
		}
		$src = 'pages-widget-min.webp?version=' . ADVANCED_SIDEBAR_MENU_BASIC_VERSION;
		if ( Category::NAME === $widget->id_base ) {
			$src = 'category-widget-min.webp?version=' . ADVANCED_SIDEBAR_MENU_BASIC_VERSION;
		}
		?>
		<div
			data-js="advanced-sidebar-menu/pro/preview/<?php echo esc_attr( (string) $widget->id ); ?>"
			class="advanced-sidebar-desktop-only advanced-sidebar-menu-full-width advanced-sidebar-menu-preview-wrap">
			<div class="dashicons dashicons-no-alt advanced-sidebar-menu-close-icon"></div>
			<img
				data-js="advanced-sidebar-menu/pro/preview/image"
				class="advanced-sidebar-menu-preview-image"
				src="https://onpointplugins.com/plugins/assets/shared/<?php echo esc_attr( $src ); ?>"
				srcset="https://onpointplugins.com/plugins/assets/shared/<?php echo esc_attr( str_replace( '-min.webp', '-1x-min.webp', $src ) ); ?> 1x, https://onpointplugins.com/plugins/assets/shared/<?php echo esc_attr( $src ); ?> 2x"
				alt="<?php esc_attr_e( 'PRO version widget options', 'advanced-sidebar-menu' ); ?>" />
		</div>
		<?php
	}


	/**
	 * Display a "Go PRO" action link in the plugins' list.
	 *
	 * @param array $actions - Array of actions and their link.
	 *
	 * @return array
	 */
	public function plugin_action_link( array $actions ) {
		if ( null === static::instance()->get_pro_version() ) {
			$actions['go-pro'] = \sprintf( '<a href="%1$s" target="_blank" style="color:#3db634;font-weight:700;">%2$s</a>', 'https://onpointplugins.com/product/advanced-sidebar-menu-pro/?utm_source=wp-plugins&utm_campaign=gopro&utm_medium=wp-dash', __( 'Go PRO', 'advanced-sidebar-menu' ) );
		}
		return $actions;
	}


	/**
	 * Get a list of PRO plugin features for display in
	 * the info panel for widgets and blocks.
	 *
	 * @return array
	 */
	public function get_features() {
		return [
			__( 'Styling options including borders, bullets, colors, backgrounds, font size and weight.', 'advanced-sidebar-menu' ),
			__( 'Accordion menus.', 'advanced-sidebar-menu' ),
			__( 'Support for custom navigation menus from Appearance -> Menus.', 'advanced-sidebar-menu' ),
			__( 'Select and display custom post types and taxonomies.', 'advanced-sidebar-menu' ),
			__( 'Priority support with access to members only support area.', 'advanced-sidebar-menu' ),
		];
	}
}
