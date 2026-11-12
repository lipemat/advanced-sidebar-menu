import type {WebpackConfig} from '@lipemat/js-boilerplate/config/webpack.dist.ts';

export default ( webpackConfig: WebpackConfig ) => {
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
	const entries: { [ name: string ]: string } = {};
	const current = webpackConfig.entry as { [ name: string ]: string };
	Object.keys( current ).forEach( key => {
		entries[ key + '.min' ] = current[ key ];
	} );

	return {
		entry: entries,
	};
};
