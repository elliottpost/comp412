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
	public $_communities = array();

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
		$communityIdToZipRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/zip-code-to-community-id.csv";
		$censusCsvLocal = CSV_PATH . "census-data.csv";
		$foodCsvLocal = CSV_PATH . "food-inspections.csv";
		$communityIdToZipLocal = CSV_PATH . "zip-code-to-community-id.csv";

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
		$this->_communityIdMap = FileParser::createCommunityIdMap( $communityIdData );
		//because of duplicates, we can't just use array_flip to get the other map, so we'll 
		//create the map as we iterate through the ID map to generate the communities.
		// $this->_zipCodeMap = array_flip( $this->_communityIdMap );
		$this->_zipCodeMap = array();

		//set up our neighborhoods
		foreach( $this->_communityIdMap as $communityId => $zipCodeArray ) {
			//build a community! (if you haven't already!)
			if( !array_key_exists( $communityId, $this->_communities ) )
				$this->_communities[ $communityId ] = new Community( $communityId );

			//add the zip code to the community
			$this->_communities[ $communityId ]->setZipCodes( $zipCodeArray );

			//build our zip code -> community ID map
			foreach( $zipCodeArray as $zipCode) {
				if( array_key_exists( $zipCode, $this->_zipCodeMap ) )
					$this->_zipCodeMap[ $zipCode ][] = $communityId;
				else
					$this->_zipCodeMap[ $zipCode ] = array( $communityId );
			} //foreach $zipCodeArray as $zipCode
		}

		$this->_foodAggregated = array( 
				'totalPass' => 0, 
				'totalFail' => 0,
				'uniquePass' => 0,
				'uniqueFail' => 0
			);
	}

	/**
	 * Takes a zip code and returns an array of community IDs that match
	 * @param int $zip
	 * @return int[] $communityIds
	 */
	private function zipToCommunityIds( $zip ) {
		return $this->_zipCodeMap[ $zip ];
	} //zipToCommunityIds

	/**
	 * Takes a community ID and returns an array of zip codes IDs that match
	 * @param int $id
	 * @return int[] $zipCodes
	 */
	private function communityIdToZips( $id ) {
		return $this->_communityIdMap[ $zip ];
	} //communityIdToZips

	/**
	 * Iterates the food data and keeps aggregate totals for the entire dataset as well 
	 * as per zip code
	 * @return void;
	 */
	public function iterateFoodData() {
		
		foreach( $this->_foodData as $record ) {
			//ensure the zip code is within our data limits
			if( !array_key_exists( $record['zip_code'], $this->_zipCodeMap ) )
				continue;

			//now get the community IDs to update
			$communityIds = $this->zipToCommunityIds( $record['zip_code'] );

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

	} //iterateFoodData

	public function iterateCensusData() {
		foreach( $this->_censusData as $record ) {
			$this->_communities[ $record['community_id' ] ]->setName( $record['community_name'] );
			$this->_communities[ $record['community_id' ] ]->setHouseholdsBelowPoverty( $record['percent_households_below_poverty'] );
			$this->_communities[ $record['community_id' ] ]->setPerCapitaIncome( $record['per_capita_income'] );
		} //censusData as $record
	} //iterateCensusData

	/**
	 * returns the number of total passes counted
	 */
	public function getTotalPasses() {
		return $this->_foodAggregated[ 'totalPass' ];
	} //getTotalPasses

	/**
	 * returns the number of total fails counted
	 */
	public function getTotalFails() {
		return $this->_foodAggregated[ 'totalFail' ];
	} //getTotalFails

	/**
	 * returns the number of unique passes (passes only counted once)
	 */
	public function getUniquePasses() {
		return $this->_foodAggregated[ 'uniquePass' ];
	} //getUniquePasses

	/**
	 * returns the number of unique fails (fails only counted once)
	 */
	public function getUniqueFails() {
		return $this->_foodAggregated[ 'uniqueFails' ];
	} //getUniqueFails
	
	/** 
	 * Returns the array of Community objects with data populated
	 * @return Community[] Communities
	 */
	public function getCommunities() {
		return $this->_communities;
	} //getCommunities

} //DataProcessor