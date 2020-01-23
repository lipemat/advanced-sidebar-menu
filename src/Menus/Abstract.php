<?php

/**
 * Advanced_Sidebar_Menu_Menus_Abstract
 *
 * @author OnPoint Plugins
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

	const LEVEL_CHILD       = 'child';
	const LEVEL_DISPLAY_ALL = 'display-all';
	const LEVEL_GRANDCHILD  = 'grandchild';
	const LEVEL_PARENT      = 'parent';

	/**
	 * Widget Args
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Widget instance
	 *
	 * @var array
	 */
	public $instance;

	/**
	 * @deprecated 7.0.0
	 *
	 * @var string
	 */
	public $order = 'ASC';

	/**
	 * @deprecated 7.0.0
	 *
	 * @var string
	 */
	public $order_by;

	/**
	 * Track the ids which have been used in case of
	 * plugins like Elementor that we need to manually increment.
	 *
	 * @since 7.6.0
	 * @ticket #4775
	 *
	 * @var string[]
	 */
	protected static $unique_widget_ids = array();


	public function __construct( array $widget_instance, array $widget_args ) {
		$this->instance = apply_filters( 'advanced-sidebar-menu/menus/widget-instance', $widget_instance, $widget_args, $this );
		$this->args     = $widget_args;

		$this->increment_widget_id();
	}


	abstract public function get_top_parent_id();


	abstract public function get_order_by();


	abstract public function get_order();


	abstract public function render();


	abstract public function is_displayed();


	abstract public function get_levels_to_display();


	/**
	 * Increment the widget id until it is unique to all widgets being displayed
	 * in the current context.
	 *
	 * Required because plugins like Elementor will reuse the same generic id for
	 * widgets within page content and we need a unique id to properly target with
	 * styles, accordions, etc.
	 *
	 * @since 7.6.0
	 * @ticket #4775
	 *
	 * @return void
	 */
	protected function increment_widget_id() {
		if ( ! isset( $this->args['widget_id'] ) ) {
			return;
		}
		if ( in_array( $this->args['widget_id'], self::$unique_widget_ids, true ) ) {
			$suffix = 2;
			do {
				$alt_widget_id = $this->args['widget_id'] . "-$suffix";
				$suffix ++;
			} while ( in_array( $alt_widget_id, self::$unique_widget_ids, true ) );
			$this->args['widget_id']   = $alt_widget_id;
			self::$unique_widget_ids[] = $alt_widget_id;
		} else {
			self::$unique_widget_ids[] = $this->args['widget_id'];
		}
	}


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
		return $this->checked( self::INCLUDE_PARENT ) && ! $this->is_excluded( $this->get_top_parent_id() );
	}


	/**
	 * Is this id excluded from this menu?
	 *
	 * @param int $id ID of the object.
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
		$excluded = array_filter( $excluded, 'is_numeric' );
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

			// phpcs:disable
			echo $this->args['before_title'] . $title . $this->args['after_title'];
			// phpcs:enable
		}
	}


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
