<?php
/**
 * Advanced_Sidebar_Menu_Pro_Post_TypeTest.php
 *
 * @author  mat
 * @since   10/9/2017
 *
 * @package wordpress *
 */


class Advanced_Sidebar_Menu_Pro_Post_TypeTest extends WP_UnitTestCase {
	/**
	 * o
	 *
	 * @var \Advanced_Sidebar_Menu_Pro_Post_Type
	 */
	private $o;

	public function setUp() {
		parent::setUp();
		$this->reset_post_types();
		require_once ADVANCED_SIDEBAR_MENU_PRO_DIR . 'src/widget-options/page/Advanced_Sidebar_Menu_Pro_Post_Type.php';
		$this->o = new Advanced_Sidebar_Menu_Pro_Post_Type();
	}


	public function test_available_post_types(){
		register_post_type( 'test', array(
			'hierarchical' => true,
		) );
		$post_types = call_private_method( $this->o, 'get_post_types' );
		$this->assertCount( 1, $post_types, print_r( $post_types, true ) );
	}

	public function test_available_post_type_conditions(){
		$post_types = call_private_method( $this->o, 'get_post_types' );
		$this->assertCount( 0, $post_types, print_r( $post_types, true ) );
		ob_start();
		$this->o->WidgetOutput( 'fake', array() );
		$this->assertEmpty( ob_get_clean(), 'Outputting for no reason' );
	}
}
