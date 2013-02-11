/* ------------------------------------------------------------*
 *
 * create map object
 * - set map specific options
 * - add custom marker and layer
 *
 * requires: leaflet.js
 * ------------------------------------------------------------*/
var cg_map = {};

/* ------------------------------------------------------------*
 *
 * some helper functions
 * DOM interaction
 *
 * ------------------------------------------------------------*/

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

function setCheckIn() {
    cg_geolocation.observer(); // refresh, just to make sure
    alert("check");
    var data;
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

            // annnnnnd now write it to the database!!
            $.ajax({
                url: 'http://lokal.horst/websites/mapgames/index.php/app/setCheckIn/'+data[i].id,
                async: false
            });

            //user frage freischalten
            //var task = data[i].task;
            //alert(data[i].task);
            //$( '<div id="new-task">'+data[i].task+'</div>' ).appendTo( '.cg-marker-control-layer' );
        }
    }
}


/* ------------------------------------------------------------*
 * define custom icons
 * ------------------------------------------------------------*/
cg_map.homeIcon = L.icon({
    iconUrl: 'http://lokal.horst/websites/mapgames/application/images/leaf-green.png',
    //shadowUrl: 'leaf-shadow.png',

    iconSize:     [38, 95], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

cg_map.currentIcon = L.icon({
});

cg_map.taskIconsOpen = L.icon({
});

cg_map.taskIconsComplete = L.icon({
});



/* ------------------------------------------------------------*
 *
 * create custom control = panel with different options
 * @return DOM Element -> container
 *
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

        var checkIn;

        for( var i = 1; i <= 3; i++ ){
            markerCollectionLayer[i] = cg_map.getMarkerCollection( i ); //get bundled markers
            var that = markerCollectionLayer[i];
            markerType[i] = $( '<input type="checkbox" id="markerType_'+i+'" value="type_'+i+'" /><span class="fakeBox"></span><span class="markerToggle" >Typ '+i+'</span>' ).appendTo( $ ( container ) );
        }


        $( markerType ).each( function( index, elem ) {
            $( this ).on( 'click', function( event ) {

                if( $( this ).attr( 'checked' ) === 'checked' ) {
                    visible = false;
                } else {
                    visible = true;
                }

                layerToggle.apply( markerCollectionLayer[ index ], [ { 'visible': visible } ] );
                event.stopPropagation();
            });
        });

        checkIn = $( '<div id="checkIn">Check in</div>' ).appendTo( $ ( container ) );
        $( checkIn ).on( 'click', function( event ) {
            setCheckIn();
            event.stopPropagation();
        });

        $( container ).on( 'click', function() { markerControlPanelToggle.apply( domElem ); } );

        return container;
    }
});


/* ------------------------------------------------------------*
 * add some functionality to the map object
 * ------------------------------------------------------------*/
cg_map.init = function() {
    cg_map.map = L.map( 'map', {
        center: new L.LatLng( cg_user.home.lat, cg_user.home.lng ),
        zoom: 15,
        layers: []
    });
    L.tileLayer("http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png", { minZoom: 12, maxZoom: 18, detectRetina: true } ).addTo( cg_map.map );
    cg_map.setMarker();
}


/* ------------------------------------------------------------*
 * set home marker
 * ------------------------------------------------------------*/
cg_map.setMarker = function() {
    cg_map.homeMarker = L.marker( [ cg_user.home.lat, cg_user.home.lng ], { icon: cg_map.homeIcon } );
    cg_map.currentMarker = L.marker( [ cg_user.current.lat, cg_user.current.lng ] );
    cg_map.nextCheckPointMarker = L.marker( cg_map.getNextCheckPoint() );

    cg_map.homeMarker.addTo( cg_map.map );
    cg_map.currentMarker.addTo( cg_map.map );
    cg_map.nextCheckPointMarker.addTo( cg_map.map );

    cg_map.homeMarker.bindPopup( "<p>Startposition!</p>" ); //.openPopup();
    cg_map.currentMarker.bindPopup( "<p>Aktuelle Position</p>" );
    cg_map.nextCheckPointMarker.bindPopup( "<p>NEXT</p>" );
}


/* ------------------------------------------------------------*
 * get next checkpoint
 * ------------------------------------------------------------*/
cg_map.getNextCheckPoint = function() {
    var data;
    var request = $.ajax({
        url: 'http://lokal.horst/websites/mapgames/index.php/app/getNextCheckPoint/',
        async: false
    });

    if ( request ) {
        data =  $.parseJSON( request.responseText );
        return [ data.lat, data.lng ];
    } else {
        return [ 0, 0 ] //false; TODO handle this somehow...
    }
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

