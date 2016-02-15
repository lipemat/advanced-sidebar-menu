<?php

/**
 * Advanced_Sidebar_Menu_Deprecated
 *
 * For backward compatibility on template overrides only
 *
 * @see
 */
class Advanced_Sidebar_Menu_Deprecated {


	/**
	 * @deprecated 5.0.0
	 */
	function page_children( $pID ) {
		global $wpdb, $table_prefix;

		_deprecated_function( 'advancedSidebarMenu::page_children', "5.0.0", 'advancedSidebarMenu::hasChildren' );

		return $wpdb->get_results( "SELECT ID FROM " . $table_prefix . "posts WHERE post_parent = " . $pID . " AND post_type = $this->post_type AND post_status='publish' ORDER By " . $this->order_by );

	}


	/**
	 * @deprecated 5.0.0
	 */
	function page_ancestor( $pID ) {
		global $post;
		if( is_numeric( $pID ) ){
			$pID = get_post( $pID );
		}

		if( $pID->ID == $post->ID or $pID->ID == $post->post_parent or @in_array( $pID->ID, $post->ancestors ) ){
			$return = true;
		} else {
			$return = false;
		}

		return apply_filters( 'advanced_sidebar_menu_page_ancestor', $return, $pID, $this );
	}



	/**
	 * @deprecated 5.0.0
	 */
	function grandChildLegacyMode( $pID ) {
		#-- if the link that was just listed is the current page we are on
		if( !$this->page_ancestor( $pID ) ){
			return;
		}

		//Get the children of this page
		$grandkids = $this->page_children( $pID );
		if( $grandkids ){
			#-- Create a new menu with all the children under it
			$content .= '<ul class="grandchild-sidebar-menu">';
			$content .= wp_list_pages( "post_type=" . $this->post_type . "&sort_column=$this->order_by&title_li=&echo=0&exclude=" . $this->instance[ 'exclude' ] . "&child_of=" . $pID->ID );

			$content .= '</ul>';
		}

		return $content;
	}


	/**
	 * @deprecated 5.0.0
	 */
	function displayGrandChildMenu( $page ) {
		static $count = 0;
		$count++;

		//If the page sent is not a child of the current page
		if( !$this->page_ancestor( $page ) ){
			return;
		}

		//if there are no children of the current page bail
		if( !$children = $this->page_children( $page->ID ) ){
			return;
		}


		$content = sprintf( '<ul class="grandchild-sidebar-menu level-%s children">', $count );
		foreach( $children as $child ){

			//If this page should be excluded bail
			if( in_array( $child->ID, $this->exclude ) ){
				continue;
			}

			$args = array(
				'post_type'   => $this->post_type,
				'sort_column' => $this->order_by,
				'title_li'    => '',
				'echo'        => 0,
				'depth'       => 1,
				'include'     => $child->ID
			);

			$content .= $this->openListItem( wp_list_pages( $args ) );

			//If this newly outputed child is a direct parent of the current page
			if( $this->page_ancestor( $child ) ){
				$content .= $this->displayGrandChildMenu( $child );
			}

			$content .= '</li>';

		}
		$content .= '</ul>';

		return $content;

	}
}