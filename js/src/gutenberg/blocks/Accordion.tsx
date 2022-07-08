import {I18N} from '../../globals/config';
import {CheckboxControl, PanelBody} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';

export type AccordionOptions = {
	enable_accordion: boolean;
}

type Props = Pick<BlockEditProps<AccordionOptions>, 'attributes' | 'setAttributes'>;

const Accordion = ( {attributes, setAttributes}: Props ) => {
	return (
		<PanelBody
			title={I18N.accordion}
			initialOpen={attributes.enable_accordion}>
			<CheckboxControl
				label={I18N.enableAccordion}
				checked={!! attributes.enable_accordion}
				onChange={value => {
					setAttributes( {
						enable_accordion: !! value,
					} );
				}}
			/>
		</PanelBody>
	);
};

export default Accordion;
