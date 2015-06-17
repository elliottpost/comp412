//global vars
var geocoder;
var map;
var infowindow = new google.maps.InfoWindow();
var marker;

//update our status
jQuery(document).ready(function($){

	//ensure we have data to work with
	if( typeof( censusDataJSON ) == 'undefined' || typeof( foodDataJSON ) == 'undefined' ) {
		// console.log( "JSON not provided. Exiting." ); 
		throw new Error("JSON not provided. Exiting.");
	}

	//Parsing JSON from PHP for these structures gives quirky results. Instead, we'll be using objects
	//supplied from PHP instead of JS MD arrays. So, we do not need jQuery to parse the JSON for us.
	// var censusData = $.parseJSON( censusDataJSON );
	// var foodData = $.parseJSON( foodDataJSON );

	//log the values
	// console.log( censusDataJSON );
	// console.log( foodDataJSON );


	var step = 1;
	$( "ul#status" ).append( "<li id='status-" + step + "'>Asynchronously matching food inspection results to latitude and longitude&hellip;</li>" );

	/*$.each( foodDataJSON, function( value ) {
		conole.log( value );
	} );*/

	processMapQuery();
});


function processMapQuery() {
	// codeLatLng( lat, long );
	codeLatLng();
}

/**
 * Inits the google map with the KML layer sourced from: 
 * @link https://www.google.com/maps/d/u/0/viewer?mid=zYiwUHdW8Wmg.kNxj-e0srss0
 */
function initialize() {
	geocoder = new google.maps.Geocoder();
	var chicago = new google.maps.LatLng(41.875696,-87.624207);
	var mapOptions = {
		zoom: 8,
		center: chicago
	}

	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

	var neighborhoodLayer = new google.maps.KmlLayer({
		url: 'http://projects.ellytronic.media/homework/comp412/hw2/data/chicago-neighborhoods.kmz'
	});
	neighborhoodLayer.setMap(map);
}

/**
 * processes a reverse geocoding request
 * @link https://developers.google.com/maps/documentation/javascript/examples/geocoding-reverse
 */
function codeLatLng() {
  var lat = parseFloat(41.87424299);
  var lng = parseFloat(-87.63347653);
  var latlng = new google.maps.LatLng(lat, lng);
  geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[1]) {
        map.setZoom(11);
        marker = new google.maps.Marker({
            position: latlng,
            map: map
        });
        infowindow.setContent(results[1].formatted_address);
        infowindow.open(map, marker);
      } else {
        alert('No results found');
      }
    } else {
      alert('Geocoder failed due to: ' + status);
    }
  });
}


google.maps.event.addDomListener( window, 'load', initialize );