import Preview, {type ServerSideRenderRequired} from '../../../../src/gutenberg/blocks/Preview';
import {fireEvent, render} from '@testing-library/react';
import {addFilter} from '@wordpress/hooks';
import CategorySimpleAccordion from '../../../fixtures/category-counts-accordion';

const mockServerSideRender = jest.fn();

jest.mock( '@wordpress/server-side-render', () => {
	return ( attr: ServerSideRenderRequired ) => mockServerSideRender( attr );
} );

jest.mock( '@wordpress/block-editor', () => {
	return {
		useBlockProps: () => {
		},
	};
} );

const ControlledPreview = ( {attributes} ) => {
	return <Preview
		attributes={attributes}
		block={'advanced-sidebar-menu/pages'}
		clientId={'unit-test-client-id'}
	/>;
};

describe( 'Preview component', () => {
	/**
	 * We can't test a component from the basic version within the PRO version, so
	 * instead we test the existence and usage of the filter.
	 *
	 * @see advanced-sidebar-menu-pro/js/jest/tests/gutenberg/blocks/widget-styles/section.test.tsx:'Does not refresh preview when section is opened or closed'
	 */
	it( 'Does not refresh when `closed_sections` attribute is updated.', () => {
		const attributes = {
			order_by: 'title',
			closed_sections: [ 'settings' ],
		};
		const {rerender} = render( <ControlledPreview attributes={attributes} /> );

		expect( mockServerSideRender ).toHaveBeenCalledTimes( 1 );
		let calledWith = mockServerSideRender.mock.calls[ 0 ][ 0 ].attributes;
		expect( calledWith ).toStrictEqual( {
			...attributes,
			isServerSideRenderRequest: true,
			clientId: 'unit-test-client-id',
			sidebarId: '',
		} );

		// Mimic filter given in PRO version. @see settings.render
		addFilter( 'advanced-sidebar-menu.blocks.preview.attributes', 'advanced-sidebar-menu-pro', ( attr: object ) => {
			return {...attr, closed_sections: []};
		} );

		rerender( <ControlledPreview attributes={attributes} /> );

		expect( mockServerSideRender ).toHaveBeenCalledTimes( 2 );
		calledWith = mockServerSideRender.mock.calls[ 1 ][ 0 ].attributes;
		expect( calledWith ).toStrictEqual( {
			order_by: 'title',
			closed_sections: [],
			isServerSideRenderRequest: true,
			clientId: 'unit-test-client-id',
			sidebarId: '',
		} );
	} );


	it( 'prevents link events from firing on click', () => {
		// Set up test attributes
		const attributes = {
			order_by: 'title',
		};
		mockServerSideRender.mockImplementation( () => CategorySimpleAccordion );
		const {getByTestId, getByText} = render( <ControlledPreview attributes={attributes} /> );

		const NO_FIRE = [
			'Indoor Hobbies',
			'(0)',
			'City Planter',
			'Boating',
		];

		const FIRE = [
			'simple-li',
			'simple-ul',
			'simple-icon',
		];
		NO_FIRE.forEach( text => {
			expect( getByText( text ) ).toBeInTheDocument();
			const result = fireEvent.click( getByText( text ) );
			expect( result ).toBe( false );
		} );
		FIRE.forEach( testId => {
			expect( getByTestId( testId ) ).toBeInTheDocument();
			const result = fireEvent.click( getByTestId( testId ) );
			expect( result ).toBe( true );
		} );
	} );
} );
