<?php
/** 
 * Counters PHP Units terrible method for including files for unit tests
 */
namespace elly;

class ProjectAutoload {

	public function __construct() {
		if( !defined( "PROJECT_PATH" ) )
			define( "PROJECT_PATH", "/var/www/html/ellytronic/public_html/luc/comp412-hw2/" );

		if( !defined( "LOCAL" ) )
			define( "LOCAL", FALSE ); //force working remotely for testing

		require_once PROJECT_PATH . 'lib/classes/FileParser.php';
		require_once PROJECT_PATH . 'lib/classes/DataProcessor.php';
		require_once PROJECT_PATH . 'lib/classes/Community.php';
		require_once PROJECT_PATH . 'lib/classes/StatsProcessor.php';

		//set up some constants
		if( !defined( "CSV_PATH" ) )
			define( "CSV_PATH", PROJECT_PATH . "data" . DIRECTORY_SEPARATOR );

		require_once PROJECT_PATH . 'lib/classes/Community.php';
	}
}