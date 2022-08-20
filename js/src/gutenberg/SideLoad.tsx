import {withFilters} from '@wordpress/components';
import {select} from '@wordpress/data';
import {isEmpty} from 'lodash';

type Props = {
	clientId: string;
};

let firstClientId = '';
/**
 * The customizer area does not include a `PluginArea` component,
 * so our slot fills do not load.
 *
 * We can use filters, but the Fills double up each time
 * another block is added to the page.
 *
 * Track the clientId of the first block we add the Fill to
 * and only return the Fill for that block. The rest of the blocks
 * inherit the Fill from the first block via their Slots.
 */
const SideLoad = ( {clientId, children} ) => {
	if ( ! isEmpty( firstClientId ) && clientId !== firstClientId ) {
		// Make sure block still exists.
		if ( -1 !== select( 'core/block-editor' ).getBlockIndex( firstClientId ) ) {
			return null;
		}
	}
	firstClientId = clientId;
	return children ?? null;
};

export default withFilters<Props>( 'advanced-sidebar-menu.blocks.side-load' )( SideLoad );
