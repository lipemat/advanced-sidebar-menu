<?php


/**
 * Advanced_Sidebar_Menu_Menus_Page
 *
 * @author OnPoint Plugins
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Menus_Page extends Advanced_Sidebar_Menu_Menus_Abstract {
	const WIDGET = 'page';

	/**
	 * post_type
	 *
	 * @deprecated
	 *
	 * @var string
	 */
	public $post_type = 'page';

	/**
	 *
	 * @var null|\WP_Post
	 */
	protected $post;

	protected $ancestors;


	public function set_current_post( WP_Post $post ) {
		$this->post = $post;
	}


	/**
	 * Gets the current queried post unless it
	 * has been set explicitly.
	 *
	 * @return null|\WP_Post
	 */
	public function get_current_post() {
		if ( null === $this->post ) {
			if ( is_page() || is_singular() ) {
				$this->post = get_queried_object();
			}
		}

		return $this->post;
	}


	public function get_order_by() {
		return apply_filters( 'advanced_sidebar_menu_order_by', $this->instance[ self::ORDER_BY ], $this->get_current_post(), $this->args, $this->instance, $this );
	}


	public function get_order() {
		return apply_filters( 'advanced_sidebar_menu_page_order', 'ASC', $this->get_current_post(), $this->args, $this->instance, $this );
	}


	/**
	 * Get the id of page which is the top level parent of
	 * the page we are currently on.
	 *
	 * Returns -1 if we don't have one.
	 *
	 * @return int
	 */
	public function get_top_parent_id() {
		$top_id = - 1;
		$ancestors = get_post_ancestors( $this->get_current_post() );
		if ( ! empty( $ancestors ) ) {
			$top_id = end( $ancestors );
		} elseif ( null !== $this->get_current_post() ) {
			$top_id = $this->get_current_post()->ID;
		}

		return apply_filters( 'advanced_sidebar_menu_top_parent', $top_id, $this->args, $this->instance, $this );
	}


	public function is_displayed() {
		$display   = false;
		$post_type = $this->get_post_type();
		if ( is_page() || ( is_single() && $post_type === $this->get_current_post()->post_type ) ) {
			//if we are on the correct post type
			if ( get_post_type( $this->get_top_parent_id() ) === $post_type ) {
				//if we have children
				if ( $this->has_pages() ) {
					$display = true;
					//no children + not excluded + include parent +include childless parent
				} elseif ( $this->checked( self::INCLUDE_CHILDLESS_PARENT ) && $this->checked( self::INCLUDE_PARENT ) && ! $this->is_excluded( $this->get_top_parent_id() ) ) {
					$display = true;
				}
			}
		}

		$display = ! apply_filters_deprecated( 'advanced_sidebar_menu_proper_single', array(
			! $display,
			$this->args,
			$this->instance,
			$this,
		), '7.0.0', 'advanced-sidebar-menu/menus/page/is-displayed' );


		return apply_filters( 'advanced-sidebar-menu/menus/page/is-displayed', $display, $this->args, $this->instance, $this );

	}


	/**
	 * Do we have child pages at all on this menu?
	 *
	 * Return false if all we have is the top parent page
	 * Return true if we have at least a second level
	 *
	 * @return bool
	 */
	public function has_pages() {
		$list_pages = Advanced_Sidebar_Menu_List_Pages::factory( $this );
		$children   = $list_pages->get_child_pages( $this->get_top_parent_id(), true );

		return ! empty( $children );
	}


	/**
	 * Gets the number of levels ot display when doing 'Always display'
	 *
	 * @return int
	 */
	public function get_levels_to_display() {
		$levels = 100;
		if ( $this->display_all() ) {
			// Subtract 1 level to account for the first level children.
			$levels = $this->instance[ self::LEVELS ] - 1;
		}
		return apply_filters( 'advanced-sidebar-menu/menus/page/levels', $levels, $this->args, $this->instance, $this );
	}


	/**
	 * @deprecated
	 */
	public function get_menu_depth() {
		_deprecated_function( 'get_menu_depth', '7.5.0', 'get_levels_to_display' );
		return apply_filters( 'advanced-sidebar-menu/menus/page/depth', $this->get_levels_to_display(), $this->args, $this->instance, $this );
	}


	public function get_post_type() {
		return apply_filters( 'advanced_sidebar_menu_post_type', $this->post_type, $this->args, $this->instance, $this );
	}


	/**
	 * Get ids of any pages excluded via widget settings.
	 *
	 * @return array|mixed
	 */
	public function get_excluded_ids() {
		return apply_filters( 'advanced_sidebar_menu_excluded_pages', parent::get_excluded_ids(), $this->get_current_post(), $this->args, $this->instance, $this );
	}


	/**
	 * Render the widget
	 *
	 * @return void
	 */
	public function render() {
		if ( ! $this->is_displayed() ) {
			return;
		}

        // phpcs:disable
		echo $this->args['before_widget'];

		do_action( 'advanced-sidebar-menu/menus/page/render', $this );

		if ( $this->checked( self::USE_PLUGIN_STYLES ) ) {
			Advanced_Sidebar_Menu_Core::instance()->include_plugin_styles();
		}

		$output = require Advanced_Sidebar_Menu_Core::instance()->get_template_part( 'page_list.php' );
		echo apply_filters( 'advanced_sidebar_menu_page_widget_output', $output, $this->get_current_post(), $this->args, $this->instance, $this );

		// @since 7.6.5.
		do_action( 'advanced-sidebar-menu/menus/page/render/after', $this );

		echo $this->args['after_widget'];
		// phpcs:enable
	}


	/**************** static *****************/
	/**
	 * Mostly here to call the parent _factory() in a PHP 5.2 structure
	 *
	 * @param array $widget_instance
	 * @param array $widget_args
	 *
	 * @static
	 *
	 * @return \Advanced_Sidebar_Menu_Menus_Page
	 */
	public static function factory( array $widget_instance, array $widget_args ) {
		return parent::_factory( __CLASS__, $widget_instance, $widget_args );
	}
}
