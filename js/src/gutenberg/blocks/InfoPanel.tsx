import {CONFIG} from '../../globals/config';
import {Button, PanelBody, withFilters} from '@wordpress/components';
import {InspectorControls} from '@wordpress/block-editor';
import {decodeEntities} from '@wordpress/html-entities';
import {__} from '@wordpress/i18n';

import styles from './info-panel.pcss';

type Props = {
	clientId: string;
};

const InfoPanel = ( {}: Props ) => {
	return ( <InspectorControls>
		<PanelBody
			title={__( 'Advanced Sidebar Menu PRO', 'advanced-sidebar-menu' )}
			className={styles.wrap}
		>
			<ul>
				{CONFIG.features.map( feature =>
					<li key={feature}>{decodeEntities( feature )}</li> )}
				<li>
					<a
						href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/?utm_source=block-more&utm_campaign=gopro&utm_medium=wp-dash"
						target="_blank"
						style={{textDecoration: 'none'}}
						rel="noreferrer"
					>
						{__( 'So much more…', 'advanced-sidebar-menu' )}
					</a>
				</li>
			</ul>
			<Button
				className={styles.button}
				href={'https://onpointplugins.com/product/advanced-sidebar-menu-pro/?utm_source=block-upgrade&utm_campaign=gopro&utm_medium=wp-dash#buy-now-choices'}
				target={'_blank'}
				rel={'noreferrer'}
				isPrimary
			>
				{__( 'Upgrade', 'advanced-sidebar-menu' )}
			</Button>
		</PanelBody>
	</InspectorControls> );
};

export default withFilters<Props>( 'advanced-sidebar-menu.blocks.info-panel' )( InfoPanel );
