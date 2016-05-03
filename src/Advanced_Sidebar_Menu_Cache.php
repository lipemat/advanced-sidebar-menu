<?php


/**
 * Advanced_Sidebar_Menu_Cache
 *
 * @author Mat Lipe
 * @since  12/19/2015
 *
 */
class Advanced_Sidebar_Menu_Cache {
	const CACHE_GROUP = 'advanced-sidebar-menu';
	const CHILD_PAGES_KEY = 'child-pages';

	private function __construct(){
		$this->hooks();
	}


	private function hooks(){
		add_action( 'save_post', array( $this, 'clear_cache_group' ) );
	}


	/**
	 * Clear all of items in this cache group
	 *
	 * @return void
	 */
	public function clear_cache_group(){
		wp_cache_delete( self::CHILD_PAGES_KEY, self::CACHE_GROUP );
	}


	/**
	 * Retrieve a posts child pages from the cache
	 * If no exist in the cache will return false
	 *
	 * @param Advanced_Sidebar_Menu_Menu|Advanced_Sidebar_Menu_List_Pages $class
	 *
	 * @return array|false
	 */
	public function get_child_pages( $class ){
		$key = $this->get_key_from_asm( $class );
		$all_child_pages = (array)wp_cache_get( self::CHILD_PAGES_KEY, self::CACHE_GROUP );
		if( isset( $all_child_pages[ $key ] ) ){
			return $all_child_pages[ $key ];
		} else {
			return false;
		}
	}


	/**
	 * Add a post and its children to the cache
	 * Uses a global key for all posts so this appends to an array
	 *
	 * @param Advanced_Sidebar_Menu_Menu|Advanced_Sidebar_Menu_List_Pages $class
	 * @param array                                                       $child_pages
	 *
	 * @return void
	 */
	public function add_child_pages( $class,  $child_pages ){
		$key = $this->get_key_from_asm( $class );
		$all_child_pages = (array)wp_cache_get( self::CHILD_PAGES_KEY, self::CACHE_GROUP );
		$all_child_pages[ $key ] = $child_pages;
		wp_cache_set( self::CHILD_PAGES_KEY, $all_child_pages, self::CACHE_GROUP );
	}


	/**
	 * There are many possibilities for properties
	 * set to the object used for generations.
	 * To guarantee we have a unique id for the cache
	 * we serialize the whole object and hash it
	 *
	 *
	 * @param Advanced_Sidebar_Menu_Menu|Advanced_Sidebar_Menu_List_Pages $class
	 *
	 * @return string
	 */
	private function get_key_from_asm( $class ){
		$string = serialize( $class );
		$hash = md5( $string );
		return $hash;
	}


	//********** SINGLETON FUNCTIONS **********/

	/**
	 * Instance of this class for use as singleton
	 */
	private static $instance;


	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init(){
		self::$instance = self::get_instance();
	}


	/**
	 * Get (and instantiate, if necessary) the instance of the
	 * class
	 *
	 * @static
	 * @return self
	 */
	public static function get_instance(){
		if( !is_a( self::$instance, __CLASS__ ) ){
			self::$instance = new self();
		}

		return self::$instance;
	}
}