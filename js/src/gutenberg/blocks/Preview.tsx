import {ReactElement, useEffect} from 'react';
import {CONFIG} from '../../globals/config';
import ServerSideRender from '@wordpress/server-side-render';
import {Placeholder, Spinner} from '@wordpress/components';
import {useBlockProps} from '@wordpress/block-editor';
import {sanitize} from 'dompurify';
import {doAction} from '@wordpress/hooks';
import {__} from '@wordpress/i18n';

import styles from './preview.pcss';

export type PreviewOptions = {
	isServerSideRenderRequest: boolean;
	clientId: string;
}

type Props<A> = {
	attributes: A;
	block: string;
	clientId: string;
};

/**
 * Sanitize a client id for use as an HTML id.
 *
 * Must not start with a `-` or a digit.
 *
 */
export const sanitizeClientId = ( clientId: string ): string => {
	return clientId.replace( /^([\d-])/, '_$1' );
};

/**
 * @notice Must use static constants, or the ServerSide requests
 *         will fire anytime something on the page is changed
 *         because the component re-renders.
 */
const Page = () => <Placeholder
	className={styles.placeholder}
	icon={'welcome-widgets-menus'}
	label={__( 'Advanced Sidebar - Pages', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

const Category = () => <Placeholder
	className={styles.placeholder}
	icon={'welcome-widgets-menus'}
	label={__( 'Advanced Sidebar - Categories', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

const Navigation = () => <Placeholder
	className={styles.placeholder}
	icon={'welcome-widgets-menus'}
	label={__( 'Advanced Sidebar - Navigation', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

/**
 * @notice The styles will not display for the preview
 *         in the block inserter sidebar when Webpack
 *         is enabled because the iframe has a late init.
 *
 */
const placeholder = ( block ): () => ReactElement => {
	switch ( block ) {
		case CONFIG.blocks.pages.id:
			return Page;
		case CONFIG.blocks.categories.id:
			return Category;
		case CONFIG.blocks.navigation?.id:
			return Navigation;
	}
	return () => <></>;
};


/**
 * Same as the `DefaultLoadingResponsePlaceholder` except we trigger
 * an action when the loading component is unmounted to allow
 * components to hook into when ServerSideRender has finished loading.
 *
 * @notice Using a constant to prevent reload on every content change.
 *
 */
const TriggerWhenLoadingFinished = ( {
	children,
	attributes = {
		clientId: '',
	},
} ) => {
	useEffect( () => {
		// Call action when the loading component unmounts because loading is finished.
		return () => {
			$( '[data-preview="' + `${attributes.clientId}` + '"]' )
				.find( 'a' )
				.on( 'click', ev => ev.preventDefault() );

			doAction( 'advanced-sidebar-menu.blocks.preview.loading-finished', {
				values: attributes,
				clientId: attributes.clientId,
			} );
		};
	} );

	return (
		<div className={styles.spinWrap}>
			<div className={styles.spin}>
				<Spinner />
			</div>
			<div className={styles.spinContent}>
				{children}
			</div>
		</div>
	);
};


const Preview = <A, >( {attributes, block, clientId}: Props<A> ) => {
	const blockProps = useBlockProps();

	if ( '' !== CONFIG.error ) {
		return <div
			className={styles.error}
			dangerouslySetInnerHTML={{__html: sanitize( CONFIG.error )}} />;
	}

	const sanitizedClientId = sanitizeClientId( clientId );

	return (
		<div {...blockProps} data-preview={sanitizedClientId}>
			<ServerSideRender<A & PreviewOptions>
				EmptyResponsePlaceholder={placeholder( block )}
				LoadingResponsePlaceholder={TriggerWhenLoadingFinished}
				attributes={{
					...attributes,
					// Send custom attribute to determine server side renders.
					isServerSideRenderRequest: true,
					clientId: sanitizedClientId,
				}}
				block={block}
				httpMethod={'POST'}
			/>
		</div>
	);
};

export default Preview;
