<?php


/**
 * Creates a Widget of parent Child Categories
 *
 * @author  OnPoint Plugins
 * @since   7.0.0
 * @package Advanced Sidebar Menu
 *
 *
 */
class Advanced_Sidebar_Menu_Widget_Category extends Advanced_Sidebar_Menu__Widget__Widget {
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

	protected static $hooked = false;

	/**
	 * Register the widget.
	 */
	public function __construct() {
		$widget_ops = [
			'classname'   => 'advanced-sidebar-menu advanced-sidebar-category',
			'description' => __( 'Creates a menu of all the categories using the child/parent relationship', 'advanced-sidebar-menu' ),
		];
		$control_ops = [
			'width' => wp_is_mobile() ? false : 620,
		];

		parent::__construct( 'advanced_sidebar_menu_category', __( 'Advanced Sidebar Categories Menu', 'advanced-sidebar-menu' ), $widget_ops, $control_ops );

		if ( ! self::$hooked ) {
			self::$hooked = true;
			$this->hook();
		}
	}


	/**
	 * @notice Anything using the column actions must use the $widget class passed
	 *         via do_action instead of $this
	 *
	 * @return void
	 */
	protected function hook() {
		add_action( 'advanced-sidebar-menu/widget/category/left-column', array( $this, 'box_display' ), 5, 2 );
		add_action( 'advanced-sidebar-menu/widget/category/left-column', array( $this, 'box_styles' ), 10, 2 );
		add_action( 'advanced-sidebar-menu/widget/category/left-column', array( $this, 'box_singles' ), 15, 2 );
		add_action( 'advanced-sidebar-menu/widget/category/left-column', array( $this, 'box_exclude' ), 20, 2 );

	}

	/**
	 *
	 * @param array                                  $instance
	 * @param \Advanced_Sidebar_Menu__Widget__Widget $widget
	 *
	 * @return void
	 */
	public function box_display( array $instance, $widget ) {
		?>
		<div class="advanced-sidebar-menu-column-box">
			<p>
				<?php $widget->checkbox( self::INCLUDE_PARENT ); ?>
				<label>
					<?php esc_html_e( 'Display highest level parent category', 'advanced-sidebar-menu' ); ?>
				</label>
			</p>
			<p>
				<?php $widget->checkbox( self::INCLUDE_CHILDLESS_PARENT ); ?>
				<label>
					<?php esc_html_e( 'Display menu when there is only the parent category', 'advanced-sidebar-menu' ); ?>
				</label>
			</p>
			<p>
				<?php $widget->checkbox( self::DISPLAY_ALL, self::LEVELS ); ?>
				<label>
					<?php esc_html_e( 'Always display child categories', 'advanced-sidebar-menu' ); ?>
				</label>
			</p>
			<div <?php $widget->hide_element( self::DISPLAY_ALL, self::LEVELS ); ?>>
				<p>
					<label>
						<?php esc_html_e( 'Levels of child categories to display', 'advanced-sidebar-menu' ); ?>:</label>
					<select
						name="<?php echo esc_attr( $widget->get_field_name( self::LEVELS ) ); ?>">
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

			<?php do_action( 'advanced-sidebar-menu/widget/category/display-box', $instance, $widget ); ?>

		</div>
		<?php
	}

	/**
	 *
	 * @param array                                  $instance
	 * @param \Advanced_Sidebar_Menu__Widget__Widget $widget
	 *
	 * @return void
	 */
	public function box_styles( array $instance, $widget ) {
		?>
		<div class="advanced-sidebar-menu-column-box">
			<p>
				<?php $widget->checkbox( self::USE_PLUGIN_STYLES ); ?>
				<label>
					<?php esc_html_e( "Use this plugin's default styling", 'advanced-sidebar-menu' ); ?>
				</label>
			</p>

			<?php do_action( 'advanced-sidebar-menu/widget/category/styles-box', $instance, $widget ); ?>
		</div>
		<?php
	}

	/**
	 *
	 * @param array                                  $instance
	 * @param \Advanced_Sidebar_Menu__Widget__Widget $widget
	 *
	 * @return void
	 */
	public function box_singles( array $instance, $widget ) {
		?>
		<div class="advanced-sidebar-menu-column-box">
			<p>

				<?php $widget->checkbox( self::DISPLAY_ON_SINGLE, self::EACH_CATEGORY_DISPLAY ); ?>
				<label>
					<?php esc_html_e( 'Display categories on single posts', 'advanced-sidebar-menu' ); ?>
				</label>
			</p>

			<div <?php $widget->hide_element( self::DISPLAY_ON_SINGLE, self::EACH_CATEGORY_DISPLAY ); ?>>
				<p>
					<label><?php esc_html_e( "Display each single post's category", 'advanced-sidebar-menu' ); ?>
						:</label>
					<select
						name="<?php echo esc_attr( $widget->get_field_name( self::EACH_CATEGORY_DISPLAY ) ); ?>">
						<option
							value="widget" <?php selected( 'widget', $instance[ self::EACH_CATEGORY_DISPLAY ] ); ?>>
							<?php esc_html_e( 'In a new widget', 'advanced-sidebar-menu' ); ?>
						</option>
						<option value="list" <?php selected( 'list', $instance[ self::EACH_CATEGORY_DISPLAY ] ); ?>>
							<?php esc_html_e( 'In another list in the same widget', 'advanced-sidebar-menu' ); ?>
						</option>
					</select>
				</p>
			</div>

			<?php do_action( 'advanced-sidebar-menu/widget/category/singles-box', $instance, $widget ); ?>

		</div>
		<?php
	}

	/**
	 *
	 * @param array                                  $instance
	 * @param \Advanced_Sidebar_Menu__Widget__Widget $widget
	 *
	 * @return void
	 */
	public function box_exclude( array $instance, $widget ) {
		?>
		<div class="advanced-sidebar-menu-column-box">
			<p>
				<label>
					<?php esc_html_e( 'Categories to exclude (ids), comma separated', 'advanced-sidebar-menu' ); ?>:
				</label>
				<input
					id="<?php echo esc_attr( $widget->get_field_id( self::EXCLUDE ) ); ?>"
					name="<?php echo esc_attr( $widget->get_field_name( self::EXCLUDE ) ); ?>"
					type="text"
					class="widefat"
					value="<?php echo esc_attr( $instance[ self::EXCLUDE ] ); ?>"/>
			</p>

			<?php
			do_action( 'advanced-sidebar-menu/widget/category/exclude-box', $instance, $widget );
			?>
		</div>
		<?php
	}


	/**
	 * Form
	 *
	 * @since 7.2.1
	 *
	 * @param array $instance
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$instance = $this->set_instance( $instance, self::$defaults );
		do_action( 'advanced-sidebar-menu/widget/category/before-form', $instance, $this );
		?>
		<p>
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
			<?php
			do_action( 'advanced-sidebar-menu/widget/category/left-column', $instance, $this );

			if ( has_action( 'advanced_sidebar_menu_category_widget_form' ) ) {
				?>
				<div class="advanced-sidebar-menu-column-box">
					<?php do_action( 'advanced_sidebar_menu_category_widget_form', $instance, $this ); ?>
				</div>
				<?php
			}

			?>
		</div>

		<div class="advanced-sidebar-menu-column advanced-sidebar-menu-column-right">
			<?php
			do_action( 'advanced-sidebar-menu/widget/category/right-column', $instance, $this );
			// @deprecated action.
			do_action( 'advanced_sidebar_menu_after_widget_form', $instance, $this );
			?>
		</div>
		<div class="advanced-sidebar-menu-full-width"><!-- clear --></div>

		<?php
		do_action( 'advanced-sidebar-menu/widget/category/after-form', $instance, $this );

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

		return apply_filters( 'advanced_sidebar_menu_category_widget_update', $new_instance, $old_instance );
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
		$instance = $this->set_instance( $instance, self::$defaults );
		$asm      = Advanced_Sidebar_Menu_Menus_Category::factory( $instance, $args );

		do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );

		$asm->render();

		// @since 7.6.6.
		do_action( 'advanced-sidebar-menu/widget/after-render', $asm, $this );

	}

}
