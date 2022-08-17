import {ComponentClass, FunctionComponent} from 'react';
import {TransformLegacy} from '../gutenberg/helpers';

interface JSConfig {
	blocks: {
		categories: {
			id: string;
		};
		pages: {
			id: string;
		};
		navigation?: {
			id: string;
		}
	};
	docs: {
		page: string;
		category: string;
	};
	error: false | string;
	ErrorBoundary: ComponentClass<{ attributes: Record<string, any>, block: string }>;
	features: Array<string>;
	isPostEdit: boolean;
	isPro: boolean;
	isWidgets: boolean;
	pages: {
		orderBy: { [ value: string ]: string };
	};
	Preview: FunctionComponent<any>;
	siteInfo: {
		basic: string;
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
		__TEST__?: boolean;
	}
}

export const CONFIG: JSConfig = window.ADVANCED_SIDEBAR_MENU || ( {} as JSConfig );
