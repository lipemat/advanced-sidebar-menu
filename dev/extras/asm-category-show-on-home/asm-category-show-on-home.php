<?php

    add_filter('advanced_sidebar_menu_category_ids','asm_show_on_home');
        function asm_show_on_home($catIds){
            if( !is_home() ) return $catIds;
           
            $cats = get_categories();
            return wp_list_pluck( $cats, 'term_id' );
           
        }