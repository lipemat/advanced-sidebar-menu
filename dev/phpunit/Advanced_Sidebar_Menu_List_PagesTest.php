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
	private $menu;

	private $ids = array();


	public function setUp() {
		parent::setUp();
		switch_to_blog( 3 );

		$this->menu = Advanced_Sidebar_Menu_Menus_Page::factory( $this->default_args, array() );
		$menu_orders = range( 1, 14 );
		shuffle( $menu_orders );

		$pages = $this->factory()->post->create_many( 7, array(
			'post_type' => 'page',
		) );
		$top = array_shift( $pages );
		$this->ids[] = $top;

		foreach( $pages as $id ){
			$this->ids[] = $id;
			wp_update_post( array(
				'ID'          => $id,
				'post_parent' => $top,
				'menu_order'  => array_shift( $menu_orders ),
			) );
		}
		$pages = $this->factory()->post->create_many( 7, array(
			'post_type' => 'page',
		) );

		foreach( $pages as $id ){
			$this->ids[] = $id;
			wp_update_post( array(
				'ID'          => $id,
				'post_parent' => $this->ids[ 2 ],
				'menu_order'  => array_shift( $menu_orders ),
			) );
		}
		$this->menu->set_current_post( get_post( $id ) );
	}


	public function test_posts_per_page() {
		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );
		$this->assertCount( 6, $menu->get_child_pages( $this->menu->get_top_parent_id(), true ), 'Not returning enough children. Probably posts_per_page no set' );
	}


	public function test_excluded_pages() {
		$this->menu->instance[ 'exclude' ] = "{$this->ids[3]},{$this->ids[5]}, ";

		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );

		function not_contains_page_id( $pages, Advanced_Sidebar_Menu_List_Pages $menu, Advanced_Sidebar_Menu_List_PagesTest $test, $not_contain_1, $not_contain_2 ) {
			$pages = wp_list_pluck( $pages, 'ID' );
			$test->assertNotContains( $not_contain_1, $pages, 'an excluded page is present' );
			$test->assertNotContains( $not_contain_2, $pages, 'an excluded page is present' );
			foreach( $pages as $page ){
				$children = $menu->get_child_pages( $page );
				if( !empty( $children ) ){
					not_contains_page_id( $children, $menu, $test, $not_contain_1, $not_contain_2 );
				}
			}
		}

		not_contains_page_id( $menu->get_child_pages( $this->top_parent ), $menu, $this, $this->ids[ 3 ], $this->ids[ 5 ] );

	}


	public function test_order_by_title() {
		$this->menu->instance[ Advanced_Sidebar_Menu_Menus_Page::ORDER_BY ] = 'title';

		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );

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
		$this->menu->instance[ Advanced_Sidebar_Menu_Menus_Page::ORDER_BY ] = 'menu_order';

		$menu = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );

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


	public function test_levels_to_display() {
		$this->menu->instance[ Advanced_Sidebar_Menu_Menus_Page::LEVELS ] = 1;
		$this->menu->instance[ Advanced_Sidebar_Menu_Menus_Page::DISPLAY_ALL ] = 'checked';
		$list = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );
		$args = $list->get_args( 'display-all' );

		$this->assertTrue( $this->menu->display_all() );
		$this->assertEquals( 1, $args[ 'depth' ] );

		$document = new DOMDocument();
		$document->loadHTML( wp_list_pages( $args ) );
		foreach( $document->getElementsByTagName( 'li' ) as $element ){
			/** @var \DOMElement $element */
			$this->assertEmpty( $element->getElementsByTagName( 'ul' )->item( 0 ) );
		}

		$this->menu->instance[ Advanced_Sidebar_Menu_Menus_Page::LEVELS ] = 2;
		$list = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );
		$args = $list->get_args( 'display-all' );

		$this->assertTrue( $this->menu->display_all() );
		$this->assertEquals( 2, $args[ 'depth' ] );

		$document = new DOMDocument();
		$document->loadHTML( wp_list_pages( $args ) );
		foreach( $document->getElementsByTagName( 'li' ) as $element ){
			//only the id[2] child has children
			/** @var \DOMElement $element $ul */
			if( $element->getAttribute( 'class' ) === "page_item page-item-{$this->ids[2]} page_item_has_children has_children" ){
				$ul = $element->getElementsByTagName( 'ul' )->item( 0 );
				$this->assertNotEmpty( $ul );
				$this->assertEmpty( $ul->getElementsByTagName( 'ul' )->item( 0 ) );
			} else {
				$this->assertEmpty( $element->getElementsByTagName( 'ul' )->item( 0 ) );
			}
		}
	}


	public function test_menu_depth() {
		$this->menu->instance[ 'level_limit' ] = 1;
		do_action( 'advanced_sidebar_menu_widget_pre_render', $this->menu, new Advanced_Sidebar_Menu_Widget_Page() );
		$list = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );

		$document = new DOMDocument();
		$document->loadHTML( $list->list_pages() );
		foreach( $document->getElementsByTagName( 'li' ) as $element ){
			/** @var \DOMElement $element */
			$this->assertEmpty( $element->getElementsByTagName( 'ul' )->item( 0 ) );
		}

		$this->menu->instance[ 'level_limit' ] = 2;
		do_action( 'advanced_sidebar_menu_widget_pre_render', $this->menu, new Advanced_Sidebar_Menu_Widget_Page() );
		$list = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );

		$document = new DOMDocument();
		$document->loadHTML( $list->list_pages() );
		foreach( $document->getElementsByTagName( 'li' ) as $element ){
			//only the id[2] child has children
			/** @var \DOMElement $element $ul */
			if( $element->getAttribute( 'class' ) === "page_item page-item-{$this->ids[2]} current_page_ancestor current_page_parent has_children" ){
				$ul = $element->getElementsByTagName( 'ul' )->item( 0 );
				$this->assertNotEmpty( $ul );
				$this->assertEmpty( $ul->getElementsByTagName( 'ul' )->item( 0 ) );
			} else {
				$this->assertEmpty( $element->getElementsByTagName( 'ul' )->item( 0 ) );
			}
		}

	}
}
 