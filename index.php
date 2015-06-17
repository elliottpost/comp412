<?php

namespace elly;

//include our lib
require_once 'lib/classes/FileParser.php';

//set up some constants
define( "CSV_PATH", "data" . DIRECTORY_SEPARATOR );

require_once 'lib/templates/header.php';
require_once 'lib/templates/home.php';
require_once 'lib/templates/footer.php';


//don't close the tag, it eliminates erroneous whitespace