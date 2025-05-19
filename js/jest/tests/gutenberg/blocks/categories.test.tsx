import {settings} from '../../../../src/gutenberg/blocks/categories/block';

jest.mock( '@wordpress/block-editor', () => ( {} ) );


describe( 'Categories Block', () => {
	it( 'should have the correct attributes', () => {
		expect( settings.attributes ).toEqual( {
			clientId: {
				type: 'string',
			},
			display_all: {
				default: false,
				type: 'boolean',
			},
			exclude: {
				default: '',
				type: 'string',
			},
			include_childless_parent: {
				default: false,
				type: 'boolean',
			},
			include_parent: {
				default: false,
				type: 'boolean',
			},
			isServerSideRenderRequest: {
				type: 'boolean',
			},
			levels: {
				default: 100,
				type: 'number',
			},
			new_widget: {
				default: 'list',
				enum: [
					'list',
					'widget',
				],
				type: 'string',
			},
			sidebarId: {
				type: 'string',
			},
			single: {
				default: true,
				type: 'boolean',
			},
			style: {
				type: 'object',
			},
			title: {
				type: 'string',
			},
		} );
	} );
} );
