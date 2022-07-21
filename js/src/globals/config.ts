interface JSConfig {
	blocks: {
		pages: {
			id: string;
		}
	};
	error: false | string;
	isPro: boolean;
	i18n: {
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
		}
		features: Array<string>;
		goPro: string;
		pages: {
			title: string;
			description: string;
			exclude: string;
			keywords: Array<string>;
			orderBy: {
				title: string,
				options: { [ option: string ]: string }
			}
		}
		soMuchMore: string;
		upgrade: string;
	};
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
