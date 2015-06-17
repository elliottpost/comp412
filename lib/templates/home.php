<?php
namespace elly;
global $dp;

//load the data
try {
	$dp->loadData();

} catch( \Exception $e ) {
	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
}

?>

<script src="lib/js/dataProcessor.js"></script>

<p><strong>Status:</strong></p>
<ul id="status">
	<li>CSV's parsing&hellip;done</li>
	<li>CSV's downloading&hellip;done</li>
	<li>Application loading&hellip;done</li>
</ul>
