<?php


/**
 * Advanced_Sidebar_Menu_Widgets_Page
 *
 * Parent child menu based on pages.
 *
 * @author Mat Lipe
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Widget_Page extends WP_Widget {
	const TITLE = Advanced_Sidebar_Menu_Menus_Abstract::TITLE;
	const INCLUDE_PARENT = Advanced_Sidebar_Menu_Menus_Abstract::INCLUDE_PARENT;
	const INCLUDE_CHILDLESS_PARENT = Advanced_Sidebar_Menu_Menus_Abstract::INCLUDE_CHILDLESS_PARENT;
	const ORDER_BY = Advanced_Sidebar_Menu_Menus_Abstract::ORDER_BY;
	const USE_PLUGIN_STYLES = Advanced_Sidebar_Menu_Menus_Abstract::USE_PLUGIN_STYLES;
	const EXCLUDE = Advanced_Sidebar_Menu_Menus_Abstract::EXCLUDE;
	const DISPLAY_ALL = Advanced_Sidebar_Menu_Menus_Abstract::DISPLAY_ALL;
	const LEVELS = Advanced_Sidebar_Menu_Menus_Abstract::LEVELS;

	protected static $defaults = array(
		self::TITLE                    => false,
		self::INCLUDE_PARENT           => false,
		self::INCLUDE_CHILDLESS_PARENT => false,
		self::ORDER_BY                 => 'menu_order',
		self::USE_PLUGIN_STYLES        => false,
		self::EXCLUDE                  => false,
		self::DISPLAY_ALL              => false,
		self::LEVELS                   => 1,
	);


	public function __construct() {
		$widget_ops = array(
			'classname'   => 'advanced-sidebar-menu',
			'description' => __( 'Creates a menu of all the pages using the child/parent relationship', 'advanced-sidebar-menu' ),
		);
		$control_ops = array(
			'width' => 290,
		);

		parent::__construct( 'advanced_sidebar_menu', __( 'Advanced Sidebar Pages Menu', 'advanced-sidebar-menu' ), $widget_ops, $control_ops );

	}


	/**
	 * Output a simple widget Form
	 *
	 * Not of ton of options here but who needs them
	 * Most of the magic happens automatically
	 *
	 * @filters do_action('advanced_sidebar_menu_page_widget_form', $instance, $this->get_field_name('parent_only'),
	 *          $this->get_field_id('parent_only'));
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, self::$defaults );
		?>
        <p> <?php _e( 'Title', 'advanced-sidebar-menu' ); ?>
            <br>
            <input id="<?php echo $this->get_field_id( self::TITLE ); ?>"
                    name="<?php echo $this->get_field_name( self::TITLE ); ?>" class="widefat" type="text" value="<?php echo $instance[ self::TITLE ]; ?>"/>
        </p>

        <p> <?php _e( 'Display highest level parent page', 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( self::INCLUDE_PARENT ); ?>"
                    name="<?php echo $this->get_field_name( self::INCLUDE_PARENT ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::INCLUDE_PARENT ]; ?>/>
        </p>


        <p> <?php _e( 'Display menu when there is only the parent page', 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( self::INCLUDE_CHILDLESS_PARENT ); ?>"
                    name="<?php echo $this->get_field_name( 'include_childless_parent' ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::INCLUDE_CHILDLESS_PARENT ]; ?>/>
        </p>

        <p> <?php _e( 'Order by', 'advanced-sidebar-menu' ); ?>:
            <select id="<?php echo $this->get_field_id( self::ORDER_BY ); ?>"
                    name="<?php echo $this->get_field_name( self::ORDER_BY ); ?>">
				<?php

				$order_by = array(
					'menu_order' => 'Page Order',
					'post_title' => 'Title',
					'post_date'  => 'Published Date',
				);

				foreach( $order_by as $key => $order ){

					printf( '<option value="%s" %s>%s</option>', $key, selected( $instance[ self::ORDER_BY ], $key, false ), $order );
				}
				?>
            </select>
        </p>

        <p> <?php _e( "Use this Plugin's Styling", 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( self::USE_PLUGIN_STYLES ); ?>"
                    name="<?php echo $this->get_field_name( self::USE_PLUGIN_STYLES ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::USE_PLUGIN_STYLES ]; ?>/>
        </p>

        <p> <?php _e( 'Pages to exclude (ids), comma separated', 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( self::EXCLUDE ); ?>"
                    name="<?php echo $this->get_field_name( self::EXCLUDE ); ?>" class="widefat" type="text" value="<?php echo $instance[ self::EXCLUDE ]; ?>"/>
        </p>

        <p> <?php _e( 'Always display child pages', 'advanced-sidebar-menu' ); ?>:
            <input
                    id="<?php echo $this->get_field_id( self::DISPLAY_ALL ); ?>"
                    name="<?php echo $this->get_field_name( self::DISPLAY_ALL ); ?>"
                    type="checkbox"
                    value="checked"
                    data-js="advanced-sidebar-menu/widget/page/display_all"
                    onclick="javascript:asm_reveal_element( 'levels-<?php echo $this->get_field_id( self::LEVELS ); ?>' )"
				<?php echo  $instance[ self::DISPLAY_ALL ]; ?>/>
        </p>

    <span id="levels-<?php echo $this->get_field_id( self::LEVELS ); ?>" style="<?php
	if( $instance[ 'display_all' ] === 'checked' ){
		echo 'display:block';
	} else {
		echo 'display:none';
	} ?>">
        <p> <?php _e( 'Levels to display', 'advanced-sidebar-menu' ); ?>:
    <select id="<?php echo $this->get_field_id( self::LEVELS ); ?>"
            name="<?php echo $this->get_field_name( self::LEVELS ); ?>">
		<?php
		for( $i = 1; $i < 6; $i ++ ){
			if( $i === (int) $instance[ self::LEVELS ] ){
				echo '<option value="' . $i . '" selected>' . $i . '</option>';
			} else {
				echo '<option value="' . $i . '">' . $i . '</option>';
			}
		}
		echo '</select></p></span>';

		do_action( 'advanced_sidebar_menu_page_widget_form', $instance, $this->get_field_name( 'parent_only' ), $this->get_field_id( 'parent_only' ), $this );

		do_action( 'advanced_sidebar_menu_after_widget_form', $instance, $this );

	}


	/**
	 * Handles the saving of the widget
	 *
	 * @filters apply_filters('advanced_sidebar_menu_page_widget_update', $newInstance, $oldInstance );
	 *
	 * @since   4.26.13
	 */
	public function update( $newInstance, $oldInstance ) {
		$newInstance[ 'exclude' ] = strip_tags( $newInstance[ 'exclude' ] );

		$newInstance = apply_filters( 'advanced_sidebar_menu_page_widget_update', $newInstance, $oldInstance );

		return $newInstance;
	}


	/**
	 * Widget Output
	 *
	 * @since 7.0.0
	 *
	 * @see   \Advanced_Sidebar_Menu_Menus_Page
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, self::$defaults );
		$asm = Advanced_Sidebar_Menu_Menus_Page::factory( $instance, $args );

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->render();

	}
}