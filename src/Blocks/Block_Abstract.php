<?php

namespace Advanced_Sidebar_Menu\Blocks;

use Advanced_Sidebar_Menu\Traits\Singleton;
use Advanced_Sidebar_Menu\Widget\Category;
use Advanced_Sidebar_Menu\Widget\Page;

/**
 * Functionality shared by and required by all blocks.
 *
 * @since 8.10.0
 */
abstract class Block_Abstract {
	const NAME = 'block-abstract';

	const RENDER_REQUEST = 'isServerSideRenderRequest';

	use Singleton;

	/**
	 * Get list of attributes and their types.
	 *
	 * Must be done PHP side because we're using ServerSideRender.
	 *
	 * @see  Pro_Block_Abstract::get_all_attributes()
	 *
	 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/
	 *
	 * @return array
	 */
	abstract protected function get_attributes();


	/**
	 * Get featured this block supports.
	 *
	 * Done on the PHP side, so we can easily add additional features
	 * via the PRO version.
	 *
	 * @return array
	 */
	abstract protected function get_block_support();


	/**
	 * Get the widget class, which matches this block.
	 *
	 * @return Page|Category
	 */
	abstract protected function get_widget_class();


	/**
	 * Actions and filters.
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'advanced-sidebar-menu/scripts/js-config', [ $this, 'js_config' ] );
	}


	/**
	 * Register the block.
	 *
	 * @return void
	 */
	public function register() {
		register_block_type( static::NAME, [
			'api_version'     => 2,
			'attributes'      => $this->get_all_attributes(),
			'render_callback' => [ $this, 'render' ],
			'supports'        => $this->get_block_support(),
		] );
	}


	/**
	 * Get attributes defined in this class as well
	 * as common attributes shared by all blocks.
	 *
	 * @return array
	 */
	protected function get_all_attributes() {
		return \array_merge( $this->get_attributes(), [
			'backgroundColor'    => [
				'type' => 'string',
			],
			'gradient'           => [
				'type' => 'string',
			],
			self::RENDER_REQUEST => [
				'type' => 'boolean',
			],
			'style'              => [
				'type' => 'object',
			],
		] );
	}


	/**
	 * Include this block's id and attributes in the JS config.
	 *
	 * @param array $config - JS config in current state.
	 *
	 * @filter advanced-sidebar-menu/pro-scripts/js-config
	 *
	 * @return array
	 */
	public function js_config( array $config ) {
		$config['blocks'][ \explode( '/', static::NAME )[1] ] = [
			'id' => static::NAME,
		];

		return $config;
	}


	/**
	 * Render the block template via ServerSideRender.
	 *
	 * @param array  $attr - Block attributes matching widget settings.
	 * @param string $wrap - Block wrap containing finished `useBlockProps` results.
	 *
	 * @return string
	 */
	public function render( $attr, $wrap ) {
		$parts = (array) \explode( '%s', $wrap );
		// Assure always at least 2 parts.
		$parts[] = '';

		/**
		 * Within the Editor ServerSideRender request come in as REST requests.
		 * We spoof the WP_Query as much as required to get the menus to
		 * display the same way they will on the front-end.
		 */
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $attr[ self::RENDER_REQUEST ] ) && ! empty( get_post() ) ) {
			add_action( 'advanced-sidebar-menu/widget/before-render', function( $menu ) {
				if ( method_exists( $menu, 'set_current_post' ) ) {
					$menu->set_current_post( get_post() );
				}
			} );
			add_filter( 'advanced-sidebar-menu/menus/page/is-displayed', '__return_true' );
			add_filter( 'advanced-sidebar-menu/core/include-template-parts-comments', '__return_false' );
			$GLOBALS['wp_query']->queried_object = get_post();
			$GLOBALS['wp_query']->queried_object_id = get_the_ID();
		}

		// Map the boolean values to widget style 'checked'.
		$attr = \array_map( function( $value ) {
			if ( true === $value ) {
				return 'checked';
			}
			return $value;
		}, $attr );

		ob_start();
		$widget = $this->get_widget_class();
		$widget->widget( [
			'before_widget' => $parts[0],
			'after_widget'  => $parts[1],
			'before_title'  => '',
			'after_title'   => '',
		], $attr );
		return ob_get_clean();
	}

}