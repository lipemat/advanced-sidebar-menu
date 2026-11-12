import {type BlockAttributes, BlockSupports, createBlock, CreateBlock, type LegacyWidget} from '@wordpress/blocks';
import {CONFIG, Screen} from '../globals/config';
import type {CommonAttr, ServerSideRenderRequired} from './blocks/Preview';

type LegacySettings = { [ key: string ]: string | number | boolean | object | null | undefined };

export type TransformLegacy = <Attr>( name: string ) => ( widgetValues: LegacyWidget<LegacySettings> ) => CreateBlock<Attr>;

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
	return createBlock<A>( name, translateLegacyWidget<A>( instance.raw ?? {} ) );
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
const translateLegacyWidget = <A>( settings: LegacySettings ): A => {
	Object.entries( settings ).forEach( ( [ key, value ] ) => {
		if ( 'checked' === value ) {
			settings[ key ] = true;
		}
		if ( isLegacySettings( value ) ) {
			translateLegacyWidget( value );
		}
		// Old widgets used to use "0" for some defaults.
		if ( '0' === value ) {
			delete settings[ key ];
		}
	} );
	return settings as A;
};

function isLegacySettings( value: LegacySettings[string] ): value is LegacySettings {
	return null !== value && 'object' === typeof value;
}
