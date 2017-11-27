<?php


/**
 * Advanced_Sidebar_Menu_Menus_Abstract
 *
 * @author Mat Lipe
 * @since  7.0.0
 *
 */
abstract class Advanced_Sidebar_Menu_Menus_Abstract {
	/**
	 * args
	 *
	 * Widget Args
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Ids to exclude
	 *
	 * @var array
	 */
	public $exclude = array();

	/**
	 * Widget instance
	 *
	 * @var array
	 */
	public $instance;

	/**
	 * levels
	 *
	 * @var int
	 */
	public $levels = 100;

	/**
	 * order
	 *
	 * @var string
	 */
	public $order = 'ASC';

	/**
	 * order_by
	 *
	 * @var string
	 */
	public $order_by;

	/**
	 * Top post_id or term_id
	 *
	 * @var int
	 */
	public $top_id;


	public function __construct( array $widget_instance, array $widget_args ) {
		$this->instance = $widget_instance;
		$this->args = $widget_args;
	}


	/**
	 * The instance arguments from the current widget
	 *
	 * @return array
	 */
	public function get_widget_instance() {
		return $this->instance;
	}


	/**
	 * Checks if a widgets checkbox is checked.
	 *
	 * Checks first for a value then verifies the value = checked
	 *
	 * @param string $name - name of checkbox
	 *
	 * @return bool
	 */
	public function checked( $name ) {
		return isset( $this->instance[ $name ] ) && $this->instance[ $name ] === 'checked';
	}


	/**
	 * Determines if all the children should be included
	 *
	 * @return bool
	 */
	public function display_all() {
		return $this->checked( 'display_all' );
	}


	/**
	 * Determines if the parent page or cat should be included
	 *
	 * @return bool
	 */
	public function include_parent() {
		if( $this->checked( 'include_parent' ) && !$this->is_excluded( $this->top_id ) ){
			return true;
		}

		return false;
	}


	/**
	 * Is this id excluded from this menu?
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function is_excluded( $id ) {
		$exclude = $this->get_excluded_ids();

		return in_array( (int) $id, $exclude, true );
	}


	/**
	 * Retrieve the excluded items' ids
	 *
	 * @return array
	 */
	public function get_excluded_ids() {
		$excluded = $this->exclude;
		$excluded = array_filter( $excluded );
		$excluded = array_map( 'intval', $excluded );

		return $excluded;
	}


	/**
	 * Echos the title of the widget to the page
	 *
	 * @todo find somewhere more appropriate for this?
	 *
	 */
	public function title() {
		if( !empty( $this->instance[ 'title' ] ) ){
			$title = apply_filters( 'widget_title', $this->instance[ 'title' ], $this->args, $this->instance );
			$title = apply_filters( 'advanced_sidebar_menu_widget_title', esc_html( $title ), $this->args, $this->instance, $this );

			echo $this->args[ 'before_title' ] . $title . $this->args[ 'after_title' ];
		}
	}


	protected static $current;


	/**
	 *
	 * @static
	 *
	 * @return \Advanced_Sidebar_Menu_Menus_Page|\Advanced_Sidebar_Menu_Menus_Category
	 */
	public static function get_current() {
		return self::$current;
	}


	/**
	 * static() does not exist until PHP 5.3 which means we have to do
	 * this hideous thing where we call the factory method from the child
	 * class and pass it's name.
	 * Chose to handle it this way instead of trying to maintain 2 separate
	 * factory methods with logic.
	 *
	 * @param string $class
	 * @param array  $widget_instance
	 * @param array  $widget_args
	 *
	 * @static
	 *
	 * @return mixed
	 */
	public static function factory( $class, array $widget_instance, array $widget_args ) {
		$menu = new $class( $widget_instance, $widget_args );
		self::$current = $menu;

		//backward compatibility
		Advanced_Sidebar_Menu_Menu::set_current( $menu );

		return $menu;
	}
}