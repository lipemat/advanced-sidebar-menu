const cleanCss = require( '@lipemat/js-boilerplate/lib/postcss-clean' );

module.exports = postcssConfig => {
	if ( '--script-debug' === process.argv[ 2 ] ) {
		/**
		 * Configure `css-clean` to not minify the CSS
		 * file when running `script-debug` version.
		 *
		 * @notice `css-clean` does not support sourcemaps when running
		 *         postcss-clean or our custom version of postcss-clean.
		 *         @link https://github.com/leodido/postcss-clean/issues/17
		 */
		postcssConfig.plugins.pop();
		postcssConfig.plugins.push( cleanCss( {
			format: 'beautify',
			level: 0,
		} ) );

		return {
			plugins: postcssConfig.plugins,
		};
	}
	return {};
};
