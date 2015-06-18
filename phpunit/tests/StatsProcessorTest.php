<?php
namespace elly;

class StatsProcessorTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var Commnuity $obj: our Community
	 */
	protected $obj;

	protected function setUp() {
		new ProjectAutoload;
		$this->obj = new StatsProcessor;
	}

	/**
	 * ensures CSV files are read into assoc arrays correctly
	 */
	public function testCalculateStdDev() {
		$numbers = array( 27,54,60,17,1,19,74,97,95,8 );
		$this->assertEquals( 33, floor( $this->obj->calculateStdDev( $numbers, false ) ) );
		$this->assertEquals( 35, floor( $this->obj->calculateStdDev( $numbers, true ) ) );
	}


	protected function tearDown() {

	}
}
