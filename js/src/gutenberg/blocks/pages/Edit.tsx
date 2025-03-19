import {BlockControls, InspectorControls} from '@wordpress/block-editor';
import {PanelBody, SelectControl, Slot, TextControl} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';
import Display from '../Display';
import {useSelect} from '@wordpress/data';
import InfoPanel from '../InfoPanel';
import {CONFIG} from '../../../globals/config';
import {__} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import ErrorBoundary from '../../../components/ErrorBoundary';
import SideLoad from '../../SideLoad';
import {isScreen} from '../../helpers';
import ExcludeField from '../ExcludeField';
import DOMPurify from 'dompurify';

import styles from './edit.pcss';


export type FillProps =
	Pick<BlockEditProps<Attr>, 'clientId' | 'attributes' | 'setAttributes' | 'name'>
	& { type?: Type }

type Props = BlockEditProps<Attr>;

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes, clientId, name}: Props ) => {
	const postType: Type<'edit'> | undefined = useSelect( select => {
		const type = select( 'core' ).getPostType( attributes.post_type ?? 'page' );
		return type ?? select( 'core' ).getPostType( 'page' );
	}, [ attributes.post_type ] );

	// We have a version conflict or license error.
	if ( '' !== CONFIG.error ) {
		return ( <>
			<InspectorControls>
				<div
					className={styles.error}
					dangerouslySetInnerHTML={{__html: DOMPurify.sanitize( CONFIG.error )}} />
			</InspectorControls>
			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</> );
	}

	const fillProps: FillProps = {
		type: postType,
		attributes,
		name,
		setAttributes,
		clientId,
	};


	return ( <>
		<InspectorControls>
			{isScreen( [ 'widgets', 'site-editor', 'customize' ] ) && <PanelBody>
				<TextControl
					value={attributes.title ?? ''}
					label={__( 'Title', 'advanced-sidebar-menu' )}
					onChange={title => setAttributes( {title} )} />
			</PanelBody>}
			<ErrorBoundary attributes={attributes} block={name} section={'pages/Edit/general'}>
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
						options={Object.entries( CONFIG.pages.orderBy ).map( ( [ value, label ] ) => ( {
							value,
							label,
						} ) )}
						onChange={value => {
							setAttributes( {
								order_by: value,
							} );
						}} />

					<ExcludeField
						type={postType}
						attributes={attributes}
						setAttributes={setAttributes} />

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
			</ErrorBoundary>
			<ErrorBoundary attributes={attributes} block={name} section={'pages/Edit/inspector'}>
				<Slot<FillProps>
					name="advanced-sidebar-menu/pages/inspector"
					fillProps={fillProps} />

			</ErrorBoundary>
		</InspectorControls>

		<BlockControls>
			<ErrorBoundary
				attributes={attributes}
				block={name}
				section={'pages/Edit/block-controls'}
			>
				<Slot<FillProps>
					name="advanced-sidebar-menu/pages/block-controls"
					fillProps={fillProps} />
			</ErrorBoundary>
		</BlockControls>

		<InfoPanel clientId={clientId} />

		<ErrorBoundary attributes={attributes} block={name} section={'pages/Edit/preview'}>
			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</ErrorBoundary>

		<SideLoad clientId={clientId} />
	</> );
};

export default Edit;
