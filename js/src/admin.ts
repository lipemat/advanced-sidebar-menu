import widgets from './modules/widgets';

console.debug( 'Advanced Sidebar - Loaded' );

widgets();

/**
 * 1. Blocks can't be lazy loaded, or they will be unavailable
 *    intermittently when developing.
 * 2. Theme Customizers must wait until the page is finished loading.
 *
 * @version 1.1.0
 */
if ( typeof window.wp.element !== 'undefined' && typeof window.wp.plugins !== 'undefined' ) {
	require( './gutenberg' ).default();
} else if ( typeof window.wp.customize !== 'undefined' ) {
	window.wp.customize.bind( 'ready', () => {
		require( './gutenberg' ).default();
	} );
}
