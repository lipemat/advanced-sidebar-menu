<?php

namespace Advanced_Sidebar_Menu\Blocks;

use Advanced_Sidebar_Menu\Traits\Singleton;
use Advanced_Sidebar_Menu\Widget\Page;

/**
 * Advanced Sidebar - Pages, Gutenberg block.
 *
 * @since  9.0.0
 *
 * @phpstan-import-type ATTR_SHAPE from Block_Abstract
 *
 * @phpstan-type PAGE_ATTRIBUTES array{
 *   display_all?: bool,
 *   exclude: string,
 *   include_childless_parent?: bool,
 *   include_parent?: bool,
 *   levels: int,
 *   order_by: 'menu_order'|'post_title'|'post_date',
 *   post_type?: string,
 * }
 *
 * @extends Block_Abstract<PAGE_ATTRIBUTES>
 */
class Pages extends Block_Abstract {
	use Singleton;

	public const NAME = 'advanced-sidebar-menu/pages';


	/**
	 * @deprecated 9.7.0
	 *
	 * @phpstan-return array<string>
	 */
	public function get_keywords() {
		_deprecated_function( __METHOD__, '9.7.0' );
		return [
			'Advanced Sidebar',
			'menu',
			'sidebar',
			'pages',
			'butt',
			__( 'menu', 'advanced-sidebar-menu' ),
			__( 'sidebar', 'advanced-sidebar-menu' ),
			__( 'pages', 'advanced-sidebar-menu' ),
		];
	}


	/**
	 * @deprecated 9.7.0
	 *
	 * @return string
	 */
	protected function get_description() {
		_deprecated_function( __METHOD__, '9.7.0' );
		return __( 'Creates a menu of all the pages using the parent/child relationship', 'advanced-sidebar-menu' );
	}


	/**
	 * Get featured this block supports.
	 *
	 * Done on the PHP side, so we can easily add additional features
	 * via the PRO version.
	 *
	 * @return array
	 */
	protected function get_block_support() {
		return apply_filters( 'advanced-sidebar-menu/blocks/pages/supports', [
			'anchor' => true,
			'html'   => false,
		] );
	}


	/**
	 * Get list of attributes and their types.
	 *
	 * Must be done PHP side because we're using ServerSideRender
	 *
	 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/
	 *
	 * @phpstan-return array<key-of<PAGE_ATTRIBUTES>, ATTR_SHAPE>
	 * @return array
	 */
	protected function get_attributes(): array {
		return (array) apply_filters( 'advanced-sidebar-menu/blocks/pages/attributes', [
			Page::INCLUDE_PARENT           => [
				'type' => 'boolean',
			],
			Page::INCLUDE_CHILDLESS_PARENT => [
				'type' => 'boolean',
			],
			Page::ORDER_BY                 => [
				'type'    => 'string',
				'default' => 'menu_order',
			],
			Page::EXCLUDE                  => [
				'type'    => 'string',
				'default' => '',
			],
			Page::DISPLAY_ALL              => [
				'type' => 'boolean',
			],
			Page::LEVELS                   => [
				'type'    => 'number',
				'default' => 100,
			],
		] );
	}


	/**
	 * Return a new instance of the Page widget.
	 *
	 * @return Page
	 */
	protected function get_widget_class() {
		return new Page();
	}
}
