import type {Attr as PageAttr} from '../gutenberg/blocks/pages/block';
import type {Attr as CategoryAttr} from '../gutenberg/blocks/categories/block';
import type {BlockAttributes, BlockSettings, BlockSupports} from '@wordpress/blocks';
import type {CommonAttr, ServerSideRenderRequired} from '../gutenberg/blocks/Preview';
import type {PassedGlobals} from '../gutenberg/index';

export type Screen = 'site-editor' | 'widgets' | 'post' | 'customize';

export type WPBoolean = '1' | '';

export interface JSConfig {
	blocks: {
		commonAttr: BlockAttributes<CommonAttr>;
		previewAttr: BlockAttributes<ServerSideRenderRequired>;
		blockSupport: BlockSupports;
		categories: {
			id: 'advanced-sidebar-menu/categories';
			attributes: BlockAttributes<CategoryAttr>;
			// @todo: Remove when required PRO version is 9.9.0+.
			supports?: BlockSettings<CategoryAttr>['supports'];
		};
		pages: {
			id: 'advanced-sidebar-menu/pages';
			attributes: BlockAttributes<PageAttr>;
			// @todo: Remove when required PRO version is 9.9.0+.
			supports?: BlockSettings<PageAttr>['supports'];
		};
		navigation?: {
			id: 'advanced-sidebar-menu/navigation';
			attributes: BlockAttributes<object>;
			// @todo: Remove when required PRO version is 9.9.0+.
			supports?: BlockSettings<object>['supports'];
		}
	};
	categories: {
		displayEach: {
			list: string;
			widget: string;
		};
	};
	currentScreen: Screen;
	docs: {
		page: string;
		category: string;
	};
	error: string;
	features: Array<string>;
	isPostEdit: WPBoolean;
	isPro: WPBoolean;
	isProCommonAttr?: WPBoolean;
	isWidgets: WPBoolean;
	pages: {
		orderBy: { [ value: string ]: string };
	};
	siteInfo: {
		basic: string;
		classicWidgets: boolean;
		menus: Array<object>;
		php: string;
		pro: string | false;
		scriptDebug: boolean;
		WordPress: string;
	};
	support: string;
}


declare global {
	interface Window {
		ADVANCED_SIDEBAR_MENU: JSConfig & PassedGlobals;
		advancedSidebarMenuAdmin: {
			init: () => void;
			handlePreviews: () => void;
			showHideElements: () => void;
			clickReveal: ( id: string ) => void;
			setHideState( target: JQuery<HTMLElement> ): void;
		};
		__TEST__?: boolean;
	}
}

export const CONFIG: JSConfig & PassedGlobals = window.ADVANCED_SIDEBAR_MENU;
