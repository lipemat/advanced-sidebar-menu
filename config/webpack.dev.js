/*
 * We have multiple entry points, so we need to tell webpack to split the runtime
 * into one chunk that we load separately.
 *
 * We enqueue the runtime chunk in the local-config.php file.
 *
 * @link https://webpack.js.org/configuration/optimization/#optimizationruntimechunk
 */
module.exports = function( config ) {
	return {
		optimization: {
			...config.optimization, runtimeChunk: 'single',
		},
	};
};
