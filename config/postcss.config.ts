import cleanCss from '@lipemat/js-boilerplate-shared/lib/postcss-clean.js';
import type {PostcssConfig} from '@lipemat/js-boilerplate-shared/helpers/postcss-config.js';

export default ( postcssConfig: PostcssConfig ) => {
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

		const config: Partial<PostcssConfig> = {
			plugins: postcssConfig.plugins,
		};
		return config;
	}
	return {};
};
