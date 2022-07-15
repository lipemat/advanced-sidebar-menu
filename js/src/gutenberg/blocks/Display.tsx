import {CheckboxControl, PanelBody} from '@wordpress/components';
import {CONFIG, I18N} from '../../globals/config';
import type {Attr, setAttributes} from './pages/block';
import {sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import {range} from 'lodash';
import reactStringReplace from 'react-string-replace';
import {Taxonomy} from '@wordpress/api/taxonomies';


type Props = {
	attributes: Attr;
	setAttributes: setAttributes;
	type: Type | Taxonomy;
};


const checkboxes: { [attr in keyof Partial<Attr>]: string } = {
	include_parent: I18N.display.highest,
	include_childless_parent: I18N.display.childless,
	display_all: I18N.display.always,
};

/**
 * Display Options shared between all 3 widgets.
 *
 * 1. Display the highest level parent page.
 * 2. Display menu when there is only the parent page.
 * 3. Always display child pages.
 * 5. Display levels of child pages.
 *
 */
const Display = ( {attributes, setAttributes, type}: Props ) => {
	return (
		<PanelBody
			title={I18N.display.title}
		>
			{Object.keys( checkboxes ).map( item => {
				let label = type.labels?.singular_name.toLowerCase() ?? '';
				if ( 'display_all' === item ) {
					label = type.labels?.name.toLowerCase() ?? '';
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
			{( CONFIG.isPro || attributes.display_all ) && reactStringReplace( I18N.display.levels.replace( '%2$s', type.labels?.name.toLowerCase() ?? '' ), '%1$s',
				() => <select onChange={ev => setAttributes( {levels: ev.target.value} )}>
					<option value="100">
						{I18N.display.all}
					</option>
					{range( 1, 10 ).map( n => <option
						key={n}
						value={n}
						selected={attributes.levels === n.toString()}>{n}</option> )}
				</select>
			)}
		</PanelBody>
	);
};

export default Display;
