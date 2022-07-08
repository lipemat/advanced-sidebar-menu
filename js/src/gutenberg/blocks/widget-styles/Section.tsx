import {Props as StyleProps} from '../WidgetStyles';
import {CONFIG} from '../../../globals/config';
import {PanelColorSettings} from '@wordpress/block-editor';

import styles from './section.pcss';

export type SectionData = {
	label: string;
	prefix: string;
}

export type Bullets = {
	circle: string;
	decimal: string;
	disc: string;
	square: string;
	'upper-alpha': string;
	'lower-alpha': string;
	none: string;
}

export type Fields = {
	_color: string;
	_bg_color: string;
	_font_weight: string;
	_font_size: string;
	_hover_color: string;
	_hover_bg_color: string;
}

export type FontWeights = {
	[ weight: string ]: string;
}


type Props = StyleProps & {
	section: SectionData;
}

const labels = CONFIG.i18n.styles;

/**
 * Section of settings for a menu level.
 *
 * 1. Current Item.
 * 2. Current Item Parent.
 * 3. Highest Level Parent.
 * 4. Child.
 * 5. Grandchild.
 */
const Section = ( {attributes, setAttributes, section}: Props ) => {
	//### START HERE ###########
	//#
	// 1. Finish the settings for each section.
	// Colors are done, but need the rest.
	//
	// 2. Layout the sections.
	// 3. Add "Settings" section (displays first like in widgets).


	return (
		<div className={styles.wrap}>
			<h3>{section.label}</h3>
			<PanelColorSettings
				title={'Color settings'}
				colorSettings={Object.keys( labels.fields )
					.filter( field => field.includes( 'color' ) )
					.map( field => {
						return {
							value: attributes[ section.prefix + field ],
							onChange: color => setAttributes( {
								[ section.prefix + field ]: color,
							} ),
							label: labels.fields[ field ],
						};
					} )}
			/>
		</div>
	);
};

export default Section;
