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
	const TITLE = Advanced_Sidebar_Menu_Menus_Abstract::TITLE;
	const INCLUDE_PARENT = Advanced_Sidebar_Menu_Menus_Abstract::INCLUDE_PARENT;
	const INCLUDE_CHILDLESS_PARENT = Advanced_Sidebar_Menu_Menus_Abstract::INCLUDE_CHILDLESS_PARENT;
	const ORDER_BY = Advanced_Sidebar_Menu_Menus_Abstract::ORDER_BY;
	const USE_PLUGIN_STYLES = Advanced_Sidebar_Menu_Menus_Abstract::USE_PLUGIN_STYLES;
	const EXCLUDE = Advanced_Sidebar_Menu_Menus_Abstract::EXCLUDE;
	const DISPLAY_ALL = Advanced_Sidebar_Menu_Menus_Abstract::DISPLAY_ALL;
	const LEVELS = Advanced_Sidebar_Menu_Menus_Abstract::LEVELS;

	const DISPLAY_ON_SINGLE = Advanced_Sidebar_Menu_Menus_Category::DISPLAY_ON_SINGLE;
	const EACH_CATEGORY_DISPLAY = Advanced_Sidebar_Menu_Menus_Category::EACH_CATEGORY_DISPLAY;

	protected static $defaults = array(
		self::TITLE                    => '',
		self::INCLUDE_PARENT           => false,
		self::INCLUDE_CHILDLESS_PARENT => false,
		self::USE_PLUGIN_STYLES        => false,
		self::DISPLAY_ON_SINGLE        => false,
		self::EACH_CATEGORY_DISPLAY    => 'widget',
		self::EXCLUDE                  => '',
		self::DISPLAY_ALL              => false,
		self::LEVELS                   => 1,
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

		$instance = wp_parse_args( $instance, self::$defaults );

		?>
        <p> <?php _e( 'Title', 'advanced-sidebar-menu' ); ?>
            <br>
            <input id="<?php echo $this->get_field_name( self::TITLE ); ?>"
                    name="<?php echo $this->get_field_name( self::TITLE ); ?>" class="widefat" type="text" value="<?php echo $instance[ self::TITLE ]; ?>"/>
        </p>


        <p> <?php _e( 'Display highest level parent category', 'advanced-sidebar-menu' ); ?>
            <input id="<?php echo $this->get_field_name( self::INCLUDE_PARENT ); ?>"
                    name="<?php echo $this->get_field_name( self::INCLUDE_PARENT ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::INCLUDE_PARENT ]; ?>/>
        </p>


        <p> <?php _e( 'Display menu when there is only the parent category', 'advanced-sidebar-menu' ); ?>
            <input id="<?php echo $this->get_field_name( self::INCLUDE_CHILDLESS_PARENT ); ?>"
                    name="<?php echo $this->get_field_name( self::INCLUDE_CHILDLESS_PARENT ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::INCLUDE_CHILDLESS_PARENT ]; ?>/>
        </p>

        <p> <?php _e( 'Use this plugins styling', 'advanced-sidebar-menu' ); ?>
            <input id="<?php echo $this->get_field_name( self::USE_PLUGIN_STYLES ); ?>"
                    name="<?php echo $this->get_field_name( self::USE_PLUGIN_STYLES ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::USE_PLUGIN_STYLES ]; ?>/>
        </p>

        <p> <?php _e( 'Display categories on single posts', 'advanced-sidebar-menu' ); ?>
            <input id="<?php echo $this->get_field_name( self::DISPLAY_ON_SINGLE ); ?>"
                    name="<?php echo $this->get_field_name( self::DISPLAY_ON_SINGLE ); ?>" type="checkbox" value="checked"
                    onclick="javascript:asm_reveal_element( 'new-widget-<?php echo $this->get_field_name( self::EACH_CATEGORY_DISPLAY ); ?>' )"
				<?php echo $instance[ self::DISPLAY_ON_SINGLE ]; ?>/>
        </p>

        <span id="new-widget-<?php echo $this->get_field_name( self::EACH_CATEGORY_DISPLAY ); ?>" style="<?php
		if( $instance[ self::DISPLAY_ON_SINGLE ] === 'checked' ){
			echo 'display:block';
		} else {
			echo 'display:none';
		} ?>">
                 <p><?php _e( "Display each single post's category", 'advanced-sidebar-menu' ); ?>
                     <select id="<?php echo $this->get_field_name( self::EACH_CATEGORY_DISPLAY ); ?>"
                             name="<?php echo $this->get_field_name( self::EACH_CATEGORY_DISPLAY ); ?>">
		                 <?php
		                 if( $instance[ self::EACH_CATEGORY_DISPLAY ] === 'widget' ){
		                     ?>
			                 <option value="widget" selected>
                                    <?php _e( 'In a new widget', 'advanced-sidebar-menu' ); ?>
                             </option>
			                 <option value="list">
                                 <?php _e( 'In another list in the same widget', 'advanced-sidebar-menu' ); ?>
                             </option>
                             <?php
		                 } else {
		                     ?>
                             <option value="widget">
                                    <?php _e( 'In a new widget', 'advanced-sidebar-menu' ); ?>
                             </option>
                             <option value="list" selected>
                                 <?php _e( 'In another list in the same widget', 'advanced-sidebar-menu' ); ?>
                             </option>
                             <?php
		                 }

		                 ?></select>
                 </p>
            </span>


        <p>
            <?php _e( 'Categories to exclude (ids), comma separated', 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_name( self::EXCLUDE ); ?>"
                    name="<?php echo $this->get_field_name( self::EXCLUDE ); ?>" type="text" class="widefat" value="<?php echo $instance[ self::EXCLUDE ]; ?>"/>
        </p>

        <p> <?php _e( 'Always display child categories', 'advanced-sidebar-menu' ); ?>
            <input id="<?php echo $this->get_field_name( self::DISPLAY_ALL ); ?>"
                    name="<?php echo $this->get_field_name( self::DISPLAY_ALL ); ?>" type="checkbox" value="checked"
                    onclick="javascript:asm_reveal_element( 'levels-<?php echo $this->get_field_name( self::LEVELS ); ?>' )"
				<?php echo $instance[ self::DISPLAY_ALL ]; ?>/>
        </p>

        <span id="levels-<?php echo $this->get_field_name( self::LEVELS ); ?>" style="<?php
		if( $instance[ self::DISPLAY_ALL ] === 'checked' ){
			echo 'display:block';
		} else {
			echo 'display:none';
		} ?>">
        <p>
            <?php _e( 'Levels to display', 'advanced-sidebar-menu' ); ?>
            <select id="<?php echo $this->get_field_name( self::LEVELS ); ?>"
                    name="<?php echo $this->get_field_name( self::LEVELS ); ?>">
		<?php
		for( $i = 1; $i < 6; $i ++ ){
			if( $i === (int) $instance[ 'levels' ] ){
				echo '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				echo '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		?>
            </select>
        </p>
    </span>
		<?php

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
		$instance = wp_parse_args( $instance, self::$defaults );
		$asm = Advanced_Sidebar_Menu_Menus_Category::factory( $instance, $args );

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->render();
	}

}
