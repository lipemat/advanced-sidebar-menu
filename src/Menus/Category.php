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

	const DISPLAY_ON_SINGLE = 'single';
	const EACH_CATEGORY_DISPLAY = 'new_widget';

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
	 * top_level_term
	 *
	 * @var WP_Term
	 */
	public $top_level_term;


	/**
	 * @todo find a more appropriate place for this
	 *
	 * @return void
	 */
	public function hook() {
		add_filter( 'category_css_class', array( $this, 'add_has_children_category_class' ), 2, 2 );
	}


	/**
	 * If we are on a post we could potentially have more than one
	 * top level term so we end up calling this more than once.
	 *
	 *
	 * @param WP_Term $term
	 *
	 * @return void
	 */
	public function set_current_top_level_term( WP_Term $term ) {
		$this->top_id         = $term->term_id;
		$this->top_level_term = $term;
	}


	/**
	 * Return the list of args for wp_list_categories()
	 *
	 * @param string  $level - level of menu so we have full control of updates
	 * @param WP_Term $term  - Term for child and grandchild
	 *
	 * @return array
	 */
	public function get_list_categories_args( $level = null, $term = null ) {
		$args = array(
			'echo'             => 0,
			'exclude'          => $this->get_excluded_ids(),
			'order'            => $this->get_order(),
			'orderby'          => $this->get_order_by(),
			'show_option_none' => false,
			'taxonomy'         => $this->get_taxonomy(),
			'title_li'         => '',
		);

		if ( null === $level ) {
			return $args;
		}
		switch ( $level ) {
			case 'parent':
				$args['hide_empty'] = 0;
				$args['include']    = trim( $this->get_top_parent_id() );
				break;
			case 'display-all':
				$args['child_of'] = $this->get_top_parent_id();
				$args['depth']    = $this->get_levels_to_display();
				break;
			case 'child':
				$args['include'] = $term->term_id;
				$args['depth']   = 1;
				break;
			case 'grandchild':
				$args['child_of'] = $term->term_id;
				$args['depth']    = $this->get_menu_depth();
				break;
		}

		return apply_filters( 'advanced-sidebar-menu/menus/category/get-list-categories-args', $args, $level, $this );
	}


	/**
	 * Get the first level child terms.
	 *
	 * $this->set_current_top_level_term() most likely should be called
	 * before this.
	 *
	 * @see Advanced_Sidebar_Menu_Menus_Category::set_current_top_level_term()
	 *
	 *
	 * @return array
	 */
	public function get_child_terms() {
		$child_terms = array_filter(
			get_terms(
				array(
					'taxonomy' => $this->get_taxonomy(),
					'parent'   => $this->get_top_parent_id(),
					'orderby'  => $this->get_order_by(),
					'order'    => $this->get_order(),
				)
			)
		);

		return $child_terms;
	}


	/**
	 * Gets the number of levels ot display when doing 'Alwasy display'
	 *
	 * @return int
	 */
	public function get_levels_to_display() {
		return apply_filters( 'advanced-sidebar-menu/menus/category/levels', $this->instance[ self::LEVELS ], $this->args, $this->instance, $this );
	}


	/**
	 * Gets the number of levels to display when not doing 'Always display'
	 *
	 * @return int
	 */
	public function get_menu_depth() {
		return apply_filters( 'advanced-sidebar-menu/menus/category/depth', 3, $this->args, $this->instance, $this );
	}


	/**
	 * Get the top level terms for current page
	 * If on a single this could be multiple.
	 * If on an archive this will be one.
	 *
	 * @return array
	 */
	public function get_top_level_terms() {
		$child_term_ids     = $this->get_included_term_ids();
		$top_level_term_ids = array();
		foreach ( $child_term_ids as $_term_id ) {
			$top_level_term_ids[] = $this->get_highest_parent( $_term_id );
		}
		$terms = array();
		if ( ! empty( $top_level_term_ids ) ) {
			$terms = get_terms( array(
				'include'    => array_unique( array_filter( $top_level_term_ids ) ),
				'hide_empty' => false,
				'orderby'    => $this->get_order_by(),
				'order'      => $this->get_order(),
			) );
		}
		if ( is_wp_error( $terms ) ) {
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
		if ( is_single() ) {
			$term_ids = wp_get_object_terms( get_the_ID(), $this->get_taxonomy(), array( 'fields' => 'ids' ) );
		} elseif ( $this->is_tax() ) {
			$term_ids[] = get_queried_object()->term_id;
		}

		return (array) apply_filters( 'advanced_sidebar_menu_category_ids', $term_ids, $this->args, $this->instance, $this );
	}


	public function get_taxonomy() {
		$this->taxonomy = apply_filters( 'advanced_sidebar_menu_taxonomy', 'category', $this->args, $this->instance, $this );

		return $this->taxonomy;
	}


	public function get_top_parent_id() {
		if ( empty( $this->top_level_term->term_id ) ) {
			return null;
		}

		return $this->top_level_term->term_id;
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
		if ( is_single() ) {
			if ( $this->checked( self::DISPLAY_ON_SINGLE ) ) {
				$display = true;
			}
			if ( has_filter( 'advanced_sidebar_menu_proper_single' ) ) {
				_deprecated_hook( 'advanced_sidebar_menu_proper_single', '7.0.0', 'advanced-sidebar-menu/menus/category/is-displayed' );
				$display = ! apply_filters( 'advanced_sidebar_menu_proper_single', ! $display, $this->args, $this->instance, $this );
			}
		} elseif ( $this->is_tax() ) {
			$display = true;
		}

		return apply_filters( 'advanced-sidebar-menu/menus/category/is-displayed', $display, $this->args, $this->instance, $this );
	}


	/**
	 * Is this term and it's children displayed
	 *
	 * 1. If children not empty we always display (or at least let the view handle it)
	 * 2. If children empty and not include parent we don't display
	 * 3. If children empty and not include childless parent we don't display
	 * 4. If children empty and the top parent is excluded we don't display
	 *
	 *
	 * @param array $child_terms
	 *
	 * @return bool
	 */
	public function is_term_displayed( array $child_terms ) {
		if ( empty( $child_terms ) ) {
			if ( ! $this->checked( self::INCLUDE_PARENT ) || ! $this->checked( self::INCLUDE_CHILDLESS_PARENT ) ) {
				return false;
			}
			if ( $this->is_excluded( $this->get_top_parent_id() ) ) {
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
		if ( 'category' === $taxonomy ) {
			if ( is_category() ) {
				return true;
			}
		} elseif ( is_tax( $taxonomy ) ) {
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
		if ( ! $item ) {
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
		$cat_ancestors   = array();
		$cat_ancestors[] = $term_id;

		do {
			$term = get_term( $term_id, $this->get_taxonomy() );
			if ( ! is_wp_error( $term ) ) {
				$term_id         = $term->parent;
				$cat_ancestors[] = $term_id;
			} else {
				$term = false;
			}
		} while ( $term );

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
		if ( ! empty( $children ) ) {
			$classes[] = 'has_children';
		}

		return array_unique( $classes );
	}


	/**
	 * 1. Is this term's parent our top_parent_id?
	 * 2. Is this term not excluded?
	 * 3. Does the filter allow this term?
	 *
	 * When looping through the view via the terms from $this->get_child_terms()
	 * the term->parent conditions will most likely always be true.
	 *
	 * @param \WP_Term $term
	 *
	 * @return bool
	 */
	public function is_first_level_term( WP_Term $term ) {
		$return = false;
		if ( ! $this->is_excluded( $term->term_id ) && (int) $term->parent === (int) $this->get_top_parent_id() ) {
			$return = true;
		}

		return apply_filters( 'advanced_sidebar_menu_first_level_category', $return, $term, $this );
	}


	/**
	 * Is this term an ancestor of the current term?
	 * Does this term have children?
	 *
	 * @param \WP_Term $term
	 *
	 * @return mixed
	 */
	public function is_current_term_ancestor( WP_Term $term ) {
		$return = false;
		if ( (int) $term->term_id === (int) $this->top_level_term->term_id || in_array( $term->term_id, $this->ancestors, false ) ) {
			$all_children = get_terms( $this->get_taxonomy(), array(
				'child_of' => $term->term_id,
				'fields'   => 'ids',
			) );
			if ( ! empty( $all_children ) ) {
				$return = true;
			}
		}

		if ( has_filter( 'advanced_sidebar_menu_second_level_category' ) ) {
			_deprecated_hook( 'advanced_sidebar_menu_second_level_category', '7.0.0', 'advanced-sidebar-menu/menus/category/is-current-term-ancestor' );
			$return = apply_filters( 'advanced_sidebar_menu_second_level_category', $return, $term, $this );
		}

		return apply_filters( 'advanced-sidebar-menu/menus/category/is-current-term-ancestor', $return, $term, $this );

	}


	/**
	 * Does this term have children?
	 *
	 * @param \WP_Term $term
	 *
	 * @return mixed
	 */
	public function has_children( WP_Term $term ) {
		$return   = false;
		$children = get_term_children( $term->term_id, $this->get_taxonomy() );
		if ( ! empty( $children ) ) {
			$return = true;
		}

		return apply_filters( 'advanced-sidebar-menu/menus/category/has-children', $return, $term, $this );
	}


	/**
	 * Render the widget output
	 *
	 * @return void
	 */
	public function render() {
		if ( ! $this->is_displayed() ) {
			return;
		}

		$menu_open  = false;
		$close_menu = false;

		foreach ( $this->get_top_level_terms() as $_cat ) {
			$this->set_current_top_level_term( $_cat );
			//@deprecated 7.0.0 variable name
			$all_categories = $this->get_child_terms();
			if ( ! $this->is_term_displayed( $all_categories ) ) {
				continue;
			}

			if ( ! $menu_open || ( 'widget' === $this->instance[ self::EACH_CATEGORY_DISPLAY ] ) ) {
				//Start the menu
				echo $this->args['before_widget'];

				do_action( 'advanced-sidebar-menu/menus/category/render', $this );

				if ( ! $menu_open ) {
					//must remain in the loop vs the template
					$this->title();
					if ( $this->checked( self::USE_PLUGIN_STYLES ) ) {
						Advanced_Sidebar_Menu_Core::instance()->include_plugin_styles();
					}

					$menu_open  = true;
					$close_menu = true;
					if ( 'list' === $this->instance[ self::EACH_CATEGORY_DISPLAY ] ) {
						$close_menu = false;
					}
				}
			}

			$output = require Advanced_Sidebar_Menu_Core::instance()->get_template_part( 'category_list.php' );

			echo apply_filters( 'advanced_sidebar_menu_category_widget_output', $output, $this->args, $this->instance, $this );

			if ( $close_menu ) {
				echo $this->args['after_widget'];
			}
		}

		if ( ! $close_menu && $menu_open ) {
			echo $this->args['after_widget'];
		}

	}


	/**
	 * @deprecated
	 *
	 */
	public function first_level_category( WP_Term $term ) {
		_deprecated_function( 'Advanced_Sidebar_Menu_Menus_Category::first_level_category', '7.0.0', 'Advanced_Sidebar_Menu_Menus_Category::is_first_level_term' );

		return $this->is_first_level_term( $term );
	}


	/**
	 * @deprecated
	 */
	public function second_level_cat( WP_Term $term ) {
		_deprecated_function( 'Advanced_Sidebar_Menu_Menus_Category::second_level_cat', '7.0.0', 'Advanced_Sidebar_Menu_Menus_Category::is_current_term_ancestor' );

		$return = ( $this->is_current_term_ancestor( $term ) && $this->has_children( $term ) );

		return apply_filters( 'advanced_sidebar_menu_second_level_category', $return, $term, $this );

	}

	/******************** static ****************************/

	/**
	 * Mostly here to call the parent _factory in a PHP 5.2 structure
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
