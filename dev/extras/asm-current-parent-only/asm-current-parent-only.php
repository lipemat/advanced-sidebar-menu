<?php
/*
Plugin Name: Advanced Sidebar Menu Current Parent Only Addon
Plugin URI: http://matlipe.com/advanced-sidebar-menu/
Description: Adds an option to only display the direct ancestors of the current Page
Author: Mat Lipe
Version: 1.0.1
Author URI: http://matlipe.com
Since: 9.24.13
*/

add_filter('advanced_sidebar_menu_child_pages', 'asm_current_parent_only_child_pages', 1, 5);
function asm_current_parent_only_child_pages( $child_pages, $post, $args, $instance, $asm ){
    
    if( !isset( $instance['parent_only'] ) || ($instance['parent_only'] != 'checked') || ($post->post_parent == 0) ) return $child_pages;
    
    $exclude;
    foreach( $child_pages as $key => $id ){
        if( !$asm->page_ancestor($id) ){
            unset( $child_pages[$key] );
            $asm->exclude[] = $id->ID;
        }
    }

    return $child_pages;
}

add_action('advanced_sidebar_menu_page_widget_form', 'asm_current_parent_only_widget_form', 1, 3 );
function asm_current_parent_only_widget_form($instance, $name, $id){
    ?>
        <p class="update-nag"> Display Current Pages Parent Only: 
        
            <input id="<?php echo $id; ?>" 
                name="<?php echo $name; ?>" 
                type="checkbox" 
                value="checked" 
                <?php echo $instance['parent_only']; ?>
            />
         </p>
    <?php
        
}
    