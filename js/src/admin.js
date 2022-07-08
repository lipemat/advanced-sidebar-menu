console.log( 'Advanced Sidebar - Loaded' );

import Gutenberg from './gutenberg';


if ( typeof wp.element !== 'undefined' && typeof wp.plugins !== 'undefined' ) {
	/**
	 * 	Blocks can't be lazy loaded, or they will be unavailable intermittently when developing.
	 *
	 * 	They may however have individual parts lazy loaded.
	 *
	 * 	@see seo block for the lazy load pattern.
	 */
	Gutenberg();
}
