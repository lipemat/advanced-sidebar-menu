import {useSelect} from '@wordpress/data';
import {CONFIG} from '../../../globals/config';
import {sanitize} from 'dompurify';
import {BlockControls, InspectorControls} from '@wordpress/block-editor';
import Preview from '../Preview';
import {Attr, block} from './block';
import {Taxonomy} from '@wordpress/api/taxonomies';
import {BlockEditProps} from '@wordpress/blocks';
import ErrorBoundary from '../../../components/ErrorBoundary';
import Display from '../Display';
import {
	CheckboxControl,
	PanelBody,
	SelectControl,
	Slot,
	TextControl,
} from '@wordpress/components';
import {__, sprintf} from '@wordpress/i18n';
import InfoPanel from '../InfoPanel';

import styles from '../pages/edit.pcss';
import SideLoad from '../../SideLoad';

export type FillProps =
	Pick<BlockEditProps<Attr>, 'clientId' | 'attributes' | 'setAttributes' | 'name'>
	& { type?: Taxonomy }

type Props = BlockEditProps<Attr>;

const Edit = ( {attributes, setAttributes, clientId, name}: Props ) => {
	const taxonomy: Taxonomy | undefined = useSelect( select => {
		const type = select( 'core' ).getTaxonomy( attributes.taxonomy ?? 'category' );
		return type ?? select( 'core' ).getTaxonomy( 'category' );
	}, [ attributes.taxonomy ] );

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
		type: taxonomy,
		attributes,
		name,
		setAttributes,
		clientId,
	};

	return ( <>
		<InspectorControls>
			<ErrorBoundary attributes={attributes} block={name}>
				{( 'widgets' === CONFIG.currentScreen || 'site-editor' === CONFIG.currentScreen || 'customize' === CONFIG.currentScreen ) &&
					<PanelBody>
						<TextControl
							value={attributes.title ?? ''}
							label={__( 'Title', 'advanced-sidebar-menu' )}
							onChange={title => setAttributes( {title} )} />
					</PanelBody>}
				<Display
					attributes={attributes}
					clientId={clientId}
					name={name}
					setAttributes={setAttributes}
					type={taxonomy}
				>
					{/* Not offering "Display categories on single posts"
		                when editing a post because this must be true, or
		                the block won't display.

		                We default the attribute to `true` if we are editing
		                a post during register of block attributes. */}
					{'post' !== CONFIG.currentScreen && <CheckboxControl
						/* translators: Selected taxonomy plural label */
						label={sprintf( __( 'Display %s on single posts', 'advanced-sidebar-menu' ), taxonomy?.labels?.name.toLowerCase() ?? '' )}
						checked={!! attributes.single}
						onChange={value => {
							setAttributes( {
								single: !! value,
							} );
						}}
					/>}
					{/*
						  Only widget screens support this option because we
						  have no widget wrap to use on other screens, so they are
						  list only. */}
					{( 'widgets' === CONFIG.currentScreen ) && attributes.single &&
						<SelectControl<'list' | 'widget'>
							/* translators: Selected taxonomy single label */
							label={sprintf( __( 'Display each single post\'s %s', 'advanced-sidebar-menu' ), taxonomy?.labels?.name.toLowerCase() ?? '' )}
							value={attributes.new_widget}
							options={Object.entries( CONFIG.categories.displayEach ).map( ( [ value, label ] ) => ( {
								value,
								label,
							} ) )}
							/* eslint-disable-next-line camelcase */
							onChange={new_widget => setAttributes( {new_widget} )}
						/>}
				</Display>

				<div className={'components-panel__body is-opened'}>

					<Slot<FillProps>
						name="advanced-sidebar-menu/categories/general"
						fillProps={fillProps} />

					<TextControl
						/* translators: Selected post type plural label */
						label={sprintf( __( '%s to exclude (ids, comma separated)', 'advanced-sidebar-menu' ), taxonomy?.labels?.name ?? '' )}
						value={attributes.exclude}
						onChange={value => {
							setAttributes( {
								exclude: value,
							} );
						}} />
					<p>
						<a
							href={CONFIG.docs.category}
							target="_blank"
							rel="noopener noreferrer"
						>
							{__( 'block documentation', 'advanced-sidebar-menu' )}
						</a>
					</p>
				</div>

				<Slot<FillProps>
					name="advanced-sidebar-menu/categories/inspector"
					fillProps={fillProps} />

			</ErrorBoundary>
		</InspectorControls>

		<BlockControls>
			<ErrorBoundary attributes={attributes} block={name}>
				<Slot<FillProps>
					name="advanced-sidebar-menu/categories/block-controls"
					fillProps={fillProps} />
			</ErrorBoundary>
		</BlockControls>

		<InfoPanel clientId={clientId} />

		<ErrorBoundary attributes={attributes} block={name}>
			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</ErrorBoundary>

		<SideLoad clientId={clientId} />
	</> );
};

export default Edit;
