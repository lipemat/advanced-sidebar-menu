import {CheckboxControl, PanelBody, SelectControl, Slot} from '@wordpress/components';
import {CONFIG} from '../../globals/config';
import type {Attr as PageAttr} from './pages/block';
import type {Attr as CategoryAttr} from './categories/block';
import {__, sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import {range} from 'lodash';
import {Taxonomy} from '@wordpress/api/taxonomies';
import ErrorBoundary from '../../components/ErrorBoundary';
import {BlockEditProps} from '@wordpress/blocks';
import {PropsWithChildren} from 'react';


export type DisplayOptions = {
	display_all?: boolean;
	include_childless_parent?: boolean;
	include_parent?: boolean;
	levels: number;
}

export type FillProps =
	Pick<BlockEditProps<PageAttr | CategoryAttr>, 'clientId' | 'attributes' | 'setAttributes' | 'name'>
	& { type?: Type<'edit'> | Taxonomy<'edit'> }

type Props = PropsWithChildren<{
	attributes: PageAttr | CategoryAttr;
	setAttributes: BlockEditProps<PageAttr | CategoryAttr>['setAttributes'];
	type?: Type<'edit'> | Taxonomy<'edit'>;
	name: string;
	clientId: string;
}>;

const checkboxes: { [attr in keyof Partial<DisplayOptions>]: string } = {
	/* translators: Selected taxonomy single label */
	include_parent: __( 'Display highest level parent %s', 'advanced-sidebar-menu' ),
	/* translators: Selected taxonomy single label */
	include_childless_parent: __( 'Display menu when there is only the parent %s', 'advanced-sidebar-menu' ),
	/* translators: Selected taxonomy plural label */
	display_all: __( 'Always display child %s', 'advanced-sidebar-menu' ),
};

const LEVEL_OPTIONS: Array<{value: string, label: string}> = [
	{
		value: '100',
		label: __( '- All -', 'advanced-sidebar-menu' ),
	},
	...range( 1, 11 ).map( n => (
		{
			value: n.toString(),
			label: n.toString(),
		}
	) ),
]

/**
 * Display Options shared between widgets.
 *
 * 1. Display the highest level parent page.
 * 2. Display the menu when there is only the parent page.
 * 3. Always display child pages.
 * 5. Display levels of child pages.
 *
 */
const Display = ( {
	attributes,
	setAttributes,
	type,
	name,
	clientId,
	children,
}: Props ) => {
	const showLevels = ( CONFIG.blocks.pages.id === name && '1' === CONFIG.isPro ) || true === attributes.display_all;

	const fillProps: FillProps = {
		type,
		attributes,
		name,
		setAttributes,
		clientId,
	};

	return (
		<PanelBody title={__( 'Display', 'advanced-sidebar-menu' )}>
			{Object.keys( checkboxes ).map( item => {
				let label = type?.labels?.singular_name.toLowerCase() ?? '';
				if ( 'display_all' === item ) {
					label = type?.labels?.name.toLowerCase() ?? '';
				}
				return <CheckboxControl
					key={item}
					//eslint-disable-next-line @wordpress/valid-sprintf
					label={sprintf( checkboxes[ item ], label )}
					checked={true === attributes[ item ]}
					onChange={checked => {
						setAttributes( {
							[ item ]: checked,
						} );
					}}
					// @ts-expect-error -- Not technically available until WP 6.7.
					__nextHasNoMarginBottom
				/>;
			} )}
			{showLevels &&
				<SelectControl
					key={'levels'}
					/* translators: {select HTML input}, {post type plural label} */
					label={sprintf( __( 'Levels of child %s to display', 'advanced-sidebar-menu' ), type?.labels?.name.toLowerCase() ?? '' )}
					className={'advanced-sidebar-menu-display-select'}
					value={attributes.levels.toString()}
					onChange={value => {
						setAttributes( {
							levels: parseInt( value ),
						} )
					}}
					options={LEVEL_OPTIONS}
					// @ts-expect-error -- Not technically available until WP 6.7.
					__next40pxDefaultSize
					__nextHasNoMarginBottom
				/>}
			{children}

			<ErrorBoundary attributes={attributes} block={name} section={'Display/slots'}>
				{CONFIG.blocks.pages.id === name &&
					<Slot<FillProps>
						name="advanced-sidebar-menu/pages/display"
						fillProps={fillProps} />}
				{CONFIG.blocks.categories.id === name &&
					<Slot<FillProps>
						name="advanced-sidebar-menu/categories/display"
						fillProps={fillProps} />}
			</ErrorBoundary>

		</PanelBody>
	);
};

export default Display;
