<?php

namespace elly;

//get the data
$censusCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-data.csv";
$foodCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/food-inspections.csv";
$censusCsvLocal = CSV_PATH . "census-data.csv";
$foodCsvLocal = CSV_PATH . "food-inspections.csv";

//basic formatting if xdebug not installed
/*if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "<pre>";*/

try {

	if( !LOCAL ):
		$censusData = FileParser::readCsvToArray( $censusCsvRemote );
		$foodData = FileParser::readCsvToArray( $foodCsvRemote );
	else:
		$censusData = FileParser::readCsvToArray( $censusCsvLocal );
		$foodData = FileParser::readCsvToArray( $foodCsvLocal );
	endif;

} catch( \Exception $e ) {

	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
/*	//close formatting
	if( !function_exists( 'xdebug_get_code_coverage' ) )
	  echo "</pre>"; */
	return;
}

//close formatting
/*if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "</pre>"; */

?>

<script>
	var censusDataJSON = <?=json_encode( $censusData, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK );?>;
	var foodDataJSON = <?=json_encode( $foodData, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK );?>;
</script>

<script src="lib/js/dataProcessor.js"></script>

<p><strong>Status:</strong></p>
<ul id="status">
	<li>CSV's downloaded and parsed.</li>
</ul>


<div id="map-canvas" style="min-height: 300px"></div>