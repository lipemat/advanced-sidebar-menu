<?php


/**
 * Advanced_Sidebar_Menu_List_Pages
 *
 * Parse and build the child and grandchild menus
 * Create the opening and closing <ul class="child-sidebar-menu">
 * in the view and this will fill in the guts.
 *
 * Send the args ( similar to wp_list_pages ) to the constructor and then
 * display by calling list_pages()
 *
 * @package Advanced Sidebar Menu
 *
 * @author  OnPoint Plugins <support@onpointplugins.com>
 *
 * @since   5.0.0
 */
class Advanced_Sidebar_Menu_List_Pages {

	/**
	 * The page list
	 *
	 * @var string
	 */
	public $output = '';

	/**
	 * Used when walking the list
	 *
	 * @var null|\WP_Post
	 */
	protected $current_page;

	/**
	 * The top level parent id according to the menu class
	 *
	 * @var int
	 */
	protected $top_parent_id;

	/**
	 * Passed during construct given to walker and used for queries
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * Used exclusively for caching
	 * Holds the value of the latest parent we
	 * retrieve children for so Cache can distinguish
	 * between calls.
	 *
	 * @var int
	 */
	protected $current_children_parent = 0;

	/**
	 * Menu class
	 *
	 * @var \Advanced_Sidebar_Menu_Menus_Page
	 */
	protected $menu;


	/**
	 * Constructor
	 *
	 * @param \Advanced_Sidebar_Menu_Menus_Page $menu - The menu class.
	 */
	protected function __construct( Advanced_Sidebar_Menu_Menus_Page $menu ) {
		$this->menu          = $menu;
		$this->top_parent_id = $menu->get_top_parent_id();
		$this->current_page  = $menu->get_current_post();

		$args = array(
			'post_type' => $menu->get_post_type(),
			'orderby'   => $menu->get_order_by(),
			'order'     => $menu->get_order(),
			'exclude'   => $menu->get_excluded_ids(),
			'levels'    => $menu->get_levels_to_display(),
		);

		$this->args = $this->parse_args( $args );
		$this->hook();
	}


	/**
	 * Hooks should only hook once
	 *
	 * @todo find a more appropriate place for this?
	 *
	 * @return void
	 */
	protected function hook() {
		add_filter( 'page_css_class', array( $this, 'add_list_item_classes' ), 2, 2 );
	}


	/**
	 * Add the custom classes to the list items
	 *
	 * @param array    $classes - Provided classes for item.
	 * @param \WP_Post $post - The item.
	 *
	 * @return array
	 */
	public function add_list_item_classes( $classes, WP_Post $post ) {
		if ( $post->ID === $this->top_parent_id ) {
			$children = $this->get_child_pages( $post->ID, true );
		} else {
			$children = $this->get_child_pages( $post->ID );
		}
		if ( ! empty( $children ) ) {
			$classes[] = 'has_children';
		}

		// page posts are handled by wp core. This is for custom post types.
		if ( 'page' !== $post->post_type ) {
			$ancestors = get_post_ancestors( $post );
			if ( ! empty( $ancestors ) && in_array( $this->get_current_page_id(), $ancestors, false ) ) { //phpcs:ignore
				$classes[] = 'current_page_ancestor';
			} elseif ( $this->get_current_page_id() === $post->post_parent ) {
				$classes[] = 'current_page_parent';
			}
		}

		return array_unique( $classes );
	}


	/**
	 * Return the list of args that have been populated by this class
	 * For use with wp_list_pages()
	 *
	 * @param string $level - level of menu so we have full control of updates.
	 *
	 * @return array
	 */
	public function get_args( $level = null ) {
		if ( null === $level ) {
			return $this->args;
		}
		$args = $this->args;
		switch ( $level ) {
			case Advanced_Sidebar_Menu_Menus_Page::LEVEL_PARENT:
				$args['include'] = $this->menu->get_top_parent_id();
				break;
			case Advanced_Sidebar_Menu_Menus_Page::LEVEL_DISPLAY_ALL:
				$args['child_of']    = $this->menu->get_top_parent_id();
				$args['depth']       = $this->menu->get_levels_to_display();
				$args['sort_column'] = $this->menu->get_order_by();
				break;
		}

		return apply_filters( 'advanced-sidebar-menu/list-pages/get-args', $args, $level, $this );
	}


	/**
	 * Return menu which was passed to this class
	 *
	 * @return Advanced_Sidebar_Menu_Menus_Page
	 */
	public function get_menu() {
		return $this->menu;
	}


	/**
	 * __toString
	 *
	 * Magic method to allow using a simple echo to get output
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->output;
	}


	/**
	 * Do any adjustments to class args here
	 *
	 * @param array $args - Arguments for walk_page_tree.
	 *
	 * @return array
	 */
	protected function parse_args( $args ) {
		$defaults = array(
			'exclude'          => '',
			'echo'             => 0,
			'order'            => 'ASC',
			'orderby'          => 'menu_order, title',
			'walker'           => new Advanced_Sidebar_Menu_Page_Walker(),
			'link_before'      => '',
			'link_after'       => '',
			'title_li'         => '',
			'levels'           => 100,
			'item_spacing'     => 'preserve',
			'posts_per_page'   => 100,
			'suppress_filters' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( is_string( $args['exclude'] ) ) {
			$args['exclude'] = explode( ',', $args['exclude'] );
		}
		// sanitize, mostly to keep spaces out.
		$args['exclude'] = preg_replace( '/[^0-9,]/', '', implode( ',', apply_filters( 'wp_list_pages_excludes', $args['exclude'] ) ) );

		return apply_filters( 'advanced_sidebar_menu_list_pages_args', $args, $this );

	}


	/**
	 * Return the ID of the current page or 0 if not on a page.
	 * Helper method to prevent a bunch of conditionals throughout.
	 *
	 * @since 7.7.2
	 */
	public function get_current_page_id() {
		if ( null !== $this->current_page ) {
			return $this->current_page->ID;
		}
		return 0;
	}


	/**
	 * List the pages very similar to wp_list_pages.
	 *
	 * @return string
	 */
	public function list_pages() {

		$pages = $this->get_child_pages( $this->top_parent_id, true );
		foreach ( $pages as $page ) {
			$this->output .= walk_page_tree( array( $page ), 1, $this->get_current_page_id(), $this->args );
			$this->output .= $this->list_grandchild_pages( $page->ID, 0 );
			$this->output .= '</li>' . "\n";
		}

		$this->output = apply_filters( 'wp_list_pages', $this->output, $this->args, $pages );
		if ( ! $this->args['echo'] ) {
			return $this->output;
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->output;
		return '';
	}


	/**
	 * List all levels of grandchild pages up to the limit set in the widget.
	 * All grandchild pages will be rendered inside `grandchild-sidebar-menu` uls.
	 *
	 * @param int $parent_page_id - Id of the page we are getting the grandchildren of.
	 * @param int $level - Level of grandchild pages we are displaying.
	 *
	 * @return string
	 */
	protected function list_grandchild_pages( $parent_page_id, $level ) {
		if ( $level >= (int) $this->args['levels'] ) {
			return '';
		}
		if ( ! $this->menu->display_all() && ! $this->is_current_page_ancestor( $parent_page_id ) ) {
			return '';
		}
		$pages = $this->get_child_pages( $parent_page_id );
		if ( empty( $pages ) ) {
			return '';
		}

		$content = sprintf( '<ul class="grandchild-sidebar-menu level-%s children">', $level );

		$inside = '';
		foreach ( $pages as $page ) {
			$inside .= walk_page_tree( [ $page ], 1, $this->get_current_page_id(), $this->args );
			$inside .= $this->list_grandchild_pages( $page->ID, $level + 1 );
			$inside .= "</li>\n";
		}

		if ( '' === $inside ) {
			return '';
		}

		return $content . $inside . "</ul>\n";
	}


	/**
	 * Retrieve the child pages of specific page_id
	 *
	 * @param int  $parent_page_id - Page id we are getting children of.
	 * @param bool $is_first_level - Is this the first level of child pages?.
	 *
	 * @since 7.5.5 - Add 'advanced-sidebar-menu/list-pages/grandchild-pages' filter.
	 *
	 * @return WP_Post[]
	 */
	public function get_child_pages( $parent_page_id, $is_first_level = false ) {
		// holds a unique key so Cache can distinguish calls.
		$this->current_children_parent = $parent_page_id;

		$cache       = Advanced_Sidebar_Menu_Cache::instance();
		$child_pages = $cache->get_child_pages( $this );
		if ( false === $child_pages ) {
			$args                     = $this->args;
			$args['post_parent']      = $parent_page_id;
			$args['fields']           = 'ids';
			$args['suppress_filters'] = false;
			$child_pages              = get_posts( $args );

			$cache->add_child_pages( $this, $child_pages );
		}

		$child_pages = array_map( 'get_post', (array) $child_pages );

		if ( $is_first_level ) {
			return apply_filters( 'advanced-sidebar-menu/list-pages/first-level-child-pages', $child_pages, $this, $this->menu );
		}

		// @since 7.5.5
		return apply_filters( 'advanced-sidebar-menu/list-pages/grandchild-pages', $child_pages, $this, $this->menu );

	}


	/**
	 * Is the specified page an ancestor of the current page?
	 *
	 * @param int $page_id - Post id to check against.
	 *
	 * @return bool
	 */
	public function is_current_page_ancestor( $page_id ) {
		$return = false;
		$current_page_id = $this->get_current_page_id();
		if ( ! empty( $current_page_id ) ) {
			if ( (int) $page_id === $current_page_id ) {
				$return = true;
			} elseif ( $this->current_page->post_parent === (int) $page_id ) {
				$return = true;
			} else {
				$ancestors = get_post_ancestors( $this->current_page );
				if ( ! empty( $ancestors ) && in_array( (int) $page_id, $ancestors, true ) ) {
					$return = true;
				}
			}
		}

		return apply_filters( 'advanced_sidebar_menu_page_ancestor', $return, $current_page_id, $this );
	}


	/**
	 * List Pages Factory
	 *
	 * @param \Advanced_Sidebar_Menu_Menus_Page $menu - The menu class.
	 *
	 * @static
	 *
	 * @return Advanced_Sidebar_Menu_List_Pages
	 */
	public static function factory( Advanced_Sidebar_Menu_Menus_Page $menu ) {
		return new self( $menu );
	}

}
