<?php

namespace Advanced_Sidebar_Menu;

use Advanced_Sidebar_Menu\Traits\Singleton;

/**
 * Scripts and styles.
 *
 * @author Mat Lipe
 * @since  7.7.0
 */
class Scripts {
	use Singleton;

	const ADMIN_HANDLE = 'advanced-sidebar-menu/scripts/admin-js';


	/**
	 * Add various scripts to the cue.
	 */
	public function hook() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
		// Elementor support.
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'admin_scripts' ] );
		// UGH! Beaver Builder hack.
		if ( isset( $_GET['fl_builder'] ) ) { // phpcs:ignore
			add_action( 'wp_enqueue_scripts', [ $this, 'admin_scripts' ] );
		}

		add_action( 'advanced-sidebar-menu/widget/category/after-form', [ $this, 'init_widget_js' ], 1000 );
		add_action( 'advanced-sidebar-menu/widget/page/after-form', [ $this, 'init_widget_js' ], 1000 );
		add_action( 'advanced-sidebar-menu/widget/navigation-menu/after-form', [ $this, 'init_widget_js' ], 1000 );
	}


	/**
	 * Add JS and CSS to the admin and in specific cases the front-end.
	 *
	 * @action admin_enqueue_scripts 10 0
	 *
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script(
			'advanced-sidebar-menu-script',
			trailingslashit( (string) ADVANCED_SIDEBAR_MENU_URL ) . 'resources/js/advanced-sidebar-menu.js',
			[ 'jquery' ],
			ADVANCED_SIDEBAR_BASIC_VERSION,
			false
		);

		wp_enqueue_style(
			'advanced-sidebar-menu-style',
			trailingslashit( (string) ADVANCED_SIDEBAR_MENU_URL ) . 'resources/css/advanced-sidebar-menu.css',
			[],
			ADVANCED_SIDEBAR_BASIC_VERSION
		);

		if ( ! wp_should_load_block_editor_scripts_and_styles() ) {
			return;
		}
		$js_dir = apply_filters( 'advanced-sidebar-menu/js-dir', ADVANCED_SIDEBAR_MENU_URL . 'js/dist/' );

		wp_enqueue_script( self::ADMIN_HANDLE, $js_dir . 'admin.js', [
			'jquery',
			'react',
			'react-dom',
		], ADVANCED_SIDEBAR_BASIC_VERSION, true );

		wp_localize_script( self::ADMIN_HANDLE, 'ADVANCED_SIDEBAR_MENU', $this->js_config() );

		wp_enqueue_style( 'advanced-sidebar-menu/master-css', $js_dir . 'master.css', [], ADVANCED_SIDEBAR_BASIC_VERSION );
	}


	/**
	 * Configuration passed from PHP to JavaScript.
	 *
	 * @return array
	 */
	public function js_config() {
		return apply_filters( 'advanced-sidebar-menu/scripts/js-config', [
			'error' => apply_filters( 'advanced-sidebar-menu/scripts/js-config/error', '' ),
			'i18n'  => [
				'display'    => [
					'title'     => __( 'Display', 'advanced-sidebar-menu' ),
					/* translators: Selected taxonomy single label */
					'highest'   => __( 'Display the highest level parent %s', 'advanced-sidebar-menu' ),
					/* translators: Selected taxonomy single label */
					'childless' => __( 'Display menu when there is only the parent %s', 'advanced-sidebar-menu' ),
					/* translators: Selected taxonomy plural label */
					'always'    => __( 'Always display child %s', 'advanced-sidebar-menu' ),
					/* translators: {select html input}, {Selected post type plural label} */
					'levels'    => __( 'Display %1$s levels of child %2$s', 'advanced-sidebar-menu' ),
					'all'       => __( '- All -', 'advanced-sidebar-menu' ),
				],
				'docs'       => [
					'title'    => __( 'block documentation', 'advanced-sidebar-menu' ),
					'page'     => 'https://onpointplugins.com/advanced-sidebar-menu/#advanced-sidebar-pages-menu',
					'category' => 'https://onpointplugins.com/advanced-sidebar-menu/#advanced-sidebar-categories-menu',
				],
				'features'   => Notice::instance()->get_features(),
				'goPro'      => __( 'Advanced Sidebar Menu PRO', 'advanced-sidebar-menu' ),
				'pages'      => [
					'title'       => __( 'Advanced Sidebar - Pages', 'advanced-sidebar-menu' ),
					'description' => __( 'Creates a menu of all the pages using the child/parent relationship', 'advanced-sidebar-menu' ),
					// English and translated so both will be searchable.
					'keywords'    => [
						'Advanced Sidebar',
						'menu',
						'sidebar',
						'pages',
						__( 'menu', 'advanced-sidebar-menu' ),
						__( 'sidebar', 'advanced-sidebar-menu' ),
						__( 'pages', 'advanced-sidebar-menu' ),
					],
				],
				'soMuchMore' => __( 'So much more...', 'advanced-sidebar-menu' ),
				'upgrade'    => __( 'Upgrade', 'advanced-sidebar-menu' ),
			],
			'isPro' => false,
		] );
	}


	/**
	 * Trigger any JS needed by the widgets.
	 * This is outputted into the markup for each widget, so it may be
	 * trigger whether the widget is loaded on the front-end by
	 * page builders or the backend by standard WordPress or
	 * really anywhere.
	 *
	 * @notice Does not work in Gutenberg as widget's markup is loaded via REST API
	 *         and React.
	 *
	 * @return void
	 */
	public function init_widget_js() {
		if ( WP_DEBUG ) {
			?>
			<!-- <?php echo __FILE__; ?>-->
			<?php
		}
		?>
		<script>
			if ( typeof ( advanced_sidebar_menu ) !== 'undefined' ) {
				advanced_sidebar_menu.init();
			}
		</script>
		<?php
	}

}
