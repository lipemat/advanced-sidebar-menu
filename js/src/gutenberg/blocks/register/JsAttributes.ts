/**
 * Translate short JS attributes to the standard format expected by blocks.
 *
 * @see \Advanced_Sidebar_Menu\Blocks\Register\JS_Attribute
 *
 * @since 9.8.0
 */
import type {BlockAttributes} from '@wordpress/blocks';

type ShortAttr = {
	t: ShortType | ShortType[];
	d?: string | number | boolean | Array<string | number> | object | null;
	e?: Array<string | number | boolean>;
}

type ShortType = `${ShortAttrType}`

export type ShortBlockAttributes<Attr> = {
	[key in keyof Attr]: ShortAttr;
}

enum AttrType {
	ARRAY = 'array',
	BOOLEAN = 'boolean',
	INTEGER = 'integer',
	NULL = 'null',
	NUMBER = 'number',
	OBJECT = 'object',
	STRING = 'string',
}

enum ShortAttrType {
	ARRAY = 'a',
	BOOLEAN = 'b',
	INTEGER = 'i',
	NULL = 'u',
	NUMBER = 'n',
	OBJECT = 'o',
	STRING = 's',
}

namespace ShortAttrType {
	export function toLong( type: ShortType ): AttrType {
		switch ( type ) {
			case ShortAttrType.ARRAY:
				return AttrType.ARRAY;
			case ShortAttrType.BOOLEAN:
				return AttrType.BOOLEAN;
			case ShortAttrType.INTEGER:
				return AttrType.INTEGER;
			case ShortAttrType.NULL:
				return AttrType.NULL;
			case ShortAttrType.NUMBER:
				return AttrType.NUMBER;
			case ShortAttrType.OBJECT:
				return AttrType.OBJECT;
			case ShortAttrType.STRING:
				return AttrType.STRING;
		}

		throw new Error( `Invalid type: ${type}` );
	}
}


export function translateShortAttributes<Attr>( attr: ShortBlockAttributes<Attr> ): BlockAttributes<Attr> {
	const translated: Partial<BlockAttributes<Attr>> = {};
	Object.entries( attr ).forEach( ( [ key, value ]: [ string, ShortAttr ] ) => {
		if ( Array.isArray( value.t ) ) {
			translated[ key ] = {
				type: value.t.map( ShortAttrType.toLong ),
			};
		} else {
			translated[ key ] = {
				type: ShortAttrType.toLong( value.t ),
			};
		}
		if ( 'd' in value ) {
			translated[ key ].default = value.d;
		}
		if ( 'e' in value ) {
			translated[ key ].enum = value.e;
		}
	} );
	return translated as BlockAttributes<Attr>;
}
