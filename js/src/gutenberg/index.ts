import {autoloadBlocks} from '@lipemat/js-boilerplate-gutenberg';
import Preview from './blocks/Preview';
import {transformLegacyWidget} from './helpers';
import ErrorBoundary from '../components/ErrorBoundary';

export type CommonAttr = {}

/**
 * Use our custom autoloader to automatically require,
 * register and add HMR support to the Gutenberg-related items.
 *
 * Will load from the specified directory recursively.
 */
export default () => {
	// Load all blocks
	autoloadBlocks( () => require.context( './blocks', true, /block\.tsx$/ ), module );

	// Expose helpers and Preview component to the window, so we can use them in PRO.
	window.ADVANCED_SIDEBAR_MENU.ErrorBoundary = ErrorBoundary;
	window.ADVANCED_SIDEBAR_MENU.Preview = Preview;
	window.ADVANCED_SIDEBAR_MENU.transformLegacyWidget = transformLegacyWidget;
}
