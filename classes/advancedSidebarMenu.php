<?php
/**
 * advancedSidebarMenu
 *
 * These Functions are Specific to the Advanced Sidebar Menu
 *
 * @author  Mat Lipe <mat@matlipe.com>
 *
 * @package Advanced Sidebar Menu
 */
class advancedSidebarMenu extends Advanced_Sidebar_Menu_Deprecated {

	var $instance; //The widget instance
	var $top_id; //Either the top cat or page
	var $exclude;
	var $ancestors; //For the category ancestors
	var $count = 1; //Count for grandchild levels
	var $order_by;
	var $taxonomy; //For filters to override the taxonomy
	var $current_term; //Current category or taxonomy

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
	 * Check is a post has children by id
	 *
	 * @since 8.29.13
	 *
	 * @param int $postId
	 *
	 * @return bool
	 */
	function hasChildren( $postId ){
		$args = array(
			'post_parent' => $postId,
			'fields' => 'ids',
			'post_type' => get_post_type( $postId ),
			'post_status' => 'publish'
		);

		$children  = get_children( $args );

		if( count( $children ) != 0 ){
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Checks if a widgets checkbox is checked.
	 * * this one is special and does a double check
	 *
	 * @since 4.1.3
	 *
	 * @param string $name - name of checkbox
	 */
	function checked( $name ) {
		if( isset( $this->instance[ $name ] ) && $this->instance[ $name ] == 'checked' ){
			return true;
		}

		return false;

	}


	/**
	 * Used by a uasort to sort taxonomy arrays by term order
	 *
	 * @since 4.3.0
	 */
	function sortTerms( $a, $b ) {
		if( !isset( $a->{$this->order_by} ) || !isset( $b->{$this->order_by} ) ){
			return 0;
		}

		return $a->{$this->order_by} > $b->{$this->order_by};

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
	 * @param string $item - an <li></li> item
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
	 * Adds the class for any menu item with children
	 *
	 * @param array $css  the currrent css classes
	 * @param obj   $page the page being checked
	 *
	 *
	 * @since 8.29.13
	 *
	 * @return array
	 */
	function hasChildrenClass( $css, $page ) {
		if( $this->hasChildren( $page->ID ) ){
			$css[ ] = 'has_children';
		}

		return $css;

	}


	/**
	 * Adds the class for current page item etc to the page list when using a custom post type
	 *
	 * @param array $css            the currrent css classes
	 * @param obj   $this_menu_item the page being checked
	 *
	 * @return array
	 * @since 10.10.12
	 */
	function custom_post_type_css( $css, $this_menu_item ) {
		global $post;
		if( isset( $post->ancestors ) && in_array( $this_menu_item->ID, (array)$post->ancestors ) ){
			$css[ ] = 'current_page_ancestor';
		}
		if( $this_menu_item->ID == $post->ID ){
			$css[ ] = 'current_page_item';

		} elseif( $this_menu_item->ID == $post->post_parent ) {
			$css[ ] = 'current_page_parent';
		}

		return $css;
	}


	/**
	 *
	 * IF this is a top level category
	 *
	 * @param obj $cat the cat object
	 *
	 * @since 6.13.13
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
	 * @param obj $cat the cat
	 *
	 * @since 6.13.13
	 */
	function second_level_cat( $cat ) {

		//if this is the currrent cat or a parent of the current cat
		if( $cat->term_id == $this->current_term || in_array( $cat->term_id, $this->ancestors ) ){

			$all_children = array();
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

			if( $this->checked( 'legacy_mode' ) ){
				echo '<h4 class="widgettitle">' . $title . '</h4>';
			} else {
				echo $this->args[ 'before_title' ] . $title . $this->args[ 'after_title' ];
			}
		}

	}


	/**
	 *
	 * Checks is this id is excluded or not
	 *
	 * @param int $id the id to check
	 *
	 * @return bool
	 */
	function exclude( $id ) {
		if( !in_array( $id, $this->exclude ) ){
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Allows for Overwritting files in the child theme
	 *
	 * @since 4.23.13
	 *
	 * @param string $file the name of the file to overwrite
	 */

	static function file_hyercy( $file, $legacy = false ) {
		if( $theme_file = locate_template( array( 'advanced-sidebar-menu/' . $file ) ) ){
			$file = $theme_file;
		} elseif( $legacy ) {
			$file = ADVANCED_SIDEBAR_LEGACY_DIR . $file;
		} else {
			$file = ADVANCED_SIDEBAR_VIEWS_DIR . $file;
		}

		return apply_filters( 'advanced_sidebar_menu_view_file', $file, $legacy );

	}

} //End class

