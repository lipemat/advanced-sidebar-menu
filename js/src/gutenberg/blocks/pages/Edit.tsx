import {BlockControls, InspectorControls} from '@wordpress/block-editor';
import {SelectControl, Slot, TextControl} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';
import Display from '../Display';
import {useSelect} from '@wordpress/data';
import InfoPanel from '../InfoPanel';
import {CONFIG} from '../../../globals/config';
import {sanitize} from 'dompurify';
import {__, sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import ErrorBoundary from '../../../components/ErrorBoundary';

import styles from './edit.pcss';

export type FillProps =
	Pick<BlockEditProps<Attr>, 'clientId' | 'attributes' | 'setAttributes'>
	& { type?: Type }

type Props = BlockEditProps<Attr>;

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes, clientId, name}: Props ) => {
	const postType: Type | undefined = useSelect( select => {
		const type = select( 'core' ).getPostType( attributes.post_type ?? 'page' );
		return type ?? select( 'core' ).getPostType( 'page' );
	}, [ attributes.post_type ] );

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

	const fillProps: FillProps = {
		type: postType,
		attributes,
		setAttributes,
		clientId,
	};

	return ( <>
		<InspectorControls>
			<ErrorBoundary>
				<Display
					attributes={attributes}
					clientId={clientId}
					name={name}
					setAttributes={setAttributes}
					type={postType} />

				<div className={'components-panel__body is-opened'}>

					<Slot<FillProps>
						name="advanced-sidebar-menu/pages/general"
						fillProps={fillProps} />

					<SelectControl
						label={__( 'Order by', 'advanced-sidebar-menu' )}
						value={attributes.order_by}
						labelPosition={'side'}
						options={Object.entries( CONFIG.orderBy ).map( ( [ value, label ] ) => ( {
							value,
							label,
						} ) )}
						onChange={value => {
							setAttributes( {
								order_by: value,
							} );
						}} />
					<TextControl
						/* translators: Selected post type plural label */
						label={sprintf( __( '%s to exclude (ids, comma separated)', 'advanced-sidebar-menu' ), postType?.labels?.name ?? '' )}
						value={attributes.exclude}
						onChange={value => {
							setAttributes( {
								exclude: value,
							} );
						}} />
					<p>
						<a
							href={CONFIG.docs.page}
							target="_blank"
							rel="noopener noreferrer"
						>
							{__( 'block documentation', 'advanced-sidebar-menu' )}
						</a>
					</p>
				</div>

				<Slot<FillProps>
					name="advanced-sidebar-menu/pages/inspector"
					fillProps={fillProps} />

			</ErrorBoundary>
		</InspectorControls>

		<BlockControls>
			<ErrorBoundary>
				<Slot<FillProps>
					name="advanced-sidebar-menu/pages/block-controls"
					fillProps={fillProps} />
			</ErrorBoundary>
		</BlockControls>

		<InfoPanel />

		<ErrorBoundary>
			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</ErrorBoundary>

	</> );
};

export default Edit;
