console.log( 'Advanced Sidebar - Loaded' );

/**
 * 1. Blocks can't be lazy loaded, or they will be unavailable intermittently when developing.
 * 2. Theme Customizers must wait until the page is finished loading.
 */
if ( typeof wp.element !== 'undefined' && typeof wp.plugins !== 'undefined' ) {
	require( './gutenberg' ).default();
} else if ( typeof wp.customize !== 'undefined' ) {
	jQuery( function() {
		require( './gutenberg' ).default();
	} );
}
