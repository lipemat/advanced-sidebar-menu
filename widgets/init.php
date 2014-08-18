<?php 

          /**
           * Registers the widgets
           * @author Mat Lipe
           * @since 4/13/12
           * @package Advanced Sidebar Menu
           * 
           */
          
//The list of widgets

require( 'page.widget.php' );
require( 'category.widget.php' );
	
add_action( 'widgets_init', 'advanced_sidebar_menu_widgets' );

function advanced_sidebar_menu_widgets(){ 
	
	register_widget( "advanced_sidebar_menu_page" );
	register_widget( "advanced_sidebar_menu_category" );
	
}
	
