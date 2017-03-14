jQuery( function($){
	setTimeout( function(){
		for( var $widget_id in asm_pro.accordion._enabled_accordions ){
			var el = $( document.getElementById( $widget_id ) )
			if( asm_pro.accordion._enabled_accordions[$widget_id]['include_parent'] ){
				parents = el.find( '> ul > li .has_children' ).has( 'ul' );
			} else {
				parents = el.find( 'ul .has_children' ).has( 'ul' );
			}
			parents.each( function(){
				var _parent_menu = $( this );
				_parent_menu.on( 'click', '> a', function( ev ){
					ev.preventDefault();
					asm_pro.accordion._expand_sub_menu( _parent_menu, _parent_menu.find( '> span' ) );
				} );

			} );
		}
	}, 1000 );
});