import {autoloadBlocks} from '@lipemat/js-boilerplate-gutenberg';
import Preview from './blocks/Preview';
import {getBlockSupports, TransformLegacy, transformLegacyWidget, translateBlockAttributes} from './helpers';
import ErrorBoundary from '../components/ErrorBoundary';
import NavigationIcon from './blocks/NavigationIcon';


// @see content/plugins/advanced-sidebar-menu-pro/js/src/globals/config.ts
export type PassedGlobals = Partial<{
	ErrorBoundary: typeof ErrorBoundary;
	NavigationIcon: typeof NavigationIcon;
	Preview: typeof Preview;
	getBlockSupports: typeof getBlockSupports;
	transformLegacyWidget: TransformLegacy;
	translateBlockAttributes: typeof translateBlockAttributes;
}>;

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
	if ( '' === window.ADVANCED_SIDEBAR_MENU.isPro ) {
		return;
	}
	window.ADVANCED_SIDEBAR_MENU.ErrorBoundary = ErrorBoundary;
	window.ADVANCED_SIDEBAR_MENU.NavigationIcon = NavigationIcon;
	window.ADVANCED_SIDEBAR_MENU.Preview = Preview;
	window.ADVANCED_SIDEBAR_MENU.getBlockSupports = getBlockSupports;
	window.ADVANCED_SIDEBAR_MENU.transformLegacyWidget = transformLegacyWidget;
	window.ADVANCED_SIDEBAR_MENU.translateBlockAttributes = translateBlockAttributes;
}
