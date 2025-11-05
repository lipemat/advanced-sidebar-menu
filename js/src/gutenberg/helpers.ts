import {type BlockAttributes, BlockSupports, createBlock, CreateBlock} from '@wordpress/blocks';
import {CONFIG, Screen} from '../globals/config';
import type {CommonAttr, ServerSideRenderRequired} from './blocks/Preview';

export type TransformLegacy = <Attr>( name: string ) => ( widgetValues: {
	instance: { [ key: string ]: string | number | object | boolean }
} ) => CreateBlock<Attr>;

/**
 * Are we on one of the provided screens?
 */
export const isScreen = ( screens: Array<Screen> ): boolean => {
	return screens.includes( CONFIG.currentScreen );
};

/**
 * Transform a legacy widget to the matching block.
 *
 */
export const transformLegacyWidget: TransformLegacy = <A>( name: string ) => ( {instance} ) => {
	return createBlock<A>( name, translateLegacyWidget<A>( instance.raw ) );
};

/**
 * Merge the common attributes and preview attributes into the block attributes.
 *
 * @since 9.7.0
 */
export function translateBlockAttributes<Attr>( attributes: BlockAttributes<Attr> ): BlockAttributes<Attr & CommonAttr & ServerSideRenderRequired> {
	return {...attributes, ...CONFIG.blocks.commonAttr, ...CONFIG.blocks.previewAttr};
}

/**
 * Get block support from a common location.
 *
 * @since 9.7.0
 */
export function getBlockSupports(): BlockSupports {
	return CONFIG.blocks.blockSupport;
}

/**
 * Translate the widget's "checked" to the boolean
 * version used in the block.
 *
 */
const translateLegacyWidget = <A>( settings ): A => {
	Object.entries( settings ).forEach( ( [ key, value ] ) => {
		if ( 'checked' === value ) {
			settings[ key ] = true;
		}
		if ( 'object' === typeof value ) {
			translateLegacyWidget( settings[ key ] );
		}
		// Old widgets used to use "0" for some defaults.
		if ( '0' === value ) {
			delete settings[ key ];
		}
	} );
	return settings;
};
