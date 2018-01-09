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
 * @author  Mat Lipe <mat@matlipe.com>
 *
 * @since   5.0.0
 *
 */
class Advanced_Sidebar_Menu_List_Pages {

	/**
	 * output
	 *
	 * The page list
	 *
	 * @var string
	 */
	public $output = '';

	/**
	 * current_page
	 *
	 * Used when walking the list
	 *
	 * @var WP_Post
	 */
	protected $current_page;

	/**
	 * top_parent_id
	 *
	 * The top level parent id according to the menu class
	 *
	 * @var int
	 */
	protected $top_parent_id;

	/**
	 * args
	 *
	 * Passed during construct given to walker and used for queries
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * level
	 *
	 * Level of grandchild pages we are on
	 *
	 * @var int
	 */
	protected $level = 0;

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
	 * menu
	 *
	 * Menu class
	 *
	 * @var \Advanced_Sidebar_Menu_Menus_Page
	 */
	protected $menu;


	/**
	 * Constructor
	 *
	 * @param \Advanced_Sidebar_Menu_Menus_Page $menu
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
			'levels'    => $menu->get_menu_depth(),
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
	 *
	 * @param array    $classes
	 * @param \WP_Post $post
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

		//page posts are handled by wp core. This is for custom post types
		if ( 'page' !== $post->post_type ) {
			$ancestors = get_post_ancestors( $post );
			if ( ! empty( $ancestors ) && in_array( $this->current_page->ID, $ancestors, false ) ) {
				$classes[] = 'current_page_ancestor';
			} elseif ( $this->current_page->ID === $post->post_parent ) {
				$classes[] = 'current_page_parent';
			}
		}

		return array_unique( $classes );
	}


	/**
	 * Return the list of args that have been populated by this class
	 * For use with wp_list_pages()
	 *
	 * @param string $level - level of menu so we have full control of updates
	 *
	 * @return array
	 */
	public function get_args( $level = null ) {
		if ( null === $level ) {
			return $this->args;
		}
		$args = $this->args;
		switch ( $level ) {
			case 'parent':
				$args['include'] = $this->menu->get_top_parent_id();
				break;
			case 'display-all':
				$args['child_of'] = $this->menu->get_top_parent_id();
				$args['depth']    = $this->menu->get_levels_to_display();
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
	 *
	 * Do any adjustments to class args here
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected function parse_args( $args ) {
		$defaults = array(
			'exclude'          => '',
			'echo'             => 0,
			'order'            => 'ASC',
			'orderby'          => 'menu_order, post_title',
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
		//sanitize, mostly to keep spaces out
		$args['exclude'] = preg_replace( '/[^0-9,]/', '', implode( ',', apply_filters( 'wp_list_pages_excludes', $args['exclude'] ) ) );

		return apply_filters( 'advanced_sidebar_menu_list_pages_args', $args, $this );

	}


	/**
	 * list_pages
	 *
	 * List the pages very similar to wp_list_pages
	 *
	 * @return string
	 */
	public function list_pages() {
		$pages = $this->get_child_pages( $this->top_parent_id, true );
		foreach ( $pages as $page ) {
			$this->output .= walk_page_tree( array( $page ), 1, $this->current_page->ID, $this->args );
			$this->output .= $this->list_grandchild_pages( $page->ID );
			$this->output .= '</li>' . "\n";
		}

		$this->output = apply_filters( 'wp_list_pages', $this->output, $this->args );
		if ( ! $this->args['echo'] ) {
			return $this->output;
		}
		echo $this->output;
	}


	/**
	 * list_grandchild_pages
	 *
	 * List as many levels as exist within the grandchild-sidebar-menu ul
	 *
	 * @param int $parent_page_id
	 *
	 * @return string
	 */
	protected function list_grandchild_pages( $parent_page_id ) {
		if ( $this->level >= (int) $this->args['levels'] ) {
			return '';
		}
		if ( ! $this->is_current_page_ancestor( $parent_page_id ) ) {
			return '';
		}
		$pages = $this->get_child_pages( $parent_page_id );
		if ( empty( $pages ) ) {
			return '';
		}

		$this->level ++;
		$content = sprintf( '<ul class="grandchild-sidebar-menu level-%s children">', $this->level );

		$inside = '';
		foreach ( $pages as $page ) {
			$inside .= walk_page_tree( array( $page ), 1, $this->current_page->ID, $this->args );
			$inside .= $this->list_grandchild_pages( $page->ID );
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
	 * @param int  $parent_page_id
	 * @param bool $is_first_level
	 *
	 * @return WP_Post[]
	 */
	public function get_child_pages( $parent_page_id, $is_first_level = false ) {
		//holds a unique key so Cache can distinguish calls
		$this->current_children_parent = $parent_page_id;

		$cache       = Advanced_Sidebar_Menu_Cache::instance();
		$child_pages = $cache->get_child_pages( $this );
		if ( false === $child_pages ) {
			$args                = $this->args;
			$args['post_parent'] = $parent_page_id;
			$args['fields']      = 'ids';
			$child_pages         = get_posts( $args );

			$cache->add_child_pages( $this, $child_pages );
		}

		$child_pages = array_map( 'get_post', (array) $child_pages );

		//we only filter the first level with this filter for backward pro compatibility
		if ( $is_first_level ) {
			if ( has_filter( 'advanced_sidebar_menu_child_pages' ) ) {
				//@todo uncomment deprecated hook notice once pro 3.0.0 is released
				//_deprecated_hook( 'advanced_sidebar_menu_child_pages', '7.1.0', 'advanced-sidebar-menu/list-pages/first-level-child-pages' );
				$child_pages = apply_filters( 'advanced_sidebar_menu_child_pages', $child_pages, $this->current_page, $this->menu->instance, $this->menu->args, $this->menu );
			}

			$child_pages = apply_filters( 'advanced-sidebar-menu/list-pages/first-level-child-pages', $child_pages, $this, $this->menu );
		}

		return $child_pages;

	}


	/**
	 * is_current_page_ancestor
	 *
	 * Is the current page and ancestor of the specified page?
	 *
	 * @param $page_id
	 *
	 * @return bool
	 */
	public function is_current_page_ancestor( $page_id ) {
		$return = false;
		if ( ! empty( $this->current_page->ID ) ) {
			if ( (int) $page_id === $this->current_page->ID ) {
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

		return apply_filters( 'advanced_sidebar_menu_page_ancestor', $return, $this->current_page->ID, $this );
	}


	/**
	 *
	 * @param \Advanced_Sidebar_Menu_Menus_Page $menu
	 *
	 * @static
	 *
	 * @return Advanced_Sidebar_Menu_List_Pages
	 */
	public static function factory( Advanced_Sidebar_Menu_Menus_Page $menu ) {
		return new self( $menu );
	}

}
