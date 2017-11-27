<?php


/**
 * Advanced_Sidebar_Menu_List_PagesTest.php
 *
 * @author  mat
 * @since   8/18/14
 *
 * @package advacned-sidebar-menu
 */
class Advanced_Sidebar_Menu_List_PagesTest extends WP_UnitTestCase {

	private $top_parent = 2; //sample-page

	public $default_args = array(
		'post_type' => 'page',
		'exclude'   => '',
		'order_by'  => 'menu_order, post_title',
		'order'     => 'ASC',
		'levels'    => 0,
	);

	/**
	 * menu
	 *
	 * @var \Advanced_Sidebar_Menu_Menus_Page
	 */
	private $mock;


	public function setUp() {
		parent::setUp();
		switch_to_blog( 3 );

		$this->mock = Advanced_Sidebar_Menu_Menus_Page::factory( $this->default_args, array() );
		$this->mock->set_current_post( $this->factory()->post->create_and_get( array( 'post_type' => 'page' ) ) );
	}


	public function test_posts_per_page() {
		$pages = $this->factory()->post->create_many( 7, array( 'post_type' => 'page' ) );
		$top = array_shift( $pages );
		foreach( $pages as $id ){
			wp_update_post( array( 'ID' => $id, 'post_parent' => $top ) );
		}
		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->mock );
		$this->assertCount( 6, $menu->get_child_pages( $top, true ), 'Not returning enough children. Probably posts_per_page no set' );
	}


	public function test_excluded_pages() {
		$this->mock->instance[ 'exclude' ] = '7,19, ';

		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->mock );

		function not_contains_page_id( $pages, Advanced_Sidebar_Menu_List_Pages $menu, Advanced_Sidebar_Menu_List_PagesTest $test ) {
			$pages = wp_list_pluck( $pages, 'ID' );
			$test->assertNotContains( 7, $pages, 'an excluded page is present' );
			$test->assertNotContains( 19, $pages, 'an excluded page is present' );
			foreach( $pages as $page ){
				$children = $menu->get_child_pages( $page );
				if( !empty( $children ) ){
					not_contains_page_id( $children, $menu, $test );
				}
			}
		}

		not_contains_page_id( $menu->get_child_pages( $this->top_parent ), $menu, $this );

	}


	public function test_order_by_title() {
		$this->mock->instance[ 'order_by' ] = 'title';

		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->mock );

		function ordered_by_title( $pages, Advanced_Sidebar_Menu_List_Pages $menu, $test ) {
			$orig = wp_list_pluck( $pages, 'post_title' );
			$sorted = $orig;
			natsort( $sorted );
			$test->assertEquals( $orig, $sorted, 'Pages were not ordered by title properly' );
			foreach( $pages as $page ){
				$children = $menu->get_child_pages( $page->ID );
				if( !empty( $children ) ){
					ordered_by_title( $children, $menu, $test );
				}
			}
		}

		ordered_by_title( $menu->get_child_pages( $this->top_parent ), $menu, $this );
	}


	public function test_order_by_menu_order() {
		$this->mock->instance[ 'order_by' ] = 'menu_order';

		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->mock );

		function ordered_by_menu_order( $pages, $menu, $test ) {
			$orig = wp_list_pluck( $pages, 'menu_order' );
			$sorted = $orig;
			sort( $sorted, SORT_NUMERIC );
			$test->assertEquals( $orig, $sorted, 'Pages were not ordered by menu order properly' );
			foreach( $pages as $page ){
				$children = $menu->get_child_pages( $page->ID );
				if( !empty( $children ) ){
					ordered_by_menu_order( $children, $menu, $test );
				}
			}
		}

		ordered_by_menu_order( $menu->get_child_pages( $this->top_parent ), $menu, $this );
	}

}
 