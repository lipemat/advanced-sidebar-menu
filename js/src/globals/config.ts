import type {Attr as PageAttr} from '../gutenberg/blocks/pages/block';
import type {Attr as CategoryAttr} from '../gutenberg/blocks/categories/block';
import type {BlockAttributes, BlockSupports} from '@wordpress/blocks';
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
		};
		pages: {
			id: 'advanced-sidebar-menu/pages';
			attributes: BlockAttributes<PageAttr>;
		};
		navigation?: {
			id: 'advanced-sidebar-menu/navigation';
			attributes: BlockAttributes<object>;
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
