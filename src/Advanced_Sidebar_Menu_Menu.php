<?php
/**
 * Advanced_Sidebar_Menu_Menu
 *
 * These Functions are Specific to the Advanced Sidebar Menu
 *
 * @author  Mat Lipe <mat@matlipe.com>
 *
 * @package Advanced Sidebar Menu
 */
class Advanced_Sidebar_Menu_Menu {

	public $instance; //The widget instance
	public $top_id; //Either the top cat or page
	public $exclude = array();
	public $ancestors; //For the category ancestors
	public $count = 1; //Count for grandchild levels
	public $order_by = null;
	public $order = 'ASC';
	public $taxonomy; //For filters to override the taxonomy
	public $current_term; //Current category or taxonomy

	/**
	 * args
	 *
	 * Widget Args
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * post_type
	 *
	 * @var string
	 */
	public $post_type = 'page';

	public $levels = 100;


	/**
	 * The instance arguments from the current widget
	 *
	 * @return []
	 */
	public function get_widget_instance(){
		return $this->instance;
	}


	/**
	 * Check is a post has children
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	function has_children( $post_id ){
		$args = array(
			'post_parent' => $post_id,
			'fields'      => 'ids',
			'post_type'   => get_post_type( $post_id ),
			'post_status' => 'publish',
			'numberposts' => 1,
		);

		$children = get_children( $args );

		return !empty( $children );
	}


	/**
	 * Checks if a widgets checkbox is checked.
	 * * this one is special and does a double check
	 *
	 * @since 4.1.3
	 *
	 * @param string $name - name of checkbox
	 *
	 * @return bool
	 */
	function checked( $name ) {
		if( isset( $this->instance[ $name ] ) && $this->instance[ $name ] == 'checked' ){
			return true;
		}

		return false;

	}


	/**
	 * Retrieves the Highest level Category Id
	 *
	 * @since 6.6.13
	 *
	 * @param int $catId - id of cat looking for top parent of
	 *
	 * @return int
	 */
	function getTopCat( $catId ) {
		$cat_ancestors    = array();
		$cat_ancestors[ ] = $catId;

		do {
			$catId            = get_term( $catId, $this->taxonomy );
			$catId            = $catId->parent;
			$cat_ancestors[ ] = $catId;
		} while( $catId );

		//Reverse the array to start at the last
		$this->ancestors = array_reverse( $cat_ancestors );

		//forget the [0] because the parent of top parent is always 0
		return $this->ancestors[ 1 ];

	}


	/**
	 * Removes the closing </li> tag from a list item to allow for child menus inside of it
	 *
	 * @param string|bool $item - an <li></li> item
	 *
	 * @return string|bool
	 * @since 4.7.13
	 */
	function openListItem( $item = false ) {
		if( !$item ){
			return false;
		}

		return substr( trim( $item ), 0, -5 );
	}


	/**
	 * If a category has children add the has_children class
	 *
	 * @param [] $classes
	 * @param \WP_Term $category
	 *
	 * @return array
	 */
	public function add_has_children_category_class( $classes, $category ) {
		$children = get_terms( $category->taxonomy, array(
			'parent'     => $category->term_id,
			'hide_empty' => false,
			'number'     => 1,
		) );
		if( !empty( $children ) ){
			$classes[] = 'has_children';
		}

		return $classes;
	}


	/**
	 * Adds the class for any menu item with children
	 *
	 * @param array $classes  the current css classes
	 * @param \WP_Post $page the page being checked
	 *
	 * @return array
	 */
	public function add_has_children_class( $classes, $page ) {
		if( $this->has_children( $page->ID ) ){
			$classes[] = 'has_children';
		}

		return $classes;
	}


	/**
	 * Adds the class for current page item etc to the page list when using a custom post type
	 *
	 * @param array    $classes        the current css classes
	 * @param \WP_Post $this_menu_item the page being checked
	 *
	 * @return array
	 */
	function custom_post_type_css( $classes, $this_menu_item ) {
		global $post;
		if( isset( $post->ancestors ) && in_array( $this_menu_item->ID, (array)$post->ancestors ) ){
			$classes[ ] = 'current_page_ancestor';
		}
		if( $this_menu_item->ID == $post->ID ){
			$classes[ ] = 'current_page_item';

		} elseif( $this_menu_item->ID == $post->post_parent ) {
			$classes[ ] = 'current_page_parent';
		}

		if( $this->has_children( $this_menu_item->ID ) ){
			$classes[] = 'has_children';
		}

		return $classes;
	}


	/**
	 *
	 * IF this is a top level category
	 *
	 * @param \WP_Term $cat the cat object
	 *
	 * @return bool
	 */
	function first_level_category( $cat ) {

		if( !in_array( $cat->term_id, $this->exclude ) && $cat->parent == $this->top_id ){
			$return = true;
		} else {
			$return = false;
		}

		return apply_filters( 'advanced_sidebar_menu_first_level_category', $return, $cat, $this );

	}


	/**
	 * If the cat is a second level cat
	 *
	 * @param \WP_Term $cat the cat
	 *
	 * @return bool
	 */
	function second_level_cat( $cat ) {

		//if this is the current cat or a parent of the current cat
		if( $cat->term_id == $this->current_term || in_array( $cat->term_id, $this->ancestors ) ){
			$all_children = get_terms( $this->taxonomy, array( 'child_of' => $cat->term_id, 'fields' => 'ids' ) );
			if( !empty( $all_children ) ){
				$return = true;
			} else {
				$return = false;
			}

		} else {
			$return = false;
		}

		return apply_filters( 'advanced_sidebar_menu_second_level_category', $return, $cat, $this );


	}


	/**
	 * Determines if all the children should be included
	 *
	 * @since 6.26.13
	 * @return bool
	 */
	function display_all() {
		return $this->checked( 'display_all' );
	}


	/**
	 * Determines if the parent page or cat should be included
	 *
	 * @since 6.26.13
	 * @return bool
	 */
	function include_parent() {
		if( !$this->checked( 'include_parent' ) ){
			return false;
		}

		if( ( !in_array( $this->top_id, $this->exclude ) ) ){
			return true;
		}

		return false;
	}


	/**
	 * Echos the title of the widget to the page
	 *
	 * @since 6.26.13
	 */
	function title() {
		if( isset( $this->instance[ 'title' ] ) && ( $this->instance[ 'title' ] != '' ) ){
			$title = apply_filters( 'widget_title', $this->instance[ 'title' ], $this->args, $this->instance );
			$title = apply_filters( 'advanced_sidebar_menu_widget_title', $title, $this->args, $this->instance, $this );

			echo $this->args[ 'before_title' ] . $title . $this->args[ 'after_title' ];
		}

	}


	/**
	 * Retrieve the excluded items' ids
	 *
	 * @return array
	 */
	public function get_excluded_ids(){
		$excluded = $this->exclude;
		$excluded = array_filter( $excluded );
		$excluded = array_map( 'intval', $excluded );
		return $excluded;
	}


	/**
	 *
	 * Checks is this id is excluded or not
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function is_excluded( $id ) {
		if( !in_array( $id, $this->exclude ) ){
			return true;
		} else {
			return false;
		}
	}

	/*** static ***********/
	/**
	 * current
	 *
	 * @static
	 * @var Advanced_Sidebar_Menu_Menu
	 */
	private static $current;


	public static function factory( array $instance, array $args ){
		self::$current = new self();
		self::$current->instance = $instance;
		self::$current->args = $args;

		return self::$current;
	}


	public static function get_current(){
		return self::$current;
	}
}
