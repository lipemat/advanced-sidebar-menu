<?php


/**
 * Advanced_Sidebar_Menu_Menus_Category
 *
 * @author Mat Lipe
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Menus_Category extends Advanced_Sidebar_Menu_Menus_Abstract {
	/**
	 * ancestors
	 *
	 * @var array
	 */
	public $ancestors = array();

	/**
	 * taxonomy
	 *
	 * @var string
	 */
	public $taxonomy = 'category';

	/**
	 * current_term
	 *
	 * @var WP_Term
	 */
	public $current_term;



	/**
	 * Removes the closing </li> tag from a list item to allow for child menus inside of it
	 *
	 * @param string|bool $item - an <li></li> item
	 *
	 * @return string|bool
	 */
	public function openListItem( $item = false ) {
		if( !$item ){
			return false;
		}

		return substr( trim( $item ), 0, -5 );
	}

	/**
	 * Retrieve the lights level term_id based on the a given
	 * term's ancestors
	 *
	 * @param int $term_id
	 *
	 * @return int
	 */
	public function get_highest_parent( $term_id ) {
		$cat_ancestors    = array();
		$cat_ancestors[] = $term_id;

		do {
			$term            = get_term( $term_id, $this->taxonomy );
			if( !is_wp_error( $term ) ){
				$term_id = $term->parent;
				$cat_ancestors[] = $term_id;
			} else {
				$term = false;
			}
		} while( $term );

		//we only track the last calls ancestors because we only care
		//about these when on a single term archive
		$this->ancestors = array_reverse( $cat_ancestors );
		list( $_, $top_cat ) = $this->ancestors;
		return $top_cat;

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
	 * IF this is a top level category
	 *
	 * @param \WP_Term $term
	 *
	 * @return bool
	 */
	public function first_level_category( WP_Term $term ) {
		$return = false;
		if( (int)$term->parent === (int)$this->top_id && !$this->is_excluded( $term->term_id)  ){
			$return = true;
		}

		return apply_filters( 'advanced_sidebar_menu_first_level_category', $return, $term, $this );
	}


	/**
	 * If the term is a second level term
	 *
	 * @param \WP_Term $term
	 *
	 * @return bool
	 */
	public function second_level_cat( WP_Term $term ) {
		$return = false;
		//if this is the current term or a parent of the current term
		if( (int)$term->term_id === (int)$this->current_term || in_array( $term->term_id, $this->ancestors, false ) ){
			$all_children = get_terms( $this->taxonomy, array( 'child_of' => $term->term_id, 'fields' => 'ids' ) );
			if( !empty( $all_children ) ){
				$return = true;
			}
		}

		return apply_filters( 'advanced_sidebar_menu_second_level_category', $return, $term, $this );

	}


}