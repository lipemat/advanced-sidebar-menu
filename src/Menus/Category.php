<?php


/**
 * Advanced_Sidebar_Menu_Menus_Category
 *
 * @author Mat Lipe
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Menus_Category extends Advanced_Sidebar_Menu_Menus_Abstract {
	const WIDGET = 'category';

	/**
	 * ancestors
	 *
	 * @var array
	 */
	public $ancestors = array();

	/**
	 * taxonomy
	 *
	 * @deprecated 7.0.0
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
	 * @todo find a more appropriate place for this
	 *
	 * @return void
	 */
	public function hook() {
		static $hooked;
		if( null === $hooked ){
			add_filter( 'category_css_class', array( $this, 'add_has_children_category_class' ), 2, 2 );
		}
		$hooked = true;
	}


	public function set_current_term( WP_Term $term ) {
		//$this->top_id is deprecated 7.0.0
		$this->top_id = $term->term_id;
		$this->current_term = $term;
	}


	public function get_child_terms() {
		$child_terms = array_filter(
			get_terms(
				$this->get_taxonomy(), array(
					'parent'  => $this->get_top_parent_id(),
					'orderby' => $this->get_order_by(),
					'order'   => $this->get_order(),
				)
			)
		);

		return (array) $child_terms;
	}


	public function get_levels_to_display() {
		return apply_filters( 'advanced-sidebar-menu/menus/category/levels', $this->levels, $this->args, $this->instance, $this );
	}


	/**
	 * Get the top level terms for current page
	 * If on a single this could be multiple.
	 * If on an archive this will be one.
	 *
	 * @return array
	 */
	public function get_top_level_terms() {
		$child_term_ids = $this->get_included_term_ids();
		$top_level_term_ids = array();
		foreach( $child_term_ids as $_term_id ){
			$top_level_term_ids[] = $this->get_highest_parent( $_term_id );
		}
		$terms = array();
		if( !empty( $top_level_term_ids ) ){
			$terms = get_terms( array(
				'include'    => array_unique( array_filter( $top_level_term_ids ) ),
				'hide_empty' => false,
				'orderby'    => $this->get_order_by(),
				'order'      => $this->get_order(),
			) );
		}
		if( is_wp_error( $terms ) ){
			return array();
		}

		return $terms;

	}


	/**
	 * Get the term ids for either the current term archive
	 * or the terms attached to the current post
	 *
	 * @return array
	 */
	public function get_included_term_ids() {
		$term_ids = array();
		if( is_single() ){
			$term_ids = wp_get_object_terms( get_the_ID(), $this->get_taxonomy(), array( 'fields' => 'ids' ) );
		} elseif( $this->is_tax() ) {
			$term_ids[] = get_queried_object()->term_id;
		}

		return (array) apply_filters( 'advanced_sidebar_menu_category_ids', $term_ids, $this->args, $this->instance, $this );
	}


	public function get_taxonomy() {
		$this->taxonomy = apply_filters( 'advanced_sidebar_menu_taxonomy', 'category', $this->args, $this->instance, $this );

		return $this->taxonomy;
	}


	public function get_top_parent_id() {
		return $this->current_term->term_id;
	}


	public function get_order_by() {
		$this->order_by = apply_filters( 'advanced_sidebar_menu_category_orderby', 'name', $this->args, $this->instance, $this );

		return $this->order_by;
	}


	public function get_order() {
		$this->order = apply_filters( 'advanced_sidebar_menu_category_order', 'ASC', $this->args, $this->instance, $this );

		return $this->order;
	}


	public function is_displayed() {
		$display = false;
		if( is_single() ){
			if( $this->checked( 'single' ) ){
				$display = true;
			}
			if( has_filter( 'advanced_sidebar_menu_proper_single' ) ){
				_deprecated_hook( 'advanced_sidebar_menu_proper_single', '7.0.0', 'advanced-sidebar-menu/menus/category/is-displayed' );
				$display = !apply_filters( 'advanced_sidebar_menu_proper_single', !$display, $this->args, $this->instance, $this );
			}
		} elseif( $this->is_tax() ){
		    $display = true;
		}

		return apply_filters( 'advanced-sidebar-menu/menus/category/is-displayed', $display, $this->args, $this->instance, $this );
	}


	public function is_section_displayed( array $child_terms ) {
		if( empty( $child_terms ) ){
			if( !$this->checked( 'include_parent' ) || !$this->checked( 'include_childless_parent' ) ){
				return false;
			}
			if( $this->is_excluded( $this->get_top_parent_id() ) ){
				return false;
			}
		}

		return true;
	}


	/**
	 * Simplified way to verify if we are on a taxonomy
     * archive
	 *
	 * @return bool
	 */
	protected function is_tax() {
		$taxonomy = $this->get_taxonomy();
		if( 'category' === $taxonomy ){
			if( is_category() ){
				return true;
			}
		} elseif( is_tax( $taxonomy ) ) {
			return true;
		}

		return false;
	}

	public function get_excluded_ids() {
		$excluded = parent::get_excluded_ids();

		return apply_filters( 'advanced_sidebar_menu_excluded_categories', $excluded, $this->args, $this->instance, $this );
	}


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

		return substr( trim( $item ), 0, - 5 );
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
		$cat_ancestors = array();
		$cat_ancestors[] = $term_id;

		do {
			$term = get_term( $term_id, $this->get_taxonomy() );
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
		if( (int) $term->parent === (int) $this->get_top_parent_id() && !$this->is_excluded( $term->term_id ) ){
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
		if( (int) $term->term_id === (int) $this->current_term || in_array( $term->term_id, $this->ancestors, false ) ){
			$all_children = get_terms( $this->get_taxonomy(), array(
				'child_of' => $term->term_id,
				'fields'   => 'ids',
			) );
			if( !empty( $all_children ) ){
				$return = true;
			}
		}

		return apply_filters( 'advanced_sidebar_menu_second_level_category', $return, $term, $this );

	}


	public function render() {
		if( !$this->is_displayed() ){
			return;
		}

		$menu_open = false;
		$close_menu = false;

		foreach( $this->get_top_level_terms() as $_cat ){
			$this->set_current_term( $_cat );
			//@deprecated 7.0.0 variable name
			$all_categories = $this->get_child_terms();
			if( !$this->is_section_displayed( $all_categories ) ){
				continue;
			}
			do_action( 'advanced-sidebar-menu/menus/category/render', $this );

			if( !$menu_open || ( $this->instance[ 'new_widget' ] === 'widget' ) ){
				//Start the menu
				echo $this->args[ 'before_widget' ];
				if( !$menu_open ){
					//must remain in the loop vs the template
					$this->title();
					if( $this->checked( 'css' ) ){
						?>
                        <style>
                            <?php include Advanced_Sidebar_Menu_Core::instance()->get_template_part( 'sidebar-menu.css', true ); ?>
                        </style>
						<?php
					}

					$menu_open = true;
					$close_menu = true;
					if( $this->instance[ 'new_widget' ] === 'list' ){
						$close_menu = false;
					}
				}
			}

			$output = require Advanced_Sidebar_Menu_Core::instance()->get_template_part( 'category_list.php' );

			echo apply_filters( 'advanced_sidebar_menu_category_widget_output', $output, $this->args, $this->instance, $this );

			if( $close_menu ){
				echo $this->args[ 'after_widget' ];
			}
		}

		if( !$close_menu && $menu_open ){
			echo $this->args[ 'after_widget' ];
		}

	}

	/******************** static ****************************/

	/**
	 * Mostly here to call the paretn _factory in a PHP 5.2 structure
	 *
	 * @param array $widget_instance
	 * @param array $widget_args
	 *
	 * @static
	 *
	 * @return \Advanced_Sidebar_Menu_Menus_Category
	 */
	public static function factory( array $widget_instance, array $widget_args ) {
		$class = parent::_factory( __CLASS__, $widget_instance, $widget_args );
		$class->hook();
		return $class;
	}

}