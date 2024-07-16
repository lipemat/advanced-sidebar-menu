import {CONFIG} from '../../globals/config';
import {Button, PanelBody, withFilters} from '@wordpress/components';
import {InspectorControls} from '@wordpress/block-editor';
import {decodeEntities} from '@wordpress/html-entities';
import {__} from '@wordpress/i18n';

import styles from './info-panel.pcss';

type Props = {
	clientId: string;
};

const titleStyles = {
	color: '#3db634',
	fontWeight: 700,
};

const headingStyles = {
	margin: '16px 0 0',
	fontSize: '1.2em',
};

const InfoPanel = ( {}: Props ) => {
	return ( <InspectorControls>
		<PanelBody
			title={<span style={titleStyles}>
				{__( 'Go PRO', 'advanced-sidebar-menu' )}
			</span>}
			className={styles.wrap}
			initialOpen={false}
		>
			<h2 style={headingStyles}>
				{__( 'Advanced Sidebar Menu PRO', 'advanced-sidebar-menu' )}
			</h2>
			<ul>
				{CONFIG.features.map( feature => (
					<li key={feature}>
						{decodeEntities( feature )}
					</li>
				) )}
				<li>
					{__( 'And so much more…', 'advanced-sidebar-menu' )}
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
