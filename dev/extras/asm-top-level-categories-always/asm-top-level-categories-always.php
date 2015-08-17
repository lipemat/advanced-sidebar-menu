<?php 
 
/**
 * Will display all top level categories always
 * Will only display child categories if on the parent or child of parent
 * 
 * This will affect the second row as well, instead of the default 3rd row only
 */ 
 
add_filter( 'advanced_sidebar_menu_category_ids', array( $this, 'allTopCategories' ) );
     function allTopCategories($term_ids){
         //Once inside the function it grabs all the product_category ids             
         $terms =  get_terms(
                        'product_category',
                              array(
                                  'order_by' => 'term_order',
                                   )
                          );
       
         //Create a keyed array to avoid additional database calls
         foreach( $terms as $k => $t ){
            if( in_array( $t->term_id, $term_ids ) ){
                $keyed_terms[$t->term_id] = $t;   
            }
         }

         foreach( $terms as $k => $t ){    
            //If this is a sub category we don't care about it
            if( $t->parent != 0 ) {
                continue;
            }
            
            //remove it from the original array to prevent duplicates
            if( in_array( $t->term_id, $term_ids ) ){
                 unset( $term_ids[array_search($t->term_id, $term_ids)] );
            }
            
            //Put the child term first if it exists which will make this work later
            foreach( $term_ids as $id ){
                if( $keyed_terms[$id]->parent == $t->term_id ){
                    $sorted_terms[] = $keyed_terms[$id]->term_id;
                    break;
                } 
            }
            $sorted_terms[] =  $t->term_id; 

         }
 
          return array_filter($sorted_terms);
       }
     


add_filter('advanced_sidebar_menu_first_level_category', array($this,'filterChildCategories'), 1, 3 );
     /**
      * Filters out sub categories from displaying when not on the parent category
      */
     function filterChildCategories($return, $cat, $obj){
        
        
         if( !in_array( get_queried_object()->term_id, $obj->ancestors ) ){
                 
             return false;
         } else {
             return true;
         }
     }     