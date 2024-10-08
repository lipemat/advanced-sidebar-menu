import {ComponentClass, FunctionComponent} from 'react';
import {TransformLegacy} from '../gutenberg/helpers';
import type {Attr as PageAttr} from '../gutenberg/blocks/pages/block';
import type {Attr as CategoryAttr} from '../gutenberg/blocks/categories/block';
import type {BlockAttributes, BlockSettings} from '@wordpress/blocks';
import type {PreviewOptions} from '../gutenberg/blocks/Preview';

export type Screen = 'site-editor' | 'widgets' | 'post' | 'customize';

export type WPBoolean = '1' | '';

export interface JSConfig {
	blocks: {
		categories: {
			id: 'advanced-sidebar-menu/categories';
			attributes: BlockAttributes<CategoryAttr & PreviewOptions>;
			supports: BlockSettings<CategoryAttr>['supports'];
		};
		pages: {
			id: 'advanced-sidebar-menu/pages';
			attributes: BlockAttributes<PageAttr & PreviewOptions>;
			supports: BlockSettings<PageAttr>['supports'];
		};
		navigation?: {
			id: 'advanced-sidebar-menu/navigation';
			attributes: BlockAttributes<object>;
			supports: BlockSettings<object>['supports'];
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
	ErrorBoundary: ComponentClass<{
		attributes: object,
		block: string;
		section: string;
	}>;
	features: Array<string>;
	isPostEdit: WPBoolean;
	isPro: WPBoolean;
	isWidgets: WPBoolean;
	pages: {
		orderBy: { [ value: string ]: string };
	};
	Preview: FunctionComponent<object>;
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
	transformLegacyWidget: TransformLegacy;
}


declare global {
	interface Window {
		ADVANCED_SIDEBAR_MENU: JSConfig;
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

export const CONFIG: JSConfig = window.ADVANCED_SIDEBAR_MENU;
