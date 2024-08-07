module.exports = webpackConfig => {
	if ( '--script-debug' === process.argv[ 2 ] ) {
		/**
		 * 1. Remove Webpack cleanup plugin to prevent deleting
		 * minified files when running the second round.
		 *
		 * 2. Remove manifest generation to prevent overriding
		 * the manifest.json file.
		 */
		return {
			devtool: 'source-map',
			mode: 'development',
			plugins: webpackConfig.plugins.slice( 0, 2 ),
		};
	}

	/**
	 * Convert the names of the outputted files to `min`
	 * when not generating `script-debug` versions.
	 */
	const entries = {};
	Object.keys( webpackConfig.entry ).forEach( key => {
		entries[ key + '.min' ] = webpackConfig.entry[ key ];
	} );
	return {
		entry: entries,
	};
};
