<?php


/**
 * Creates a Widget of parent Child Categories
 *
 * @author  mat lipe
 * @since   1.7.14
 * @package Advanced Sidebar Menu
 *
 *
 */
class advanced_sidebar_menu_category extends WP_Widget {

	private $defaults = array(
		'title'                    => '',
		'include_parent'           => false,
		'include_childless_parent' => false,
		'css'                      => false,
		'single'                   => false,
		'new_widget'               => 'widget',
		'exclude'                  => '',
		'display_all'              => false,
		'levels'                   => 1,
		'order'                    => 'DESC',
	);


	function __construct(){

		$widget_ops  = array(
			'classname'   => 'advanced-sidebar-menu advanced-sidebar-category',
			'description' => __( 'Creates a menu of all the categories using the child/parent relationship', 'advanced-sidebar-menu' ),
		);
		$control_ops = array( 'width' => 290 );

		parent::__construct( 'advanced_sidebar_menu_category', __( 'Advanced Sidebar Categories Menu', 'advanced-sidebar-menu' ), $widget_ops, $control_ops );
	}


	/**
	 * Creates a form for the Widget Options
	 *
	 * @since   1.7.14
	 *
	 * @param array $instance
	 *
	 * @actions do_action('advanced_sidebar_menu_category_widget_form', $instance );
	 */
	function form( $instance ){

		$instance = wp_parse_args( $instance, $this->defaults );

		?>
		<p> <?php _e( 'Title', 'advanced-sidebar-menu' ); ?>
			<br>
			<input id="<?php echo $this->get_field_name( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" type="text" value="<?php echo $instance[ 'title' ]; ?>"/>
		</p>


		<p> <?php _e( 'Include Parent Category', 'advanced-sidebar-menu' ); ?>
			<input id="<?php echo $this->get_field_name( 'include_parent' ); ?>"
				name="<?php echo $this->get_field_name( 'include_parent' ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ 'include_parent' ]; ?>/>
		</p>


		<p> <?php _e( 'Include Parent Even With No Children', 'advanced-sidebar-menu' ); ?>
			<input id="<?php echo $this->get_field_name( 'include_childless_parent' ); ?>"
				name="<?php echo $this->get_field_name( 'include_childless_parent' ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ 'include_childless_parent' ]; ?>/>
		</p>

		<p> <?php _e( 'Use this plugins styling', 'advanced-sidebar-menu' ); ?>
			<input id="<?php echo $this->get_field_name( 'css' ); ?>"
				name="<?php echo $this->get_field_name( 'css' ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ 'css' ]; ?>/>
		</p>

		<p> <?php _e( 'Display Categories on Single Posts', 'advanced-sidebar-menu' ); ?>
			<input id="<?php echo $this->get_field_name( 'single' ); ?>"
				name="<?php echo $this->get_field_name( 'single' ); ?>" type="checkbox" value="checked"
				onclick="javascript:asm_reveal_element( 'new-widget-<?php echo $this->get_field_name( 'new_widget' ); ?>' )"
				<?php echo $instance[ 'single' ]; ?>/>
		</p>

		<span id="new-widget-<?php echo $this->get_field_name( 'new_widget' ); ?>" style="<?php
		if( $instance[ 'single' ] == 'checked' ){
			echo 'display:block';
		} else {
			echo 'display:none';
		} ?>">
                 <p><?php _e( "Display Each Single Post's Category", 'advanced-sidebar-menu' ); ?>
	                 <select id="<?php echo $this->get_field_name( 'new_widget' ); ?>"
		                 name="<?php echo $this->get_field_name( 'new_widget' ); ?>">
		                 <?php
		                 if( $instance[ 'new_widget' ] == 'widget' ){
			                 echo '<option value="widget" selected> In a new widget </option>';
			                 echo '<option value="list"> In another list in the same widget </option>';
		                 } else {
			                 echo '<option value="widget"> In a new widget </option>';
			                 echo '<option value="list" selected> In another list in the same widget </option>';
		                 }

		                 ?></select>
                 </p>
            </span>


		<p> <?php _e( "Categories to Exclude, Comma Separated", 'advanced-sidebar-menu' ); ?>:
			<input id="<?php echo $this->get_field_name( 'exclude' ); ?>"
				name="<?php echo $this->get_field_name( 'exclude' ); ?>" type="text" class="widefat" value="<?php echo $instance[ 'exclude' ]; ?>"/>
		</p>

		<p> <?php _e( "Always Display Child Categories", 'advanced-sidebar-menu' ); ?>
			<input id="<?php echo $this->get_field_name( 'display_all' ); ?>"
				name="<?php echo $this->get_field_name( 'display_all' ); ?>" type="checkbox" value="checked"
				onclick="javascript:asm_reveal_element( 'levels-<?php echo $this->get_field_name( 'levels' ); ?>' )"
				<?php echo $instance[ 'display_all' ]; ?>/>
		</p>

	<span id="levels-<?php echo $this->get_field_name( 'levels' ); ?>" style="<?php
	if( $instance[ 'display_all' ] == 'checked' ){
		echo 'display:block';
	} else {
		echo 'display:none';
	} ?>">
		<p> <?php _e( "Levels to Display", 'advanced-sidebar-menu' ); ?>
	<select id="<?php echo $this->get_field_name( 'levels' ); ?>"
		name="<?php echo $this->get_field_name( 'levels' ); ?>">
		<?php
		for( $i = 1; $i < 6; $i ++ ){
			if( $i == $instance[ 'levels' ] ){
				echo '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				echo '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		echo '</select></p></span>';

		do_action( 'advanced_sidebar_menu_category_widget_form', $instance, $this );

		do_action( 'advanced_sidebar_menu_after_widget_form', $instance, $this );

	}


	/**
	 * Updates the widget data
	 *
	 * @filter - $newInstance = apply_filters('advanced_sidebar_menu_category_widget_update', $newInstance,
	 *         $oldInstance );
	 * @since  5.19.13
	 */
	function update( $newInstance, $oldInstance ){
		$newInstance[ 'exclude' ] = strip_tags( $newInstance[ 'exclude' ] );
		$newInstance              = apply_filters( 'advanced_sidebar_menu_category_widget_update', $newInstance, $oldInstance );

		return $newInstance;
	}




	#---------------------------------------------------------------------------------------------------------------------------

	/**
	 * Outputs the categories widget to the page
	 *
	 * @since   11.15.13
	 * @uses    loads the views/category_list.php
	 *
	 * @filters apply_filters('advanced_sidebar_menu_category_widget_output', $content, $args, $instance );
	 *           apply_filters('advanced_sidebar_menu_taxonomy', 'post', $args, $instance );
	 *           apply_filters('advanced_sidebar_menu_proper_single', $asm->checked('single'), $args, $instance)
	 *           apply_filters( 'advanced_sidebar_menu_category_ids', $cat_ids, $args, $instance )
     *
     * @todo Clean up filters to match structure of page widget and use apply_filters_ref_array()
     *       update web docs when doing so
     *       Keep backward compat filters in place
	 *
	 */
	function widget( $args, $instance ){
		$instance = wp_parse_args( $instance, $this->defaults );
		if( is_single() && !isset( $instance[ 'single' ] ) ){
			return;
		}

		$asm = Advanced_Sidebar_Menu_Menu::factory( $instance, $args );
		$cat_ids  = $already_top = array();
		$asm_once = false; //keeps track of how many widgets this created
        $close = false;

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->order_by = apply_filters( 'advanced_sidebar_menu_category_orderby', 'name', $args, $instance );
		$asm->order = apply_filters( 'advanced_sidebar_menu_category_order', $asm->order, $args, $instance );
		$asm->exclude = apply_filters( 'advanced_sidebar_menu_excluded_categories', explode( ',', $instance[ 'exclude' ] ), $args, $instance, $asm );
		$asm->taxonomy = apply_filters( 'advanced_sidebar_menu_taxonomy', 'category', $args, $instance, $asm );

        add_filter( 'category_css_class', array( $asm, 'add_has_children_category_class' ), 2, 2 );



		//If on a single page create an array of each category and create a list for each
		if( is_single() ){
			if( !apply_filters( 'advanced_sidebar_menu_proper_single', $asm->checked( 'single' ), $args, $instance, $asm ) ){
				return;
			}
			$cat_ids = wp_get_object_terms( get_the_ID(), $asm->taxonomy, array( 'fields' => 'ids' ) );

			//IF on a category page get the id of the category
		} elseif( is_tax() || is_category() ) {
			$asm->current_term = get_queried_object()->term_id;
			$cat_ids[]         = get_queried_object()->term_id;
		}

		$cat_ids = apply_filters( 'advanced_sidebar_menu_category_ids', $cat_ids, $args, $instance, $asm );

		if( empty( $cat_ids ) ){
			return;
		}

		//Go through each category there will be only one if this is a category page multiple possible if this is single
		$top_level_cats = array();
		foreach( $cat_ids as $cat_id ){
			$top_level_cat = $asm->getTopCat( $cat_id );
			if( !in_array( $top_level_cat, $top_level_cats ) ){
				$top_level_cats[] = $top_level_cat;
			}
		}

		if( !empty( $top_level_cats ) ){
			$top_level_cats = get_terms( array(
				'include'    => $top_level_cats,
				'hide_empty' => false,
				'orderby'    => $asm->order_by,
				'order'      => $asm->order,
			) );
		}

		foreach( $top_level_cats as $_cat ){
            $asm->top_id = $_cat->term_id;

			//Check for children
			$all_categories = $all = array_filter(
				get_terms(
					$asm->taxonomy, array(
						'parent'  => $asm->top_id,
						'orderby' => $asm->order_by,
						'order'   => $asm->order,
					)
				)
			);


			//If there are no children and not displaying childless parent - bail
			if( empty( $all_categories ) && !( $asm->checked( 'include_childless_parent' ) ) ){
				continue;
			}
			//If there are no children and the parent is excluded bail
			if( empty( $all_categories ) && in_array( $asm->top_id, $asm->exclude ) ){
				continue;
			}

			//Creates a new widget for each category the single page has if the options are selected to do so
			//Also starts the first widget
			if( !$asm_once || ( $instance[ 'new_widget' ] == 'widget' ) ){

				//Start the menu
				echo $args[ 'before_widget' ];
				if( !$asm_once ){
					//must remain in the loop instead of the template because we only want it to display once
					$asm->title();

					if( $asm->checked( 'css' ) ){
						echo '<style type="text/css">';
						include( Advanced_Sidebar_Menu::get_instance()->get_template_part( 'sidebar-menu.css' ) );
						echo '</style>';
					}

					$asm_once = true;  //There has been a div
					$close    = true; //The div should be closed at the end

					if( $instance[ 'new_widget' ] == 'list' ){
						$close = false;  //If this is a list leave it open for now
					}
				}
			}

			//Bring in the view
			$output = require( Advanced_Sidebar_Menu::get_instance()->get_template_part( 'category_list.php' ) );

			//backward compatibility for old views that didn't returns
			if( empty( $output ) && isset( $content ) ){
				$output = $content;
			}
			echo apply_filters( 'advanced_sidebar_menu_category_widget_output', $output, $args, $instance, $asm );

			if( $close ){
				//End the Widget Area
				echo $args[ 'after_widget' ];
				echo '<!-- First $after_widget -->';
			}

		} //End of each cat loop

		//IF we were waiting for all the individual lists to complete
		if( !$close && $asm_once ){
			//End the Widget Area
			echo $args[ 'after_widget' ];;
			echo '<!-- Second $after_widget -->';

		}
	}

}