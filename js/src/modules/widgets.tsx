import ReactDOM from 'react-dom';
import TransformNotice, {DISMISS_KEY} from '../components/TransformNotice';
import {CONFIG} from '../globals/config';


function renderTransformNotice() {
	// Only render on the block based widgets screen.
	if ( CONFIG.siteInfo.classicWidgets ) {
		return;
	}
	// Don't render if the user has dismissed the notice.
	if ( '1' === localStorage.getItem( DISMISS_KEY ) ) {
		return;
	}
	const placeholders = document.querySelectorAll( '[data-js="advanced-sidebar-menu/transform-notice"]' );

	/**
	 * This is not a priority component, so it anything fails
	 * we fail silently.
	 */
	try {
		placeholders.forEach( placeholder => {
			// eslint-disable-next-line -- Still using React 17 on some WP versions.
			ReactDOM.render( <TransformNotice />, placeholder );
		} );
	} catch ( e ) {
		console.error( e );
	}
}


/**
 * Modern JS for legacy widgets on the widgets screen.
 *
 * @see resources/js/advanced-sidebar-menu.js for the legacy JS.
 */
export default () => {
	// Only load on the widgets screen.
	if ( '1' !== CONFIG.isWidgets ) {
		return;
	}
	// Fire when each widget loads.
	jQuery( document ).on( 'widget-added', function() {
		renderTransformNotice();
	} );
}
