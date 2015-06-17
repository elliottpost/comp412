<?php
/**
 * Processes the data
 */
namespace elly;

final class DataProcessor {

	/**
	 * @var $_foodData: the assoc array of food inspections parsed and cleaned already from CSV
	 */
	private $_foodData;

	/**
	 * @var $_foodAggregated: aggregated food data
	 */
	private $_foodAggregated;

	/**
	 * @var $_censusData: the assoc array of census inspections parsed and cleaned already from CSV
	 */
	private $_censusData;

	/**
	 * @var $_communityIdMap: the assoc array of community ID -> zip codes
	 */
	private $_communityIdMap;

	/**
	 * @var $_zipCodeMap: the reverse assoc array of communityIdMap (eg: zip code->communityId )
	 */
	private $_zipCodeMap;

	/**
	 * @var $_communities: the aggregated data
	 */
	private $_communities = array();

	/**
	 * constructs the inspection processor
	 */ 
	public function __construct( ) {} //constructor

	/**
	 * loads the data from the FileParser
	 * @return void;
	 * @throws \Exception if FileParser cannot parse CSV
	 */ 
	public function loadData() {
		//get the data
		$censusCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-data.csv";
		$foodCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/food-inspections.csv";
		$communityIdToZipRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-community-id-to-zip-code.csv";
		$censusCsvLocal = CSV_PATH . "census-data.csv";
		$foodCsvLocal = CSV_PATH . "food-inspections.csv";
		$communityIdToZipLocal = CSV_PATH . "census-community-id-to-zip-code.csv";

		if( !LOCAL ):
			$this->_censusData = FileParser::readCsvToAssocArray( $censusCsvRemote );
			$this->_foodData = FileParser::readCsvToAssocArray( $foodCsvRemote );
			$communityIdData = FileParser::readCsvToArray( $communityIdToZipLocal );
		else:
			$this->_censusData = FileParser::readCsvToAssocArray( $censusCsvLocal );
			$this->_foodData = FileParser::readCsvToAssocArray( $foodCsvLocal );
			$communityIdData = FileParser::readCsvToArray( $communityIdToZipLocal );
		endif;

		//get the maps for community ID -> zip and back
		$this->_communityIdMap = FileParser::createZipCodeAssocArray( $communityIdToZipLocal );
		//because of duplicates, we can't just use array_flip to get the other map, so we'll 
		//create the map as we iterate through the ID map to generate the communities.
		// $this->_zipCodeMap = array_flip( $this->_communityIdMap );

		//set up our neighborhoods
		foreach( $this->_communityIdMap as $communityId => $zipCode ) {
			//build a community!
			$this->_communities[ $communityId ] = new Community( $communityId );
			$this->_communities[ $communityId ]->addZipCode( $zipCode );

			//build our map
			if( array_key_exists( $zipCode, $this->_zipCodeMap ) )
				$this->_zipCodeMap[ $zipCode ][] = $communityId;
			else
				$this->_zipCodeMap[ $zipCode ] = array( $communityId );
		}

		$this->_foodAggregated = array( 
				'totalPass' => 0, 
				'totalFail' => 0,
				'uniquePass' => 0,
				'uniqueFail' => 0
			);
	}

	/**
	 * Iterates the food data and keeps aggregate totals for the entire dataset as well 
	 * as per zip code
	 * @return void;
	 */
	public function iterateFoodData() {
		foreach( $this->_foodData as $record ) {
			//ensure the zip code is within our data limits
			if( !array_key_exists( $record['zip'], $this->_zipCodeMap ) )
				continue;

			//now get the community IDs to update
			$communityIds = $this->zipToCommunityIds( $record['zip'] );

			//make sure that we have census data for this/these community
			if( empty( $communityIds ) )
				continue;

			//iterate through each id and perform aggregate data
			foreach( $communityIds as $id ) {
				if( $record['results'] == "pass" ) {
					$this->_foodAggregated['totalPass']++;
					$this->_communities[ $id ]->incrementPasses();

				} else {
					$this->_foodAggregated['totalFail']++;
					$this->_communities[ $id ]->incrementFails();
				}
			}

			//record our unique totals as well
			if( $record['results'] == "pass" )
				$this->_foodAggregated['uniquePass']++;
			else
				$this->_foodAggregated['uniqueFail']++;
		}
		var_dump( $this );
	} //iterateFoodData

	/**
	 * Takes a zip code and returns an array of community IDs that match
	 * @param int $zip
	 * @return int[] $communityIds
	 */
	private function zipToCommunityIds( $zip ) {
		return $this->_zipCodeMap[ $zip ];
	} //zipToCommunityIds
	
} //DataProcessor