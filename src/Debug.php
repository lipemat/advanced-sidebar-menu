<?php

/**
 * Advanced_Sidebar_Menu_Debug
 *
 * @author OnPoint Plugins
 * @since  6.3.1
 * @since  7.4.8 - Use URL arguments to test different configurations.
 */
class Advanced_Sidebar_Menu_Debug {
	const DEBUG_PARAM = 'asm_debug';


	/**
	 * Add actions and filters.
	 *
	 * @return void
	 */
	protected function hook() {
		if ( ! empty( $_GET[ self::DEBUG_PARAM ] ) ) { //phpcs:ignore
			add_action( 'advanced_sidebar_menu_widget_pre_render', array( $this, 'print_instance' ), 1, 2 );

			if ( is_array( $_GET[ self::DEBUG_PARAM ] ) ) { //phpcs:ignore
				add_filter( 'advanced-sidebar-menu/menus/widget-instance', array( $this, 'adjust_widget_settings' ) );
			}
		}
	}


	/**
	 * Adjust widget settings using the URL.
	 *
	 * @param array $instance - Widget settings.
	 *
	 * @return array
	 */
	public function adjust_widget_settings( array $instance ) {
		//phpcs:ignore
		$overrides = array_map( 'sanitize_text_field', (array) $_GET[ self::DEBUG_PARAM ] );

		return wp_parse_args( $overrides, $instance );
	}


	/**
	 * Print the widget settings as a js variable.
	 *
	 * @param Advanced_Sidebar_Menu_Menus_Abstract $asm    - Menu class.
	 * @param Advanced_Sidebar_Menu_Widget_Page    $widget - Widget class.
	 *
	 * @return void
	 */
	public function print_instance( $asm, $widget ) {
		static $printed = false;
		$data = array(
			'version'   => ADVANCED_SIDEBAR_BASIC_VERSION,
			$widget->id => $asm->instance,
		);
		if ( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ) {
			$data['pro_version'] = ADVANCED_SIDEBAR_MENU_PRO_VERSION;
		}

		if ( ! $printed ) {
			?>
			<script class="<?php echo esc_attr( self::DEBUG_PARAM ); ?>">
				var <?php echo esc_attr( self::DEBUG_PARAM ); ?> = <?php echo wp_json_encode( $data ); ?>;
			</script>
			<?php
			$printed = true;
		} else {
			?>
			<script class="<?php echo esc_attr( self::DEBUG_PARAM ); ?>">
					<?php echo esc_attr( self::DEBUG_PARAM ); ?>['<?php echo esc_js( $widget->id ); ?>'] = <?php echo wp_json_encode( $asm->instance ); ?>;
			</script>
			<?php
		}
	}


	/**
	 * Instance of this class for use as singleton
	 *
	 * @var Advanced_Sidebar_Menu_Debug
	 */
	private static $instance;


	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		self::instance()->hook();
	}


	/**
	 * Get (and instantiate, if necessary) the instance of the
	 * class
	 *
	 * @static
	 * @return self
	 */
	public static function instance() {
		if ( ! is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
