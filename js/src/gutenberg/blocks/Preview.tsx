import {type ReactElement, useEffect} from 'react';
import {CONFIG} from '../../globals/config';
import ServerSideRender from '@wordpress/server-side-render';
import {Placeholder, Spinner} from '@wordpress/components';
import {useBlockProps} from '@wordpress/block-editor';
import DOMPurify from 'dompurify';
import {applyFilters, doAction} from '@wordpress/hooks';
import {__} from '@wordpress/i18n';
import {select} from '@wordpress/data';
import {isScreen} from '../helpers';
import PagesIcon from './pages/PagesIcon';
import CategoriesIcon from './categories/CategoriesIcon';
import NavigationIcon from './NavigationIcon';

import styles from './preview.pcss';


/**
 * @see \Advanced_Sidebar_Menu\Blocks\Common_Attributes::get_common_attributes
 */
export type CommonAttr = {
	title?: string;
	style?: object;
}

/**
 * @see \Advanced_Sidebar_Menu\Blocks\Common_Attributes::get_server_side_render_attributes
 */
export type ServerSideRenderRequired = {
	isServerSideRenderRequest: boolean;
	clientId: string;
	sidebarId: string;
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
	icon={PagesIcon}
	label={__( 'Advanced Sidebar - Pages', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

const Category = () => <Placeholder
	className={styles.placeholder}
	icon={CategoriesIcon}
	label={__( 'Advanced Sidebar - Categories', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

const Navigation = () => <Placeholder
	className={styles.placeholder}
	icon={NavigationIcon}
	label={__( 'Advanced Sidebar - Navigation', 'advanced-sidebar-menu' )}
	instructions={__( 'No preview available', 'advanced-sidebar-menu' )}
/>;

/**
 * @notice The styles will not display for the preview
 *         in the block inserter sidebar when Webpack
 *         is enabled because the iframe has a late init.
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
	const blockProps = useBlockProps( {
		className: styles.wrap,
	} );

	if ( '' !== CONFIG.error ) {
		return <div
			className={styles.error}
			dangerouslySetInnerHTML={{__html: DOMPurify.sanitize( CONFIG.error )}} />;
	}


	const sanitizedClientId = sanitizeClientId( clientId );

	let attributesToSend: A & ServerSideRenderRequired = {
		...attributes,
		isServerSideRenderRequest: true,
		clientId: sanitizedClientId,
		sidebarId: getSidebarId( clientId ),
	};
	/**
	 * Filters the attributes sent to the preview.
	 *
	 * @since 9.6.3
	 *
	 * @param {Object} attributes The attributes to send to the preview.
	 * @param {string} block      The block name.
	 * @param {string} clientId   The client id.
	 */
	attributesToSend = applyFilters( 'advanced-sidebar-menu.blocks.preview.attributes', attributesToSend, block, clientId );

	return (
		// eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-static-element-interactions
		<div
			{...blockProps}
			onClick={ev => {
				const element = ev.target as HTMLElement;
				if ( 'A' === element.nodeName || ( 'SPAN' === element.nodeName && 'A' === element.parentNode?.nodeName ) ) {
					ev.preventDefault();
				}
			}}
		>
			<ServerSideRender<A & ServerSideRenderRequired>
				EmptyResponsePlaceholder={placeholder( block )}
				LoadingResponsePlaceholder={TriggerWhenLoadingFinished}
				attributes={attributesToSend}
				block={block}
				httpMethod={'POST'}
				skipBlockSupportAttributes
			/>
		</div>
	);
};

export default Preview;
