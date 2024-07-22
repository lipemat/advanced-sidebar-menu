import 'core-js/stable';
import 'regenerator-runtime/runtime';
import '@testing-library/jest-dom';

import type {JSConfig} from '../src/globals/config';

// Support jQuery in tests.
import $ from 'jquery';
// @ts-ignore
global.jQuery = $;
// @ts-ignore
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
Object.defineProperty = ( o, p, c ) => defineProperty( o, p, Object.assign( {}, c ?? {}, {configurable: true} ) );

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
	// @ts-ignore
	ErrorBoundary: ( {children} ) => children,
	Preview: () => null,
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
		basic: '9.6.0',
		classicWidgets: false,
		menus: [],
		php: '7.4.30',
		pro: false,
		scriptDebug: true,
		WordPress: '6.6',
	},
	support: 'https://wordpress.org/support/plugin/advanced-sidebar-menu/#new-topic-0',
	blocks: {
		categories: {
			id: 'advanced-sidebar-menu/categories',
			attributes: {
				clientId: {
					type: 'string',
				},
				isServerSideRenderRequest: {
					type: 'boolean',
				},
				sidebarId: {
					type: 'string',
				},
				style: {
					type: 'object',
				},
				title: {
					type: 'string',
				},
				include_parent: {
					type: 'boolean',
					default: false,
				},
				include_childless_parent: {
					type: 'boolean',
					default: false,
				},
				exclude: {
					type: 'string',
					default: '',
				},
				display_all: {
					type: 'boolean',
					default: false,
				},
				single: {
					type: 'boolean',
					default: true,
				},
				new_widget: {
					type: 'string',
					default: 'list',
					enum: [
						'list',
						'widget',
					],
				},
				levels: {
					type: 'number',
					default: 100,
				},
			},
			supports: {
				anchor: true,
			},
		},
		pages: {
			id: 'advanced-sidebar-menu/pages',
			attributes: {
				clientId: {
					type: 'string',
				},
				isServerSideRenderRequest: {
					type: 'boolean',
				},
				sidebarId: {
					type: 'string',
				},
				style: {
					type: 'object',
				},
				title: {
					type: 'string',
				},
				include_parent: {
					type: 'boolean',
				},
				include_childless_parent: {
					type: 'boolean',
				},
				order_by: {
					type: 'string',
					default: 'menu_order',
				},
				exclude: {
					type: 'string',
					default: '',
				},
				display_all: {
					type: 'boolean',
				},
				levels: {
					type: 'number',
					default: 100,
				},
			},
			supports: {
				anchor: true,
			},
		},
	},
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
