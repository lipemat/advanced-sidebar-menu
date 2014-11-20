<?php 
          /**
           * Advanced Sidebar Menu Page
		   * 
		   * Creates a Widget of parent Child Pages
           * 
           * @author mat lipe <mat@matlipe.com>
		   * 
           * @package Advanced Sidebar Menu
           * @class  advanced_sidebar_menu_page
		   * 
           */
class advanced_sidebar_menu_page extends WP_Widget {
    
    private $defaults = array(
                           'title'                    => false,
                           'include_parent'           => false,
                           'include_childless_parent' => false,
                           'order_by'                 => 'menu_order',
                           'css'                      => false,
                           'exclude'                  => false,
                           'legacy_mode'              => false,
                           'display_all'              => false,
                           'levels'                   => 1
                        );
    
    

    /**
     * Build the widget like a BOSS
     * 
     * @since 4.5.13
     * 
     */
    function __construct() {
        /* Widget settings. */
        $widget_ops = array( 
        	'classname' => 'advanced-sidebar-menu', 
        	'description' => __('Creates a menu of all the pages using the child/parent relationship', 'advanced-sidebar-menu') 
		);
        $control_ops = array( 
        	'width' => 290 
		);

        /* Create the widget. */
        $this->WP_Widget( 'advanced_sidebar_menu', __('Advanced Sidebar Pages Menu','advanced-sidebar-menu'), $widget_ops, $control_ops);
		
    }
    
    
    /**
     * Output a simple widget Form
     * Not of ton of options here but who need them
     * Most of the magic happens automatically
     * 
     * @filters do_action('advanced_sidebar_menu_page_widget_form', $instance, $this->get_field_name('parent_only'), $this->get_field_id('parent_only'));
     * 
     * @since 11.4.13
     */
    function form( $instance ) {
        
        $instance = wp_parse_args($instance, $this->defaults);
        
         ?>
            <p> <?php _e('Title','advanced-sidebar-menu'); ?> <br>
             <input id="<?php echo $this->get_field_id('title'); ?>" 
                name="<?php echo $this->get_field_name('title'); ?>" class="widefat" type="text" value="<?php echo $instance['title']; ?>"/></p>

            <p> <?php _e('Include Parent Page','advanced-sidebar-menu'); ?>: <input id="<?php echo $this->get_field_id('include_parent'); ?>" 
                name="<?php echo $this->get_field_name('include_parent'); ?>" type="checkbox" value="checked" 
                <?php echo $instance['include_parent']; ?>/></p>
            
                        
            <p> <?php _e('Include Parent Even With No Children','advanced-sidebar-menu'); ?>: <input id="<?php echo $this->get_field_id('include_childless_parent'); ?>"
            name="<?php echo $this->get_field_name('include_childless_parent'); ?>" type="checkbox" value="checked" 
                    <?php echo $instance['include_childless_parent']; ?>/>
            </p>
            
            <p> <?php _e( 'Order By','advanced-sidebar-menu'); ?>: <select id="<?php echo $this->get_field_id('order_by'); ?>" 
            name="<?php echo $this->get_field_name('order_by'); ?>">
                <?php
                
                $order_by = array( 
                            'menu_order' => 'Page Order',
                            'post_title' => 'Title',
                            'post_date'  => 'Published Date'
                            );
                
                foreach( $order_by as $key => $order ){
                    
                    printf('<option value="%s" %s>%s</option>', $key, selected($instance['order_by'], $key, false), $order );
                }
                ?>
             </select>
           </p>
             
            <p> <?php _e("Use this Plugin's Styling",'advanced-sidebar-menu'); ?>: <input id="<?php echo $this->get_field_id('css'); ?>"
            name="<?php echo $this->get_field_name('css'); ?>" type="checkbox" value="checked" 
                    <?php echo $instance['css']; ?>/></p>
                    
            <p> <?php _e( "Pages to Exclude (ids), Comma Separated",'advanced-sidebar-menu'); ?>: <input id="<?php echo $this->get_field_id('exclude'); ?>" 
                name="<?php echo $this->get_field_name('exclude'); ?>" class="widefat" type="text" value="<?php echo $instance['exclude']; ?>"/></p>
                
            <p> <?php _e( "Legacy Mode: (use pre 4.0 structure and css)",'advanced-sidebar-menu'); ?> <input id="<?php echo $this->get_field_name('legacy_mode'); ?>"
            name="<?php echo $this->get_field_name('legacy_mode'); ?>" type="checkbox" value="checked" 
                    <?php echo $instance['legacy_mode']; ?>/>
            </p>    
                
            <p> <?php _e( "Always Display Child Pages", 'advanced-sidebar-menu'); ?>: <input id="<?php echo $this->get_field_id('display_all'); ?>" 
                name="<?php echo $this->get_field_name('display_all'); ?>" type="checkbox" value="checked" 
                onclick="javascript:asm_reveal_element( 'levels-<?php echo $this->get_field_id('levels'); ?>' )"
                <?php echo $instance['display_all']; ?>/></p>
            
            <span id="levels-<?php echo $this->get_field_id('levels'); ?>" style="<?php 
                  if( $instance['display_all'] == 'checked' ){
                    echo 'display:block';
                  } else {
                    echo 'display:none';
                  } ?>"> 
            <p> <?php _e("Levels to Display",'advanced-sidebar-menu'); ?>: <select id="<?php echo $this->get_field_id('levels'); ?>" 
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
                
                
           do_action('advanced_sidebar_menu_page_widget_form', $instance, $this->get_field_name('parent_only'), $this->get_field_id('parent_only'), $this ); 
           
           
           do_action('advanced_sidebar_menu_after_widget_form', $instance, $this );  
                
            
        }


    /**
     * Handles the saving of the widget
     * 
     * @filters apply_filters('advanced_sidebar_menu_page_widget_update', $newInstance, $oldInstance );
     * 
     * @since 4.26.13
     */
    function update( $newInstance, $oldInstance ) {
            $newInstance['exclude'] = strip_tags($newInstance['exclude']);
            
            $newInstance = apply_filters('advanced_sidebar_menu_page_widget_update', $newInstance, $oldInstance );
            
            return $newInstance;
    }


#---------------------------------------------------------------------------------------------------------------------------

    /**
     * Outputs the page list
     * @see WP_Widget::widget()
     * 
     * @uses for custom post types send the type to the filter titled 'advanced_sidebar_menu_post_type'
     * @uses change the top parent manually with the filter 'advanced_sidebar_menu_top_parent'
     * @uses change the order of the 2nd level pages with 'advanced_sidebar_menu_order_by' filter
     * 
     * @filter apply_filters('advanced_sidebar_menu_page_widget_output',$content, $args, $instance );
     *         apply_filters('advanced_sidebar_menu_order_by', 'menu_order', $post, $args, $instance );
     *         apply_filters('advanced_sidebar_menu_top_parent', $top_parent, $post, $args, $instance );
     *         apply_filters('advanced_sidebar_menu_post_type', 'page', $args, $instance );
     * 
     *
     * 
     * @see Geansai - pointed out a notice level error. Thanks Geansai!!
     */
    function widget($args, $instance) {
        global $wpdb, $post, $table_prefix;
        
        $asm = new advancedSidebarMenu();
        $asm->instance = $instance;
        $asm->args = $args;

        do_action( 'advanced_sidebar_menu_widget_pre_render', $asm, $this );               
        
        //The excluded pages
        $exclude = apply_filters( 'advanced_sidebar_menu_excluded_pages', explode(',', $instance['exclude']), $post, $args, $instance, $asm );
        $asm->exclude = $exclude;

        extract($args);
        
        //Filter this one with a 'single' for a custom post type will default to working for pages only
        $post_type = apply_filters('advanced_sidebar_menu_post_type', 'page', $args, $instance, $asm  );
        $asm->post_type = $post_type;
        
        //Add a has_children class to appropriate pages
        add_filter( 'page_css_class', array( $asm, 'hasChildrenClass' ), 2, 2 );
        
        //Add the default classes to pages from a custom post type
        if( $asm->post_type != 'page' ){
             add_filter( 'page_css_class', array( $asm, 'custom_post_type_css' ), 2, 4 );   
        }
        
        
        $proper_single = !( is_page() || ( is_single() && $asm->post_type == get_post_type() ) );
        //Filter the single post check if try to display the menu somewhere else like a category page
        if( apply_filters( 'advanced_sidebar_menu_proper_single', $proper_single, $args, $instance, $asm ) ){
       		return;
		}
        

        //Get the Top Parent Id
        if($post->ancestors){
             $ancestors = $post->ancestors;
             $top_parent = end( $ancestors );
        } else {
             $top_parent = $post->ID;
        }   
        //Filter for specifying the top parent
        $top_parent = apply_filters( 'advanced_sidebar_menu_top_parent', $top_parent, $post, $args, $instance, $asm );
        $asm->top_id = $top_parent;


        //Bail if the parent page does not belong in this menu
        if( get_post_type( $asm->top_id ) != $asm->post_type ) return;
        
        
        //Filter for specifiying the order by
        $order_by = apply_filters('advanced_sidebar_menu_order_by', $instance['order_by'], $post, $args, $instance, $asm );
        $asm->order_by = $order_by; 
            
        /**
         * Must be done this way to prevent doubling up of pages
         */
        $child_pages = $wpdb->get_results( "SELECT ID FROM ". $wpdb->posts ." WHERE post_parent = $top_parent AND post_status='publish' AND post_type='$post_type' Order by $order_by" );
		 
        //for depreciation
        $p = $top_parent;
        $result = $child_pages = apply_filters( 'advanced_sidebar_menu_child_pages', $child_pages, $post, $args, $instance, $asm );

        #---- if there are no children do not display the parent unless it is check to do so
        if( (!empty($child_pages)) || $asm->checked('include_childless_parent') && (!in_array($top_parent, $exclude) )  ){
            
                $legacy = $asm->checked('legacy_mode');
            
                if( $asm->checked('css') ){
                    echo '<style type="text/css">';
                        include( $asm->file_hyercy('sidebar-menu.css', $legacy ) );
                    echo '</style>';
                }
    
                
                //Start the menu
                echo $before_widget;
                        #-- Bring in the 
                        $content = '';
                        require( $asm->file_hyercy( 'page_list.php', $legacy ) );
                        echo apply_filters('advanced_sidebar_menu_page_widget_output', $content, $args, $instance );
                echo $after_widget;
                
        }
		
    } #== /widget()
    
} #== /Clas