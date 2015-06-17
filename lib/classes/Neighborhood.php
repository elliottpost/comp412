<?php
/**
 * Represents a neighborhood
 */
namespace elly;

final class Neighborhood {

	/**
	 * @var $passes: aggregated food inspection passes
	 */
	private $_passes = 0;

	/**
	 * @var $fails: aggregated food inspection fails
	 */
	private $_fails = 0;

	/**
	 * @var $name: the neighborhood name
	 */
	private $_name;

	/**
	 * @var $householdsBelowPoverty: percentage of households below poverty in this neighborhood
	 */
	private $_householdsBelowPoverty;

	/**
	 * @var $_perCapitaIncome: per capita income
	 */
	private $_perCapitaIncome;

	/**
	 * constructs the inspection processor
	 */ 
	public function __construct( ) {} //constructor


	public function getFails( $name ) {
		return $this->_fails;
	}

	public function incrementFails() {
		$this->_fails++;
	}

	public function getPasses( $name ) {
		return $this->_passes;
	}

	public function incrementPasses() {
		$this->_passes++;
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
		$this->_householdsBelowPoverty = $num;
	}

	public function getPerCapitaIncome() {
		return $this->_perCapitaIncome;
	}

	public function setPerCapitaIncome( $num ) {
		$this->_perCapitaIncome = $num;
	}
	
} //Neighborhood