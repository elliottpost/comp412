<?php
namespace elly;
global $dp;

//do our processing
try {

	//load the data and process the CSVs
	$dp->loadData();
	$dp->iterateFoodData();
	$dp->iterateCensusData();

	//get some numbers and
	//do some light weight computations
	$totalPasses = $dp->getTotalPasses();
	$totalFails = $dp->getTotalFails();
	$totalInspections = $totalPasses + $totalFails;
	
	$uniquePasses = $dp->getUniquePasses();
	$uniqueFails = $dp->getUniqueFails();
	$uniqueInspections = $uniquePasses + $uniqueFails;
	
	$totalPassPercentage = round( ( $totalPasses / $totalInspections ) * 100, 2 );
	$totalFailPercentage = round( ( $totalFails / $totalInspections ) * 100, 2 );

	$uniquePassPercentage = round( ( $uniquePasses / $uniqueInspections ) * 100, 2 );
	$uniqueFailPercentage = round( ( $uniqueFails / $uniqueInspections ) * 100, 2 );

	//get the communities 
	$communities = $dp->getCommunities();
	
	?>
	<h3>Aggregate Data</h3>
	<ul>
		<li>Total Passes: <?=$totalPasses?> (<?=$totalPassPercentage?>%)</li>
		<li style="margin-bottom:1em;">Total Fails: <?=$totalFails?> (<?=$totalFailPercentage?>%)</li>

		<li>Unique Passes: <?=$uniquePasses?> (<?=$uniquePassPercentage?>%)</li>
		<li style="margin-bottom:1em;">Unique Fails: <?=$uniqueFails?> (<?=$uniqueFailPercentage?>%)</li>
		
		<li>Number of duplicate tests recorded: <?=$totalInspections - $uniqueInspections?></li>
	</ul>
	<p>The duplicate tests should be a high value, given we are using zip codes to lookup community ID, instead of Lat/Long lookup for neighboorhoods (See app readme for details). This means because a community ID
	can have several zip codes, every time a zip code is reported in the food inspection it affects <em>at least</em> one community, but usually more.
	However, the percentages between total and unique should be very close suggesting that although our data has flaws, it should not affect the percentages of the zip codes, only the aggregate values and individual community data.</p>

	<h3>Community/Zip Code Breakdowns</h3>
	<table class="table table-hover table-bordered table-condensed table-data-table">
        <thead>
            <tr>
                <th width="5%!important;">ID</th>
                <th width="16%!important;">Name</th>
                <th width="16%!important;">Num. of Passes</th>
                <th width="16%!important;">Num. of Fails</th>
                <th width="15%!important;">Households Below Poverty</th>
                <th width="16%!important;">Per Capita Income</th>
                <th width="16%!important;">Zip Codes</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Num. of Passes</th>
                <th>Num. of Fails</th>
                <th>Households Below Poverty</th>
                <th>Per Capita Income</th>
                <th>Zip Codes</th>
            </tr>
        </tfoot>
        <tbody>
            <?php
            //create a couple arrays where we will store count values
            //for determining std. dev later
            $passCounts = array();
            $failCounts = array();

            //set up some values so as we're iterating through our results we can
            //track best/worst for later
            $passes = array();

            //build an array we'll use to create a csv later
            $csvArr = array(
            		array( "community_id","per_capita_income","percentage_pass" )
            	);
			foreach( $communities as $id => $community ):
				//calculate pass & fail totals/percentages
				$p = $community->getPasses();
				$f = $community->getFails();
				$i =  $p + $f;
				$pp = round( ( $p / $i ) * 100, 2 );
				$pf = round( ( $f / $i ) * 100, 2 );

				//add the counts to our array of counts
				$passCounts[] = $p;
				$failCounts[] = $f;

				//keep track of best/worst
				$passes[ $id ] = $pp;

				//update our csv Array
				$csvArr[] = array( $id, $community->getPerCapitaIncome(), $pp );
				?>
				<tr>
					<td><?=$id;?></td>
					<td><?=$community->getName();?></td>
					<td><?=$p;?> (<?=$pp?>%)</td>
					<td><?=$f;?> (<?=$pf?>%)</td>
					<td><?=number_format( $community->getHouseholdsBelowPoverty() );?></td>
					<td>$<?=number_format( $community->getPerCapitaIncome() );?></td>
					<td><?=implode( ", ", $community->getZipCodes() );?></td>
				</tr>
				<?php
			endforeach; //communities as community
			?>
        </tbody>
    </table>

    <?php
    //now that we have our CSV, write it for our analytics below
	$fh = fopen( "lib/js/results.csv", "w" );
	//make it utf-8
	fprintf( $fh, "\xEF\xBB\xBF" );
	foreach( $csvArr as $line )
		fputcsv( $fh, $line );
	fclose( $fh );
    ?>
    

    <h3>Standard Deviations</h3>
    <ul>
    	<li>Standard Deviation of Pass Counts: <?=StatsProcessor::calculateStdDev( $passCounts );?></li>
    	<li>Standard Deviation of Fail Counts: <?=StatsProcessor::calculateStdDev( $failCounts );?></li>
    </ul>


    <h3>Top 5 Zip Codes/Communities as Measured By Pass Percentage</h3>
    <table class="table table-striped table-bordered table-condensed">
    	<thead>
    		<tr>
    			<th>Community ID</th>
    			<th>Community Name</th>
    			<th>Pass %</th>
    			<th>Per Capita Income</th>
    			<th>Zip Codes</th>
    		</tr>
    	</thead>
    	<tbody>
	    	<?php
			//sort our values by highest to lowest while maintaining index
		    arsort( $passes );
		    $k = 0;
		    foreach( $passes as $id => $percentage ) {
		    	?>
		    	<tr>
		    		<td><?=$id?></td>
		    		<td><?=$communities[ $id ]->getName();?></td>
		    		<td><?=$percentage?>%</td>
		    		<td>$<?=number_format( $communities[ $id ]->getPerCapitaIncome() )?></td>
		    		<td><?=implode( ", ", $community->getZipCodes() );?></td>
		    	</tr>
		    	<?php
		    	$k++;
		    	if( $k >= 4 )
		    		break;
		    }
	    	?>
    	</tbody>
    </table>

    <h3>Bottom 5 Zip Codes/Communities as Measured By Pass Percentage</h3>
    <table class="table table-striped table-bordered table-condensed">
    	<thead>
    		<tr>
    			<th>Community ID</th>
    			<th>Community Name</th>
    			<th>Pass %</th>
    			<th>Per Capita Income</th>
    			<th>Zip Codes</th>
    		</tr>
    	</thead>
    	<tbody>
	    	<?php
			//sort our values by lowest to highest while maintaining index
		    asort( $passes );
		    $k = 0;
		    foreach( $passes as $id => $percentage ) {
		    	?>
		    	<tr>
		    		<td><?=$id?></td>
		    		<td><?=$communities[ $id ]->getName();?></td>
		    		<td><?=$percentage?>%</td>
		    		<td>$<?=number_format( $communities[ $id ]->getPerCapitaIncome() )?></td>
		    		<td><?=implode( ", ", $community->getZipCodes() );?></td>
		    	</tr>
		    	<?php
		    	$k++;
		    	if( $k >= 4 )
		    		break;
		    }
	    	?>
    	</tbody>
    </table>

    <!--h3>Q-Q Plot of Per Capita Income &amp; Pass Percentage</h3-->
	<?php
	// require_once 'lib/templates/qq.php';
	?>

    <!--h3>Scatterplot Matrix of Per Capita Income &amp; Pass Percentage</h3-->
	<?php
	// require_once 'lib/templates/splom.php';
	?>

	<?php

} catch( \Exception $e ) {
	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
}
