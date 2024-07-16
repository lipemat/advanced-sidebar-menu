import {createBlock, CreateBlock} from '@wordpress/blocks';
import {CONFIG, Screen} from '../globals/config';

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
