import {InspectorControls} from '@wordpress/block-editor';
import {withFilters} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';
import Display from '../Display';
import {select} from '@wordpress/data';
import InfoPanel from '../InfoPanel';
import {CONFIG} from '../../../globals/config';
import {sanitize} from 'dompurify';

import styles from './edit.pcss';

type Props = BlockEditProps<Attr>;

const ProFields = withFilters<Partial<Props>>( 'advanced-sidebar-menu.blocks.pages.pro-fields' )( () =>
	<InfoPanel /> );

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes, clientId}: Props ) => {
	const postType = select( 'core' ).getPostType( attributes.post_type ?? 'page' );

	// We have a version conflict or license error.
	if ( '' !== CONFIG.error ) {
		return ( <>
			<InspectorControls>
				<div
					className={styles.error}
					dangerouslySetInnerHTML={{__html: sanitize( CONFIG.error )}} />
			</InspectorControls>
			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</> );
	}


	// @todo - Finish building the various inputs.
	// @todo - Make reusable components for other blocks where possible.
	return ( <>
		<InspectorControls>
			<Display
				attributes={attributes}
				setAttributes={setAttributes}
				type={postType} />
		</InspectorControls>

		<ProFields
			attributes={attributes}
			setAttributes={setAttributes}
			clientId={clientId} />

		<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
	</> );
};

export default Edit;
