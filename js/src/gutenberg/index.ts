import {autoloadBlocks} from '@lipemat/js-boilerplate-gutenberg';
import Preview from './blocks/Preview';
import {transformLegacyWidget} from './helpers';

/**
 * Use our custom autoloader to automatically require,
 * register and add HMR support to Gutenberg related items.
 *
 * Will load from specified directory recursively.
 */
export default () => {
	// Load all blocks
	autoloadBlocks( () => require.context( './blocks', true, /block\.tsx$/ ), module );

	// Expose helpers and Preview component to window, so we can use them in PRO.
	window.ADVANCED_SIDEBAR_MENU.Preview = Preview;
	window.ADVANCED_SIDEBAR_MENU.transformLegacyWidget = transformLegacyWidget;
}
