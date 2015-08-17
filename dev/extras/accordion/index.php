<?php
/**
 * Adjusts advanced sidebar menu to work in a custom way
 * 
 * @uses Must have the widget set to show all subs
 * 
 * @since 9.26.13
 */
function nusd_sidebar_menu_arterations(){
    ?>
    <script type="text/javascript">
        jQuery( function($){
            $('.advanced-sidebar-menu .children ul').hide();  
            $('.advanced-sidebar-menu .children .has_children > a').before('<span class="plus">+</span>');
           
            $('.advanced-sidebar-menu .plus').click( function(){
                var e = $(this).next().next();
                e.slideToggle();
                if( $(this).text() == '-' ){
                    $(this).text('+');
                } else {
                    $(this).text('-');   
                }
            });
            
            $('.advanced-sidebar-menu .children .has_children.current_page_item,.advanced-sidebar-menu .children .has_children.current_page_ancestor').find('.plus').click();
           
        });    
    </script> 
    <?php
}

