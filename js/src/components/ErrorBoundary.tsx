import React from 'react';
import {CONFIG} from '../globals/config';
import {addQueryArgs} from '@wordpress/url';
import {sanitize} from 'dompurify';

/**
 * Wrap any component in me, which may throw errors, and I will
 * prevent the entire UI from disappearing,
 *
 * @link https://reactjs.org/docs/error-boundaries.html#introducing-error-boundaries
 */
class ErrorBoundary extends React.Component<{}, { hasError: boolean, error: Error | null }> {
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
	 * @notice Will not log anything when in dev mode!
	 *         Still prevents UI crashes but only logs
	 *         in production mode.
	 *
	 * @param  error
	 * @param  info
	 */
	componentDidCatch( error: Error, info: React.ErrorInfo ) {
		console.error( 'The React UI was protected by an Error Boundary else it wouldn\'t be showing right now.' );
		console.error( error );
		console.error( info );
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
							<strong><em>User Agent</em></strong> <br />
							<code>
								{navigator.userAgent}
							</code>
						</p>
						<p>
							<strong><em>Versions</em></strong> <br />
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
