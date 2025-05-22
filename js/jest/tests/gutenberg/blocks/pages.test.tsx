import {settings} from '../../../../src/gutenberg/blocks/pages/block';

jest.mock( '../../../../src/gutenberg/blocks/pages/Edit.tsx', () => ( {} ) );


describe( 'Pages Block', () => {
	describe( 'Block Attributes', () => {
		it( 'should have the correct attributes', () => {
			expect( settings.attributes ).toEqual( {
				clientId: {
					type: 'string',
				},
				display_all: {
					type: 'boolean',
				},
				exclude: {
					default: '',
					type: 'string',
				},
				include_childless_parent: {
					type: 'boolean',
				},
				include_parent: {
					type: 'boolean',
				},
				isServerSideRenderRequest: {
					type: 'boolean',
				},
				levels: {
					default: 100,
					type: 'number',
				},
				order_by: {
					default: 'menu_order',
					type: 'string',
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
			} );
		} );
	} );


	it( 'Should have the correct support', () => {
		expect( settings.supports ).toEqual( {
			anchor: true,
			html: false,
		} );
	} );
} );
