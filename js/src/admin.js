console.log( 'Advanced Sidebar - Loaded' );

/**
 * 1. Blocks can't be lazy loaded, or they will be unavailable
 *    intermittently when developing.
 * 2. Theme Customizers must wait until the page is finished loading.
 *
 * @version 1.1.0
 */
if ( typeof wp.element !== 'undefined' && typeof wp.plugins !== 'undefined' ) {
	require( './gutenberg' ).default();
} else if ( typeof wp.customize !== 'undefined' ) {
	wp.customize.bind( 'ready', () => {
		require( './gutenberg' ).default();
	} );
}
