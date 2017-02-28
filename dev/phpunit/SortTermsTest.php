<?php


/**
 * SortTermsTest.php
 *
 * @author  mat
 * @since   2/28/2017
 *
 * @package wordpress *
 */
class SortTermsTest extends WP_UnitTestCase {

	private $names = array(
		2016,
		2017,
		2015,
		2013,
		3000,
		2014,
	);


	public function test_sort_numeric(){
		foreach( $this->names as $_name ){
			$term = $this->factory()->category->create_and_get( array( 'name' => (string) $_name ) );
			$this->factory()->category->add_post_terms( 1, $term->term_id, 'category', true );
		}

		$category_array = wp_get_object_terms( 1, 'category' );

		$asm           = new Advanced_Sidebar_Menu_Menu;
		$asm->order_by = 'slug';
		$asm->order    = 'DESC';
		usort( $category_array, array( $asm, 'sortTerms' ) );

		$this->assertEquals( $category_array[ 1 ]->name, '3000', 'Cat sorting not working' );

		$asm->order = 'ASC';
		usort( $category_array, array( $asm, 'sortTerms' ) );

		$this->assertEquals( $category_array[ 0 ]->name, '2013', 'Cat sorting not working' );

	}
}
