<?php
namespace elly;

class CommunityTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var Commnuity $obj: our Community
	 */
	protected $obj;

	/**
	 * @var COMMUNITY_ID: the community ID we'll be working with
	 */
	const COMMUNITY_ID = 5;

	protected function setUp() {
		new ProjectAutoload;
	}

	protected function DP_zipCodes() {
		$data = array(
					array( 
						array(
							60626, 12345, 54455
						)
					)
				);
		return $data;
	}
	

	/** 
	 * @expectedException Exception
	 * Expects an \Exception by creating a community without passing an ID
	 */
	public function testCommunityConstruct_Illegal() {
		$this->obj = new Community;
	}

	public function testCommunityConstruct() {
		$this->obj = new Community( self::COMMUNITY_ID );
	}


	/**
	 * @depends testCommunityConstruct
	 * ensures the ID was set and returned correctly
	 */
	public function testGetId() {
		//rebuild the community
		$this->testCommunityConstruct();
		$this->assertTrue( $this->obj->getId() == 5 );
		$this->assertFalse( !$this->obj->getId() );
	}

	/**
	 * @depends testCommunityConstruct
	 * ensures name can be set and get
	 */
	public function testGetName() {
		//rebuild the community
		$this->testCommunityConstruct();
		$this->obj->setName( "Loyola" );
		$this->assertEquals( "Loyola", $this->obj->getName() );
	}

	/**
	 * @depends testCommunityConstruct
	 * ensures both pass & fail values can be incremented and get
	 */
	public function testIncrementsSetAndGet() {
		//rebuild the community
		$this->testCommunityConstruct();
		for( $i = 0; $i < 5; $i++ )
			$this->obj->incrementPasses();
		for( $i = 0; $i < 3; $i++ )
			$this->obj->incrementFails();
		$this->assertEquals( 5, $this->obj->getPasses() );
		$this->assertEquals( 3, $this->obj->getFails() );
	}

	/**
	 * @depends testCommunityConstruct
	 * ensures other instance vars can be set via method and get
	 */
	public function testCommunityDetailsSetAndGet() {
		//rebuild the community
		$this->testCommunityConstruct();
		$this->obj->setHouseholdsBelowPoverty( "12" );
		$this->assertEquals( 12, $this->obj->getHouseholdsBelowPoverty() );
		$this->obj->setHouseholdsBelowPoverty( 7 );
		$this->assertEquals( 7, $this->obj->getHouseholdsBelowPoverty() );
		$this->obj->setHouseholdsBelowPoverty( null );
		$this->assertEquals( 0, $this->obj->getHouseholdsBelowPoverty() );
		$this->obj->setHouseholdsBelowPoverty( true );
		$this->assertEquals( 1, $this->obj->getHouseholdsBelowPoverty() );
		$this->obj->setHouseholdsBelowPoverty( "true" );
		$this->assertEquals( 1, $this->obj->getHouseholdsBelowPoverty() );

		$this->obj->setPerCapitaIncome( "12" );
		$this->assertEquals( 12, $this->obj->getPerCapitaIncome() );
		$this->obj->setPerCapitaIncome( 7 );
		$this->assertEquals( 7, $this->obj->getPerCapitaIncome() );
		$this->obj->setPerCapitaIncome( null );
		$this->assertEquals( 0, $this->obj->getPerCapitaIncome() );
		$this->obj->setPerCapitaIncome( true );
		$this->assertEquals( 1, $this->obj->getPerCapitaIncome() );
		$this->obj->setPerCapitaIncome( "true" );
		$this->assertEquals( 1, $this->obj->getPerCapitaIncome() );
	}

	/**
	 * @depends testZipCodesSetAndGet
	 * @dataProvider DP_zipCodes
	 * ensures both pass & fail values can be incremented and get
	 */
	public function testZipCodesSetAndGet( $expected ) {
		//rebuild the community
		$this->testCommunityConstruct();
		$this->obj->setZipCodes( array( 60626, "12345", "54455" ))
		$this->assertEquals( $expected, $this->obj->getZipCodes() );
	}


	protected function tearDown() {

	}
}
