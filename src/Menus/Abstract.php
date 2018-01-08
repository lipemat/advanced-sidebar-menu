<?php

/**
 * Advanced_Sidebar_Menu_Menus_Abstract
 *
 * @author Mat Lipe
 * @since  7.0.0
 */
abstract class Advanced_Sidebar_Menu_Menus_Abstract {
	// keys available in both widgets.
	const TITLE                    = 'title';
	const INCLUDE_PARENT           = 'include_parent';
	const INCLUDE_CHILDLESS_PARENT = 'include_childless_parent';
	const ORDER                    = 'order';
	const ORDER_BY                 = 'order_by';
	const USE_PLUGIN_STYLES        = 'css';
	const EXCLUDE                  = 'exclude';
	const DISPLAY_ALL              = 'display_all';
	const LEVELS                   = 'levels';

	/**
	 * args
	 *
	 * Widget Args
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * exclude
	 *
	 * @deprecated
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
	 * @deprecated
	 *
	 * @var int
	 */
	public $levels = 100;

	/**
	 * order
	 *
	 * @deprecated 7.0.0
	 *
	 * @var string
	 */
	public $order = 'ASC';

	/**
	 * order_by
	 *
	 * @deprecated 7.0.0
	 *
	 * @var string
	 */
	public $order_by;

	/**
	 * Top post_id or term_id
	 *
	 * @deprecated 7.0.0
	 *
	 * @var int
	 */
	public $top_id;


	public function __construct( array $widget_instance, array $widget_args ) {
		$this->instance = $widget_instance;
		$this->args     = $widget_args;
	}

	abstract public function get_top_parent_id();

	abstract public function get_order_by();

	abstract public function get_order();

	abstract public function render();

	abstract public function is_displayed();

	abstract public function get_levels_to_display();

	abstract public function get_menu_depth();


	/**
	 * Return the type of widget we are working with
	 * Used for comparisons like so
	 *
	 * $menu->get_widget_type() === Menus_Page::WIDGET
	 *
	 * @return string - 'page', 'category',
	 */
	public function get_widget_type() {
		return self::WIDGET;
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
	 * The widget arguments from the current widget
	 *
	 * @return array
	 */
	public function get_widget_args() {
		return $this->args;
	}


	/**
	 * Checks if a widgets checkbox is checked.
	 *
	 * Checks first for a value then verifies the value = checked
	 *
	 * @param string $name - name of checkbox.
	 *
	 * @return bool
	 */
	public function checked( $name ) {
		return isset( $this->instance[ $name ] ) && 'checked' === $this->instance[ $name ];
	}


	/**
	 * Determines if all the children should be included
	 *
	 * @return bool
	 */
	public function display_all() {
		return $this->checked( self::DISPLAY_ALL );
	}


	/**
	 * Determines if the parent page or cat should be included
	 *
	 * @return bool
	 */
	public function include_parent() {
		if ( $this->checked( self::INCLUDE_PARENT ) && ! $this->is_excluded( $this->get_top_parent_id() ) ) {
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
		$excluded = explode( ',', $this->instance[ self::EXCLUDE ] );
		$excluded = array_filter( $excluded );
		$excluded = array_map( 'intval', $excluded );

		return $excluded;
	}


	/**
	 * Echos the title of the widget to the page
	 *
	 * @todo find somewhere more appropriate for this?
	 */
	public function title() {
		if ( ! empty( $this->instance[ self::TITLE ] ) ) {
			$title = apply_filters( 'widget_title', $this->instance[ self::TITLE ], $this->args, $this->instance );
			$title = apply_filters( 'advanced_sidebar_menu_widget_title', esc_html( $title ), $this->args, $this->instance, $this );

			echo $this->args['before_title'] . $title . $this->args['after_title'];
		}
	}

	/********************* static *******************************/

	/**
	 *
	 * @static
	 *
	 * @var \Advanced_Sidebar_Menu_Menus_Page|\Advanced_Sidebar_Menu_Menus_Category
	 */
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
	public static function _factory( $class, array $widget_instance, array $widget_args ) {
		$menu          = new $class( $widget_instance, $widget_args );
		self::$current = $menu;
		return $menu;
	}
}
