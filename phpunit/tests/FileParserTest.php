<?php
namespace elly;

class FileParserTest extends \PHPUnit_Framework_TestCase {
	
	/**
	 * @var Commnuity $obj: our Community
	 */
	protected $obj;

	protected function setUp() {
		new ProjectAutoload;
	}

	/**
	 * ensures CSV files are read into assoc arrays correctly
	 */
	public function testReadCsvToAssocArray() {
		$array = FileParser::readCsvToAssocArray( PROJECT_PATH . "data/tests/FileParser.csv" );
		$this->assertEquals( 3, count( $array ) );
		$this->assertEquals( "Elliott", $array[0]['name'] );
		$this->assertEquals( "Perez, John", $array[1]['name'] );
		$this->assertEquals( "Nancy Drew", $array[2]['name'] );
		$this->assertEquals( "26", $array[0]['age'] );
		$this->assertEquals( "19", $array[1]['age'] );
		$this->assertEquals( "85", $array[2]['age'] );
		$this->assertEquals( "08/08/1988", $array[0]['dob'] );
		$this->assertEquals( "7/5/1230", $array[1]['dob'] );
		$this->assertEquals( "11/30/2011", $array[2]['dob'] );
	}

	/**
	 * ensures CSV files are read into standard arrays correctly
	 */
	public function testReadCsvToArray() {
		$array = FileParser::readCsvToAssocArray( PROJECT_PATH . "data/tests/FileParser.csv" );
		$this->assertEquals( 4, count( $array ) );

		$this->assertEquals( "name", $array[0][0] );
		$this->assertEquals( "age", $array[0][1] );
		$this->assertEquals( "dob", $array[0][2] );

		$this->assertEquals( "Elliott", $array[1][0] );
		$this->assertEquals( "Perez, John", $array[2][0] );
		$this->assertEquals( "Nancy Drew", $array[3][0] );
		$this->assertEquals( "26", $array[1][1] );
		$this->assertEquals( "19", $array[2][1] );
		$this->assertEquals( "85", $array[3][1] );
		$this->assertEquals( "08/08/1988", $array[1][2] );
		$this->assertEquals( "7/5/1230", $array[2][2] );
		$this->assertEquals( "11/30/2011", $array[3][2] );
	}

	/**
	 * This test is added for completeness; however, this functionality is actually tested as
	 * a dependency in TestDataProcessor.php
	 */
	public function testCreateCommunityIdMap() {
		$this->assertTrue( true );
	}

	protected function tearDown() {

	}
}
