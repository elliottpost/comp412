<?php

namespace elly;

//get the data
$censusCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-data.csv";
$foodCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/food-inspections.csv";
$neighborhoodsRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/neighborhoods-zips.csv";
$censusCsvLocal = CSV_PATH . "census-data.csv";
$foodCsvLocal = CSV_PATH . "food-inspections.csv";
$neighborhoodsLocal = CSV_PATH . "neighborhoods-zips.csv";

//basic formatting if xdebug not installed
/*if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "<pre>";*/

try {

	if( !LOCAL ):
		$censusData = FileParser::readCsvToAssocArray( $censusCsvRemote );
		$foodData = FileParser::readCsvToAssocArray( $foodCsvRemote );
		$neighborhoodData = FileParser::readCsvToArray( $neighborhoodsRemote );
	else:
		$censusData = FileParser::readCsvToAssocArray( $censusCsvLocal );
		$foodData = FileParser::readCsvToAssocArray( $foodCsvLocal );
		$neighborhoodData = FileParser::readCsvToArray( $neighborhoodsLocal );
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
var_dump( $neighborhoodData );
var_dump( FileParser::createZipCodeAssocArray( $neighborhoodData ) );
?>


<script src="lib/js/dataProcessor.js"></script>

<p><strong>Status:</strong></p>
<ul id="status">
	<li>CSV's downloaded and parsed.</li>
</ul>
