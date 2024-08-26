import type {CssLoaderConfig} from '@lipemat/js-boilerplate/config/css-loader.config';

module.exports = ( cssLoaderConfig: CssLoaderConfig ): Partial<CssLoaderConfig> => {
	if ( '--script-debug' === process.argv[ 2 ] ) {
		/**
		 * Use long names for CSS classes when running
		 * `script-debug` version.
		 */
		const modules = cssLoaderConfig.modules;

		if ( 'object' === typeof modules ) {
			modules.localIdentName = '[name]__[local]__[contenthash:base64:2]';
		}

		return {
			modules,
			sourceMap: true,
		};
	}
	return {};
};
