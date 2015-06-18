<?php

namespace etm;

class UsersTest extends \PHPUnit_Framework_TestCase {

	protected $obj = NULL;

	protected function setUp() {
		$GLOBALS['db'] = new \mysqli( "localhost", "root", "root", "etm_billing" );	
	}
	
	public function useridsNoFilterDP() {
		$data = array();
		$data[] = array( array( 1, 2, 3, 4, 5, 6 ) );
		return $data;
	}

	/**
	 * @dataProvider useridsNoFilterDP
	 */
	public function testUseridsNoFilter( $expected ) {
		$this->assertEquals( $expected, Users::getAllUserids() );
	}


	public function useridsDP() {
		$data = array();
		$data[] = array( array( 1, 2, 3) ); //add filters
		return $data;
	}

	/**
	 * @dataProvider useridsDP
	 */
	public function testUserids( $expected ) {
		$filter = array(
    					"userid" => 4,
    					"userid_operator" => "<"
    					);

		$this->assertEquals( $expected, Users::getAllUserids($filter) );
	}

	/**
	 * @dataProvider useridsDP
	 */
	public function testUseridsInNotIn( $expected ) {
		$filter = array(
    					"userid" => array(1, 3, 2),
    					"userid_operator" => "IN"
    					);

		$this->assertEquals( $expected, Users::getAllUserids($filter) );
	}

	public function accountNumberDataProvider() {
		$data[] = array( AccountTest::ETM_ACCOUNT_NUMBER, array( 1, 6 ) ); //account Number
		return $data;
	}

	/**
	 * @dataProvider accountNumberDataProvider
	 */
	public function testGetUseridsByAccountNumber( $accountNumber, $accountUserids ) {
		$this->assertEquals( $accountUserids, Users::getUseridsByAccountNumber( $accountNumber ) );
	} 

	/**
	 * @dataProvider accountNumberDataProvider
	 * @depends testGetUseridsByAccountNumber
	 * @expectedException InvalidArgumentException
	 */
	public function testGetUseridsByAccountNumber_Illegal_One( $accountNumber, $accountUserids ) {
		//use an illegal format
		$this->assertEquals( $accountUserids, Users::getUseridsByAccountNumber( "not-a-number" ) );
	} 

	/**
	 * @dataProvider accountNumberDataProvider
	 * @depends testGetUseridsByAccountNumber
	 * @expectedException Exception
	 */
	public function testGetUseridsByAccountNumber_Illegal_Two( $accountNumber, $accountUserids ) {
		//a valid format, but invalid account number
		$this->assertEquals( $accountUserids, Users::getUseridsByAccountNumber( 11111111 ) );
	} 

	/** 
	 * @todo need method testing getUserObjectsByAccountNumber and illegal tests
	 */

	// /**
	//  * @dataProvider loadData
	//  */
	// public function getUseridsByAccountNumber ( $expected ) {
	// 	$account = 3;
	// 	$this->assertEquals( $expected, etm\Users::getUseridsByAccountNumber($account));
	// }
	
	protected function tearDown() {
// 		unset($this->obj);
	}
}
