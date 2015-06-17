<?php
namespace elly;
global $dp;

//load the data
try {
	$dp->loadData();
	$dp->iterateFoodData();
	$dp->iterateCensusData();

	$totalPasses = $dp->getTotalPasses();
	$totalFails = $dp->getTotalFails();
	$uniquePasses = $dp->getUniquePasses();
	$uniqueFails = $dp->getUniqueFails();

	var_dump( $dp->_communities );
} catch( \Exception $e ) {
	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
}
