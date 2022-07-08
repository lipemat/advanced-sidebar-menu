interface JSConfig {
	blocks: {
		pages: {
			id: string;
		}
	};
	i18n: {
		widgetPages: string;
	};
}


declare global {
	interface Window {
		ADVANCED_SIDEBAR_MENU: JSConfig;
		__TEST__?: boolean;
	}
}

export const CONFIG: JSConfig = window.ADVANCED_SIDEBAR_MENU || ( {} as JSConfig );
export const I18N = CONFIG.i18n || {};
