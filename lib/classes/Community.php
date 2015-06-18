<?php
/**
 * Represents a Community
 */
namespace elly;

final class Community {

	/**
	 * @var $passes: aggregated food inspection passes
	 */
	private $_passes = 0;

	/**
	 * @var $fails: aggregated food inspection fails
	 */
	private $_fails = 0;

	/**
	 * @var $name: the Community name
	 */
	private $_name;

	/**
	 * @var $householdsBelowPoverty: percentage of households below poverty in this Community
	 */
	private $_householdsBelowPoverty;

	/**
	 * @var $_perCapitaIncome: per capita income
	 */
	private $_perCapitaIncome;

	/**
	 * @var $_zipCodes: the Community ZIP codes
	 */
	private $_zipCodes;

	/**
	 * @var $_id: the community ID
	 */
	private $_id;

	/**
	 * constructs the inspection processor
	 * @param int $id: the community ID
	 */ 
	public function __construct( $id ) {
		$this->_id = $id;
	} //constructor


	public function getFails() {
		return $this->_fails;
	}

	public function incrementFails() {
		$this->_fails++;
	}

	public function getPasses() {
		return $this->_passes;
	}

	public function incrementPasses() {
		$this->_passes++;
	}

	public function getId() {
		return $this->_id;
	}

	public function getName() {
		return $this->_name;
	}

	public function setName( $name ) {
		$this->_name = $name;
	}

	public function getHouseholdsBelowPoverty() {
		return $this->_householdsBelowPoverty;
	}

	public function setHouseholdsBelowPoverty( $num ) {
		$this->_householdsBelowPoverty = (int) $num;
	}

	public function getPerCapitaIncome() {
		return $this->_perCapitaIncome;
	}

	public function setPerCapitaIncome( $num ) {
		$this->_perCapitaIncome = (int) $num;
	}

	public function getZipCodes() {
		return $this->_zipCodes;
	}

	public function setZipCodes( $zipArray ) {
		$this->_zipCodes = $zipArray;
	}
	
} //Community