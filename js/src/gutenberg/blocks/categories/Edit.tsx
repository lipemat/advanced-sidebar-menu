import {useSelect} from '@wordpress/data';
import {CONFIG, I18N} from '../../../globals/config';
import {sanitize} from 'dompurify';
import {InspectorControls} from '@wordpress/block-editor';
import Preview from '../Preview';
import {Attr, block} from './block';
import {Taxonomy} from '@wordpress/api/taxonomies';
import {BlockEditProps} from '@wordpress/blocks';
import ErrorBoundary from '../../../components/ErrorBoundary';
import Display from '../Display';
import {Slot, TextControl, withFilters} from '@wordpress/components';
import {sprintf} from '@wordpress/i18n';
import InfoPanel from '../InfoPanel';

import styles from '../pages/edit.pcss';


type Props = BlockEditProps<Attr>;

const ProFields = withFilters<Partial<Props> & { taxonomy?: Taxonomy }>( 'advanced-sidebar-menu.blocks.categories.pro-fields' )( () =>
	<InfoPanel /> );

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
	return ( <>
		<InspectorControls>
			<ErrorBoundary>
				<Display
					attributes={attributes}
					name={name}
					setAttributes={setAttributes}
					type={taxonomy} />

				<div className={'components-panel__body is-opened'}>

					<Slot name="AdvancedSidebarMenuCategories" />

					<TextControl
						//eslint-disable-next-line @wordpress/valid-sprintf
						label={sprintf( I18N.exclude, taxonomy?.labels?.name ?? '' )}
						value={attributes.exclude}
						onChange={value => {
							setAttributes( {
								exclude: value,
							} );
						}} />
					<p>
						<a
							href={I18N.docs.category}
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
				taxonomy={taxonomy} />

			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</ErrorBoundary>

	</> );
};

export default Edit;
