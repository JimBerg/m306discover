/* ------------------------------------------------------------*
 *
 * create game and user object
 * - set methods and properties
 *
 * ------------------------------------------------------------*/
var cg_game = {};
var cg_user = {};
var cg_geolocation = {};


/* ------------------------------------------------------------*
 * runs everytime the app is loaded
 *
 * 1. check if there's already a location in local storage
 * 2. else check if geoloction is supported and set local storage
 *
 * ------------------------------------------------------------*/
cg_game.init = function() {
    if ( window.localStorage.getItem( 'location-home-lat' ) && window.localStorage.getItem( 'location-home-lng' ) ) {
        cg_geolocation.observer(); //observe position - detect changes

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

    //TODO: SET THIS ONLY ON REGISTRATION // REGISTRATION FORM
    /* only used for registration but we may not load it in first init process
    maybe user cancel registration or there's an input error...
     */
    var lat = window.localStorage.getItem( 'location-home-lat' );
    var lng = window.localStorage.getItem( 'location-home-lng' );
    // set hidden fields for storing to database
    $( 'input#position-lat' ).val( lat );
    $( 'input#position-lng' ).val( lng );
}


cg_game.getNextTask = function() {


    console.log(test);

    // get current user

    // get his latest task -> resp. next task from db settings


}



/* ------------------------------------------------------------*
 *
 * try to get position, set local storage items for starting p.
 * set hidden values to save location tob db
 *
 * ------------------------------------------------------------*/
cg_geolocation.onsuccess = function( position ) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;

    // only on first visit
    if( !window.localStorage.getItem( 'location-home-lat' ) ) {
        localStorage.setItem( 'location-home-lat', lat );
        localStorage.setItem( 'location-home-lng', lng );

        localStorage.setItem( 'location-current-lat', lat );
        localStorage.setItem( 'location-current-lng', lng );
    } else if( window.localStorage.getItem( 'location-current-lat' ) != lat || window.localStorage.getItem( 'location-current-lng' ) != lng ) { //umkreis vernachlässigbar in diesem fall
        localStorage.setItem( 'location-current-lat', lat );
        localStorage.setItem( 'location-current-lng', lng );
    }

}


/* ------------------------------------------------------------*
 *
 * handle geolocation errors
 * TODO: something WITH the error messages
 *
 * ------------------------------------------------------------*/
cg_geolocation.onerror = function( error ) {
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
}


/* ------------------------------------------------------------*
 * watch current location and refresh if neccessary
 * ------------------------------------------------------------*/
cg_geolocation.observer = function() {
    navigator.geolocation.watchPosition( cg_geolocation.onsuccess, cg_geolocation.onerror, { enableHighAccuracy: true } );
}










