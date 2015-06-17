<?php
namespace elly;
global $dp;

//load the data
try {
	$dp->loadData();
	$dp->iterateFoodData();

} catch( \Exception $e ) {
	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
}
