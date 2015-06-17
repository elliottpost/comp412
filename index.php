<?php

namespace elly;

//include our lib
require_once 'lib/classes/FileParser.php';
require_once 'lib/classes/DataProcessor.php';
require_once 'lib/classes/Community.php';
require_once 'lib/classes/StatsProcessor.php';

//set up some constants
define( "CSV_PATH", "data" . DIRECTORY_SEPARATOR );

//override this if working on localhost
// define( "LOCAL", ( $_SERVER['DOCUMENT_ROOT'] == "/home/coahvytt/public_html/projects" ) ? true : false );
define( "LOCAL", true );

$dp = new DataProcessor;

require_once 'lib/templates/header.php';
require_once 'lib/templates/home.php';
require_once 'lib/templates/footer.php';

//don't close the tag, it eliminates erroneous whitespace