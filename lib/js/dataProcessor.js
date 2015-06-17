//ensure we have data to work with
if( typeof( censusDataJSON ) == 'undefined' || typeof( foodDataJSON ) == 'undefined' ) {
	// console.log( "JSON not provided. Exiting." ); 
	throw new Error("JSON not provided. Exiting.");
}

//parse the JSON
var $ = jQuery;
//Parsing JSON from PHP for these structures gives quirky results. Instead, we'll be using objects
//supplied from PHP instead of JS MD arrays. So, we do not need jQuery to parse the JSON for us.
// var censusData = $.parseJSON( censusDataJSON );
// var foodData = $.parseJSON( foodDataJSON );

//log the values
console.log( censusDataJSON );
console.log( foodDataJSON );