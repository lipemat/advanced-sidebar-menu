<?php 


          /**
           * Creates a Widget of parent Child Categories
           * 
           * @author mat lipe
           * @since 1.7.14
           * @package Advanced Sidebar Menu
           *
           * @todo Clean this bad boy up. Still rookie code from years ago
           * 
           * 
           */
class advanced_sidebar_menu_category extends WP_Widget {
    
     private $defaults = array(
                           'title'                    => false,
                           'include_parent'           => false,
                           'include_childless_parent' => false,
                           'single'                   => false,
                           'css'                      => false,
                           'exclude'                  => false,
                           'new_widget'               => 'list',
                           'legacy_mode'              => false,
                           'display_all'              => false,
                           'levels'                   => 1
                        );


#-------------------------------------------------------------------------------------------------------------------------

    /**
     * Build the widget like a Baller
     * 
     * @since 8.1.13
     */
    function __construct() {
                /* Widget settings. */
        $widget_ops = array( 'classname' => 'advanced-sidebar-menu advanced-sidebar-category', 'description' => 'Creates a menu of all the Categories using the child/parent relationship' );
        $control_ops = array( 'width' => 290 );
        /* Create the widget. */
        $this->WP_Widget( 'advanced_sidebar_menu_category', 'Advanced Sidebar Categories Menu', $widget_ops, $control_ops );
        }




    /**
     * Creates a form for the Widget Options
     * 
     * @since 1.7.14
     * 
     * @param array $instance
     * 
     * @actions do_action('advanced_sidebar_menu_category_widget_form', $instance ); 
     */
    function form( $instance ) {
    
              $instance = wp_parse_args($instance, $this->defaults);
                 
            ?>
             <p> <?php _e('Title','advanced-sidebar-menu'); ?> <br>
             <input id="<?php echo $this->get_field_name('title'); ?>" 
                name="<?php echo $this->get_field_name('title'); ?>" class="widefat" type="text" value="<?php echo $instance['title']; ?>"/></p>
            
            
            <p> <?php _e( 'Include Parent Category','advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('include_parent'); ?>" 
                name="<?php echo $this->get_field_name('include_parent'); ?>" type="checkbox" value="checked" 
                <?php echo $instance['include_parent']; ?>/></p>
            
                        
            <p> <?php _e( 'Include Parent Even With No Children','advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('include_childless_parent'); ?>"
            name="<?php echo $this->get_field_name('include_childless_parent'); ?>" type="checkbox" value="checked" 
                    <?php echo $instance['include_childless_parent']; ?>/></p>
                    
            <p> <?php _e('Use this plugins styling','advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('css'); ?>"
            name="<?php echo $this->get_field_name('css'); ?>" type="checkbox" value="checked" 
                    <?php echo $instance['css']; ?>/></p>
                    
            <p> <?php _e( 'Display Categories on Single Posts','advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('single'); ?>"
            name="<?php echo $this->get_field_name('single'); ?>" type="checkbox" value="checked" 
            onclick="javascript:asm_reveal_element( 'new-widget-<?php echo $this->get_field_name('new_widget'); ?>' )"
                    <?php echo $instance['single']; ?>/></p>    
            
            <span id="new-widget-<?php echo $this->get_field_name('new_widget'); ?>" style="<?php 
                  if( $instance['single'] == 'checked' ){
                    echo 'display:block';
                  } else {
                    echo 'display:none';
                  } ?>">        
                 <p><?php _e("Display Each Single Post's Category",'advanced-sidebar-menu'); ?> 
                    <select id="<?php echo $this->get_field_name('new_widget'); ?>" 
                            name="<?php echo $this->get_field_name('new_widget'); ?>">
                    <?php 
                        if( $instance['new_widget'] == 'widget' ){
                            echo '<option value="widget" selected> In a new widget </option>';
                            echo '<option value="list"> In another list in the same widget </option>';
                        } else {
                            echo '<option value="widget"> In a new widget </option>';
                            echo '<option value="list" selected> In another list in the same widget </option>';
                        }
                    
                    ?></select>
                 </p>
            </span>
         
                
                    
            <p> <?php _e( "Categories to Exclude, Comma Separated", 'advanced-sidebar-menu'); ?>:<input id="<?php echo $this->get_field_name('exclude'); ?>" 
                name="<?php echo $this->get_field_name('exclude'); ?>" type="text" class="widefat" value="<?php echo $instance['exclude']; ?>"/></p>
            
            
            <p> <?php _e( "Legacy Mode: (use pre 4.0 structure and css)",'advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('legacy_mode'); ?>"
            name="<?php echo $this->get_field_name('legacy_mode'); ?>" type="checkbox" value="checked" 
                    <?php echo $instance['legacy_mode']; ?>/>
            </p>    
                
                
            <p> <?php _e("Always Display Child Categories",'advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('display_all'); ?>" 
                name="<?php echo $this->get_field_name('display_all'); ?>" type="checkbox" value="checked" 
                onclick="javascript:asm_reveal_element( 'levels-<?php echo $this->get_field_name('levels'); ?>' )"
                <?php echo $instance['display_all']; ?>/></p>
            
            <span id="levels-<?php echo $this->get_field_name('levels'); ?>" style="<?php 
                  if( $instance['display_all'] == 'checked' ){
                    echo 'display:block';
                  } else {
                    echo 'display:none';
                  } ?>"> 
            <p> <?php _e( "Levels to Display",'advanced-sidebar-menu'); ?> <select id="<?php echo $this->get_field_name('levels'); ?>" 
            name="<?php echo $this->get_field_name('levels'); ?>">
            <?php 
                for( $i= 1; $i<6; $i++ ){
                    if( $i == $instance['levels'] ){
                        echo '<option value="'.$i.'" selected>'.$i.'</option>';
                    } else {
                        echo '<option value="'.$i.'">'.$i.'</option>';
                    }
                } 
                echo '</select></p></span>';
                
                
           do_action('advanced_sidebar_menu_category_widget_form', $instance, $this );   
           
           do_action('advanced_sidebar_menu_after_widget_form', $instance, $this );     
                
        }


    /**
     * Updates the widget data
     * 
     * @filter - $newInstance = apply_filters('advanced_sidebar_menu_category_widget_update', $newInstance, $oldInstance );
     * @since 5.19.13
     */
    function update( $newInstance, $oldInstance ) {
            $newInstance['exclude'] = strip_tags($new_instance['exclude']);
            $newInstance = apply_filters('advanced_sidebar_menu_category_widget_update', $newInstance, $oldInstance );

            return $newInstance;
        }




#---------------------------------------------------------------------------------------------------------------------------

    /**
     * Outputs the categories widget to the page
     * 
     * @since 11.15.13
     * @uses loads the views/category_list.php
     * 
     * @filters apply_filters('advanced_sidebar_menu_category_widget_output', $content, $args, $instance );
     *           apply_filters('advanced_sidebar_menu_taxonomy', 'post', $args, $instance ); 
     *           apply_filters('advanced_sidebar_menu_proper_single', $asm->checked('single'), $args, $instance)
     *           apply_filters( 'advanced_sidebar_menu_category_ids', $cat_ids, $args, $instance )
     * 
     */
    function widget($args, $instance) {
        
        $defaults = array(
            'title'                    => '',
            'include_parent'           => false,
            'include_childless_parent' => false,
            'css'                      => false, 
            'single'                   => false,
            'new_widget'               => 'widget',
            'exclude'                  => '',
            'legacy_mode'              => false,      
            'display_all'              => false,
            'levels'                   => 1,
            'order'                    => 'DESC'
            );
            
        $instance = wp_parse_args( $instance, $defaults);
        
        
        
        if( is_single() && !isset( $instance['single'] ) ) return;
        $asm = new advancedSidebarMenu;
        $asm->instance = $instance;
        $asm->args = $args;
        
        //Had to display twice for backward compat - because originaly not set to anything
        $asm->order_by = apply_filters('advanced_sidebar_menu_category_orderby', null, $args, $instance );
           
        do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );  
        
        $legacy = $asm->checked('legacy_mode');

        $cat_ids = $already_top = array();
        $asm_once = false; //keeps track of how many widgets this created

        
        $exclude = explode(',', $instance['exclude']);
        $asm->exclude = $exclude;
        
        $asm->taxonomy = apply_filters('advanced_sidebar_menu_taxonomy', 'category', $args, $instance );
        
        extract( $args);
        
        //If on a single page create an array of each category and create a list for each
        if( is_single() ){
            if( !apply_filters('advanced_sidebar_menu_proper_single', $asm->checked('single'), $args, $instance) ) return;
            global $post;
            $category_array = wp_get_object_terms($post->ID, $asm->taxonomy);
           
            //Sort by a field like term order for other plugins
            $asm->order_by = apply_filters('advanced_sidebar_menu_category_orderby', 'name', $args, $instance );

            uasort( $category_array, array( $asm, 'sortTerms'));

            foreach( $category_array as $id => $cat ){
                $cat_ids[] = $cat->term_id;
            }
            
        //IF on a category page get the id of the category
        } elseif( is_tax() || is_category() ){
            
            $asm->current_term = get_queried_object()->term_id;
            $cat_ids[] = get_queried_object()->term_id;
        }
        

        
        $cat_ids = apply_filters( 'advanced_sidebar_menu_category_ids', $cat_ids, $args, $instance );

        if( empty( $cat_ids ) ) return;

        //Go through each category there will be only one if this is a category page mulitple possible if this is single
        foreach( $cat_ids as $cat_id ){
            
             //Get the top category id
             $asm->top_id = $asm->getTopCat($cat_id);
            
             //Keeps track or already used top levels so this won't double up
             if( in_array( $asm->top_id, $already_top ) ) continue;
             
             $already_top[] = $asm->top_id;

            //Check for children
            $all_categories = $all = array_filter( 
                get_terms( 
                    $asm->taxonomy, array( 
                              'child_of' => $asm->top_id, 
                              'orderby' => $asm->order_by,
                              'order'   => $instance['order']
                    )
                ) 
            );

            //For Backwards Compatibility
            foreach( $all_categories as $tc ){
               $tc->cat_ID = $tc->term_id;   
            }    
            

            
            //If there are no children and not displaying childless parent - bail
            if( empty($all_categories ) && !( $asm->checked('include_childless_parent') ) ) continue;
            //If there are no children and the parent is excluded bail
            if( empty($all_categories ) && in_array($asm->top_id, $exclude) ) continue;
                
                    
            //Creates a new widget for each category the single page has if the options are selected to do so
            //Also starts the first widget
            if( !$asm_once || ($instance['new_widget'] == 'widget') ){
                
                //Start the menu
                echo $before_widget;
                   if( !$asm_once ) {
                       $asm->title();
                       if( $asm->checked('css') ){
                            echo '<style type="text/css">';
                            include( $asm->file_hyercy('sidebar-menu.css', $legacy ) );
                            echo '</style>';
                        }

                    $asm_once = true;  //There has been a div
                    $close = true; //The div should be closed at the end
    
                    if($instance['new_widget'] == 'list'){
                        $close = false;  //If this is a list leave it open for now
                    } 
                   }                    
             }
            
            //for deprecation
            $top_cat = $asm->top_id;
            $cat_ancestors = $asm->ancestors;
            
            //Bring in the view
            require( $asm->file_hyercy( 'category_list.php', $legacy ) );
                              
            echo apply_filters('advanced_sidebar_menu_category_widget_output', $content, $args, $instance );        
      
            if( $close ){
                //End the Widget Area
                echo $after_widget;
                echo '<!-- First $after_widget -->';
            }
                    

        } //End of each cat loop
        
        
        //IF we were waiting for all the individual lists to complete
        if( !$close && $asm_once ){
            //End the Widget Area
            echo $after_widget;
            echo '<!-- Second $after_widget -->';
            
        }
            
    
             
    } #== /widget()
    
} #== /Clas