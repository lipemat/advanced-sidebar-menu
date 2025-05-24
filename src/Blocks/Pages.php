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
 * @phpstan-import-type PAGE_SETTINGS from Page as WIDGET_SETTINGS
 * @phpstan-import-type DEFAULTS from Page as DEFAULTS
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
 * @extends Block_Abstract<PAGE_ATTRIBUTES, WIDGET_SETTINGS, DEFAULTS>
 * @implements Block<WIDGET_SETTINGS, DEFAULTS>
 */
class Pages extends Block_Abstract implements Block {
	use Singleton;

	public const NAME = 'advanced-sidebar-menu/pages';


	/**
	 * Get the description of this block.
	 *
	 * @return string
	 */
	public function get_description(): string {
		return __( 'Creates a menu of all the pages using the parent/child relationship', 'advanced-sidebar-menu' );
	}


	/**
	 * Return a new instance of the Page widget.
	 *
	 */
	public function get_widget_class(): Page {
		return new Page();
	}


	/**
	 * Get the list of attributes and their types.
	 *
	 * Must be done PHP side because we're using ServerSideRender
	 *
	 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/
	 *
	 * @phpstan-return array<key-of<PAGE_ATTRIBUTES>, ATTR_SHAPE>
	 * @return array
	 */
	public function get_attributes(): array {
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
	 * @deprecated 9.8.0
	 *
	 * @phpstan-return array<string>
	 */
	public function get_keywords() {
		_deprecated_function( __METHOD__, '9.8.0' );

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
	 * @deprecated 9.8.0
	 *
	 * @phpstan-return array<string, bool>
	 */
	protected function get_block_support() {
		_deprecated_function( __METHOD__, '9.8.0' );

		return apply_filters( 'advanced-sidebar-menu/blocks/pages/supports', [
			'anchor' => true,
			'html'   => false,
		] );
	}
}
