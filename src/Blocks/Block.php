<?php

namespace Advanced_Sidebar_Menu\Blocks;

use Advanced_Sidebar_Menu\Widget\WidgetWithId;

/**
 * Rules a block must follow.
 *
 * Replacement for `Block_Abstract` as we move from inheritance to composition.
 *
 * @author OnPoint Plugins
 * @since  9.7.0
 *
 * @phpstan-import-type ATTR_SHAPE from Block_Abstract
 *
 * @template SETTINGS of array<string, mixed>
 * @template DEFAULTS of array<string, mixed>
 */
interface Block {
	/**
	 * Get the list of attributes and their types.
	 *
	 * Must be done on both PHP and JS sides to support default values
	 * and SeverSideRender.
	 *
	 * @see  Pro_Block_Abstract::get_all_attributes()
	 *
	 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/
	 *
	 * @return array<string, ATTR_SHAPE>
	 */
	public function get_attributes();


	/**
	 * Get the widget class, which matches this block.
	 *
	 * @return WidgetWithId<SETTINGS, DEFAULTS>
	 */
	public function get_widget_class(): WidgetWithId;
}
