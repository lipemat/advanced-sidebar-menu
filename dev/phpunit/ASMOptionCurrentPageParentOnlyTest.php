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

	/**
	 * menu
	 *
	 * @var \Advanced_Sidebar_Menu_Menu
	 */
	private $menu;

	/**
	 * widget
	 *
	 * @var \advanced_sidebar_menu_page
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

		$pages = static::factory()->post->create_many( 9, array( 'post_type' => 'page' ) );

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

		$this->menu = $this->getMockBuilder( 'Advanced_Sidebar_Menu_Menu' )
			               ->setMethods( array( 'fake' ) ) //use all original methods
		                   ->getMock();
		$this->menu->top_id = $this->top_parent;

		$this->widget = $this->getMockBuilder( 'advanced_sidebar_menu_page' )
		                     ->getMock();
		$this->widget->id_base = 'advanced_sidebar_menu';

	}

	public function test_filtering() {
		$this->menu->instance[ $this->o->get_option_name() ] = 'checked';
		do_action( 'advanced_sidebar_menu_widget_pre_render', $this->menu, $this->widget );

		$list_pages = Advanced_Sidebar_Menu_List_Pages::factory( clone $this->menu, get_post( reset( $this->sub_children_one ) ) );

		$child_pages = wp_list_pluck( $list_pages->get_child_pages( $this->top_parent, true ), 'ID' );
		$this->assertContains( $this->child_one, $child_pages, 'Current page parent only not working' );
		$this->assertNotContains( $this->child_two, $child_pages, 'Current page parent only not working' );

		$list_pages = Advanced_Sidebar_Menu_List_Pages::factory( clone $this->menu, get_post( reset( $this->sub_children_two ) ) );

		$child_pages = wp_list_pluck( $list_pages->get_child_pages( $this->top_parent, true ), 'ID' );
		$this->assertContains( $this->child_two, $child_pages, 'Current page parent only not working' );
		$this->assertNotContains( $this->child_one, $child_pages, 'Current page parent only not working' );
	}

}
