/**
 * Change the style display to block
 * For the element that is sent to it
 * Use the id or inline tags for this
 *
 **/
function asm_reveal_element(this_element_id) {
	//old used ids. New use data-js so multiple may be used specifically
	//@todo remove this select of id once PRO has been converted over
	var el = jQuery( '[id="' + this_element_id + '"]:first');
	if( ! el.length ){
		el = jQuery( '[data-js="' + this_element_id + '"]' );
	}
	el.toggle();
	var status = el.is(':visible') ? 'show' : 'hide';
	jQuery( document ).trigger('advanced-sidebar-menu/reveal-element', [this_element_id,  status]);
}
