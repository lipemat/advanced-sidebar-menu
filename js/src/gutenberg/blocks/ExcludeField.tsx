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
const ExcludeField = ( {
	type,
	attributes,
	setAttributes,
}: Props ) => {
	return (
		<TextControl
			/* translators: Selected post type plural label */
			label={sprintf( __( '%s to exclude (ids, comma separated)', 'advanced-sidebar-menu' ), type?.labels?.name ?? '' )}
			value={attributes.exclude}
			onChange={value => {
				setAttributes( {
					exclude: value,
				} );
			}} />
	);
};

export default withFilters<Props>( 'advanced-sidebar-menu.blocks.exclude-field' )( ExcludeField );
