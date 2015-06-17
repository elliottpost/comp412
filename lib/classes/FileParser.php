<?php

namespace elly;

final class FileParser {

	/**
	 * Private constructor: PHP's solution to abstract final classes.
	 */
	private function __construct() {
		//do nothing
	} //__construct

	/**
	 * Reads a CSV file to an array
	 * @param String $file: the full path to the file (can be remote)
	 * @throws \Exception if cannot open CSV
	 */
	public static function readCsvToArray( $file ) {
		if( ( $handle = fopen( $file, 'r' ) ) === FALSE )
			throw new \Exception( "Could not open file {$file}." );

		$arr = array();
		$cols = array();
		$i = -1;
		while( ( $data = fgetcsv( $handle ) ) !== FALSE ):
			//if first entry, just record the colum names
			if( $i < 0 ) {
				$cols = $data;
				$i++;
				continue;
			} 

			//foreach remaining entry, record colum names as key => value
			$arr[ $i ] = array();
			foreach( $cols as $k => $col ) {
				$arr[ $i ][ $col ] = $data[ $k ];
			}

			$i++;
		endwhile;
		
		fclose( $handle );

		return $arr;

	} //readCsvToArray

} //FileParser
