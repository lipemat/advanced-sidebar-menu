<?php

namespace Advanced_Sidebar_Menu\Blocks;

use Advanced_Sidebar_Menu\Scripts;
use Advanced_Sidebar_Menu\Utils;

/**
 * Functionality shared by and required by all blocks.
 *
 * @since 9.0.0
 */
abstract class Block_Abstract {
	const NAME = 'block-abstract';

	const RENDER_REQUEST = 'isServerSideRenderRequest';


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
	 * Get list of words used to search for the block.
	 *
	 * @return string[]
	 */
	abstract protected function get_keywords();


	/**
	 * Get the description of this block.
	 *
	 * @return string
	 */
	abstract protected function get_description();


	/**
	 * Get the widget class, which matches this block.
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
		add_filter( 'widget_types_to_hide_from_legacy_widget_block', [ $this, 'exclude_from_legacy_widgets' ] );
	}


	/**
	 * Exclude this block from new Legacy Widgets.
	 *
	 * Leave existing intact while forcing users to use the block
	 * instead for new Widgets.
	 *
	 * @param array $blocks - Excluded blocks.
	 *
	 * @action
	 *
	 * @return array
	 */
	public function exclude_from_legacy_widgets( $blocks ) {
		/**
		 * Programmatically opt in to exclude legacy widgets from the Block Inserter
		 * if legacy widgets a not needed to match a theme's styles.
		 *
		 * In the future, this filter will be removed in favor of not allowing new legacy
		 * widgets in the block inserter.
		 *
		 * @link https://developer.wordpress.org/block-editor/how-to-guides/widgets/legacy-widget-block/#3-hide-the-widget-from-the-legacy-widget-block
		 */
		if ( ! apply_filters( 'advanced-sidebar-menu/block-abstract/exclude-legacy-widgets', false, $this->get_widget_class(), $blocks, $this ) ) {
			return $blocks;
		}

		$widget = $this->get_widget_class();
		$blocks[] = $widget::NAME;
		return $blocks;
	}


	/**
	 * Register the block.
	 *
	 * @link   https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
	 *
	 * @see    Pro_Block_Abstract::register()
	 *
	 * @action init 10 0
	 *
	 * @return void
	 */
	public function register() {
		register_block_type( static::NAME,
			apply_filters( 'advanced-sidebar-menu/block-register/' . static::NAME, [
				'api_version'     => 2,
				'attributes'      => $this->get_all_attributes(),
				'description'     => $this->get_description(),
				'editor_script'   => Scripts::GUTENBERG_HANDLE,
				'editor_style'    => Scripts::GUTENBERG_CSS_HANDLE,
				'keywords'        => $this->get_keywords(),
				'render_callback' => [ $this, 'render' ],
				'supports'        => $this->get_block_support(),
			] ) );
	}


	/**
	 * Get attributes defined in this class as well
	 * as common attributes shared by all blocks.
	 *
	 * @return array
	 */
	protected function get_all_attributes() {
		return \array_merge( $this->get_attributes(), [
			'clientId'           => [
				'type' => 'string',
			],
			self::RENDER_REQUEST => [
				'type' => 'boolean',
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
			add_filter( 'advanced-sidebar-menu/core/include-template-parts-comments', '__return_false' );
			$GLOBALS['wp_query']->queried_object = get_post();
			$GLOBALS['wp_query']->queried_object_id = get_the_ID();
			$GLOBALS['wp_query']->is_singular = true;
			if ( get_post_type() === 'page' ) {
				$GLOBALS['wp_query']->is_page = true;
			} else {
				$GLOBALS['wp_query']->is_single = true;
			}
		}

		// Map the boolean values to widget style 'checked'.
		$attr = Utils::instance()->array_map_recursive( function( $value ) {
			if ( true === $value ) {
				return 'checked';
			}
			return $value;
		}, $attr );

		ob_start();
		$widget = $this->get_widget_class();
		$widget_args = [
			'before_widget' => $parts[0],
			'after_widget'  => $parts[1],
			'before_title'  => '',
			'after_title'   => '',
		];
		// Passed via ServerSideRender, so we can enable accordions in Gutenberg editor.
		if ( ! empty( $attr['clientId'] ) ) {
			$widget_args['widget_id'] = $attr['clientId'];
		}
		$widget->widget( $widget_args, $attr );
		return ob_get_clean();
	}

}
