interface JSConfig {
	blocks: {
		categories: {
			id: string;
		};
		pages: {
			id: string;
		};
	};
	error: false | string;
	i18n: {
		categories: {
			title: string;
			description: string;
			eachCategory: {
				title: string;
				options: { [ option: string ]: string }
			};
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
		noPreview: string;
		pages: {
			title: string;
			description: string;
			keywords: Array<string>;
			orderBy: {
				title: string,
				options: { [ option: string ]: string }
			}
		}
		soMuchMore: string;
		upgrade: string;
	};
	isPro: boolean;
	siteInfo: {
		basic: string;
		pro: string;
		scriptDebug: boolean;
		wordpress: string;
	};
	support: string;
}


declare global {
	interface Window {
		ADVANCED_SIDEBAR_MENU: JSConfig;
		__TEST__?: boolean;
	}
}

export const CONFIG: JSConfig = window.ADVANCED_SIDEBAR_MENU || ( {} as JSConfig );
export const I18N = CONFIG.i18n || {};
