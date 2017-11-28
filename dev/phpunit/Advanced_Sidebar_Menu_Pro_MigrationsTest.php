<?php
/**
 * Advanced_Sidebar_Menu_Pro_MigrationsTest.php
 *
 * @author  mat
 * @since   10/23/2017
 *
 * @package wordpress *
 */


class Advanced_Sidebar_Menu_Pro_MigrationsTest extends WP_UnitTestCase {

	public function test_exclude_pages_migration(){
		$o = new Advanced_Sidebar_Menu_Pro_Migrations();
		//random posts
		$ids = get_posts( 'fields=ids&post_type=page' );
		foreach( $ids as $id ){
			update_post_meta( $id, '_exclude_page', false );
		}
		update_post_meta( $ids[1], '_exclude_page', true );
		update_post_meta( $ids[3], '_exclude_page', true );


		delete_option( Advanced_Sidebar_Menu_Pro_Migrations::DB_OPTION );
		$o->run_migrations();
		foreach( $ids as $k => $id ){
			$meta = get_post_custom( $id );
			$this->assertNotContains( '_exclude_page', $meta, 'empty excluded pages key not not deleting' );
			if( $k === 1 || $k === 3 ){
				$this->assertContains( AdvancedSidebarMenuProTitle::EXCLUDE_PAGE, array_keys( $meta ), 'new exclude page key not migrating over' );
			} else {
				$this->assertNotContains( AdvancedSidebarMenuProTitle::EXCLUDE_PAGE, array_keys( $meta ), "new excluded pages key exists where it shouldn't" );
			}
		}

	}
}
