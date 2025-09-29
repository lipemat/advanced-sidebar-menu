// phpcs:disable Lipe.JS.HTMLExecutingFunctions, Lipe.JS.Window.location -- Running in browser console all execution is possible.
import DOMPurify from 'dompurify';

/**
 * Debugging utilities available when `asm_debug` is included in the URL.
 *
 * - Print information to the console on page load.
 * - Exposes `advancedSidebarMenuDebug` function to add parameters to the URL.
 *
 * @since 9.6.0
 */

type JsonBoolean = '1' | '';

/**
 * @see \Advanced_Sidebar_Menu\Debug
 */
type DEBUG_INFO = {
	basic: string;
	classicWidgets: JsonBoolean;
	classicEditor?: JsonBoolean;
	excludedCategories?: number[];
	excluded_pages?: number[];
	menus: { [ menuId: string ]: string | number | object }
	php: string;
	pro: string | false;
	scriptDebug: JsonBoolean;
	WordPress: string;
};


declare global {
	interface Window {
		asm_debug: DEBUG_INFO;
		advancedSidebarMenuDebug: typeof advancedSidebarMenuDebug;
	}
}

/**
 * @see \Advanced_Sidebar_Menu\Debug::DEBUG_PARAM
 */
const DEBUG_PARAM = 'asm_debug';

export function serializeObject( params: object, prefix = '' ): Array<[ string, string ]> {
	const queryParts: Array<[ string, string ]> = [];
	for ( const [ key, value ] of Object.entries( params ) ) {
		const prefixedKey = prefix !== '' ? `${prefix}[${key}]` : DEBUG_PARAM + '[' + key + ']';
		if ( 'object' === typeof value && value !== null && ! Array.isArray( value ) ) {
			queryParts.push( ...serializeObject( value, prefixedKey ) );
		} else {
			queryParts.push( [ prefixedKey, encodeURIComponent( value ) ] );
		}
	}
	return queryParts;
}

/**
 * Add multi-level object as URL parameters.
 */
export function addObjectAsUrlParams( url: string | URL, params: object ) {
	const urlObj = new URL( url );
	const serializedParams = serializeObject( params );
	serializedParams.forEach( param => {
		const [ key, value ] = param;
		urlObj.searchParams.append( key, value );
	} );
	return urlObj.toString();
}

/**
 * Debugging utility to add parameters to the URL.
 *
 * @example `advancedSidebarMenuDebug({links_expand: "checked", links_expand_levels: {all: 'checked'}})`
 */
export function advancedSidebarMenuDebug( params: object ) {
	const url = new URL( DOMPurify.sanitize( window.location.href ) );
	window.location.href = DOMPurify.sanitize( addObjectAsUrlParams( `${url.origin}${url.pathname}`, params ) );
}

window.advancedSidebarMenuDebug = advancedSidebarMenuDebug;

console.debug( 'Advanced Sidebar Info:' );
console.debug( {...window.asm_debug, menus: 'See below for menus.'} );
console.debug( 'Advanced Sidebar Menus:' );
console.debug( window.asm_debug.menus );
console.debug( 'The `advancedSidebarMenuDebug` function is available for debugging.' );
