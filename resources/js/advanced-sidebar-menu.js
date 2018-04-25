/**
 * Change the style display to block
 * For the element that is sent to it
 * Use the id or inline tags for this
 *
 **/
function asm_reveal_element(this_element_id) {
	if (document.getElementById(this_element_id).style.display === 'none') {
		document.getElementById(this_element_id).style.display = 'block';

		//action fires when this element is shown
		jQuery( document ).trigger('advanced-sidebar-menu/reveal-element', [this_element_id, 'show']);
	} else {
		document.getElementById(this_element_id).style.display = 'none';

		//action fires when this element is hidden
		jQuery( document ).trigger('advanced-sidebar-menu/reveal-element', [this_element_id, 'hide']);
	}
}
