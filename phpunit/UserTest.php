<?php
namespace etm;

class UserTest extends \PHPUnit_Framework_TestCase {
	
	protected $admin;
	protected $employee;
	protected $client;
	protected $disabledEmployee;
	protected $disabledClient;
	const ADMIN_USERID = 3;
	const EMPLOYEE_USERID = 4;
	const CLIENT_USERID = 1;
	const DISABLED_CLIENT_USERID = 2;
	const DISABLED_EMPLOYEE_USERID = 5;


	protected function setUp() {
		$GLOBALS['db'] = new \mysqli( "localhost", "root", "root", "etm_billing" );	
		$this->admin = Users::getUserObjectByUserid( self::ADMIN_USERID );
		$this->employee = Users::getUserObjectByUserid( self::EMPLOYEE_USERID );
		$this->client = Users::getUserObjectByUserid( self::CLIENT_USERID );
		$this->disabledEmployee = Users::getUserObjectByUserid( self::DISABLED_EMPLOYEE_USERID );
		$this->disabledClient = Users::getUserObjectByUserid( self::DISABLED_CLIENT_USERID );
	}
	

	public function testIsEmployee() {
		$this->assertFalse( $this->client->isEmployee() );
		$this->assertTrue( $this->employee->isEmployee() );
		$this->assertTrue( $this->admin->isEmployee() );
	}


	public function testIsAdmin() {
		$this->assertFalse( $this->client->isAdmin() );
		$this->assertFalse( $this->employee->isAdmin() );
		$this->assertTrue( $this->admin->isAdmin() );
	}


	public function testIsClient() {
		$this->assertTrue( $this->client->isClient() );
		$this->assertFalse( $this->employee->isClient() );
		$this->assertFalse( $this->admin->isClient() );
	}


	public function testGetProfile() {
		$clientProfile = &$this->client->getProfile();
		$this->assertEquals( self::CLIENT_USERID, $clientProfile->userid );
		$this->assertEquals( "rick@james.com", $clientProfile->email );
		$this->assertEquals( "rick", $clientProfile->fName );
		$this->assertEquals( "james", $clientProfile->lName );
		$this->assertEquals( "couch.jpg", $clientProfile->avatar );
		$this->assertEquals( 1, $clientProfile->status );
	}

	/**
	 * @depends testGetProfile
	 */
	public function testSaveProfile() {
		//get the profile as-is
		$clientProfile = &$this->client->getProfile();
		//save a backup of the email
		$originalEmail = $clientProfile->email;
		$this->assertEquals( "rick@james.com", $originalEmail );
		//change the email 
		$clientProfile->email = "notrick@james.com";
		//save
		$this->assertTrue( $this->client->saveProfile() );

		//get a fresh instance of the user straight from the DB
		$clientDuplicate = Users::getUserObjectByUserid( self::CLIENT_USERID );
		$duplicateProfile = &$clientDuplicate->getProfile();

		$this->assertEquals( $clientProfile->email, $duplicateProfile->email );

		//revert to original
		unset( $clientDuplicate, $duplicateProfile );
		$clientProfile->email = $originalEmail;
		$this->assertTrue( $this->client->saveProfile() );

		//confirm reverted to default
		$this->testGetProfile();
	}


	/**
	 * @depends testSaveProfile
	 * @expectedException Exception
	 */
	public function testIllegalSaveProfile_Userid() {
		//get the profile as-is
		$clientProfile = &$this->client->getProfile();
		$clientProfile->userid = 12;
		$this->client->saveProfile();
	}


	/**
	 * @depends testSaveProfile
	 * @expectedException Exception
	 */
	public function testIllegalSaveProfile_Email_Null() {
		//get the profile as-is
		$clientProfile = &$this->client->getProfile();
		$clientProfile->email = null;
		$this->client->saveProfile();
	}

	
	/**
	 * @depends testSaveProfile
	 * @expectedException Exception
	 */
	public function testIllegalSaveProfile_Email_Blank() {
		//get the profile as-is
		$clientProfile = &$this->client->getProfile();
		$clientProfile->email = " ";
		$this->client->saveProfile();
	}


	protected function tearDown() {

	}
}
