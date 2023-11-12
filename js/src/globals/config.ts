import {ComponentClass, FunctionComponent} from 'react';
import {TransformLegacy} from '../gutenberg/helpers';
import type {Attr as PageAttr} from '../gutenberg/blocks/pages/block';
import type {Attr as CategoryAttr} from '../gutenberg/blocks/categories/block';
import type {BlockAttributes, BlockSettings} from '@wordpress/blocks';

export type Screen = 'site-editor' | 'widgets' | 'post' | 'customize';

export type WPBoolean = '1' | '';

interface JSConfig {
	blocks: {
		categories: {
			id: 'advanced-sidebar-menu/categories';
			attributes: BlockAttributes<CategoryAttr>;
			supports: BlockSettings<CategoryAttr>['supports'];
		};
		pages: {
			id: 'advanced-sidebar-menu/pages';
			attributes: BlockAttributes<PageAttr>;
			supports: BlockSettings<PageAttr>['supports'];
		};
		navigation?: {
			id: 'advanced-sidebar-menu/navigation';
			attributes: BlockAttributes<any>;
			supports: BlockSettings<any>['supports'];
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
		attributes: Record<string, any>,
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
	Preview: FunctionComponent<any>;
	siteInfo: {
		basic: string;
		classicWidgets: boolean;
		php: string;
		pro: string;
		scriptDebug: boolean;
		wordpress: string;
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
