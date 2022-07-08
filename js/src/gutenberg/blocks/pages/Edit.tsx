import {BlockControls, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, withFilters} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';

type Props = BlockEditProps<Attr>;

const ProFields = withFilters<Partial<Props>>( 'advanced-sidebar-menu.blocks.pages.pro-fields' )( () => <></> );

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes}: Props ) => {
	// @todo - Finish building the various inputs.
	// @todo - Make reusable components for other blocks where possible.
	return ( <>
		<InspectorControls>
			<PanelBody>
			</PanelBody>
		</InspectorControls>

		<BlockControls>
		</BlockControls>

		<ProFields attributes={attributes} setAttributes={setAttributes} />

		<Preview attributes={attributes} block={block.id} />
	</> );
};

export default Edit;
