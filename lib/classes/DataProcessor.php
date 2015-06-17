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
			$this->_scores[ $zip ] = new Neighborhood;
			$this->_scores[ $zip ]->setName( $name );
		}
	}

	/**
	 * Iterates the food data and keeps aggregate totals for the entire dataset as well 
	 * as per zip code
	 */
	public function iterateFoodData() {
		foreach( $this->_foodData as $record ) {
			if( $record['results'] == "pass" )
				$this->_foodAggregated['totalPass']++;
			else
				$this->_foodAggregated['totalFail']++;
			break;
		}
	} //iterateFoodData
	
} //DataProcessor