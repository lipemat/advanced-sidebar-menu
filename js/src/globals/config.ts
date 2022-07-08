import {
	Bullets,
	Fields,
	FontWeights,
	SectionData,
} from '../gutenberg/blocks/widget-styles/Section';

type AccordionStyle = {
	contract: string;
	expand: string;
	label: string;
}

interface JSConfig {
	accordion_styles: Record<'arrow' | 'hamburger' | 'plus' | 'solid_arrow', AccordionStyle>;
	accordion_style_key: string;
	blocks: {
		pages: {
			id: string;
		}
	};
	i18n: {
		accordion: string;
		enableAccordion: string;
		includeParent: string;
		hide_styles: string;
		menuStyles: string;
		show_styles: string;
		styles: {
			bullets: Bullets;
			fields: Fields;
			fontWeights: FontWeights;
			sections: SectionData[];
		}
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
