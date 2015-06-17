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
	<p>The duplicate tests should be a high value, given we are using zip codes to lookup community ID, instead of Lat/Long lookup for neighboorhoods. This means because a community ID
	can have several zip codes, every time a zip code is reported in the food inspection it affects <em>at least</em> one community, but usually more.
	However, the percentages between total and unique should be very close suggesting that although our data has flaws, it should not affect the percentages of the results, only the aggregate values.</p>

	<h3>Community Breakdowns</h3>
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
			foreach( $communities as $community ):
				//calculate pass & fail totals/percentages
				$p = $community->getPasses();
				$f = $community->getFails();
				$i =  $p + $f;
				$pp = round( ( $p / $i ) * 100, 2 );
				$pf = round( ( $f / $i ) * 100, 2 );

				?>
				<tr>
					<td><?=$community->getId();?></td>
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
    <span class="pull-right"><small>*Times listed are UTC</small></span>


	<?php

} catch( \Exception $e ) {
	echo "<p style='color: #ff0000; font-weight: 700;'>An error occurred during processing: " . $e->getMessage() . "</p>";
}
