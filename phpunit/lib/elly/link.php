<?php

namespace elly;

//set up our absolute path, with trailing slash included
define( "DOC_ROOT", "/var/www/ellytronic/luc/comp412-hw2/" );

//include our lib
require_once DOC_ROOT . 'lib/classes/FileParser.php';
require_once DOC_ROOT . 'lib/classes/DataProcessor.php';
require_once DOC_ROOT . 'lib/classes/Community.php';
require_once DOC_ROOT . 'lib/classes/StatsProcessor.php';

//set up some constants
define( "CSV_PATH", DOC_ROOT . "data" . DIRECTORY_SEPARATOR );

//override this if working on localhost
// define( "LOCAL", ( $_SERVER['DOCUMENT_ROOT'] == "/home/coahvytt/public_html/projects" ) ? true : false );
define( "LOCAL", true );

//don't close the tag, it eliminates erroneous whitespace