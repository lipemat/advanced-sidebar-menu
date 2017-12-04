<?php
/**
 * Advanced_Sidebar_Menu_Pro_Update.php
 *
 * @author  mat
 * @since   12/4/2017
 *
 * @package wordpress *
 */


class Advanced_Sidebar_Menu_Pro_UpdateTest extends WP_UnitTestCase {
	public function testCheckForUpdate() {
		$update = new AdvancedSidebarMenuProUpdate();
		$data = $update->checkForUpdate( $this->generate_transient_data() );
		$this->assertEquals( ADVANCED_SIDEBAR_MENU_PRO_VERSION, $data->response[ 'advanced-sidebar-menu-pro/advanced-sidebar-menu-pro.php' ]->version );
	}


	public function testPlugin_api_call() {
		add_filter( 'pre_site_transient_update_plugins', array( $this, 'generate_transient_data' ) );
		$result = apply_filters( 'plugins_api',
			'_x', 'plugin_information', (object) array( 'slug' => 'advanced-sidebar-menu-pro' ) );
		$this->assertEquals( ADVANCED_SIDEBAR_MENU_PRO_VERSION, $result->version );
	}


	public function generate_transient_data() {
		return (object) array(
			'checked' => array(
				'advanced-sidebar-menu-pro/advanced-sidebar-menu-pro.php' => '0.0.1',
			),
		);
	}
}
