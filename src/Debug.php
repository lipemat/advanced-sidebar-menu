<?php


/**
 * Advanced_Sidebar_Menu_Debug
 *
 * @author Mat Lipe
 * @since  6.3.1
 *
 */
class Advanced_Sidebar_Menu_Debug {
	const DEBUG_PARAM = 'asm_debug';


	protected function hook() {
		if ( ! empty( $_GET[ self::DEBUG_PARAM ] ) ) {
			add_action( 'advanced_sidebar_menu_widget_pre_render', array( $this, 'print_instance' ), 1, 2 );
		}
	}


	/**
	 *
	 * @param Advanced_Sidebar_Menu_Menu        $asm
	 * @param Advanced_Sidebar_Menu_Widget_Page $widget
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

	//********** SINGLETON **********/


	/**
	 * Instance of this class for use as singleton
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
