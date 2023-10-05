module.exports = cssLoaderConfig => {
	if ( '--script-debug' === process.argv[ 2 ] ) {
		/**
		 * Use long names for CSS classes when running
		 * `script-debug` version.
		 */
		const modules = cssLoaderConfig.modules;
		modules.localIdentName = '[name]__[local]__[contenthash:base64:2]';

		return {
			modules,
		};
	}
	return {};
};
