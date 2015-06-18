<?php

/**
 *
 * @todo needs comment
 */
namespace etm;

/**
 * @todo remove on framework init
 */
require_once 'I_Account.php';

class Account implements I_Account {

	private $accountNumber;
	private $profile;	

	/** 
	 * @todo needs comments
	 * @throws 
	 */
	public function __construct( $accountNumber ) {
		if( ! self::isValidAccountNumber( $accountNumber ) )
			throw new \InvalidArgumentException( "Account Number is invalid." );

		$this->accountNumber = (int) $accountNumber;

	}

	/** 
	 * returns the account profile (company name, billing email, TODO?)
	 * @todo needs comments
	 */
	public function &getProfile() {

	}

	/** 
	 * returns the account profile (company name, billing email, TODO?)
	 * @todo needs comments
	 */
	public function saveProfile() {

	}

	/**
	 * Verifies an account number meets valid formatting
	 * does NOT verify the account number exists
	 * @todo needs comments
	 */
	public static function isValidAccountNumber( $accountNumber ) {
		if( !is_numeric( $accountNumber ) || empty( $accountNumber )
		|| (int) $accountNumber < 1 )
			return false;

		return ( fmod( $accountNumber, 1) == 0 );

	}

	/**
	 * Verifies an account number exists
	 * does NOT verify valid account number
	 * @todo needs comments
	 */
	public static function accountNumberExists( $accountNumber ) {
		/**
		 * @todo 
		 */
	}

	/**
	 * Generates an account number
	 * @todo needs comments
	 */
	public static function generateAccountNumber() {
		/**
		 * @todo 
		 */
	}

}