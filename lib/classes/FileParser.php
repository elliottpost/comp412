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
	 * Generates an associative array from an array of community ID => array( zip codes )
	 * @param $rows: the array from readCsvToArray
	 * @return int[] $communityId => int[] $zipCodes
	 */
	public static function createCommunityIdMap( $rows ) {
		$map = array();

		foreach( $rows as $row ) {
			//skip column headers
			if( !is_numeric( $row[0] ) )
				continue;
			
			//row[0] = zip code
			//row[1] = community ID
			$zip = (int) trim( $row[0] );
			$communityId = (int) trim( $row[1] );

			//build our map by adding an array of zip codes that match this ID
			if( !array_key_exists( $communityId, $map ) )
				$map[ $communityId ] = array( $zip );
			else
				$map[ $communityId ][] = $zip;
			
		} //foreach $rows as $row
		return $map;
		
	} //createZipCodeAssocArray

} //FileParser
