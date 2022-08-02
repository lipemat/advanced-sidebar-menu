import {CheckboxControl, PanelBody, Slot} from '@wordpress/components';
import {CONFIG, I18N} from '../../globals/config';
import type {Attr as PageAttr, setAttributes} from './pages/block';
import type {Attr as CategoryAttr} from './categories/block';
import {sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import {range} from 'lodash';
import reactStringReplace from 'react-string-replace';
import {Taxonomy} from '@wordpress/api/taxonomies';
import ErrorBoundary from '../../components/ErrorBoundary';
import {BlockEditProps} from '@wordpress/blocks';


export type DisplayOptions = {
	display_all: boolean;
	include_childless_parent: boolean;
	include_parent: boolean;
	levels: number;
}

export type FillProps =
	Pick<BlockEditProps<PageAttr | CategoryAttr>, 'clientId' | 'attributes' | 'setAttributes'>
	& { type?: Type | Taxonomy }

type Props = {
	attributes: PageAttr | CategoryAttr;
	setAttributes: setAttributes;
	type?: Type | Taxonomy;
	name: string;
	clientId: string;
};

const checkboxes: { [attr in keyof Partial<DisplayOptions>]: string } = {
	include_parent: I18N.display.highest,
	include_childless_parent: I18N.display.childless,
	display_all: I18N.display.always,
};

/**
 * Display Options shared between widgets.
 *
 * 1. Display the highest level parent page.
 * 2. Display menu when there is only the parent page.
 * 3. Always display child pages.
 * 5. Display levels of child pages.
 *
 */
const Display = ( {attributes, setAttributes, type, name, clientId}: Props ) => {
	const showLevels = ( CONFIG.blocks.pages.id === name && CONFIG.isPro ) || attributes.display_all;

	const fillProps: FillProps = {
		type,
		attributes,
		setAttributes,
		clientId,
	};

	return (
		<PanelBody
			title={I18N.display.title}
		>
			{Object.keys( checkboxes ).map( item => {
				let label = type?.labels?.singular_name.toLowerCase() ?? '';
				if ( 'display_all' === item ) {
					label = type?.labels?.name.toLowerCase() ?? '';
				}
				return <CheckboxControl
					key={item}
					//eslint-disable-next-line @wordpress/valid-sprintf
					label={sprintf( checkboxes[ item ], label )}
					checked={!! attributes[ item ]}
					onChange={value => {
						setAttributes( {
							[ item ]: !! value,
						} );
					}}
				/>;
			} )}
			{showLevels && <div className={'components-base-control'}>
				{reactStringReplace( I18N.display.levels.replace( '%2$s', type?.labels?.name.toLowerCase() ?? '' ), '%1$s',
					() => (
						<select
							key={'levels'}
							value={attributes.levels}
							onChange={ev => setAttributes( {levels: parseInt( ev.target.value )} )}
						>
							<option value="100">
								{I18N.display.all}
							</option>
							{range( 1, 10 ).map( n => <option key={n} value={n}>
								{n}
							</option> )}
						</select>
					) )}
			</div>}

			<ErrorBoundary>
				{CONFIG.blocks.pages.id === name &&
					<Slot<FillProps>
						name="AdvancedSidebarMenuPagesDisplay"
						fillProps={fillProps} />}
				{CONFIG.blocks.categories.id === name &&
					<Slot<FillProps>
						name="AdvancedSidebarMenuCategoriesDisplay"
						fillProps={fillProps} />}
			</ErrorBoundary>

		</PanelBody>
	);
};

export default Display;
