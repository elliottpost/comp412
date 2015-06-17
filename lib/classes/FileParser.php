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
	 * Reads a CSV file to an associative array
	 * header row is used as key for values
	 * @param String $file: the full path to the file (can be remote)
	 * @throws \Exception if cannot open CSV
	 */
	public static function readCsvToAssocArray( $file ) {
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

	} //readCsvToAssocArray

	/**
	 * Reads a CSV file to an array
	 * includes header row
	 * @param String $file: the full path to the file (can be remote)
	 * @throws \Exception if cannot open CSV
	 */
	public static function readCsvToArray( $file ) {
		if( ( $handle = fopen( $file, 'r' ) ) === FALSE )
			throw new \Exception( "Could not open file {$file}." );

		$arr = array();
		while( ( $data = fgetcsv( $handle ) ) !== FALSE ):
			$arr[] = $data;
		endwhile;
		
		fclose( $handle );

		return $arr;

	} //readCsvToArray

	/**
	 * Generates an associative array from an array of neighboor hoods => zip codes
	 * zip codes that match multiple neighborhoods will check which neighborhood does not yet exist in array
	 * new values will be favored. On both duplicates, stable algorithm in place
	 * @param $rows: the array from readCsvToArray
	 * @return int[] $zipCodes => (String) Neighborhood
	 */
	public static function createZipCodeAssocArray( $rows ) {
		$arr = array();
		foreach( $rows as $row ) {
			//row[0] = neighborhood
			//row[1] = zips
			// split the string of zips into an array
			$zipsArr = explode( ",", $row[1] );
			foreach( $zipsArr as $zip ) {
				//force the zip as a string to ensure we have at least 5 chars
				$zip = (String) trim( $zip );
				if( strlen( $zip ) != 5 )
					continue;

				//check for dependencies
				if( !in_array( $row[0], $arr ) )
					$arr[ (int) $zip ] = $row[0];
			} //foreach $zips as $zip
		} //foreach $arr as $row
		return $arr;
	} //createZipCodeAssocArray

} //FileParser
