/**
 * Change the style display to block
 * For the element that is sent to it
 * Use the id or inline tags for this
 *
 **/
function asm_reveal_element( this_element_id ){
	//If the toggle has already been clicked to show
	//This will hide it
	if( document.getElementById( this_element_id ).style.display === 'none' ){
		document.getElementById( this_element_id ).style.display = 'block';
		//If the toggle has already been clicked to hide
		//This will show it
	} else {
		document.getElementById( this_element_id ).style.display = 'none';
	}
}






