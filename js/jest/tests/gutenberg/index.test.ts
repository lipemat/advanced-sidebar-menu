import Index from '../../../src/gutenberg/index';

jest.mock( '@wordpress/block-editor', () => ( {} ) );
jest.mock( '@lipemat/js-boilerplate-gutenberg', () => ( {
	autoloadBlocks: jest.fn( () => null ),
} ) );

describe( 'Gutenberg', () => {
	it( 'Translates block attributes into PRO < 9.9.0 format.', () => {
		Index();

		const blocks = window.ADVANCED_SIDEBAR_MENU.blocks;
		expect( blocks.categories.attributes ).toEqual( {
			...blocks.categories.attributes,
			...blocks.commonAttr,
			...blocks.previewAttr,
		} );
		expect( blocks.pages.attributes ).toEqual( {
			...blocks.pages.attributes,
			...blocks.commonAttr,
			...blocks.previewAttr,
		} );
		if ( window.ADVANCED_SIDEBAR_MENU.blocks.navigation && blocks.navigation ) {
			expect( blocks.navigation.attributes ).toEqual( {
				...blocks.navigation.attributes,
				...blocks.commonAttr,
				...blocks.previewAttr,
			} );
		}
	} );
} );
