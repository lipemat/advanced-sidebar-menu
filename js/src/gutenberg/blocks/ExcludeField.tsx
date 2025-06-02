import {TextControl, withFilters} from '@wordpress/components';
import {__, sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import {Attr as PageAttr} from './pages/block';
import {Attr as CategoryAttr} from './categories/block';
import {BlockEditProps} from '@wordpress/blocks';
import {Taxonomy} from '@wordpress/api/taxonomies';
import DOMPurify from 'dompurify';

type Props = {
	attributes: PageAttr | CategoryAttr;
	setAttributes: BlockEditProps<PageAttr | CategoryAttr>['setAttributes'];
	type?: Type<'edit'> | Taxonomy<'edit'>;
};

/**
 * The Exclude field shared between widgets.
 *
 * Removed and replaced via filter by the PRO version.
 */
const ExcludeField = ( {type, attributes, setAttributes}: Props ) => {
	const help = <p>
		<br />
		<span
			dangerouslySetInnerHTML={{
				__html: DOMPurify.sanitize( sprintf(
					/* translators: 1: Opening anchor tag, 2: Closing anchor tag */
					__( 'For more robust and intuitive exclusions use the %1$sPRO version%2$s.', 'advanced-sidebar-menu' ), '<a href="https://onpointplugins.com/advanced-sidebar-menu/advanced-sidebar-menu-pro-widget-docs/advanced-sidebar-menu-pro-excluding-menu-items/?utm_source=exclude-field&utm_campaign=gopro&utm_medium=wp-dash" target="_blank" rel="noopener noreferrer">', '</a>' ), {ADD_ATTR: [ 'target' ]} ),
			}} />
	</p>;

	return (
		<TextControl
			/* translators: Selected post type plural label */
			label={sprintf( __( '%s to exclude', 'advanced-sidebar-menu' ), type?.labels?.name ?? '' )}
			value={attributes.exclude}
			help={<>{__( 'ids, comma separated', 'advanced-sidebar-menu' )}{help}</>}
			onChange={value => {
				setAttributes( {
					exclude: value,
				} );
			}}
			// @ts-expect-error -- Not technically supported until WP 6.7
			__nextHasNoMarginBottom
		/>
	);
};

export default withFilters<Props>( 'advanced-sidebar-menu.blocks.exclude-field' )( ExcludeField );
