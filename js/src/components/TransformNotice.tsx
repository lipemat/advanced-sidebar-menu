import {__} from '@wordpress/i18n';
import {useState} from 'react';
import {Notice} from '@wordpress/components';

import styles from './transform-notice.pcss';

type Props = Record<string, never>;

export const DISMISS_KEY = 'advanced-sidebar-menu/transform-notice-dismissed';

/**
 * Display a dismissible notice above widget forms to inform
 * users the widget may be transformed into a block.
 *
 * Only applies to legacy widgets which should theoretically
 * be phased out in favor of blocks.
 * This notice is intended to help facilitate the transition.
 *
 */
const TransformNotice = ( {}: Props ) => {
	const [ shown, setShown ] = useState<boolean>( true );

	if ( ! shown ) {
		return null;
	}

	return (
		<Notice
			className={styles.wrap}
			politeness={'polite'}
			onDismiss={() => {
				setShown( false );
				localStorage.setItem( DISMISS_KEY, '1' );
			}}
			actions={[ {
				label: __( 'Blocks are the future of Advanced Sidebar Menu.', 'advanced-sidebar-menu' ),
				url: 'https://onpointplugins.com/advanced-sidebar-menu/advanced-sidebar-menu-gutenberg-blocks/#transform-widgets-to-blocks',
				variant: 'link',
			} ]}
		>
			<span className="dashicons dashicons-arrow-up-alt" />&nbsp;
			{__( 'This widget may be transformed into a block.', 'advanced-sidebar-menu' )}
		</Notice>
	);
};

export default TransformNotice;
