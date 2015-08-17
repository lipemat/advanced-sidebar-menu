<?php


########### Work in Progress #################

add_filter( 'advanced_sidebar_menu_top_parent', 'asm_change_top_page' );
function asm_change_top_page($topId){
    if( is_category(1) ){
        return 39;
    } else {
        return $topId;
    }   
    
}


add_filter( 'advanced_sidebar_menu_proper_single', 'asm_allow_menu_on_archive' );
function asm_allow_menu_on_archive($single){
     if( is_category(1) ){ 
        return false;   
     } else {
        return $single;
     }
}
