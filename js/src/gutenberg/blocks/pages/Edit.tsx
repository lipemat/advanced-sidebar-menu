import React from 'react';
import {BlockControls, InspectorControls} from '@wordpress/block-editor';
import {CheckboxControl, PanelBody} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';
import {I18N} from '../../../globals/config';
import WidgetStyles from '../WidgetStyles';
import Accordion from '../Accordion';

type Props = BlockEditProps<Attr>;

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes}: Props ) => {
	// @todo - Finish building the various inputs.
	// @todo - Make reusable components for other blocks where possible.
	return ( <>
		<InspectorControls>
			<PanelBody>
				<CheckboxControl
					label={I18N.includeParent}
					checked={!! attributes.include_parent}
					onChange={value => {
						setAttributes( {
							include_parent: !! value,
						} );
					}}
				/>
			</PanelBody>
			<Accordion
				attributes={attributes}
				setAttributes={setAttributes} />
		</InspectorControls>

		<BlockControls>
			<WidgetStyles
				attributes={attributes}
				setAttributes={setAttributes} />
		</BlockControls>

		<Preview attributes={attributes} block={block.id} />
	</>
	);
};

export default Edit;
