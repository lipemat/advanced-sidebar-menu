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
	private $current_page = NULL;

	/**
	 * current_page_id
	 *
	 * Holds id of current page. Separate from current_page because
	 * current_page could be empty if something custom going on
	 *
	 * @var int
	 */
	private $current_page_id = 0;

	/**
	 * top_parent_id
	 *
	 * Id of current page unless filtered when whatever set during
	 * widgetcreation
	 *
	 * @var int
	 */
	private $top_parent_id = 0;

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
	 * Constructor
	 *
	 * Used in the view
	 *
	 * @param int                        $parent_id - $asm->top_id
	 * @param Advanced_Sidebar_Menu_Menu $asm
	 */
	public function __construct( $parent_id, $asm ){

		$this->top_parent_id = $parent_id;

		$args = array(
			'post_type'   => $asm->post_type,
			'sort_column' => $asm->order_by,
			'sort_order'  => $asm->order,
			'exclude'     => $asm->exclude,
			'levels'      => $asm->levels,
		);

		$this->parse_args( $args );

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

		if ( is_page() || is_singular() ) {
			$this->current_page = get_queried_object();
			$this->current_page_id = $this->current_page->ID;
		}

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

		$pages = $this->get_child_pages( $this->top_parent_id );

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

		if( $this->level == $this->args[ 'levels' ] ){
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

		if( '' == $inside ){
			return '';
		}


		return $content . $inside  . "</ul>\n";
	}


	/**
	 * page_children
	 *
	 * Retrieve the child pages of specific page_id
	 *
	 * @param $parent_page_id
	 *
	 * @return array
	 */
	public function get_child_pages( $parent_page_id ) {
		$this->current_children_parent = $parent_page_id;

		$cache = Advanced_Sidebar_Menu_Cache::get_instance();
		$child_pages = $cache->get_child_pages( $this );
		if( $child_pages === false ){
			$args = $this->args;
			$args[ 'parent' ] = $this->current_children_parent;
			$child_pages = get_pages( $args );

			$cache->add_child_pages( $this, $child_pages );
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
			if( $page_id == $this->current_page_id ){
				$return = true;
			} elseif( $this->current_page->post_parent == $page_id ) {
				$return = true;
			} else {
				if( !empty( $this->current_page->ancestors ) ){
					if( in_array( $page_id, $this->current_page->ancestors ) ){
						$return = true;
					}
				}
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
	 *
	 * @static
	 *
	 * @return \Advanced_Sidebar_Menu_List_Pages
	 */
	public static function factory( Advanced_Sidebar_Menu_Menu $menu ){
		return new self( $menu->top_id, $menu );
	}

}