<?php

namespace elly;

//set up helper vars
define( "CSV_PATH", "data" . DIRECTORY_SEPARATOR );

//get the data
$censusData = fgetcsv( CSV_PATH . "census-data.csv" );
$foodData = fgetcsv( CSV_PATH . "food-inspections.csv" );

//basic formatting if xdebug not installed
if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "<pre>";

//dump the data
var_dump( CSV_PATH . "food-inspections.csv", $censusData, $foodData );

//close formatting
if( !function_exists( 'xdebug_get_code_coverage' ) )
	echo "</pre>";