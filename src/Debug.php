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
		if( !empty( $_REQUEST[ self::DEBUG_PARAM ] ) ){
			add_action( 'advanced_sidebar_menu_widget_pre_render', array( $this, 'print_instance' ), 1, 2 );
		}
	}


	/**
	 *
	 * @param Advanced_Sidebar_Menu_Menu $asm
	 * @param \advanced_sidebar_menu_page $widget
	 *
	 * @return void
	 */
	public function print_instance( $asm, $widget ){
		?>
		<script class="<?php echo  self::DEBUG_PARAM; ?>">
			var <?php echo  self::DEBUG_PARAM; ?> = window.<?php echo  self::DEBUG_PARAM; ?> || {};
			    <?php echo  self::DEBUG_PARAM; ?>['version'] = '<?php echo  ADVANCED_SIDEBAR_BASIC_VERSION; ?>';
                <?php echo  self::DEBUG_PARAM; ?>['<?php echo  $widget->id; ?>'] = <?php echo  json_encode( $asm->instance ); ?>;
		</script>
		<?php
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
		if( !is_a( self::$instance, __CLASS__ ) ){
			self::$instance = new self();
		}

		return self::$instance;
	}
}