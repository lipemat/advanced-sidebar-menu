import Index from '../../../src/gutenberg/index';

jest.mock( '@wordpress/block-editor', () => ( {} ) );
jest.mock( '@lipemat/js-boilerplate-gutenberg', () => ( {
	autoloadBlocks: jest.fn( () => null ),
} ) );

describe( 'Gutenberg', () => {
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
} );
