import {createBlock, CreateBlock} from '@wordpress/blocks';

export type TransformLegacy = <A>( name: string ) => ( widgetValues: { instance: Record<string, any> } ) => CreateBlock<A>[];

/**
 * Transform a legacy widget to the matching block.
 *
 */
export const transformLegacyWidget: TransformLegacy = <A>( name: string ) => ( {instance} ) => {
	return [ createBlock<A>( name, translateLegacyWidget<A>( instance.raw ) ) ];
};

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
	} );
	return settings;
};
