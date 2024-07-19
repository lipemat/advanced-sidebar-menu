import cleanCss from '@lipemat/js-boilerplate/lib/postcss-clean';
import type {PostCSSConfig} from '@lipemat/js-boilerplate/config/postcss.config';

module.exports = ( postcssConfig: PostCSSConfig ) => {
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

		const config: Partial<PostCSSConfig> = {
			plugins: postcssConfig.plugins,
		};
		return config;
	}
	return {};
};
