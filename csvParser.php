<?php

namespace elly;

//set up helper vars
/*define( "CSV_PATH", "data" . DIRECTORY_SEPARATOR );

//get the data
$censusCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-data.csv";
$foodCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/food-inspections.csv";
$censusCsvLocal = CSV_PATH . "census-data.csv";
$foodCsvLocal = CSV_PATH . "food-inspections.csv";
$censusHandle = fopen( $censusCsvRemote, 'r' );
$foodHandle = fopen( $foodCsvRemote, 'r' );
$censusData = fgetcsv( $censusHandle );
$foodData = fgetcsv( $foodHandle );

//basic formatting if xdebug not installed
if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "<pre>";

//dump the data
var_dump( $censusData, $foodData );

//close formatting
if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "</pre>"; */


/**
 * Reads a CSV file to an array
 * @param String $file: the full path to the file (can be remote)
 * @throws \Exception if cannot open CSV
 */
public static function readCsvToArray( $file ) {
	if( $handle = fopen( $file, 'r' ) ) === FALSE )
		throw new \Exception( "Could not open file {$file}." );

	$arr = array();
	while( ($data = fgetcsv( $handle ) ) !== FALSE ):
		$arr[] = $data;
	endwhile;
	
	fclose( $handle );

	return $arr;

} //readCsvToArray