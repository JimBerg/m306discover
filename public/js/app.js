(function( $ ) {
	
	/* -----------------------------------------------------------*
	* check if internet connection is available
	*
	* if offline - show message
	* else initialize scripts
	* 
	* declare some variables and fetch DOM Elements
	* 
	* ------------------------------------------------------------*/
	var online = navigator.onLine;
	var map = $( '#map' ) || false;
	var checkIn = $( '#checkIn' ) || false;
	var cg_markerLayer;

	if( online === false || online === 'undefined' ) {
		//console.log( 'sorry no internet connection' ); yeaaah and if no internet connection this will never be fired - becaaaaaaaaussse?
		// RIGHT!! We have no internet... and we will never ever reach this page... ha...stupid me - but nice to have this feature anyway
	} else {
		//cg_game.init();
		if( map.length > 0 ) {
			cg_map.init();
			//cg_markerLayer = new cg_markerControl();
			//cg_map.map.addControl( cg_markerLayer );
		}
	}

})( jQuery );
