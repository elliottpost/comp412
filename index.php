<?php

namespace elly;

//include our lib
require_once 'lib/classes/FileParser.php';

//set up helper vars
define( "CSV_PATH", "data" . DIRECTORY_SEPARATOR );

//get the data
$censusCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-data.csv";
$foodCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/food-inspections.csv";
$censusCsvLocal = CSV_PATH . "census-data.csv";
$foodCsvLocal = CSV_PATH . "food-inspections.csv";

//basic formatting if xdebug not installed
if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "<pre>";

try {

	$censusData = FileParser::readCsvToArray( $censusCsvRemote );
	$foodData = FileParser::readCsvToArray( $foodCsvRemote );

	var_dump( $censusData, $foodData );

} catch( \Exception $e ) {

	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
}

//close formatting
if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "</pre>"; 