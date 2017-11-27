<?php


/**
 * Advanced_Sidebar_Menu_Menus_Page
 *
 * @author Mat Lipe
 * @since  7.0.0
 *
 */
class Advanced_Sidebar_Menu_Menus_Page extends Advanced_Sidebar_Menu_Menus_Abstract {
	const WIDGET = 'page';

	/**
	 * post_type
	 *
	 * @deprecated
	 *
	 * @var string
	 */
	public $post_type = 'page';

	/**
	 * post
	 *
	 * @var \WP_Post
	 */
	protected $post;

	protected $ancestors;


	public function set_current_post( WP_Post $post ) {
		$this->post = $post;
	}


	public function get_current_post() {
		if( null === $this->post ){
			$this->post = get_post();
		}

		return $this->post;
	}


	public function get_order_by() {
		return apply_filters( 'advanced_sidebar_menu_order_by', $this->instance[ 'order_by' ], $this->get_current_post(), $this->args, $this->instance, $this );
	}


	public function get_order() {
		return apply_filters( 'advanced_sidebar_menu_page_order', 'ASC', $this->get_current_post(), $this->args, $this->instance, $this );
	}


	public function get_top_parent_id() {
		$post = $this->get_current_post();
		$ancestors = $this->get_current_post_ancestors();

		$top_id = $post->ID;
		if( !empty( $ancestors ) ){
			$top_id = end( $ancestors );
		}

		//$this->top_id is deprecated since 7.0.0
		$this->top_id = apply_filters( 'advanced_sidebar_menu_top_parent', $top_id, $this->args, $this->instance, $this );

		return $this->top_id;

	}


	protected function get_current_post_ancestors() {
		if( null === $this->ancestors ){
			$this->ancestors = get_post_ancestors( $this->get_current_post() );
		}

		return $this->ancestors;
	}


	public function is_displayed() {
		$display = false;
		$post_type = $this->get_post_type();
		if( is_page() || ( is_single() && $post_type === $this->get_current_post()->post_type ) ){
			//if we are on the correct post type
			if( $post_type === get_post_type( $this->get_top_parent_id() ) ){
				//if we have children
				if( $this->has_pages() ){
					$display = true;
					//no children + not excluded + include parent +include childless parent
				} elseif( $this->checked( 'include_childless_parent' )
				          && $this->checked( 'include_parent' )
				          && !$this->is_excluded( $this->get_top_parent_id() ) ) {
					$display = true;
				}
			}
		}

		if( has_filter( 'advanced_sidebar_menu_proper_single' ) ){
			_deprecated_hook( 'advanced_sidebar_menu_proper_single', '7.0.0', 'advanced-sidebar-menu/menus/page/is-displayed' );
			$display = !apply_filters( 'advanced_sidebar_menu_proper_single', !$display, $this->args, $this->instance, $this );
		}

		return apply_filters( 'advanced-sidebar-menu/menus/page/is-displayed', $display );

	}


	/**
	 * Do we have child pages at all on this menu?
	 *
	 * Return false if all we have is the top parent page
	 * Return true if we have at least a second level
	 *
	 * @return bool
	 */
	public function has_pages() {
		$list_pages = Advanced_Sidebar_Menu_List_Pages::factory( $this );
		$children = $list_pages->get_child_pages( $this->get_top_parent_id(), true );

		return !empty( $children );
	}


	public function get_levels_to_display() {
		return apply_filters( 'advanced-sidebar-menu/menus/page/levels', $this->levels, $this->args, $this->instance, $this );
	}


	public function get_post_type() {
		return apply_filters( 'advanced_sidebar_menu_post_type', $this->post_type, $this->args, $this->instance, $this );
	}


	public function get_excluded_ids() {
		$excluded = parent::get_excluded_ids();
		if( !empty( $this->exclude ) ){
		    //backward compatibility for PRO version
		    $excluded = array_merge( $excluded, $this->exclude );
		}

		return apply_filters( 'advanced_sidebar_menu_excluded_pages', $excluded, $this->get_current_post(), $this->args, $this->instance, $this );
	}


	/**
	 * Render the widget
	 *
	 * @return void
	 */
	public function render() {
		if( !$this->is_displayed() ){
			return;
		}

		echo $this->args[ 'before_widget' ];

		do_action( 'advanced-sidebar-menu/menus/page/render', $this );

		if( $this->checked( 'css' ) ){
			?>
            <style>
                <?php include Advanced_Sidebar_Menu_Core::instance()->get_template_part( 'sidebar-menu.css', true ); ?>
            </style>
			<?php
		}
		$output = require Advanced_Sidebar_Menu_Core::instance()->get_template_part( 'page_list.php' );
		echo apply_filters( 'advanced_sidebar_menu_page_widget_output', $output, $this->get_current_post(), $this->args, $this->instance, $this );

		echo $this->args[ 'after_widget' ];
	}


	/**************** static *****************/
	/**
	 * Mostly here to call the parent _factory() in a PHP 5.2 structure
	 *
	 * @param array $widget_instance
	 * @param array $widget_args
	 *
	 * @static
	 *
	 * @return \Advanced_Sidebar_Menu_Menus_Page
	 */
	public static function factory( array $widget_instance, array $widget_args ) {
		return parent::_factory( __CLASS__, $widget_instance, $widget_args );
	}
}