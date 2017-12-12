<?php
/**
 * Pro_Widget_Options_Category_Taxonomy_Test.php
 *
 * @author  mat
 * @since   12/12/2017
 *
 * @package wordpress *
 */


class Pro_Widget_Options_Category_Taxonomy_Test extends WP_UnitTestCase {
	const TAX = 'product_cat';

	public $default_args = array(
		Advanced_Sidebar_Menu_Widget_Category::INCLUDE_PARENT           => 1,
		Advanced_Sidebar_Menu_Widget_Category::INCLUDE_CHILDLESS_PARENT => 1,
		Advanced_Sidebar_Menu_Widget_Category::DISPLAY_ON_SINGLE        => 1
	);

	/**
	 * o
	 *
	 * @var \Advanced_Sidebar_Menu_Pro_Widget_Options_Category_Taxonomy
	 */
	private $o;

	/**
	 * menu
	 *
	 * @var \Advanced_Sidebar_Menu_Menus_Category
	 */
	private $menu;

	private $top_term;

	private $first_level_term;

	private $first_level_terms;

	private $second_level_terms;

	private $post;


	public function setUp() {
		parent::setUp();
		$this->reset_taxonomies();
		$this->register_woo_product_cats();

		$this->o = Advanced_Sidebar_Menu_Pro_Widget_Options_Category_Taxonomy::instance();
		$this->menu = Advanced_Sidebar_Menu_Menus_Category::factory( $this->default_args, array() );
		$this->menu->instance[ Advanced_Sidebar_Menu_Pro_Widget_Options_Category_Taxonomy::NAME ] = self::TAX;
	}


	public function test_get_taxonomies() {
		$taxonomies = $this->o->get_taxonomies();
		$this->assertCount( 1, $taxonomies );
		$this->assertEquals( 'product_cat', key( $taxonomies ) );
	}


	public function test_child_taxonomies() {
		$this->menu->set_current_term( get_term( $this->top_term ) );
		$this->assertCount( count( $this->first_level_terms ), $this->menu->get_child_terms() );

		$this->menu->set_current_term( get_term( $this->first_level_term ) );
		$this->assertCount( count( $this->second_level_terms ), $this->menu->get_child_terms() );
	}


	public function test_top_level_terms() {
		global $wp_query;
		$wp_query->is_single = true;
		$GLOBALS[ 'post' ] = $this->post;
		$this->assertEquals( array( get_term( $this->top_term ) ), $this->menu->get_top_level_terms() );

		unset( $GLOBALS[ 'post' ] );
		$this->assertEmpty( $this->menu->get_top_level_terms() );

		$wp_query->is_single = false;
		$wp_query->is_tax = true;
		$wp_query->queried_object = get_term( $this->first_level_term );

		$this->assertEquals( array( get_term( $this->top_term ) ), $this->menu->get_top_level_terms() );

	}


	public function test_is_displayed() {
		global $wp_query;
		$wp_query->is_single = true;
		$GLOBALS[ 'post' ] = $this->post;

		$this->menu->instance[ Advanced_Sidebar_Menu_Widget_Category::DISPLAY_ON_SINGLE ] = false;
		$this->assertFalse( $this->menu->is_displayed() );

		$this->menu->instance[ Advanced_Sidebar_Menu_Widget_Category::DISPLAY_ON_SINGLE ] = true;
		$this->assertFalse( $this->menu->is_displayed() );

		$wp_query->is_single = false;
		$wp_query->is_tax = true;
		$wp_query->queried_object = get_term( $this->first_level_term );
		$this->menu->instance[ Advanced_Sidebar_Menu_Widget_Category::DISPLAY_ON_SINGLE ] = false;
		$this->assertTrue( $this->menu->is_displayed() );

	}


	/**
	 * Exact replica of woocommerce product cat
	 * Version 3.2.5
	 *
	 * @return void
	 */
	private function register_woo_product_cats() {
		register_taxonomy( self::TAX,
			apply_filters( 'woocommerce_taxonomy_objects_product_cat', array( 'product' ) ),
			apply_filters( 'woocommerce_taxonomy_args_product_cat', array(
				'hierarchical' => true,
				'label'        => __( 'Categories', 'woocommerce' ),
				'labels'       => array(
					'name'              => __( 'Product categories', 'woocommerce' ),
					'singular_name'     => __( 'Category', 'woocommerce' ),
					'menu_name'         => _x( 'Categories', 'Admin menu name', 'woocommerce' ),
					'search_items'      => __( 'Search categories', 'woocommerce' ),
					'all_items'         => __( 'All categories', 'woocommerce' ),
					'parent_item'       => __( 'Parent category', 'woocommerce' ),
					'parent_item_colon' => __( 'Parent category:', 'woocommerce' ),
					'edit_item'         => __( 'Edit category', 'woocommerce' ),
					'update_item'       => __( 'Update category', 'woocommerce' ),
					'add_new_item'      => __( 'Add new category', 'woocommerce' ),
					'new_item_name'     => __( 'New category name', 'woocommerce' ),
					'not_found'         => __( 'No categories found', 'woocommerce' ),
				),
				'show_ui'      => true,
				'query_var'    => true,
				'capabilities' => array(
					'manage_terms' => 'manage_product_terms',
					'edit_terms'   => 'edit_product_terms',
					'delete_terms' => 'delete_product_terms',
					'assign_terms' => 'assign_product_terms',
				),
			) )
		);
		register_taxonomy_for_object_type( self::TAX, 'post' );

		$terms = $this->factory()->tag->create_many( 10, array( 'taxonomy' => self::TAX ) );
		$this->top_term = array_shift( $terms );
		//set all first level terms parent to $this->top_term
		for( $i = 0; $i < 5; $i ++ ){
			$_term_id = array_shift( $terms );
			$this->first_level_term = $_term_id;
			$this->first_level_terms[] = $_term_id;
			wp_update_term( $_term_id, self::TAX, array(
				'parent' => $this->top_term,
			) );
		}
		//set the rest's parent to $this->first_level_term
		foreach( $terms as $_term_id ){
			wp_update_term( $_term_id, self::TAX, array(
				'parent' => $this->first_level_term,
			) );
			$this->second_level_terms[] = $_term_id;
		}

		$this->post = $this->factory()->post->create_and_get();
		wp_set_object_terms( $this->post->ID, array_merge( array( $this->top_term ), $this->second_level_terms, $this->first_level_terms ), self::TAX );

	}
}
