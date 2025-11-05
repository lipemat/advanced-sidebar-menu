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
