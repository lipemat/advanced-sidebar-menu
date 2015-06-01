<?php
/*
Plugin Name: Advanced Sidebar Menu
Plugin URI: http://matlipe.com/advanced-sidebar-menu/
Description: Creates dynamic menu based on child/parent relationship.
Author: Mat Lipe
Version: 5.0.7
Author URI: http://matlipe.com
*/

define( 'ADVANCED_SIDEBAR_BASIC_VERSION', '5.0.7' );


#-- Define Constants
define( 'ADVANCED_SIDEBAR_DIR', plugin_dir_path(__FILE__) );
define( 'ADVANCED_SIDEBAR_WIDGETS_DIR', ADVANCED_SIDEBAR_DIR . 'widgets/' );
define( 'ADVANCED_SIDEBAR_VIEWS_DIR', ADVANCED_SIDEBAR_DIR . 'views/' );
define( 'ADVANCED_SIDEBAR_LEGACY_DIR', ADVANCED_SIDEBAR_DIR . 'legacy/' );


#-- Bring in the Widgets
require( ADVANCED_SIDEBAR_WIDGETS_DIR.'init.php' );
#-- Bring in the functions
require( ADVANCED_SIDEBAR_DIR.'classes/Advanced_Sidebar_Menu_Deprecated.php' );
require( ADVANCED_SIDEBAR_DIR.'classes/advancedSidebarMenu.php' );
require( ADVANCED_SIDEBAR_DIR.'classes/Advanced_Sidebar_Menu_Page_Walker.php' );
require( ADVANCED_SIDEBAR_DIR.'classes/Advanced_Sidebar_Menu_List_Pages.php' );
$asm = new advancedSidebarMenu();

#-- Translate
add_action('plugins_loaded', 'advanced_sidebar_menu_translate' );
function advanced_sidebar_menu_translate(){
    load_plugin_textdomain('advanced-sidebar-menu', false, 'advanced-sidebar-menu/languages');
}



 #-- Bring in the JQuery
add_action('admin_print_scripts', 'advanced_sidebar_menu_script');
function advanced_sidebar_menu_script() {
        wp_enqueue_script(
            apply_filters('asm_script', 'advanced-sidebar-menu-script'), 
            plugins_url('js/advanced-sidebar-menu.js', __FILE__), 
            array('jquery'), 
            ADVANCED_SIDEBAR_BASIC_VERSION
        );
};


#-- Let know about new Pro Version
add_action( 'advanced_sidebar_menu_after_widget_form', 'advanced_sidebar_menu_pro_notice' );
function advanced_sidebar_menu_pro_notice(){
    if( defined( 'ADVANCED_SIDEBAR_MENU_PRO_VERSION' ) ) return;
    ?>
        <fieldset style="border: 1px solid black; border-radius: 10px; padding: 10px;">
            <legend style="font-size: 14px; font-weight: bold;"><?php _e('Want More Options','advanced-sidebar-menu'); ?>?</legend>
                <p>
                    <strong><big><a target="blank" href="http://matlipe.com/product/advanced-sidebar-menu-pro/"><?php _e('Go Pro', 'advanced-sidebar-menu'); ?>!</a></big></strong>
                <p>
        </fieldset>
  <?php
}




