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
import {CheckboxControl, Slot, TextControl} from '@wordpress/components';
import {sprintf} from '@wordpress/i18n';
import InfoPanel from '../InfoPanel';

import styles from '../pages/edit.pcss';

export type FillProps =
	Pick<BlockEditProps<Attr>, 'clientId' | 'attributes' | 'setAttributes'>
	& { type?: Taxonomy }

type Props = BlockEditProps<Attr>;

const labels = I18N.categories;


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
					type={taxonomy}
				>
					{/* Not offering "Display categories on single posts"
		                when editing a post because this must be true, or
		                the block won't display.

		                We default the attribute to `true` if we are editing
		                a post during register of block attributes. */}
					{! CONFIG.isPostEdit && <CheckboxControl
						//eslint-disable-next-line @wordpress/valid-sprintf
						label={sprintf( labels.onSingle, taxonomy?.labels?.name.toLowerCase() ?? '' )}
						checked={!! attributes.single}
						onChange={value => {
							setAttributes( {
								single: !! value,
							} );
						}}
					/>}
					{/* Not offering the option to display in a new widget
						as we don't really have the widget wraps available
						to repeat.

						The value of the `new_widget` is set to `list` by default
						by block attributes. */}
				</Display>

				<div className={'components-panel__body is-opened'}>

					<ErrorBoundary>
						<Slot<FillProps>
							name="AdvancedSidebarMenuCategoriesGeneral"
							fillProps={fillProps} />
					</ErrorBoundary>

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

			{/* @notice Must live within InspectorControls! */}
			<Slot<FillProps>
				name="AdvancedSidebarMenuCategories"
				fillProps={fillProps} />

		</InspectorControls>

		<InfoPanel />

		<ErrorBoundary>
			<Preview<Attr> attributes={attributes} block={block.id} clientId={clientId} />
		</ErrorBoundary>

	</> );
};

export default Edit;
