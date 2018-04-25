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
class Advanced_Sidebar_Menu_Widget_Page extends Advanced_Sidebar_Menu__Widget__Widget {
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
		$widget_ops  = array(
			'classname'   => 'advanced-sidebar-menu',
			'description' => __( 'Creates a menu of all the pages using the child/parent relationship', 'advanced-sidebar-menu' ),
		);
		$control_ops = array(
			'width' => 620,
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
		$instance = $this->set_instance( $instance, self::$defaults );
		?>
		<p xmlns="http://www.w3.org/1999/html">
			<label>
				<?php esc_html_e( 'Title', 'advanced-sidebar-menu' ); ?>:
			</label>

			<input
				id="<?php echo esc_attr( $this->get_field_id( self::TITLE ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( self::TITLE ) ); ?>"
				class="widefat"
				type="text"
				value="<?php echo esc_attr( $instance[ self::TITLE ] ); ?>"/>
		</p>
		<div class="advanced-sidebar-menu-column">
			<div class="advanced-sidebar-menu-column-box">
				<p>
					<?php $this->checkbox( self::INCLUDE_PARENT ); ?>
					<label>
						<?php esc_html_e( 'Display highest level parent page', 'advanced-sidebar-menu' ); ?>
					</label>
				</p>


				<p>
					<?php $this->checkbox( self::INCLUDE_CHILDLESS_PARENT ); ?>
					<label>
						<?php esc_html_e( 'Display menu when there is only the parent page', 'advanced-sidebar-menu' ); ?>
					</label>
				</p>

				<p>
					<?php $this->checkbox( self::DISPLAY_ALL, self::LEVELS ); ?>
					<label>
						<?php esc_html_e( 'Always display child pages', 'advanced-sidebar-menu' ); ?>:
					</label>
				</p>

				<div
					data-js="<?php echo esc_attr( $this->get_field_id( self::LEVELS ) ); ?>"
					<?php $this->hide_element( self::DISPLAY_ALL ); ?>>
					<p>
						<label>
							<?php esc_html_e( 'Levels to display', 'advanced-sidebar-menu' ); ?>:</label>
						<select
							name="<?php echo esc_attr( $this->get_field_name( self::LEVELS ) ); ?>">
							<?php
							for ( $i = 1; $i < 6; $i ++ ) {
								?>
								<option
									value="<?php echo esc_attr( $i ); ?>" <?php selected( $i, (int) $instance[ self::LEVELS ] ); ?>>
									<?php echo esc_html( $i ); ?>
								</option>

								<?php
							}
							?>
						</select>
					</p>
				</div>

				<?php do_action( 'advanced-sidebar-menu/widget/page/display-box', $instance, $this ); ?>

			</div>
			<div class="advanced-sidebar-menu-column-box">
				<p>
					<label>
						<?php esc_html_e( 'Order by', 'advanced-sidebar-menu' ); ?>:
					</label>
					<select
						id="<?php echo esc_attr( $this->get_field_id( self::ORDER_BY ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( self::ORDER_BY ) ); ?>">
						<?php
						$order_by = (array) apply_filters( 'advanced-sidebar-menu/widget/page/order-by-options', array(
							'menu_order' => 'Page Order',
							'post_title' => 'Title',
							'post_date'  => 'Published Date',
						) );

						foreach ( $order_by as $key => $order ) {
							printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $instance[ self::ORDER_BY ], $key, false ), esc_html( $order ) );
						}
						?>
					</select>
				</p>
				<?php do_action( 'advanced-sidebar-menu/widget/page/order-box', $instance, $this ); ?>

			</div>
			<div class="advanced-sidebar-menu-column-box">
				<p>
					<label>
						<?php esc_html_e( 'Pages to exclude (ids), comma separated', 'advanced-sidebar-menu' ); ?>:
					</label>
					<input
						id="<?php echo esc_attr( $this->get_field_id( self::EXCLUDE ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( self::EXCLUDE ) ); ?>"
						class="widefat"
						type="text"
						value="<?php echo esc_attr( $instance[ self::EXCLUDE ] ); ?>"/>
				</p>
				<?php
				do_action( 'advanced-sidebar-menu/widget/page/exclude-box', $instance, $this );
				?>
			</div>
			<?php
			if ( has_action( 'advanced_sidebar_menu_page_widget_form' ) ) {
				?>
				<div class="advanced-sidebar-menu-column-box">
					<?php do_action( 'advanced_sidebar_menu_page_widget_form', $this->get_field_id( 'parent_only' ), $this, $instance ); ?>
				</div>
				<?php
			}
			?>
		</div>
		<div class="advanced-sidebar-menu-column advanced-sidebar-menu-column-right">
			<?php
			do_action( 'advanced_sidebar_menu_after_widget_form', $instance, $this );
			?>
		</div>
		<?php

	}


	/**
	 * Update
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array|mixed
	 */
	public function update( $new_instance, $old_instance ) {
		$new_instance['exclude'] = strip_tags( $new_instance['exclude'] );

		return apply_filters( 'advanced_sidebar_menu_page_widget_update', $new_instance, $old_instance );
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
		$asm      = Advanced_Sidebar_Menu_Menus_Page::factory( $instance, $args );

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->render();

	}
}
