<?php
namespace etm;

class AccountTest extends \PHPUnit_Framework_TestCase {
	
	protected $obj;

	const ETM_ACCOUNT_NUMBER = 49294932;
	const BLIZZARD_ACCOUNT_NUMBER = 42510983;

	protected function setUp() {
		$GLOBALS['db'] = new \mysqli( "localhost", "root", "root", "etm_billing" );	
	}
	

	/** 
	 * @expectedException InvalidArgumentException
	 */
	public function testAccountConstruct_Illegal() {
		$this->obj = new Account( "String" );
	}

	public function testAccountConstruct() {
		$this->obj = null;
		$this->obj = new Account( self::ETM_ACCOUNT_NUMBER );
	}


	/**
	 * @depends testAccountConstruct
	 */
	public function testTODO() {
		
	}


	public function testIsValidAccountNumber() {
		$this->assertTrue( Account::isValidAccountNumber( self::ETM_ACCOUNT_NUMBER ) );
		$this->assertTrue( Account::isValidAccountNumber( self::BLIZZARD_ACCOUNT_NUMBER ) );
		$this->assertTrue( Account::isValidAccountNumber( 123456 ) );
		$this->assertTrue( Account::isValidAccountNumber( 1 ) );
		$this->assertTrue( Account::isValidAccountNumber( "108" ) );

		$this->assertFalse( Account::isValidAccountNumber( 0 ) );
		$this->assertFalse( Account::isValidAccountNumber( "one-hundred-eight" ) );
		$this->assertFalse( Account::isValidAccountNumber( 68.2 ) );
	}


	public function testAccountNumberExists() {
		$this->assertTrue( Account::accountNumberExists( self::ETM_ACCOUNT_NUMBER ) );
		$this->assertTrue( Account::accountNumberExists( self::BLIZZARD_ACCOUNT_NUMBER ) );

		$this->assertFalse( Account::accountNumberExists( 1 ) );
		$this->assertFalse( Account::accountNumberExists( 0 ) );
		$this->assertFalse( Account::accountNumberExists( "one-hundred-eight" ) );
		$this->assertFalse( Account::accountNumberExists( 68.2 ) );
	}


	protected function tearDown() {

	}
}
