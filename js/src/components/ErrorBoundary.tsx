import {Component, ErrorInfo, PropsWithChildren} from 'react';
import {CONFIG} from '../globals/config';
import {addQueryArgs} from '@wordpress/url';
import DOMPurify from 'dompurify';

type Props = {
	attributes: object,
	block: string;
	section: string;
}

/**
 * Wrap any component in me, which may throw errors, and I will
 * prevent the entire UI from disappearing.
 *
 * Custom version special to Advanced Sidebar Menu with links to
 * support as well as debugging information.
 *
 * @link https://reactjs.org/docs/error-boundaries.html#introducing-error-boundaries
 */
class ErrorBoundary extends Component<PropsWithChildren<Props>, {
	hasError: boolean,
	error: Error | null
}> {
	constructor( props: Props ) {
		super( props );
		this.state = {
			hasError: false,
			error: null,
		};
	}

	static getDerivedStateFromError() {
		// Update state, so the next render will show the fallback UI.
		return {
			hasError: true,
		};
	}

	/**
	 * Log information about the error when it happens.
	 *
	 * @notice Will log "Error: A cross-origin error was thrown. React doesn't have
	 *         access to the actual error object in development" in React development
	 *         mode but provides full error info in React production.
	 */
	componentDidCatch( error: Error, info: ErrorInfo ) {
		console.warn( '%cError caught by the Advanced Sidebar ErrorBoundary!', 'color:orange; font-size: large; font-weight: bold' );
		console.warn( this.props );
		console.warn( error );
		console.warn( info );
		this.setState( {
			error,
		} );
	}

	render() {
		if ( this.state.hasError ) {
			if ( ! CONFIG.siteInfo.scriptDebug ) {
				return ( <div className={'components-panel__body is-opened'}>
					<h4 style={{color: 'red'}}>
						Something went wrong!
					</h4>
					<p>
						<button
							className={'components-button is-link'}
							onClick={e => {
								e.preventDefault();
								const location = addQueryArgs( DOMPurify.sanitize( window.location.href ), {
									'script-debug': 'true',
								} );
								window.open( location, '_blank', 'noopener,noreferrer' );
							}}
						>
							Please enable script debug
						</button>
						to retrieve error information.
					</p>
				</div> );
			}
			return (
				<div className={'components-panel__body is-opened'}>
					<h4 style={{color: 'red'}}>
						Something went wrong!
					</h4>
					<p>
						<button
							className={'components-button is-link'}
							onClick={e => {
								e.preventDefault();
								window.open( DOMPurify.sanitize( CONFIG.support ), '_blank', 'noopener,noreferrer' );
							}}
						>
							Please create a support request
						</button>
						&nbsp;with the following information:
					</p>
					<ol>
						<li>
							<button
								className={'components-button is-link'}
								onClick={e => {
									e.preventDefault();
									window.open( 'https://onpointplugins.com/how-to-retrieve-console-logs-from-your-browser/', '_blank', 'noopener,noreferrer' );
								}}
							>
								The logs from your browser console.
							</button>
						</li>
						<li>
							The following information.
						</li>
					</ol>

					<div
						style={{
							border: '2px groove',
							padding: '10px',
							width: '100%',
							overflowWrap: 'break-word',
						}}
					>
						<p>
							<strong><em>Section</em></strong> <br />
							<code>
								{this.props.section}
							</code>
						</p>
						<p>
							<strong><em>Screen</em></strong> <br />
							<code>
								{CONFIG.currentScreen}
							</code>
						</p>
						<p>
							<strong><em>Message</em></strong> <br />
							<code>
								{this.state.error?.message}
							</code>
						</p>
						<p>
							<strong><em>Block</em></strong> <br />
							<code>
								{this.props.block}
							</code>
						</p>
						<p>
							<strong><em>Attributes</em></strong> <br />
							<code>
								{JSON.stringify( this.props.attributes )}
							</code>
						</p>
						<p>
							<strong><em>Site Info</em></strong> <br />
							<code>
								{JSON.stringify( CONFIG.siteInfo )}
							</code>
						</p>
						<p>
							<strong><em>Stack</em></strong> <br />
							<code>
								{this.state.error?.stack}
							</code>
						</p>
					</div>
					<p>
						&nbsp;
					</p>
					<p>
						&nbsp;
					</p>
				</div>
			);
		}

		return this.props.children;
	}
}

export default ErrorBoundary;
