/**
 * Change the style display to block
 * For the element that is sent to it
 * Use the id or inline tags for this
 *
 **/
function asm_reveal_element(this_element_id) {
    var el = jQuery( '[data-js="' + this_element_id + '"]' );

	el.toggle();
	var status = el.is(':visible') ? 'show' : 'hide';
	jQuery( document ).trigger('advanced-sidebar-menu/reveal-element', [this_element_id,  status]);
}

jQuery(function ($) {
    /**
     * Use JS to show/hide widget elements instead of PHP because sometimes widgets are loaded
     * in weird ways like ajax and we don't want any fields hidden if the JS is never loaded
     * to later show them
     */
    $('[data-advanced-sidebar-menu-hide="1"]').hide();
    $('[data-advanced-sidebar-menu-hide="0"]').show();
});
