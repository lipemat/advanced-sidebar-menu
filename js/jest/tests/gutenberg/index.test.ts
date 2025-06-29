import Index from '../../../src/gutenberg/index';
import {getBlockSupports, transformLegacyWidget, translateBlockAttributes} from '../../../src/gutenberg/helpers';
import Preview from '../../../src/gutenberg/blocks/Preview';
import ErrorBoundary from '../../../src/components/ErrorBoundary';

jest.mock( '@wordpress/block-editor', () => ( {} ) );
jest.mock( '@lipemat/js-boilerplate-gutenberg', () => ( {
	autoloadBlocks: jest.fn( () => null ),
} ) );

const DEFAULT = structuredClone( window.ADVANCED_SIDEBAR_MENU );

describe( 'Gutenberg', () => {
	beforeEach( () => {
		window.ADVANCED_SIDEBAR_MENU = structuredClone( DEFAULT );
		window.ADVANCED_SIDEBAR_MENU.isPro = '1';
		window.ADVANCED_SIDEBAR_MENU.isProCommonAttr = '';
	} );

	describe( 'Legacy PRO < 9.9.0', () => {
		it( 'Translates categories block attributes into PRO < 9.9.0 format.', () => {
			Index();
			const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;

			const attributes = blocks.categories?.attributes ?? {};
			expect( attributes ).toMatchInlineSnapshot( `
{
  "clientId": {
    "type": "string",
  },
  "display_all": {
    "default": false,
    "type": "boolean",
  },
  "exclude": {
    "default": "",
    "type": "string",
  },
  "include_childless_parent": {
    "default": false,
    "type": "boolean",
  },
  "include_parent": {
    "default": false,
    "type": "boolean",
  },
  "isServerSideRenderRequest": {
    "type": "boolean",
  },
  "levels": {
    "default": 100,
    "type": "number",
  },
  "new_widget": {
    "default": "list",
    "enum": [
      "list",
      "widget",
    ],
    "type": "string",
  },
  "sidebarId": {
    "type": "string",
  },
  "single": {
    "default": true,
    "type": "boolean",
  },
  "style": {
    "type": "object",
  },
  "title": {
    "type": "string",
  },
}
` );
		} );

		it( 'Skips translation if PRO is not active.', () => {
			window.ADVANCED_SIDEBAR_MENU.isPro = '';

			Index();

			const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;

			const attributes = blocks.categories?.attributes ?? {};
			expect( attributes ).toMatchInlineSnapshot( `
{
  "display_all": {
    "default": false,
    "type": "boolean",
  },
  "exclude": {
    "default": "",
    "type": "string",
  },
  "include_childless_parent": {
    "default": false,
    "type": "boolean",
  },
  "include_parent": {
    "default": false,
    "type": "boolean",
  },
  "levels": {
    "default": 100,
    "type": "number",
  },
  "new_widget": {
    "default": "list",
    "enum": [
      "list",
      "widget",
    ],
    "type": "string",
  },
  "single": {
    "default": true,
    "type": "boolean",
  },
}
` );

			const supports = blocks.categories?.supports ?? {};
			expect( supports ).toMatchInlineSnapshot( `{}` );
		} );


		it( 'Skips translation if PRO is active and common attributes are active.', () => {
			window.ADVANCED_SIDEBAR_MENU.isProCommonAttr = '1';
			Index();

			const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;

			const attributes = blocks.pages?.attributes ?? {};
			expect( attributes ).toMatchInlineSnapshot( `
{
  "display_all": {
    "type": "boolean",
  },
  "exclude": {
    "default": "",
    "type": "string",
  },
  "include_childless_parent": {
    "type": "boolean",
  },
  "include_parent": {
    "type": "boolean",
  },
  "levels": {
    "default": 100,
    "type": "number",
  },
  "order_by": {
    "default": "menu_order",
    "type": "string",
  },
}
` );
			const supports = blocks.pages?.supports ?? {};
			expect( supports ).toMatchInlineSnapshot( `{}` );
		} );


		it( 'Translates pages block attributes into PRO < 9.9.0 format.', () => {
			Index();

			const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;

			const attributes = blocks.pages?.attributes ?? {};
			expect( attributes ).toMatchInlineSnapshot( `
{
  "clientId": {
    "type": "string",
  },
  "display_all": {
    "type": "boolean",
  },
  "exclude": {
    "default": "",
    "type": "string",
  },
  "include_childless_parent": {
    "type": "boolean",
  },
  "include_parent": {
    "type": "boolean",
  },
  "isServerSideRenderRequest": {
    "type": "boolean",
  },
  "levels": {
    "default": 100,
    "type": "number",
  },
  "order_by": {
    "default": "menu_order",
    "type": "string",
  },
  "sidebarId": {
    "type": "string",
  },
  "style": {
    "type": "object",
  },
  "title": {
    "type": "string",
  },
}
` );
		} );


		it( 'Translates page block supports into PRO < 9.9.0 format.', () => {
			Index();

			const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;

			const supports = blocks.pages?.supports ?? {};
			expect( supports ).toMatchInlineSnapshot( `
{
  "anchor": true,
  "html": false,
}
` );
		} );


		it( 'Translates categories block supports into PRO < 9.9.0 format.', () => {
			Index();

			const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;

			const supports = blocks.categories?.supports ?? {};
			expect( supports ).toMatchInlineSnapshot( `
{
  "anchor": true,
  "html": false,
}
` );
		} );
	} );


	describe( 'Passing to PRO', () => {
		test( 'Passed globals', () => {
			window.ADVANCED_SIDEBAR_MENU.isPro = '';
			Index();

			expect( window.ADVANCED_SIDEBAR_MENU ).not.toHaveProperty( 'ErrorBoundary' );
			expect( window.ADVANCED_SIDEBAR_MENU ).not.toHaveProperty( 'getBlockSupports' );
			expect( window.ADVANCED_SIDEBAR_MENU ).not.toHaveProperty( 'transformLegacyWidget' );
			expect( window.ADVANCED_SIDEBAR_MENU ).not.toHaveProperty( 'translateBlockAttributes' );
			expect( window.ADVANCED_SIDEBAR_MENU ).not.toHaveProperty( 'Preview' );

			window.ADVANCED_SIDEBAR_MENU.isPro = '1';
			Index();

			expect( window.ADVANCED_SIDEBAR_MENU.ErrorBoundary ).toBe( ErrorBoundary );
			expect( window.ADVANCED_SIDEBAR_MENU.getBlockSupports ).toBe( getBlockSupports );
			expect( window.ADVANCED_SIDEBAR_MENU.transformLegacyWidget ).toBe( transformLegacyWidget );
			expect( window.ADVANCED_SIDEBAR_MENU.translateBlockAttributes ).toBe( translateBlockAttributes );
			expect( window.ADVANCED_SIDEBAR_MENU.Preview ).toBe( Preview );
		} );
	} );
} );
