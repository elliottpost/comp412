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
		//do nothing
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
	 * @depends testAccountConstruct
	 * ensures the ID was set and returned correctly
	 */
	public function testGetId() {
		$this->assertTrue( $this->obj->getId() == 5 );
		$this->assertFalse( !$this->obj->getId() );
	}




	protected function tearDown() {

	}
}
