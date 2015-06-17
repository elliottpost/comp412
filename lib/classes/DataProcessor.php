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
	 * @var $_zipCodeNeighboorMap: the assoc array of zip codes -> neighborhoods
	 */
	private $_zipCodeNeighboorMap;

	/**
	 * @var $_neighborhoods: the aggregated data
	 */
	private $_neighborhoods = array();

	/**
	 * constructs the inspection processor
	 */ 
	public function __construct( ) {} //constructor

	/**
	 * loads the data from the FileParser
	 * @throws \Exception if FileParser cannot parse CSV
	 */ 
	public function loadData() {
		//get the data
		$censusCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/census-data.csv";
		$foodCsvRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/food-inspections.csv";
		$neighborhoodsRemote = "http://projects.ellytronic.media/homework/comp412/hw2/data/neighborhoods-zips.csv";
		$censusCsvLocal = CSV_PATH . "census-data.csv";
		$foodCsvLocal = CSV_PATH . "food-inspections.csv";
		$neighborhoodsLocal = CSV_PATH . "neighborhoods-zips.csv";

		if( !LOCAL ):
			$this->_censusData = FileParser::readCsvToAssocArray( $censusCsvRemote );
			$this->_foodData = FileParser::readCsvToAssocArray( $foodCsvRemote );
			$neighborhoodData = FileParser::readCsvToArray( $neighborhoodsRemote );
		else:
			$this->_censusData = FileParser::readCsvToAssocArray( $censusCsvLocal );
			$this->_foodData = FileParser::readCsvToAssocArray( $foodCsvLocal );
			$neighborhoodData = FileParser::readCsvToArray( $neighborhoodsLocal );
		endif;

		$this->_zipCodeNeighboorMap = FileParser::createZipCodeAssocArray( $neighborhoodData );

		//set up our neighborhoods
		foreach( $this->_zipCodeNeighboorMap as $zip => $name ) {
			$this->_neighborhoods[ $zip ] = new Neighborhood;
			$this->_neighborhoods[ $zip ]->setName( $name );
		}

		$this->_foodAggregated = array( 'totalPass' => 0, 'totalFail' => 0 );
	}

	/**
	 * Iterates the food data and keeps aggregate totals for the entire dataset as well 
	 * as per zip code
	 */
	public function iterateFoodData() {
		foreach( $this->_foodData as $record ) {
			//ensure the zip code is within our data limits
			if( !array_key_exists( $record['zip'], $this->_zipCodeNeighboorMap ) )
				continue;

			if( $record['results'] == "pass" ) {
				$this->_foodAggregated['totalPass']++;
				$this->_neighborhoods[ $record['zip'] ]->incrementPasses();

			} else {
				$this->_foodAggregated['totalFail']++;
				$this->_neighborhoods[ $record['zip'] ]->incrementFails();
			}
		}

	} //iterateFoodData


	
} //DataProcessor