import {ReactElement, useEffect} from 'react';
import {CONFIG, I18N} from '../../globals/config';
import ServerSideRender from '@wordpress/server-side-render';
import {Placeholder, Spinner} from '@wordpress/components';
import {useBlockProps} from '@wordpress/block-editor';
import {sanitize} from 'dompurify';
import {doAction} from '@wordpress/hooks';

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
 * @notice Must use static constants, or the ServerSide requests
 *         will fire anytime something on the page is changed
 *         because the component re-renders.
 */
const Page = () => <Placeholder
	className={styles.placeholder}
	icon={'welcome-widgets-menus'}
	label={I18N.pages.title}
/>;


const placeholder = ( block ): () => ReactElement => {
	switch ( block ) {
		case CONFIG.blocks.pages.id:
			return Page;
	}
	return () => <></>;
};
/**
 * Same as the `DefaultLoadingResponsePlaceholder` except we trigger
 * an action when the loading component is unmounted to allow
 * components to hook into when ServerSideRender has finished loading.
 *
 */
const TriggerWhenLoadingFinished = attributes => {
	return ( {children, showLoader} ) => {
		// eslint-disable-next-line react-hooks/rules-of-hooks
		useEffect( () => {
			// Call action when the loading component unmounts because loading is finished.
			return () => {
				doAction( 'advanced-sidebar-menu.blocks.preview.loading-finished', attributes );
			};
		} );

		return (
			<div style={{position: 'relative'}}>
				{showLoader && (
					<div
						style={{
							position: 'absolute',
							top: '50%',
							left: '50%',
							marginTop: '-9px',
							marginLeft: '-9px',
						}}
					>
						<Spinner />
					</div>
				)}
				<div style={{opacity: showLoader ? '0.3' : 1}}>
					{children}
				</div>
			</div>
		);
	};
};


const Preview = <A, >( {attributes, block, clientId}: Props<A> ) => {
	const blockProps = useBlockProps( {
		className: styles.wrap,
	} );

	if ( '' !== CONFIG.error ) {
		return <div
			className={styles.error}
			dangerouslySetInnerHTML={{__html: sanitize( CONFIG.error )}} />;
	}

	return (
		<div {...blockProps}>
			<ServerSideRender<A & PreviewOptions>
				EmptyResponsePlaceholder={placeholder( block )}
				LoadingResponsePlaceholder={TriggerWhenLoadingFinished( attributes )}
				attributes={{
					...attributes,
					// Send custom attribute to determine server side renders.
					isServerSideRenderRequest: true,
					clientId,
				}}
				block={block}
				httpMethod={'POST'}
			/>
		</div>
	);
};

export default Preview;
