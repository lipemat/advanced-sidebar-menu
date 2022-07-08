import {autoloadBlocks} from '@lipemat/js-boilerplate-gutenberg';

/**
 * Use our custom autoloader to automatically require,
 * register and add HMR support to Gutenberg related items.
 *
 * Will load from specified directory recursively.
 */
export default () => {
	// Load all blocks
	autoloadBlocks( () => require.context( './blocks', true, /block\.tsx$/ ), module );
}
