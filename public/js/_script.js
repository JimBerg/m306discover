(function( $ ){

	/* ------------------------------------------------------------*
	* create some objects
	* ------------------------------------------------------------*/
	var cg_game = {};
	var cg_geolocation = {};
	var cg_map = {};
	var cg_user = {};
	
	var cg_markerLayer;
	var currentLocation;
	
	
	/* ------------------------------------------------------------*
	* fetch some dom elements || caching
	* ------------------------------------------------------------*/
	var mapContainer = document.getElementById( 'map' );
	
	
	/* ------------------------------------------------------------*
	* and now let's add some functions
	* 
	* 1. check if there's already a location in local storage
	* 2. else check if geoloction is supported and set local storage
	* ------------------------------------------------------------*/
	cg_game.init = function() {

		if ( window.localStorage.getItem( 'location-home-lat' ) ) {
			cg_user.home = { 
				'lat': window.localStorage.getItem( 'location-home-lat' ),
				'lng': window.localStorage.getItem( 'location-home-lng' )
			}
			cg_user.current = { 
				'lat': window.localStorage.getItem( 'location-current-lat' ),
				'lng': window.localStorage.getItem( 'location-current-lng' )
			}
		} else {
		 	if ( navigator.geolocation ) {
		    	navigator.geolocation.getCurrentPosition( cg_geolocation.onsuccess, cg_geolocation.onerror, { enableHighAccuracy: true } );
		    } else {
		        $( '#map' ).text( "Einleitung nicht gelesen? Alter Browser heisst wirklich: Du spielst nicht mit!" );
		    }	
		}
	}	
	
	
	/* ------------------------------------------------------------*
	* try to get position, set local storage items for starting p.
	* set hidden values to save location tob db
	* ------------------------------------------------------------*/
	cg_geolocation.onsuccess = function( position ) {
		var lat = position.coords.latitude;
    	var lng = position.coords.longitude;
    	
    	if( !window.localStorage.getItem( 'location-home-lat' ) ) {
	    		localStorage.setItem( 'location-home-lat', lat );
	    		localStorage.setItem( 'location-home-lng', lng );
	    	    
	    	    localStorage.setItem( 'location-current-lat', lat );
	    		localStorage.setItem( 'location-current-lng', lng );
	    	    	
	    		$( 'input#position-lat' ).val( lat );
	    		$( 'input#position-lng' ).val( lng );
		  } else if( window.localStorage.getItem( 'location-current-lat' ) != lat || window.localStorage.getItem( 'location-current-lng' ) != lng ) {
		  		localStorage.setItem( 'location-current-lat', lat );
	    		localStorage.setItem( 'location-current-lng', lng );	
		  }
	}
	
	cg_geolocation.onerror = function( error ) {
		return;	
	}
	
	cg_geolocation.observer = function() {
		navigator.geolocation.watchPosition( cg_geolocation.onsuccess, cg_geolocation.onerror, { enableHighAccuracy: true } );
	}
	
	
	
	/* ------------------------------------------------------------*
	* 
	* TODO init process
	* 
	* ------------------------------------------------------------*/
	$( '#getLocation' ).on( 'click', function( e ) {
		e.preventDefault();
		alert( 'wir brauchen deine location' );
		var test = cg_game.init();
		console.log(test);
	});

	cg_game.init();
	
	//* on page reload == revisit
	cg_geolocation.observer();
	
	$( '#checkin' ).on( 'click', function() {
		cg_geolocation.observer();	
		
		// cg_user.current.lat, cg_user.current.lng
		var request = $.ajax({ 
	 		url: 'http://lokal.horst/websites/mapgames/index.php/app/getPOIs/', 
	 		async: false
	 	}); 
	 	
	 	data =  $.parseJSON( request.responseText );
	 	
	 	for( var i = 0; i < data.length; i++ ) {
	 		
	 		/* was ein glück betrachten wir nur die lokale umgebung */
	 		if( cg_user.current.lat >= data[i].lat_south && 
	 			cg_user.current.lat <= data[i].lat_north && 
	 			cg_user.current.lng >= data[i].lng_west && 
	 			cg_user.current.lng <= data[i].lng_east ) {
	 			
	 			//var ts = Math.round((new Date()).getTime() / 1000)
	 			//alert( "special location" + data[i].id + data[i].name + new Date());
	 			//task!
	 			// annnnnnd now write it to the database!!
	 			$.ajax({ 
			 		url: 'http://lokal.horst/websites/mapgames/index.php/app/setCheckIn/'+data[i].id, 
			 		async: false
			 	}); 
			 	
			 	//user frage freischalten
			 	//var task = data[i].task;
			 	//alert(data[i].task);
			 	$( '<div id="new-task">'+data[i].task+'</div>' ).appendTo( '.cg-marker-control-layer' );
	 		}
		}
		
	});

	
	
	
	/* ------------------------------------------------------------*
	* create / initialize map
	* ------------------------------------------------------------*/
	cg_map.init = function() {
			cg_map.map = L.map('map', {
		    center: new L.LatLng( cg_user.home.lat, cg_user.home.lng ),
		    zoom: 14,
		    layers: []
		});
 		L.tileLayer("http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png", { minZoom: 12, maxZoom: 18, detectRetina: true } ).addTo( cg_map.map );	
 		cg_map.setMarker();
	}
	
	
	
	
//TODO	
	var greenIcon = L.icon({
	    iconUrl: 'http://lokal.horst/websites/mapgames/application/images/leaf-green.png',
	    //shadowUrl: 'leaf-shadow.png',
	
	    iconSize:     [38, 95], // size of the icon
	    shadowSize:   [50, 64], // size of the shadow
	    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
	    shadowAnchor: [4, 62],  // the same for the shadow
	    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
	});
	
	
	/* ------------------------------------------------------------*
	* set home marker
	* ------------------------------------------------------------*/
	cg_map.setMarker = function() {
		cg_map.homeMarker = L.marker( [ cg_user.home.lat, cg_user.home.lng ], {icon: greenIcon} );
		cg_map.currentMarker = L.marker( [ cg_user.current.lat, cg_user.current.lng ] );
		
		cg_map.homeMarker.addTo( cg_map.map );
		cg_map.currentMarker.addTo( cg_map.map );
		
    	cg_map.homeMarker.bindPopup( "<p>Startposition!</p>" ); //.openPopup();
    	cg_map.currentMarker.bindPopup( "<p>Aktuelle Position</p>" );
	}
	
	
	/* ------------------------------------------------------------*
	* if map element exists - initialize leaflet map
	* ------------------------------------------------------------*/
	if( mapContainer != null && mapContainer != undefined ) { 
		cg_map.init();
	}
	
	
	/* ------------------------------------------------------------*
	* get all markers of a certain type
	* bundle them and them to one layer
	* @param (int) markerType 
	* @return (L.layerGroup) layer 
	* ------------------------------------------------------------*/
	cg_map.getMarkerCollection = function ( markerType ) {
		var data;
		var layer;
		var marker = [];
		
	 	var request = $.ajax({ 
	 		url: 'http://lokal.horst/websites/mapgames/index.php/app/getPOIs/'+markerType, 
	 		async: false
	 	}); 
	 	
	 	data =  $.parseJSON( request.responseText );
	 	
	 	for( var i = 0; i < data.length; i++ ) {
			marker[i] = L.marker( [ data[i].lat, data[i].lng ] );
			marker[i].bindPopup( "<h3 class='marker-tooltip-title'>"+data[i].name+"</h3><p class='marker-tooltip-description'>"+data[i].description+"</p>" );
			//marker[i].addTo( customlayer );
		}
		return layer = L.layerGroup( marker );
	}



	/* ------------------------------------------------------------*
	* toggle infobar
	* ------------------------------------------------------------*/
	function markerControlPanelToggle() {
		var that = this;
		
		if( $( that ).hasClass( 'open' ) ) {
			$( that ).removeClass( 'open' );
			$( that ).addClass( 'close' );
		} else {
			$( that ).removeClass( 'close' );
			$( that ).addClass( 'open' );
		}
	};


	/* ------------------------------------------------------------*
	* toggle layer visibility
	* ------------------------------------------------------------*/
	function layerToggle() {
	 	var that = this;
	 	var visible = arguments[0].visible || false;
	 	
		if( typeof that.onAdd !== 'function' ) { // sometimes an error occurs because click event is delegated to window
			return;
		} else {
			if( visible === false ) {
		 		cg_map.map.addLayer( that );
		 	} else {
		 		cg_map.map.removeLayer( that );
		 	}	
		}
	 }
	 
	/* ------------------------------------------------------------*
	* create custom control = panel with different options
	* @return DOM Element -> container
	* ------------------------------------------------------------*/
	var cg_markerControl = L.Control.extend({
	    options: {
	        position: 'bottomleft'
	    },
	
	    onAdd: function ( map ) {
	        var container = L.DomUtil.create( 'div', 'cg-marker-control-layer' );
	        var domElem = container;
	        var markerCollectionLayer = [];
	        var markerType  = [];
	        var visible;
	        
	       /* for( var i = 1; i <= 3; i++ ){
		        markerCollectionLayer[i] = cg_map.getMarkerCollection( i ); //get bundled markers
		        var that = markerCollectionLayer[i];
		        markerType[i] = $( '<input type="checkbox" id="markerType_'+i+'" value="type_'+i+'" /><span class="fakeBox"></span><span class="markerToggle" >Typ '+i+'</span>' ).appendTo( $ ( container ) );
		       //	$( markerType[i] ).on(  'click', function() { layerToggle.apply( that ); } );        	
	        }
	       
	       	
	       	$( markerType ).each( function( index, elem ){
	       		
	       		$( this ).on( 'click', function( event ) { 
	       			
	       			if( $( this ).attr( 'checked' ) === 'checked' ) {
	       				visible = false;
	       			} else {
	       				visible = true;
	       			}
	       			
	       			layerToggle.apply( markerCollectionLayer[ index ], [ { 'visible': visible } ] );
	       			event.stopPropagation();  
	       		});  
	       		
	       	});*/
	       	
	       	
	         $( '<h1 id="checkin">Check in</h1>' ).appendTo( $ ( container ) );
	       	
	       	//$( container ).on( 'click', function() { markerControlPanelToggle.apply( domElem ); } );
	       	
			return container;
	    }
	});
	
	if( cg_map.map !== null ) {
		cg_markerLayer = new cg_markerControl();
		cg_map.map.addControl( cg_markerLayer );
	}

	/*$( '#loginbox-login' ).on( 'click', function( event ) {
		event.preventDefault();
		$( '#login-box' ).css( { '-webkit-transform': 'rotate(180deg)' } );
	});*/
	
	
	
})( jQuery );




















