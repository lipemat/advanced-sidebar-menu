/**
 * Change the style display to block
 * For the element that is sent to it
 * Use the id or inline tags for this
 *
 **/
function asm_reveal_element(this_element_id) {
	var el = jQuery('[data-js="' + this_element_id + '"]');

	el.toggle();
	var status = el.is(':visible') ? 'show' : 'hide';
	advanced_sidebar_menu.set_hide_state(el);
	jQuery(document).trigger('advanced-sidebar-menu/reveal-element', [this_element_id, status]);
}

/**
 * Proper handling of the show/hide of elements
 * for widgets
 *
 * @since 7.4.5
 */
var advanced_sidebar_menu = {

	/**
	 * Called by PHP so this will run no matter where the widget is loaded.
	 * This solves issues with page builders as well as widget updating.
	 *
	 * @since 7.4.5
	 */
	init: function () {
		this.show_hide_elements();
		jQuery(document).trigger('advanced-sidebar-menu/init');
	},

	/**
	 * Set the data attribute to the current show/hide state so we
	 * can track it's visibility and not improperly show/hide an element
	 * when a widget is saved.
	 *
	 * Solves the issue where updating one widget could affect another.
	 *
	 * @since 7.4.5
	 *
	 * @param el
	 */
	set_hide_state: function (el) {
		if (el.is(':visible')) {
			el.data('advanced-sidebar-menu-hide', 0);
		} else {
			el.data('advanced-sidebar-menu-hide', 1);
		}
	},

	/**
	 * Use JS to show/hide widget elements instead of PHP because sometimes widgets are loaded
	 * in weird ways like ajax and we don't want any fields hidden if the JS is never loaded
	 * to later show them
	 *
	 * @since 7.4.5
	 *
	 */
	show_hide_elements: function () {
		jQuery('[data-advanced-sidebar-menu-hide]').each(function () {
			var el = jQuery(this);
			if (el.data('advanced-sidebar-menu-hide')) {
				el.hide();
			} else {
				el.show();
			}
		});
	}
};
