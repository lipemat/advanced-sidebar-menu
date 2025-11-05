import 'core-js/stable';
import '@testing-library/jest-dom';

import type {JSConfig} from '../src/globals/config';

// Support jQuery in tests.
import $ from 'jquery';
// @ts-expect-error
global.jQuery = $;
// @ts-expect-error
global.$ = global.jQuery;
jQuery.fx.off = true;
jest.dontMock( 'jquery' );

/**
 * Make all properties configurable.
 *
 * Allows us to mock Gutenberg objects which are otherwise not configurable.
 *
 * Fixes Error: "Popover is not declared configurable"
 */
const defineProperty = Object.defineProperty;
Object.defineProperty = ( o, p, c ) => {
	const descriptor = Object.getOwnPropertyDescriptor( o, p );
	if ( ! descriptor || true === descriptor.configurable ) {
		return defineProperty( o, p, Object.assign( {}, c ?? {}, {configurable: true} ) );
	}
	return defineProperty( o, p, c );
};

/**
 * Requirements of Gutenberg version 10.7.4
 *
 * @todo May not be needed once we update the minimum version of WordPress.
 */
// @ts-ignore
global.window.matchMedia = () => ( {
	matches: false,
	addListener: () => {
	},
	removeListener: () => {
	},
} );
// @ts-ignore
global.React = require( 'react' );

// Mock environmental variables
const config: JSConfig = {
	blocks: {
		commonAttr: {
			style: {
				t: 'o',
			},
			title: {
				t: 's',
			},
		},
		previewAttr: {
			clientId: {
				t: 's',
			},
			isServerSideRenderRequest: {
				t: 'b',
			},
			sidebarId: {
				t: 's',
			},
		},
		blockSupport: {
			anchor: true,
			html: false,
		},
		categories: {
			id: 'advanced-sidebar-menu/categories',
			attributes: {
				include_parent: {
					t: 'b',
					d: false,
				},
				include_childless_parent: {
					t: 'b',
					d: false,
				},
				exclude: {
					t: 's',
					d: '',
				},
				display_all: {
					t: 'b',
					d: false,
				},
				single: {
					t: 'b',
					d: true,
				},
				new_widget: {
					t: 's',
					d: 'list',
					e: [
						'list',
						'widget',
					],
				},
				levels: {
					t: 'n',
					d: 100,
				},
			},
		},
		pages: {
			id: 'advanced-sidebar-menu/pages',
			attributes: {
				include_parent: {
					t: 'b',
				},
				include_childless_parent: {
					t: 'b',
				},
				order_by: {
					t: 's',
					d: 'menu_order',
				},
				exclude: {
					t: 's',
					d: '',
				},
				display_all: {
					t: 'b',
				},
				levels: {
					t: 'n',
					d: 100,
				},
			},
		},
	},
	categories: {
		displayEach: {
			widget: 'In a new widget',
			list: 'In another list in the same widget',
		},
	},
	currentScreen: 'post',
	docs: {
		page: 'https://onpointplugins.com/advanced-sidebar-menu/basic-usage/advanced-sidebar-menu-pages/',
		category: 'https://onpointplugins.com/advanced-sidebar-menu/basic-usage/advanced-sidebar-menu-categories/',
	},
	error: '',
	features: [
		'Styling options including borders, bullets, colors, backgrounds, font size and weight.',
		'Accordion menus.',
		'Support for custom navigation menus from Appearance -> Menus.',
		'Select and display custom post types and taxonomies.',
		'Priority support with access to members only support area.',
	],
	isPostEdit: '1',
	isPro: '',
	isWidgets: '',
	pages: {
		orderBy: {
			menu_order: 'Page Order',
			post_title: 'Title',
			post_date: 'Published Date',
		},
	},
	siteInfo: {
		basic: '9.7.1',
		classicWidgets: false,
		menus: [],
		php: '7.4.30',
		pro: false,
		scriptDebug: true,
		WordPress: '6.8.3',
	},
	support: 'https://wordpress.org/support/plugin/advanced-sidebar-menu/#new-topic-0',
};


window.ADVANCED_SIDEBAR_MENU = config;


window.asm_debug = {
	basic: '9.6.0',
	classicWidgets: '',
	menus: {},
	php: '7.4.30',
	pro: '',
	scriptDebug: '1',
	WordPress: '6.6',
	classicEditor: '',
};
