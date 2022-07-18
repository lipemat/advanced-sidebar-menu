import {I18N} from '../../globals/config';
import {Button, PanelBody} from '@wordpress/components';
import {InspectorControls} from '@wordpress/block-editor';
import {decodeEntities} from '@wordpress/html-entities';

import styles from './info-panel.pcss';

type Props = {};

const InfoPanel = ( {}: Props ) => {
	return ( <InspectorControls>
		<PanelBody title={I18N.goPro} className={styles.wrap}>
			<ul>
				{I18N.features.map( feature =>
					<li key={feature}>{decodeEntities( feature )}</li> )}
				<li>
					<a
						href="https://onpointplugins.com/product/advanced-sidebar-menu-pro/?utm_source=block-more&utm_campaign=gopro&utm_medium=wp-dash"
						target="_blank"
						style={{textDecoration: 'none'}}
						rel="noreferrer"
					>
						{I18N.soMuchMore}
					</a>
				</li>
			</ul>
			<Button
				className={styles.button}
				href={'https://onpointplugins.com/product/advanced-sidebar-menu-pro/?trigger_buy_now=1&utm_source=block-upgrade&utm_campaign=gopro&utm_medium=wp-dash'}
				target={'_blank'}
				rel={'noreferrer'}
				isPrimary
			>
				{I18N.upgrade}
			</Button>
		</PanelBody>
	</InspectorControls> );
};

export default InfoPanel;
