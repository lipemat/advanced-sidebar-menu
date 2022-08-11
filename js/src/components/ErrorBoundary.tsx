import {Component, ErrorInfo} from 'react';
import {CONFIG} from '../globals/config';
import {addQueryArgs} from '@wordpress/url';
import {sanitize} from 'dompurify';

/**
 * Wrap any component in me, which may throw errors, and I will
 * prevent the entire UI from disappearing.
 *
 * Custom version special to Advanced Sidebar Menu with links to
 * support as well as debugging information.
 *
 * @link https://reactjs.org/docs/error-boundaries.html#introducing-error-boundaries
 */
class ErrorBoundary extends Component<{ attributes: Record<string, any>, block: string }, { hasError: boolean, error: Error | null }> {
	constructor( props ) {
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
		console.log( '%cError caught by the Advanced Sidebar ErrorBoundary!', 'color:orange; font-size: large; font-weight: bold' );
		console.log( this.props );
		console.log( error );
		console.log( info );
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
						Please <a href={addQueryArgs( sanitize( window.location.href ), {'script-debug': true}, )}>
							enable script debug
						</a>:
					</p>
				</div> );
			}
			return (
				<div className={'components-panel__body is-opened'}>
					<h4 style={{color: 'red'}}>
						Something went wrong!
					</h4>
					<p>
						Please <a target="_blank" href={CONFIG.support} rel="noreferrer">
							create a support request
						</a> with the following:
					</p>
					<ol>
						<li>
							The <a
								href={'https://onpointplugins.com/how-to-retrieve-console-logs-from-your-browser/'}
								target={'_blank'} rel="noreferrer">
								logs from your browser console.
							</a>
						</li>
						<li>
							The following information.
						</li>
					</ol>

					<fieldset style={{border: '2px groove', padding: '10px'}}>
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
					</fieldset>
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
