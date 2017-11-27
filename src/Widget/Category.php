<?php


/**
 * Creates a Widget of parent Child Categories
 *
 * @author  Mat Lipe
 * @since   7.0.0
 * @package Advanced Sidebar Menu
 *
 *
 */
class Advanced_Sidebar_Menu_Widget_Category extends WP_Widget {

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


	public function __construct() {
		$widget_ops = array(
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
	 * @return void
	 */
	public function form( $instance ) {

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
	public function update( $newInstance, $oldInstance ) {
		$newInstance[ 'exclude' ] = strip_tags( $newInstance[ 'exclude' ] );
		$newInstance = apply_filters( 'advanced_sidebar_menu_category_widget_update', $newInstance, $oldInstance );

		return $newInstance;
	}


	/**
	 * Widget Output
	 *
	 * @since 7.0.0
	 *
	 * @see   \Advanced_Sidebar_Menu_Menus_Category
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );
		$asm = Advanced_Sidebar_Menu_Menus_Category::factory( $instance, $args );

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->render();
	}

}