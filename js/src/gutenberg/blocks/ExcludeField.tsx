import {TextControl, withFilters} from '@wordpress/components';
import {__, sprintf} from '@wordpress/i18n';
import {Type} from '@wordpress/api/types';
import {Attr as PageAttr} from './pages/block';
import {Attr as CategoryAttr} from './categories/block';
import {BlockEditProps} from '@wordpress/blocks';
import {Taxonomy} from '@wordpress/api/taxonomies';

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
	const help = <span
		dangerouslySetInnerHTML={{ //phpcs:ignore
			__html: sprintf(
				/* translators: 1: Opening anchor tag, 2: Closing anchor tag */
				__( 'For more robust and intuitive exclusions use the %1$sPRO version%2$s.', 'advanced-sidebar-menu' ), '<a href="https://onpointplugins.com/advanced-sidebar-menu/advanced-sidebar-menu-pro-widget-docs/?utm_source=exclude-field&utm_campaign=gopro&utm_medium=wp-dash#customize-link-title" target="_blank">', '</a>' ),
		}} />;

	return (
		<TextControl
			/* translators: Selected post type plural label */
			label={sprintf( __( '%s to exclude (ids, comma separated)', 'advanced-sidebar-menu' ), type?.labels?.name ?? '' )}
			value={attributes.exclude}
			help={help}
			onChange={value => {
				setAttributes( {
					exclude: value,
				} );
			}} />
	);
};

export default withFilters<Props>( 'advanced-sidebar-menu.blocks.exclude-field' )( ExcludeField );
