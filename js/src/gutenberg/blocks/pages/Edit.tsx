import {BlockControls, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, withFilters} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';
import Display from '../Display';
import {select} from '@wordpress/data';

type Props = BlockEditProps<Attr>;

const ProFields = withFilters<Partial<Props>>( 'advanced-sidebar-menu.blocks.pages.pro-fields' )( () => <></> );

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes}: Props ) => {
	const postType = select( 'core' ).getPostType( attributes.post_type ?? 'page' );

	// @todo - Finish building the various inputs.
	// @todo - Make reusable components for other blocks where possible.
	return ( <>
		<InspectorControls>
			<PanelBody>
			</PanelBody>
			<Display
				attributes={attributes}
				setAttributes={setAttributes}
				type={postType} />
		</InspectorControls>

		<BlockControls>
		</BlockControls>

		<ProFields attributes={attributes} setAttributes={setAttributes} />

		<Preview attributes={attributes} block={block.id} />
	</> );
};

export default Edit;
