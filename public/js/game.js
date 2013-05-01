/**
* map.js
*
* set up map, markers, layers,
* add elements to map,
* handles map interactions
*
* @author Janina Imberg
* @version 1.0
* @date 25.04.2013
*
*/

/**
 * div container that holds map
 * @type {*|HTMLElement|Boolean}
 */
var mapContainer = $( '#map' ) || false;

 /**
 * get base url of application
 * @type {*|jQuery}
 */
var baseUrl = $( '#base-url' ).data( 'base-url' );

/**
 * fetch overlay from dom and cache it for
 * case of best practices ;)
 * @type {Object}
 */
var overlay = $( '#overlay' );

/**
 * create map object and it to jayMap namespace
 * {map was used to often in this context and may lead easily to a desaster...}
 * - set map specific options
 * - add custom marker and layer
 * - set options for icon size and images
 *
 * requires: leaflet.js
 */
var jayMap = {
    'currentIcon': L.icon({
        iconUrl: '/images/marker_icon_current.png',
        iconSize:     [20, 26], // size of the icon
        iconAnchor:   [10, 13], // point of the icon which will correspond to marker's location
        popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
    }),
    'taskIconsComplete': L.icon({
        iconUrl: '/images/marker_icon_solved.png',
        iconSize:     [20, 26], // size of the icon
        iconAnchor:   [10, 13], // point of the icon which will correspond to marker's location
        popupAnchor:  [0, 0] // point from which the popup should open relative to the iconAnchor
    })
};

/**
 * init map / load map
 * add map to dom
 * set map extract for current view
 * set zoom and map center
 *
 * @return void
 */
jayMap.init = function() {
    this.map = L.map( 'map',
        {
            center: new L.LatLng( game.currentLat, game.currentLng ),
            zoom: 15,
            layers: []
        }
    );
    L.tileLayer(
        "http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/999/256/{z}/{x}/{y}.png",
        {
            minZoom: 12,
            maxZoom: 18,
            detectRetina: true
        }
    ).addTo( this.map );
   this.setCurrentMarker();
   this.setMarkers();
};

/**
 * set marker options for style and position of current position
 * bind marker to map object
 *
 * @return void
 */
jayMap.setCurrentMarker = function() {
    this.currentMarker = L.marker( [ game.currentLat, game.currentLng ], { icon: this.currentIcon } );
    this.currentMarker.addTo( this.map );
    this.currentMarker.bindPopup( "<p>Aktuelle Position</p>" );
};

/**
 * get visited locations from database
 *
 * @param callback
 */
jayMap.getVisitedLocations = function( callback ) {
    $.ajax({
        type: 'GET',
        url: baseUrl+'game/getVisitedLocations',
        success: function( response ) {
            callback( response );
        },
        dataType: 'json'
    });
};

/**
 * create marker objects from given points
 *
 * @return object markerLayer
 */
jayMap.setMarkers = function() {
    jayMap.getVisitedLocations( function( data ) {
        var marker = [];
        for( var i = 0; i < data.length; i++ ) {
            marker[i] = L.marker( [ data[i][0].lat, data[i][0].lng ], { icon: jayMap.taskIconsComplete } );
            marker[i].bindPopup( "<h3 class='marker-tooltip-title'>"+data[i][0].name+"</h3>" );
            marker[i].addTo( jayMap.map );
        }
        //return layer = L.layerGroup( marker );
    });
};

/**
 * create game object / game namespace
 * set default lat/lng values to 0/0
 * handles map interactions
 * get locations from database
 * handles ajax requests
 */
var game = {
    'currentLat': 0,
    'currentLng': 0
};

/**
 * init function
 * calls geoloction function
 * get coordinates of current position
 * set game properties of current lat/lng value pair
 * init map if location and mapcontainer were found
 *
 * @return void
 */
game.init = function() {
    if ( navigator.geolocation ) {
        navigator.geolocation.getCurrentPosition( game.onsuccess, game.onerror, { enableHighAccuracy: true } );
        game.currentLat = window.localStorage.getItem( 'location-current-lat' );
        game.currentLng = window.localStorage.getItem( 'location-current-lng' );
        if( game.currentLat != 0 && game.currentLng != 0 ) {
            if( mapContainer.length > 0 ) {
                jayMap.init();
            }
        } else {
            //TODO: error
        }
    } else {
        //TODO: without geolocation you can't play -> set appropriate message
    }
};

/**
 * callback function, if geolocation was successful
 * save lat/lng value pair to local storage
 *
 * @param position
 * @return void
 */
game.onsuccess = function( position ) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;

    if( window.localStorage.getItem( 'location-current-lat' ) != lat || window.localStorage.getItem( 'location-current-lng' ) != lng ) {
        localStorage.setItem( 'location-current-lat', lat );
        localStorage.setItem( 'location-current-lng', lng );
    }
};

/**
 * callback function if geolocation failed for some reason
 *
 * @param error
 * @return string error message
 */
game.onerror = function( error ) {
    var message;
    switch ( error.code ) {
        case 0:
            message = "Diesen Fehler kenne ich nun wirklich nicht.";
            break;
        case 1:
            message = "Du hast dieser App keine Erlaubnis gegeben, deinen Standort zu benutzen.";
            break;
        case 2:
            message = "Keine Ahnung wo du steckst. Diesen Ort können wir nicht finden.";
            break;
        case 3:
            message = "Out of time. Irgendjemand war hier zu langsam.";
            break;
    }
    return message;
};

/**
 * geolocation observer object,
 * checks if current position has changed and refresh position on demand
 *
 * @return void
 */
game.observer = function() {
    navigator.geolocation.watchPosition( game.onsuccess, game.onerror, { enableHighAccuracy: true } );
};

/**
 * get users open quest
 * call controller action get next quest
 * get json object which contains quest description
 * if all quests were solved an appropriate message will be returned instead
 *
 * @return void
 */
game.getCurrentQuest = function() {
    $.ajax({
        type: 'GET',
        url: baseUrl+'game/getCurrentQuest',
        success: function( response ) {
            console.log( response );
        },
        dataType: 'json'
    });
};


/**
 * check in at current position
 * refresh current position, to make sure
 * call controller/action to compare current position with
 * current tasks lat/lng circuit values (borders south, north, east, west)
 * comparison is handled on server side
 *
 * @return String message
 */
game.setCheckIn = function( event ) {
    game.observer();
    $.ajax({
        type: 'GET',
        data: { 'lat': game.currentLat, 'lng': game.currentLng },
        url: baseUrl+'game/setCheckIn',
        success: function( response ) {
            $( '.dialog' ).find( 'h1' ).empty().text( ''+response.head+'' );
            $( '.dialog' ).find( 'h2' ).empty().html( ''+response.text+'<br /><br />' );
            $( '.dialog' ).find( 'a:first').remove();
            $( '.dialog' ).find( 'a' ).empty().text( 'Schliessen' );
        },
        dataType: 'json'
    });
   // event.preventDefault();
};

/**
 * confirm check in
 */
$( '#check-in' ).on( 'click', function() {
    overlay.fadeIn( '500', 'linear' );
    $( '<div id="confirm-check-in" class="dialog"/>')
        .html(
            '<div class="close button-close">X</div>' +
            '<h1>Ganz sicher?</h1>' +
            '<h2>Es gibt kein zurück.</h2>' +
            '<a onclick="game.setCheckIn()">Sicher!</a>' +
            '<a class="close dismiss">Doch nicht...</a>'
        )
        .appendTo( $( '#header' ) );

     // attach event handler to close buttons
    $( '.close' ).each( function( index ) {
        $( this ).on( 'click', function() {
            $( '.dialog' ).remove();
            overlay.fadeOut();
        });
    });
});


/**
 * profile delete was clicked - set message first
 * if message was confirmed redirect to delete
 *
 * @return void
 */
$( '#profile-delete' ).on( 'click', function() {
    //TODO DELETE
    alert("really?");
});


/**
 * call init function, to get things started ;)
 */
game.init();
