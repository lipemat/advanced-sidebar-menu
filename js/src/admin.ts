import './pcss/admin.pcss';

/**
 * @todo Cleanup this entire file. It's legacy code that needs to be refactored.
 */


/**
 * Misc JS for the widget forms in various contexts.
 *
 * @notice `\Advanced_Sidebar_Menu\Scripts::init_widget_js` is using this in PHP.
 * @notice `\Advanced_Sidebar_Menu\Widget\Widget_Abstract::checkbox` is using this in PHP.
 */
window.advancedSidebarMenuAdmin = {

	/**
	 * Called by PHP so this will run no matter where the widget is loaded.
	 * This solves issues with page builders as well as widget updating.
	 *
	 * For WP 5.8+ this is called via the 'widget-added' event.
	 *
	 * @since 7.4.5
	 */
	init() {
		this.handlePreviews();
		this.showHideElements();
		$( document ).trigger( 'advanced-sidebar-menu/init' );
	},
	/**
	 * Toggle the visibility of the widget form elements.
	 *
	 * Triggered via PHP.
	 *
	 * @see \Advanced_Sidebar_Menu\Widget\Widget_Abstract::checkbox
	 */
	clickReveal( id: string ) {
		const target = $( ( '[data-js="' + id + '"]' ) );
		target.toggle();
		this.setHideState( target );
	},

	/**
	 * Set the data attribute to the current show/hide state, so we
	 * can track its visibility and not improperly show/hide an element
	 * when a widget is saved.
	 *
	 * Solves the issue where updating one widget could affect another.
	 */
	setHideState( el: JQuery ) {
		if ( el.is( ':visible' ) ) {
			el.data( 'advanced-sidebar-menu-hide', 0 );
		} else {
			el.data( 'advanced-sidebar-menu-hide', 1 );
		}
	},

	/**
	 * Use JS to show/hide widget elements instead of PHP because sometimes widgets are loaded
	 * in weird ways like ajax and we don't want any fields hidden if the JS is never loaded
	 * to later show them
	 *
	 */
	showHideElements() {
		$( '[data-advanced-sidebar-menu-hide]' ).each( function() {
			const el = $( this );
			if ( 1 === el.data( 'advanced-sidebar-menu-hide' ) ) {
				el.hide();
			} else {
				el.show();
			}
		} );
	},

	/**
	 * Display the preview image and close icon when the "Preview"
	 * button is clicked.
	 *
	 * Adds a class to the wrap which allows hiding the existing options
	 * to prevent inconsistent margin requirements.
	 *
	 * @since 8.1.0
	 */
	handlePreviews() {
		/**
		 * Failsafe in case the image cannot load from onpointplugins.com.
		 * Better to not have a preview than an broken one.
		 */
		$( '[data-js="advanced-sidebar-menu/pro/preview/image"]' )
			.on( 'error', function( ev ) {
				$( ev.target ).parent().parent()
					.find( '[data-js="advanced-sidebar-menu/pro/preview/trigger"]' ).remove();
				$( ev.target ).remove();
			} );

		$( '[data-js="advanced-sidebar-menu/pro/preview/trigger"]' ).on( 'click', function( ev ) {
			ev.preventDefault();
			const el = $( '[data-js="' + $( this ).data( 'target' ) + '"]' );
			const form = el.parents( 'form' );
			form.addClass( 'advanced-sidebar-menu-open' );
			const close = el.find( '.advanced-sidebar-menu-close-icon' );
			const img = el.find( 'img' );
			img.css( 'width', '100%' );
			close.css( 'display', 'block' );
			close.on( 'click', function() {
				img.css( 'width', 0 );
				close.css( 'display', 'none' );
				form.removeClass( 'advanced-sidebar-menu-open' );
			} );
		} );
	},
};

/**
 * WP 5.8 no longer fires the <script> tag within the PHP because
 * it loads the markup via the REST API. We must use the new
 * event to init the JS.
 *
 * @link https://developer.wordpress.org/block-editor/how-to-guides/widgets/legacy-widget-block/
 */
$( document ).on( 'widget-added', function() {
	window.advancedSidebarMenuAdmin.init();
} );
