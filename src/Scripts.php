<?php

namespace Advanced_Sidebar_Menu;

use Advanced_Sidebar_Menu\Blocks\Block_Abstract;
use Advanced_Sidebar_Menu\Traits\Singleton;
use Advanced_Sidebar_Menu\Widget\Category;
use Advanced_Sidebar_Menu\Widget\Page;

/**
 * Scripts and styles.
 */
class Scripts {
	use Singleton;

	const GUTENBERG_HANDLE     = 'advanced-sidebar-menu/gutenberg';
	const GUTENBERG_CSS_HANDLE = 'advanced-sidebar-menu/gutenberg-css';

	/**
	 * Add various scripts to the cue.
	 */
	public function hook() {
		add_action( 'init', [ $this, 'register_gutenberg_scripts' ] );
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
	 * Register Gutenberg block scripts.
	 *
	 * We register instead of enqueue so Gutenberg will load them
	 * within the iframes of areas such as FSE.
	 *
	 * The actual script/style loading is done via `register_block_type`
	 * using 'editor_script' and 'editor_style.
	 *
	 * @action init 10 0
	 *
	 * @notice Must be run before `get_block_editor_settings` is
	 *         called to allow styles to be included in the Site
	 *         Editor iframe.
	 *
	 * @see    Block_Abstract::register()
	 *
	 * @link   https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#wpdefinedasset
	 *
	 * @since  9.0.0
	 *
	 * @return void
	 */
	public function register_gutenberg_scripts() {
		$js_dir = apply_filters( 'advanced-sidebar-menu/js-dir', ADVANCED_SIDEBAR_MENU_URL . 'js/dist/' );
		$file = $this->is_script_debug_enabled() ? 'admin' : 'admin.min';

		wp_register_script( self::GUTENBERG_HANDLE, "{$js_dir}{$file}.js", [
			'jquery',
			'react',
			'react-dom',
			'wp-url',
		], ADVANCED_SIDEBAR_BASIC_VERSION, true );

		wp_register_style( self::GUTENBERG_CSS_HANDLE, "{$js_dir}{$file}.css", [ 'dashicons' ], ADVANCED_SIDEBAR_BASIC_VERSION );

		/**
		 * Load separately because `$this->js_config()` is heavy, and
		 * the block scripts must be registered before we have
		 * access to `wp_should_load_block_editor_scripts_and_styles`.
		 */
		add_action( 'enqueue_block_editor_assets', function() {
			wp_localize_script( self::GUTENBERG_HANDLE, 'ADVANCED_SIDEBAR_MENU', $this->js_config() );
		}, 1 );
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
	}


	/**
	 * Is SCRIPT_DEBUG enabled or passed via URL argument.
	 *
	 * @since 9.0.0
	 *
	 * @return bool
	 */
	public function is_script_debug_enabled() {
		//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return ( \defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ! empty( $_GET['script-debug'] );
	}


	/**
	 * Configuration passed from PHP to JavaScript.
	 *
	 * @return array
	 */
	public function js_config() {
		$category = get_taxonomy( 'category' );
		return apply_filters( 'advanced-sidebar-menu/scripts/js-config', [
			'error'    => apply_filters( 'advanced-sidebar-menu/scripts/js-config/error', '' ),
			'i18n'     => [
				'categories' => [
					'title'        => __( 'Advanced Sidebar - Categories', 'advanced-sidebar-menu' ),
					'description'  => __( 'Creates a menu of all the categories using the child/parent relationship',
						'advanced-sidebar-menu' ),
					'eachCategory' => [
						/* translators: Selected taxonomy single label */
						'title'   => __( "Display each single post's %s", 'advanced-sidebar-menu' ),
						'options' => Category::get_display_each_options(),
					],
					// English and translated so both will be searchable.
					'keywords'     => [
						'Advanced Sidebar',
						'menu',
						'sidebar',
						'category',
						'categories',
						'taxonomy',
						'term',
						$category ? $category->labels->name : '',
						$category ? $category->labels->singular_name : '',
						__( 'menu', 'advanced-sidebar-menu' ),
						__( 'sidebar', 'advanced-sidebar-menu' ),
						__( 'taxonomy', 'advanced-sidebar-menu' ),
						__( 'term', 'advanced-sidebar-menu' ),
					],
					/* translators: Selected taxonomy plural label */
					'onSingle'     => __( 'Display %s on single posts', 'advanced-sidebar-menu' ),

				],
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
				/* translators: Selected post type plural label */
				'exclude'    => __( '%s to exclude (ids, comma separated)', 'advanced-sidebar-menu' ),
				'features'   => Notice::instance()->get_features(),
				'goPro'      => __( 'Advanced Sidebar Menu PRO', 'advanced-sidebar-menu' ),
				'noPreview'  => __( 'No preview available', 'advanced-sidebar-menu' ),
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
					'orderBy'     => [
						'title'   => __( 'Order by', 'advanced-sidebar-menu' ),
						'options' => Page::get_order_by_options(),
					],
				],
				'soMuchMore' => __( 'So much more...', 'advanced-sidebar-menu' ),
				'upgrade'    => __( 'Upgrade', 'advanced-sidebar-menu' ),
			],
			// Are we editing a post?
			'isPostEdit' => ! empty( $GLOBALS['pagenow'] ) && 'post.php' === $GLOBALS['pagenow'],
			'isPro' => false,
			'postType' => get_post_type_object( 'page' )->labels,
			'siteInfo' => [
				'basic'       => ADVANCED_SIDEBAR_BASIC_VERSION,
				'pro'         => false,
				'scriptDebug' => $this->is_script_debug_enabled(),
				'wordpress'   => get_bloginfo( 'version' ),
			],
			'support' => 'https://wordpress.org/support/plugin/advanced-sidebar-menu/#new-topic-0',
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
