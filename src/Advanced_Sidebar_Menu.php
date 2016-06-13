<?php


/**
 * Advanced_Sidebar_Menu
 *
 * @author Mat Lipe
 * @since  6.0.0
 *
 */
class Advanced_Sidebar_Menu {

	private function hooks(){
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}


	public function register_widgets(){
		register_widget( "advanced_sidebar_menu_page" );
		register_widget( "advanced_sidebar_menu_category" );

	}


	/**
	 * Retrieve a template file from either the theme's 'advanced-sidebar-menu' directory
	 * or this plugins views folder if one does not exist
	 *
	 * @since 6.0.0
	 *
	 * @param string $file_name
	 *
	 * @return string
	 */
	public function get_template_part( $file_name ){
		$file = locate_template( 'advanced-sidebar-menu/' . $file_name );
		if( false == $file ){
			$file = ADVANCED_SIDEBAR_DIR . 'views/' . $file_name;
		}

		//backward compatibility
		$file = apply_filters( 'advanced_sidebar_menu_view_file', $file, false );

		$_filter_args = array(
			$file,
			$file_name,
			$this,
		);

		$file = apply_filters_ref_array( 'advanced_sidebar_menu_template_part', $_filter_args );

		return $file;
	}

	//********** SINGLETON FUNCTIONS **********/

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
	public static function init(){
		self::get_instance()->hooks();
	}


	/**
	 * Get (and instantiate, if necessary) the instance of the
	 * class
	 *
	 * @static
	 * @return self
	 */
	public static function get_instance(){
		if( !is_a( self::$instance, __CLASS__ ) ){
			self::$instance = new self();
		}

		return self::$instance;
	}
}