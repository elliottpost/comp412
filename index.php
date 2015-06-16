<?php

namespace elly;

//set up helper vars
define( "CSV_PATH", "data" . DIRECTORY_SEPARATOR );

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
	echo "</pre>"; 