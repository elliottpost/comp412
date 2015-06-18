<?php
namespace elly;

class DataProcessorTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var Commnuity $obj: our Community
	 */
	protected $obj;

	protected function setUp() {
		new ProjectAutoload;
		$this->obj = new DataProcessor;
	}

	public function DP_zipCodes() {
		$data = array(
					array( 
						array(
							60626,
							60645,
							60660
						)
					)
				);
		return $data;
	}

	public function DP_communityIds() {
		$data = array(
					array( 
						array(
							3,
							5,
							6
						)
					)
				);
		return $data;
	}

	/**
	 * Ensures the data is loaded successfully
	 * throws an error if not
	 */
	public function testLoadData() {
		$this->obj->loadData();
	}

	/**
	 * @depends testLoadData
	 * @dataProvider DP_zipCodes
	 * ensures Rogers Park (Community ID 1) shows 3 results
	 */
	public function testGetZipsFromCommunityIds( $expected ) {
		//rebuild the community
		$this->obj->loadData();
		$this->assertEquals( $expected, $this->obj->communityIdToZips( 1 ) );		
	}

	/**
	 * @depends testLoadData
	 * @dataProvider DP_communityIds
	 * ensures zip code 60613 matches community IDs 3,5,6
	 */
	public function testGetCommunityIdsFromZip( $expected ) {
		//rebuild the community
		$this->obj->loadData();
		$this->assertEquals( $expected, $this->obj->zipToCommunityIds( 60613 ) );		
	}

	/**
	 * @depends testLoadData
	 * Ensures the data is loaded successfully
	 * throws an error if not
	 */
	public function testGetCommunities() {
		$this->obj->loadData();
		$this->obj->iterateFoodData();
		$this->obj->iterateCensusData();
		$this->assert( is_array( $this->obj->getCommunities() ) );
	}

	/**
	 * @depends testLoadData
	 * Ensures the data is loaded successfully
	 * throws an error if not
	 */
	public function testGetPassesFails() {
		$this->obj->loadData();
		$this->obj->iterateFoodData();
		$this->assert( is_int( $this->obj->getTotalPasses() ) );
		$this->assert( is_int( $this->obj->getTotalFails() ) );
		$this->assert( is_int( $this->obj->getUniquePasses() ) );
		$this->assert( is_int( $this->obj->getUniqueFails() ) );
	}

	protected function tearDown() {

	}
}
