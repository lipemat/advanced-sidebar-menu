<?php

/**
 * Advanced_Sidebar_Menu_List_PagesTest.php
 *
 * @author  mat
 * @since   8/18/14
 *
 * @package wordpress
 */
class Advanced_Sidebar_Menu_List_PagesTest extends WP_UnitTestCase {

	private $top_parent = 2; //sample-page

	public $default_args = array(
		'depth'        => 1,
		'exclude'      => '',
		'echo'         => 0,
		'sort_column'  => 'menu_order, post_title',
		'hierarchical' => 0,
		'link_before'  => '',
		'link_after'   => '',
		'title_li'     => '',
	);

	public function setUp() {
		parent::setUp();
		switch_to_blog( 3 );
	}

	public function test_excluded_pages(){
		$args = $this->default_args;
		$args[ 'exclude' ] = "7,19,";

		$menu = new Advanced_Sidebar_Menu_List_Pages( $this->top_parent, $args );

		function not_contains_page_id( $pages, $menu, $test ){
			$pages = wp_list_pluck( $pages, 'ID' );
			$test->assertNotContains( 7, $pages, "an excluded page is present" );
			$test->assertNotContains( 19, $pages, "an excluded page is present" );
			foreach( $pages as $page ){
				$children = $menu->get_child_pages( $page );
				if( !empty( $children ) ){
					not_contains_page_id( $children, $menu, $test );
				}
			}
		}

		not_contains_page_id( $menu->get_child_pages( $this->top_parent ), $menu, $this );

	}


	public function test_order_by_title(){
		$args = $this->default_args;
		$args[ 'sort_column' ] = "title";

		$menu = new Advanced_Sidebar_Menu_List_Pages( $this->top_parent, $args );

		function ordered_by_title( $pages, $menu, $test ){
			$orig = wp_list_pluck( $pages, 'post_title' );
			$sorted = $orig;
			sort( $sorted );
			$test->assertEquals( $orig, $sorted, "Pages were not ordered by title properly" );
			foreach( $pages as $page ){
				$children = $menu->get_child_pages( $page->ID );
				if( !empty( $children ) ){
					ordered_by_title( $children, $menu, $test );
				}
			}
		}

		ordered_by_title( $menu->get_child_pages( $this->top_parent ), $menu, $this );
	}


	public function test_order_by_menu_order(){
		$args = $this->default_args;
		$args[ 'sort_column' ] = "menu_order";

		$menu = new Advanced_Sidebar_Menu_List_Pages( $this->top_parent, $args );

		function ordered_by_menu_order( $pages, $menu, $test ){
			$orig = wp_list_pluck( $pages, 'menu_order' );
			$sorted = $orig;
			sort( $sorted, SORT_NUMERIC );
			$test->assertEquals( $orig, $sorted, "Pages were not ordered by menu order properly" );
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
 