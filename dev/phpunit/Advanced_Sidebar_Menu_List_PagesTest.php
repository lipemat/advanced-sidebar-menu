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

	public $default_args = array(
		'depth'       => 1,
		'child_of'    => 2, //sample-page
		'exclude'     => '',
		'echo'        => 0,
		'sort_column' => 'menu_order, post_title'
	);

	public function setUp() {
		parent::setUp();
		switch_to_blog( 3 );
	}

}
 