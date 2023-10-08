const config = require( '@lipemat/js-boilerplate/helpers/package-config' );

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
			entry: {
				'advanced-sidebar-menu-block-editor': [
					config.workingDirectory + '/src/block-editor.ts',
				],
				'advanced-sidebar-menu-admin': [
					config.workingDirectory + '/src/admin.ts',
				],
			},
		};
	}

	/**
	 * Convert the names of the outputted files to `min`
	 * when not generating `script-debug` versions.
	 */
	return {
		entry: {
			'advanced-sidebar-menu-block-editor.min': [
				config.workingDirectory + '/src/block-editor.ts',
			],
			'advanced-sidebar-menu-admin.min': [
				config.workingDirectory + '/src/admin.ts',
			],
		},
	};
};
