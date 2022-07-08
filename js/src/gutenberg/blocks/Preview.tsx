import {ReactElement} from 'react';
import {Attr} from './pages/block';
import {CONFIG, I18N} from '../../globals/config';
import ServerSideRender from '@wordpress/server-side-render';
import {Placeholder} from '@wordpress/components';
import {useBlockProps} from '@wordpress/block-editor';

import styles from './preview.pcss';

export type PreviewOptions = {
	isServerSideRenderRequest: boolean;
}

type Props = {
	attributes: Attr;
	block: string;
};

/**
 * @notice Must use static constants, or the ServerSide requests
 *         will fire anytime something on the page is changed
 *         because the component re-renders.
 */
const Page = () => <Placeholder
	className={styles.placeholder}
	icon={'welcome-widgets-menus'}
	label={I18N.widgetPages}
/>;


const placeholder = ( block ): () => ReactElement => {
	switch ( block ) {
		case CONFIG.blocks.pages.id:
			return Page;
	}
	return () => <></>;
};

const Preview = ( {attributes, block}: Props ) => {
	const blockProps = useBlockProps( {
		className: styles.wrap,
	} );

	return (
		<div {...blockProps}>
			<ServerSideRender<Attr>
				EmptyResponsePlaceholder={placeholder( block )}
				attributes={{
					...attributes,
					// Send custom attribute to determine server side renders.
					isServerSideRenderRequest: true,
				}}
				block={block}
				httpMethod={'POST'}
			/>
		</div>
	);
};

export default Preview;
