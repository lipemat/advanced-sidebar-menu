import {InspectorControls} from '@wordpress/block-editor';
import {SelectControl, Slot, TextControl, withFilters} from '@wordpress/components';
import {BlockEditProps} from '@wordpress/blocks';
import {Attr, block} from './block';
import Preview from '../Preview';
import Display from '../Display';
import {useSelect} from '@wordpress/data';
import InfoPanel from '../InfoPanel';
import {CONFIG, I18N} from '../../../globals/config';
import {sanitize} from 'dompurify';
import {sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import ErrorBoundary from '../../../components/ErrorBoundary';

import styles from './edit.pcss';

type Props = BlockEditProps<Attr>;

const ProFields = withFilters<Partial<Props> & { postType?: Type }>( 'advanced-sidebar-menu.blocks.pages.pro-fields' )( () =>
	<InfoPanel /> );

/**
 * Pages block content in the editor.
 */
const Edit = ( {attributes, setAttributes, clientId}: Props ) => {
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

	return ( <>
		<InspectorControls>
			<ErrorBoundary>
				<Display
					attributes={attributes}
					setAttributes={setAttributes}
					type={postType} />

				<div className={'components-panel__body is-opened'}>

					<Slot name="AdvancedSidebarMenuPages" />

					<SelectControl
						label={I18N.pages.orderBy.title}
						value={attributes.order_by}
						// @ts-ignore
						labelPosition={'side'}
						options={Object.entries( I18N.pages.orderBy.options ).map( ( [ value, label ] ) => ( {
							value,
							label,
						} ) )}
						onChange={value => {
							setAttributes( {
								order_by: value,
							} );
						}} />
					<TextControl
						//eslint-disable-next-line @wordpress/valid-sprintf
						label={sprintf( I18N.pages.exclude, postType?.labels?.name ?? '' )}
						value={attributes.exclude}
						onChange={value => {
							setAttributes( {
								exclude: value,
							} );
						}} />
					<p>
						<a
							href={I18N.docs.page}
							target="_blank"
							rel="noopener noreferrer">
							{I18N.docs.title}
						</a>
					</p>
				</div>
			</ErrorBoundary>
		</InspectorControls>

		<ErrorBoundary>
			<ProFields
				attributes={attributes}
				setAttributes={setAttributes}
				clientId={clientId}
				postType={postType} />

			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</ErrorBoundary>

	</> );
};

export default Edit;
