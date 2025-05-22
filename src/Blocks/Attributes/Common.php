<?php
//phpcs:disable WordPress.NamingConventions.ValidVariableName

namespace Advanced_Sidebar_Menu\Blocks\Attributes;

use Advanced_Sidebar_Menu\Blocks\Block_Abstract;
use Advanced_Sidebar_Menu\Menus\Menu_Abstract;

/**
 * Shared functionality between various Attribute classes
 *
 * @author OnPoint Plugins
 * @since  9.7.0
 *
 *
 * @phpstan-type COMMON_ATTR array{
 *      clientId: string,
 *      sidebarId: string,
 *      style: array<string, string>,
 *      title: string,
 *      isServerSideRenderRequest: bool,
 * }
 *
 * @template SETTINGS of array<string, string|int|bool|array<string, string>>
 */
trait Common {
	/**
	 * The client ID of the block.
	 *
	 * @var string
	 */
	public string $clientId;

	/**
	 * The widget-area the block is within.
	 *
	 * @var string
	 */
	public string $sidebarId;

	/**
	 * @var array<string, string>
	 */
	public array $style;

	/**
	 * The title of the block when used in a widget.
	 *
	 * @var string
	 */
	public string $title;

	/**
	 * Indicates if the block is being rendered on the server-side.
	 *
	 * @var bool
	 */
	public bool $isServerSideRenderRequest;


	/**
	 * Optionally, pass existing arguments to preload this class.
	 *
	 * @phpstan-param \Partial<COMMON_ATTR> $common
	 *
	 * @param array<string, mixed>          $common - Existing arguments to preload.
	 */
	final protected function set_common( array $common ): void {
		$this->clientId = $common['clientId'] ?? '';
		$this->sidebarId = $common['sidebarId'] ?? '';
		$this->style = $common['style'] ?? [];
		$this->title = $common[ Menu_Abstract::TITLE ] ?? '';
		$this->isServerSideRenderRequest = $common[ Block_Abstract::RENDER_REQUEST ] ?? false;
	}
}
