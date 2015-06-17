//global vars
var locations = [];

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
		$.delay(1000).function(){
			codeLatLng( index, element.latitude, element.longitude );*/
		}
		locations[index] = {
				results: element.results,
				// date: element.inspection_date,
				neighborhood: null
			};		
		
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