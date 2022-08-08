import {FunctionComponent} from 'react';
import {TransformLegacy} from '../gutenberg/helpers';

type TitleOptions = {
	title: string,
	options: { [ value: string ]: string }
}

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
	error: false | string;
	i18n: {
		categories: {
			title: string;
			eachCategory: TitleOptions;
			keywords: Array<string>;
			onSingle: string;
		}
		display: {
			title: string;
			highest: string;
			childless: string;
			always: string;
			levels: string;
			all: string;
		};
		docs: {
			title: string;
			page: string;
			category: string;
		};
		exclude: string;
		features: Array<string>;
		goPro: string;
		navigation?: {
			title: string;
		};
		noPreview: string;
		pages: {
			title: string;
			keywords: Array<string>;
			orderBy: TitleOptions;
		};
		postType: string;
		soMuchMore: string;
		upgrade: string;
	};
	isPostEdit: boolean;
	isPro: boolean;
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
export const I18N: JSConfig['i18n'] = CONFIG.i18n || {};
