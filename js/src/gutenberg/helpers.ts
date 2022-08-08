import {createBlock, CreateBlock} from '@wordpress/blocks';
import {Attr} from './blocks/pages/block';

/**
 * Transform a legacy widget to the matching block.
 *
 */
export const transformLegacyWidget = ( name: string ) => ( {instance} ) => {
	const blocks: CreateBlock<any>[] = [];
	if ( instance.raw.title ) {
		blocks.push( createBlock<{ content: string }>( 'core/heading', {
			content: instance.raw.title,
		} ) );
	}
	blocks.push( createBlock<Attr>( name, translateLegacyWidget( instance.raw ) ) );
	return blocks;
};

/**
 * Translate the widget's "checked" to the boolean
 * version used in the block.
 *
 */
const translateLegacyWidget = ( settings ): Attr => {
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
