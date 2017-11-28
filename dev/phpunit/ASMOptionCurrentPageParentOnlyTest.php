<?php
/**
 * ASMOptionCurrentPageParentOnlyTest.php
 *
 * @author  mat
 * @since   10/11/2017
 *
 * @package wordpress *
 */


class ASMOptionCurrentPageParentOnlyTest extends WP_UnitTestCase {

	private $top_parent;
	private $child_one;
	private $child_two;
	private $sub_children_one = array();
	private $sub_children_two = array();

	public $default_args = array(
		'post_type' => 'page',
		'exclude'   => '',
		'order_by'  => 'menu_order, post_title',
		'order'     => 'ASC',
		'levels'    => 0
	);
	/**
	 * menu
	 *
	 * @var \Advanced_Sidebar_Menu_Menus_Page
	 */
	private $menu;

	/**
	 * widget
	 *
	 * @var \Advanced_Sidebar_Menu_Widget_Page
	 */
	private $widget;

	/**
	 * parent_page_only
	 *
	 * @var \ASMOptionCurrentPageParentOnly
	 */
	private $o;

	public function setUp(){
		parent::setUp();
		require_once ADVANCED_SIDEBAR_MENU_PRO_DIR . 'src/widget-options/page/ASMOptionCurrentPageParentOnly.php';
		$this->o = new ASMOptionCurrentPageParentOnly();

		$pages = $this->factory()->post->create_many( 9, array( 'post_type' => 'page' ) );

		$this->top_parent = array_shift( $pages );
		$this->child_one = array_shift( $pages );
		wp_update_post( array( 'ID' => $this->child_one, 'post_parent' => $this->top_parent ) );
		$this->child_two = array_shift( $pages );
		wp_update_post( array( 'ID' => $this->child_two, 'post_parent' => $this->top_parent ) );

		for( $i = 1; $i <= 3; $i++ ){
			$child = array_shift( $pages );
			$this->sub_children_one[] = $child;
			wp_update_post( array( 'ID' => $child, 'post_parent' => $this->child_one ) );
		}
		for( $i = 1; $i <= 3; $i++ ){
			$child = array_shift( $pages );
			$this->sub_children_two[] = $child;
			wp_update_post( array( 'ID' => $child, 'post_parent' => $this->child_two ) );
		}

		$this->menu = Advanced_Sidebar_Menu_Menus_Page::factory( $this->default_args, array() );
		$this->menu->set_current_post( get_post( $this->top_parent ) );

		$this->widget = $this->getMockBuilder( 'Advanced_Sidebar_Menu_Widgets_Page' )
		                     ->getMock();
		$this->widget->id_base = 'advanced_sidebar_menu';

	}

	public function test_filtering() {
		$this->menu->instance[ $this->o->get_option_name() ] = 'checked';
		$this->menu->set_current_post( get_post( reset( $this->sub_children_one ) ) );
		do_action( 'advanced_sidebar_menu_widget_pre_render', $this->menu, $this->widget );

		$list_pages = Advanced_Sidebar_Menu_List_Pages::factory( clone $this->menu  );

		$child_pages = wp_list_pluck( $list_pages->get_child_pages( $this->top_parent, true ), 'ID' );
		$this->assertContains( $this->child_one, $child_pages, 'Current page parent only not working' );
		$this->assertNotContains( $this->child_two, $child_pages, 'Current page parent only not working' );

		$this->menu->set_current_post( get_post( reset( $this->sub_children_two ) ) );
		$list_pages = Advanced_Sidebar_Menu_List_Pages::factory( clone $this->menu );

		$child_pages = wp_list_pluck( $list_pages->get_child_pages( $this->top_parent, true ), 'ID' );
		$this->assertContains( $this->child_two, $child_pages, 'Current page parent only not working' );
		$this->assertNotContains( $this->child_one, $child_pages, 'Current page parent only not working' );
	}

}
