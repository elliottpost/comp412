//global vars
var geocoder;
var map;
var infowindow = new google.maps.InfoWindow();
var marker;
var locations = [];

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

	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

	var neighborhoodLayer = new google.maps.KmlLayer({
		url: 'http://projects.ellytronic.media/homework/comp412/hw2/data/chicago-neighborhoods.kmz'
	});
	neighborhoodLayer.setMap(map);

	processJSON();

}

/**
 * processes a reverse geocoding request
 * @link https://developers.google.com/maps/documentation/javascript/examples/geocoding-reverse
 */
function codeLatLng( index, lat, lng ) {
	lat = parseFloat( lat );
	lng = parseFloat( lng );
	var chicago = new google.maps.LatLng( lat, lng );
	geocoder.geocode({'latLng': chicago}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[2] ) {

				locations[index].neighborhood = results[2].address_components[0].long_name;
			} else {
				locations[index].neighborhood = "Unknown";
			}
		} else {
			throw new Error( '[Index: ' + index + '] Geocoder failed due to: ' + status );
		}
	});
}

//update our status
function processJSON() {

	var $ = jQuery;

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

	var step = 1;
	$( "ul#status" ).append( "<li id='status-" + step + "'>Asynchronously matching food inspection results to latitude and longitude&hellip;</li>" );

	$.each( foodDataJSON, function( index, element ) {
		locations[index] = {
				results: element.results,
				// date: element.inspection_date,
				neighborhood: null
			};
		codeLatLng( index, element.latitude, element.longitude );
	});

	setTimeout( function(){
		$("#details").text( JSON.stringify( locations ) );
		
		//mark this step complete
		$("li#status-" + step ).append( "done" );
		step++;
	}, 10000 );

}


//start google maps
google.maps.event.addDomListener( window, 'load', initialize );