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
 * @author Mat Lipe <mat@matlipe.com>
 *
 * @since 5.0.0
 *
 */
class Advanced_Sidebar_Menu_List_Pages{

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
	 * current_page_id
	 *
	 * Holds id of current page. Separate from current_page because
	 * current_page could be empty if something custom going on
	 *
	 * @var int
	 */
	protected $current_page_id = 0;

	/**
	 * top_parent_id
	 *
	 * Id of current page unless filtered when whatever set during
	 * widgetcreation
	 *
	 * @var int
	 */
	public $top_parent_id;

	/**
	 * args
	 *
	 * Passed during construct given to walker and used for queries
	 *
	 * @var array
	 */
	private $args = array();


	/**
	 * level
	 *
	 * Level of grandchild pages we are on
	 *
	 * @var int
	 */
	private $level = 0;

	/**
	 * Used exclusively for caching
	 * Holds the value of the latest parent we
	 * retrieve children for
	 *
	 * @var int
	 */
	private $current_children_parent = 0;

	/**
	 * menu
	 *
	 * @var \Advanced_Sidebar_Menu_Menu
	 */
	protected $menu;


	/**
	 * Constructor
	 *
	 * Used in the view
	 *
	 * @param int                        $parent_id - $asm->top_id
	 * @param Advanced_Sidebar_Menu_Menu $asm
	 * @param WP_Post $current_page;
	 */
	public function __construct( $parent_id, \Advanced_Sidebar_Menu_Menu $asm, $current_page ){
		$this->menu = $asm;
		$this->top_parent_id = $parent_id;
		$this->current_page = $current_page;
		if( null !== $current_page ){
			$this->current_page_id = $current_page->ID;
		}

		$args = array(
			'post_type' => $asm->post_type,
			'orderby'   => $asm->order_by,
			'order'     => $asm->order,
			'exclude'   => $asm->exclude,
			'levels'    => $asm->levels,
		);

		$this->parse_args( $args );
		$this->hooks();

	}


	/**
	 * Hooks should only hook once
	 *
	 * @return void
	 */
	protected function hooks() {
		static $been_hooked;
		if( null === $been_hooked ){
			$been_hooked = true;
			add_filter( 'page_css_class', array( $this, 'add_list_item_classes' ), 2, 2 );
		}

	}


	/**
	 * Add the custom classes to the list items
	 *
	 *
	 * @param array         $classes
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	public function add_list_item_classes( $classes, \WP_Post $post ) {
		if( $post->ID === $this->top_parent_id ){
			$children = $this->get_child_pages( $post->ID, true );
		} else {
			$children = $this->get_child_pages( $post->ID );
		}
		if( !empty( $children ) ){
			$classes[] = 'has_children';
		}

		//below is only for custom post types
		if( $this->current_page->post_type !== 'page' ){
			if( isset( $post->ancestors ) && in_array( $this->current_page->ID, (array) $post->ancestors, true ) ){
				$classes[] = 'current_page_ancestor';
			} elseif( $this->current_page->ID === $post->post_parent ) {
				$classes[] = 'current_page_parent';
			}
		}

		return $classes;
	}


	/**
	 * Return the list of args that have been populated by this class
	 *
	 * @return array
	 */
	public function get_args(){
		return $this->args;
	}
	

	/**
	 * __toString
	 *
	 * Magic method to allow using a simple echo to get output
	 *
	 * @return string
	 */
	public function __toString(){
		return $this->output;
	}



	/**
	 *
	 * Do any adjustments to class args here
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	private function parse_args( $args ){
		$defaults = array(
			'depth'        => 1,
			'exclude'      => '',
			'echo'         => 0,
			'sort_order'   => 'ASC',
			'sort_column'  => 'menu_order, post_title',
			'walker'       => new Advanced_Sidebar_Menu_Page_Walker(),
			'hierarchical' => 0,
			'link_before'  => '',
			'link_after'   => '',
			'title_li'     => '',
			'levels'       => 100,
			'item_spacing' => 'preserve',
		);

		$args = wp_parse_args( $args, $defaults );

		// sanitize, mostly to keep spaces out
		if( is_string( $args[ 'exclude' ] ) ){
			$args[ 'exclude' ]  = explode( ',', $args[ 'exclude' ] );
		}
		$args[ 'exclude' ] = preg_replace( '/[^0-9,]/', '', implode( ',', apply_filters( 'wp_list_pages_excludes', $args[ 'exclude' ] ) ) );

		$this->args = apply_filters( 'advanced_sidebar_menu_list_pages_args', $args, $this );

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

		foreach( $pages as $page ){

			$this->output .= walk_page_tree( array( $page ), 1, $this->current_page_id, $this->args );

				$this->output .= $this->list_grandchild_pages( $page->ID );

			$this->output .= '</li>' . "\n";

		}

		$this->output = apply_filters( 'wp_list_pages', $this->output, $this->args );

		if( !$this->args[ 'echo' ] ){
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
	private function list_grandchild_pages( $parent_page_id ){
		if( !$this->current_page_ancestor( $parent_page_id ) ){
			return '';
		}

		if( !$pages = $this->get_child_pages( $parent_page_id ) ){
			return '';
		}

		if( $this->level === (int)$this->args[ 'levels' ] ){
			return '';
		}

		$this->level++;

		$content = sprintf( '<ul class="grandchild-sidebar-menu level-%s children">', $this->level );
		$inside = '';

		foreach( $pages as $page ){
			$inside .= walk_page_tree( array( $page ), 1, $this->current_page_id, $this->args );
				$inside .= $this->list_grandchild_pages( $page->ID );
			$inside .= "</li>\n";

		}

		if( '' === $inside ){
			return '';
		}


		return $content . $inside  . "</ul>\n";
	}


	/**
	 * page_children
	 *
	 * Retrieve the child pages of specific page_id
	 *
	 * @param int  $parent_page_id
	 * @param bool $is_first_level
	 *
	 * @return array
	 */
	public function get_child_pages( $parent_page_id, $is_first_level = false ) {
		$this->current_children_parent = $parent_page_id;

		$cache = Advanced_Sidebar_Menu_Cache::get_instance();
		$child_pages = $cache->get_child_pages( $this );
		if( $child_pages === false ){
			$args = $this->args;
			$args[ 'post_parent' ] = $parent_page_id;
			$args[ 'fields' ] = 'ids';

			$child_pages = get_posts( $args );
			$cache->add_child_pages( $this, $child_pages );
		}

		$child_pages = array_map( 'get_post', (array)$child_pages );

		//we only filter the first level with this filter for backward pro compatibility
		if( $is_first_level ){
			$child_pages = apply_filters( 'advanced_sidebar_menu_child_pages', $child_pages, $this->current_page, $this->menu->instance, $this->menu->args, $this->menu );
		}

		return $child_pages;

	}


	/**
	 * current_page_ancestor
	 *
	 * Is the current page and ancestor of the specified page?
	 *
	 * @param $page_id
	 *
	 * @return bool
	 */
	private function current_page_ancestor( $page_id ) {
		$return = false;
		if( !empty( $this->current_page_id ) ){
			if( (int)$page_id === $this->current_page_id ){
				$return = true;
			} elseif( $this->current_page->post_parent === (int)$page_id ) {
				$return = true;
			} elseif( !empty( $this->current_page->ancestors ) && in_array( (int)$page_id, $this->current_page->ancestors, true ) ) {
				$return = true;
			}
		}

		$return = apply_filters(
			'advanced_sidebar_menu_page_ancestor',
			$return,
			$this->current_page_id,
			$this
		);

		return $return;
	}


	/**
	 *
	 * @param \Advanced_Sidebar_Menu_Menu $menu
	 * @param \WP_Post|null $current_page;
	 *
	 * @static
	 *
	 * @return \Advanced_Sidebar_Menu_List_Pages
	 */
	public static function factory( Advanced_Sidebar_Menu_Menu $menu, $current_page = null ){
		if( null === $current_page ){
			if ( is_page() || is_singular() ) {
				$current_page = get_queried_object();
			}
		}
		return new self( $menu->top_id, $menu, $current_page );
	}

}