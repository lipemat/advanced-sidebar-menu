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

	//@todo set the rest to constants
	const DISPLAY_PARENT = 'include_parent';

	private $defaults = array(
		'title'                    => false,
		'include_parent'           => false,
		'include_childless_parent' => false,
		'order_by'                 => 'menu_order',
		'css'                      => false,
		'exclude'                  => false,
		'display_all'              => false,
		'levels'                   => 1,
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
		$instance = wp_parse_args( $instance, $this->defaults );
		?>
        <p> <?php _e( 'Title', 'advanced-sidebar-menu' ); ?>
            <br>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>"
                    name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" type="text" value="<?php echo $instance[ 'title' ]; ?>"/>
        </p>

        <p> <?php _e( 'Display parent page', 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( self::DISPLAY_PARENT ); ?>"
                    name="<?php echo $this->get_field_name( self::DISPLAY_PARENT ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ self::DISPLAY_PARENT ]; ?>/>
        </p>


        <p> <?php _e( 'Display menu when there is only the parent page', 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( 'include_childless_parent' ); ?>"
                    name="<?php echo $this->get_field_name( 'include_childless_parent' ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ 'include_childless_parent' ]; ?>/>
        </p>

        <p> <?php _e( 'Order By', 'advanced-sidebar-menu' ); ?>:
            <select id="<?php echo $this->get_field_id( 'order_by' ); ?>"
                    name="<?php echo $this->get_field_name( 'order_by' ); ?>">
				<?php

				$order_by = array(
					'menu_order' => 'Page Order',
					'post_title' => 'Title',
					'post_date'  => 'Published Date',
				);

				foreach( $order_by as $key => $order ){

					printf( '<option value="%s" %s>%s</option>', $key, selected( $instance[ 'order_by' ], $key, false ), $order );
				}
				?>
            </select>
        </p>

        <p> <?php _e( "Use this Plugin's Styling", 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( 'css' ); ?>"
                    name="<?php echo $this->get_field_name( 'css' ); ?>" type="checkbox" value="checked"
				<?php echo $instance[ 'css' ]; ?>/>
        </p>

        <p> <?php _e( "Pages to Exclude (ids), Comma Separated", 'advanced-sidebar-menu' ); ?>:
            <input id="<?php echo $this->get_field_id( 'exclude' ); ?>"
                    name="<?php echo $this->get_field_name( 'exclude' ); ?>" class="widefat" type="text" value="<?php echo $instance[ 'exclude' ]; ?>"/>
        </p>

        <p> <?php _e( 'Always Display Child Pages', 'advanced-sidebar-menu' ); ?>:
            <input
                    id="<?php echo $this->get_field_id( 'display_all' ); ?>"
                    name="<?php echo $this->get_field_name( 'display_all' ); ?>"
                    type="checkbox"
                    value="checked"
                    data-js="advanced-sidebar-menu/widget/page/display_all"
                    onclick="javascript:asm_reveal_element( 'levels-<?php echo $this->get_field_id( 'levels' ); ?>' )"
		        <?= $instance[ 'display_all' ]; ?>/>
        </p>

    <span id="levels-<?php echo $this->get_field_id( 'levels' ); ?>" style="<?php
	if( $instance[ 'display_all' ] === 'checked' ){
		echo 'display:block';
	} else {
		echo 'display:none';
	} ?>">
        <p> <?php _e( "Levels to Display", 'advanced-sidebar-menu' ); ?>:
    <select id="<?php echo $this->get_field_id( 'levels' ); ?>"
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
	 * Widget Ouput
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
		$instance = wp_parse_args( $instance, $this->defaults );
		$asm = Advanced_Sidebar_Menu_Menus_Page::factory( $instance, $args );
		$asm->set_current_post( get_post() );

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->render();

	}
}