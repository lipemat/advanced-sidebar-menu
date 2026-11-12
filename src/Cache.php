<?php

namespace Advanced_Sidebar_Menu;

use Advanced_Sidebar_Menu\Traits\Singleton;

/**
 * Menu Cache
 *
 * @author OnPoint Plugins
 */
class Cache {
	use Singleton;

	public const CACHE_GROUP     = 'advanced-sidebar-menu';
	public const CHILD_PAGES_KEY = 'child-pages';


	/**
	 * Actions.
	 */
	protected function hook(): void {
		add_action( 'save_post', [ $this, 'clear_on_post_save' ], 10, 2 );
	}


	/**
	 * Clear all items in this cache group when a supported post type is saved.
	 *
	 * The basic version only supports the `page` post type, however filters could
	 * be used to add additional post types, so we flush any hierarchical post type.
	 *
	 * @param int      $post_id - ID of the updated posts.
	 * @param \WP_Post $post    - Full post object, so we can use the post_type.
	 *
	 * @return void
	 */
	public function clear_on_post_save( $post_id, \WP_Post $post ): void {
		$types = \apply_filters( 'advanced-sidebar-menu/utils/supported-post-types', false, [ 'page' ] );
		if ( false === $types ) {
			$types = get_post_types( [
				'hierarchical' => true,
				'public'       => true,
			] );
		}
		if ( \array_key_exists( $post->post_type, $types ) ) {
			$this->clear_cache_group();
		}
	}


	/**
	 * Clear all items in this cache group
	 *
	 * @return void
	 */
	public function clear_cache_group() {
		wp_cache_set( 'last_changed', microtime(), static::CACHE_GROUP . ':' . ADVANCED_SIDEBAR_MENU_BASIC_VERSION );
	}


	/**
	 * Get unique key for this group
	 *
	 * @return string
	 */
	public function get_cache_group() {
		$key = wp_cache_get_last_changed( static::CACHE_GROUP . ':' . ADVANCED_SIDEBAR_MENU_BASIC_VERSION );
		return static::CACHE_GROUP . ':' . ADVANCED_SIDEBAR_MENU_BASIC_VERSION . ':' . $key;
	}


	/**
	 * Retrieve a post's child pages from the cache.
	 * If no exist in the cache will return false.
	 *
	 * @note Once WP 6.2 is the lowest supported version, it may not be necessary to cache
	 *       as WP_Query will likely handle caching natively.
	 *
	 * @param array<string, mixed> $args - Arguments passed to `get_posts`.
	 *
	 * @return array<int>|false
	 */
	public function get_child_pages( array $args ) {
		$all_child_pages = (array) wp_cache_get( static::CHILD_PAGES_KEY, $this->get_cache_group() );

		return $all_child_pages[ $this->get_key( $args ) ] ?? false;
	}


	/**
	 * Add a post and its children to the cache
	 * Uses a global key for all posts so this appends to an array
	 *
	 * @param array<string, mixed> $args        - Arguments passed to `get_posts`.
	 * @param array<int>           $child_pages - List of child page ids to store.
	 *
	 * @return void
	 */
	public function add_child_pages( array $args, array $child_pages ): void {
		$all_child_pages = (array) wp_cache_get( static::CHILD_PAGES_KEY, $this->get_cache_group() );
		$all_child_pages[ $this->get_key( $args ) ] = $child_pages;

		wp_cache_set( static::CHILD_PAGES_KEY, $all_child_pages, $this->get_cache_group() );
	}


	/**
	 * Unique key for the query arguments.
	 *
	 * @param array<string, mixed> $args - Arguments passed to `get_posts`.
	 *
	 * @return string
	 */
	protected function get_key( array $args ): string {
		// Not a query arg and changes while walking.
		unset( $args['walker'] );
		return \md5( maybe_serialize( $args ) );
	}
}
