<?php
/**
 * Advanced_Sidebar_Menu_CacheTest.php
 *
 * @author  mat
 * @since   11/27/2017
 *
 * @package wordpress *
 */


class Advanced_Sidebar_Menu_CacheTest extends WP_UnitTestCase {
	private $menu;

	/**
	 * o
	 *
	 * @var \Advanced_Sidebar_Menu_Cache
	 */
	private $o;

	public $default_args = array(
		'post_type' => 'page',
		'exclude'   => '',
		'order_by'  => 'menu_order, post_title',
		'order'     => 'ASC',
		'levels'    => 0
	);

	public function setUp() {
		parent::setUp();
		$this->menu = Advanced_Sidebar_Menu_Menus_Page::factory( $this->default_args, array() );
		$this->menu->set_current_post( $this->factory()->post->create_and_get());
		$this->o = Advanced_Sidebar_Menu_Cache::instance();
	}


	public function test_cache_flush(){
		$lp = Advanced_Sidebar_Menu_List_Pages::factory( $this->menu );
		$this->assertFalse( $this->o->get_child_pages( $lp ) );
		$this->o->add_child_pages( $lp, array(true) );
		$this->assertNotEmpty( $this->o->get_child_pages( $lp ) );

		$this->o->clear_cache_group();
		$this->assertFalse( $this->o->get_child_pages( $lp ) );
		$this->o->add_child_pages( $lp, array(true) );
		$this->assertNotEmpty( $this->o->get_child_pages( $lp ) );

	}

}
