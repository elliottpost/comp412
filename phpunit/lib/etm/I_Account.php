<?php

/**
 *
 * @todo needs comment
 */
namespace etm;

interface I_Account {

	
	/** 
	 * @todo needs comments
	 * @throws 
	 */
	public function __construct( $accountNumber );

	/** 
	 * returns the account profile (company name, billing email, TODO?)
	 * @todo needs comments
	 */
	public function &getProfile();

	/** 
	 * returns the account profile (company name, billing email, TODO?)
	 * @todo needs comments
	 */
	public function saveProfile();

	/**
	 * Verifies an account number meets valid formatting
	 * does NOT verify the account number exists
	 * @todo needs comments
	 */
	public static function isValidAccountNumber( $accountNumber );

	/**
	 * Verifies an account number exists
	 * @todo needs comments
	 */
	public static function accountNumberExists( $accountNumber );

	/**
	 * Generates an account number
	 * @todo needs comments
	 */
	public static function generateAccountNumber();

}