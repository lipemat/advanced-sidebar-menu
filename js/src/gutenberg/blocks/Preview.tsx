import {ReactElement, useEffect} from 'react';
import {CONFIG} from '../../globals/config';
import ServerSideRender from '@wordpress/server-side-render';
import {Placeholder, Spinner} from '@wordpress/components';
import {useBlockProps} from '@wordpress/block-editor';
import {sanitize} from 'dompurify';
import {doAction} from '@wordpress/hooks';
import {__} from '@wordpress/i18n';
import {select} from '@wordpress/data';
import {isScreen} from '../helpers';

import styles from './preview.pcss';


export type PreviewOptions = {
	isServerSideRenderRequest: boolean;
	clientId: string;
	sidebarId: string;
	style?: object;
}

type Props<A> = {
	attributes: A;
	block: string;
	clientId: string;
};

/**
 * Same as the Dashicons `welcome-widgets-menus` icon but available inside
 * iframe editors which don't enqueue the Dashicons font.
 */
const ICON = <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
	<rect x="0" fill="none" width="20" height="20" />
	<g>
		<path d="M19 16V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v13c0 .55.45 1 1 1h15c.55 0 1-.45 1-1zM4 4h13v4H4V4zm1 1v2h3V5H5zm4 0v2h3V5H9zm4 0v2h3V5h-3zm-8.5 5c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zM6 10h4v1H6v-1zm6 0h5v5h-5v-5zm-7.5 2c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zM6 12h4v1H6v-1zm7 0v2h3v-2h-3zm-8.5 2c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zM6 14h4v1H6v-1z" />
	</g>
</svg>;

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
 * If we are in the widgets' area, the block is wrapped in
 * a "sidebar" block. We retrieve the id to pass along with
 * the request to use the `widget_args` within the preview.
 *
 */
const getSidebarId = ( clientId: string ): string => {
	if ( ! isScreen( [ 'widgets' ] ) ) {
		return '';
	}
	const rootId = select( 'core/block-editor' ).getBlockRootClientId( clientId );
	if ( 'undefined' !== typeof rootId && '' !== rootId ) {
		const ParentBlock = select( 'core/block-editor' ).getBlocksByClientId( [ rootId ] );
		if ( null !== ParentBlock[ 0 ] && 'undefined' !== typeof ParentBlock[ 0 ] && 'core/widget-area' === ParentBlock[ 0 ]?.name ) {
			return ParentBlock[ 0 ]?.attributes?.id;
		}
	}

	return '';
};

/**
 * @notice Must use static constants, or the ServerSide requests
 *         will fire anytime something on the page is changed
 *         because the component re-renders.
 */
const Page = () => <Placeholder
	className={styles.placeholder}
	icon={ICON}
	label={__( 'Advanced Sidebar - Pages', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

const Category = () => <Placeholder
	className={styles.placeholder}
	icon={ICON}
	label={__( 'Advanced Sidebar - Categories', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

const Navigation = () => <Placeholder
	className={styles.placeholder}
	icon={ICON}
	label={__( 'Advanced Sidebar - Navigation', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

/**
 * @notice The styles will not display for the preview
 *         in the block inserter sidebar when Webpack
 *         is enabled because the iframe has a late init.
 *
 */
const placeholder = ( block: string ): () => ReactElement => {
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
 * @notice Using a constant to prevent a reload on every content change.
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
			// Give the preview a chance to load on WP 5.8.
			setTimeout( () => {
				doAction( 'advanced-sidebar-menu.blocks.preview.loading-finished', {
					values: attributes,
					clientId: attributes.clientId,
				} );
			}, 100 );
		};
	} );

	/**
	 * ServerSideRender returns a <RawHTML /> filled with an error object when fetch fails.
	 *
	 * We throw an error, so our `ErrorBoundary` will catch it, otherwise we end up
	 * with a "React objects may not be used as children" error, which means nothing.
	 */
	if ( 'string' === typeof children?.props?.children?.errorMsg ) {
		throw new Error( children?.props?.children?.errorMsg ?? 'Failed' );
	}

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
		// eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions
		<div
			{...blockProps}
			onClick={ev => {
				const element = ev.target as HTMLElement;
				if ( 'A' === element.nodeName ) {
					ev.preventDefault();
				}
			}}
		>
			<ServerSideRender<A & PreviewOptions>
				EmptyResponsePlaceholder={placeholder( block )}
				LoadingResponsePlaceholder={TriggerWhenLoadingFinished}
				attributes={{
					...attributes,
					// Send custom attribute to determine server side renders.
					isServerSideRenderRequest: true,
					clientId: sanitizedClientId,
					sidebarId: getSidebarId( clientId ),
				}}
				block={block}
				httpMethod={'POST'}
				skipBlockSupportAttributes
			/>
		</div>
	);
};

export default Preview;
