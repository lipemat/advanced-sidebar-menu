<?php

namespace Advanced_Sidebar_Menu\Walkers;

use Advanced_Sidebar_Menu\Menus\Category;

/**
 * Extend the default WP Category Walker.
 *
 * @since  8.4.0
 */
class Category_Walker extends \Walker_Category {
	/**
	 * Starts the list before the elements are added.
	 *
	 * Extended to include the `data-level`, otherwise default functionality.
	 *
	 * @param string $output Used to append additional content. Passed by reference.
	 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
	 * @param array  $args   Optional. An array of arguments. Will only append content if style argument.
	 *                       value is 'list'. See wp_list_categories(). Default empty array.
	 *
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = [] ) {
		if ( 'list' !== $args['style'] ) {
			return;
		}

		// Add 1 level to the depth when coming from the grandparent level.
		if ( ! empty( $args['level'] ) && Category::LEVEL_GRANDCHILD === $args['level'] ) {
			++ $depth;
		}

		$indent = str_repeat( "\t", $depth );
		$level = $depth + 2;
		$output .= "$indent<ul class='children' data-level='{$level}'>\n";
	}
}
