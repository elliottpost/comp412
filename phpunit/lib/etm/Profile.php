<?php
/**
 * @todo needs comments 
 */

namespace etm;

final class Profile {
	
	/**
	 * @var the userid for this user
	 */
	public $userid;

	/**
	 * @var the user profile, returned as an object with some fields editable, others not
	 */
	protected $profile;

	/**
	 * @var String[] $originalProfile: a backup, non-editable version of the user profile 
	 */
	private $originalProfile;

	/**
	 * @var String[] $profileFields: a multi-dimensional array which requires keys for each column in the User db table
	 */
	protected $profileFields;

	/**
	 * @var String[] $profileFieldsInverse: a reverse-lookup array for the profileFields. Used during saving.
	 */
	protected $profileFieldsInverse;

	public function __construct( $userid ) {

		//validate userid
		if( !is_numeric( $userid ) || empty( $userid ) )
			throw new \InvalidArgumentException( "Invalid userid." );

		$this->userid = $userid;

		$this->profileFields = array( 
				"userid" => array(
						"name" => "userid",
						"canEdit" => false,
						"adminCanEdit" => false,
						"dataType" => "int",
						"canBeNull" => false,
						"canBeBlank" => false
					),
				"email" => array(
						"name" => "email",
						"canEdit" => true,
						"adminCanEdit" => true,
						"dataType" => "String",
						"canBeNull" => false,
						"canBeBlank" => false
					),
				"password" => array(
						"name" => "password",
						"canEdit" => true,
						"adminCanEdit" => true,
						"dataType" => "String",
						"canBeNull" => false,
						"canBeBlank" => false
					),
				"first_name" => array(
						"name" => "fName",
						"canEdit" => true,
						"adminCanEdit" => true,
						"dataType" => "String",
						"canBeNull" => false,
						"canBeBlank" => false
					),
				"last_name" => array(
						"name" => "lName",
						"canEdit" => true,
						"adminCanEdit" => true,
						"dataType" => "String",
						"canBeNull" => false,
						"canBeBlank" => false
					),
				"avatar" => array(
						"name" => "avatar",
						"canEdit" => true,
						"adminCanEdit" => true,
						"dataType" => "String",
						"canBeNull" => true,
						"canBeBlank" => true
					),
				"status" => array(
						"name" => "status",
						"canEdit" => false,
						"adminCanEdit" => true,
						"dataType" => "int",
						"canBeNull" => false,
						"canBeBlank" => false
					),
				"join_timestamp" => array(
						"name" => "joinTimestamp",
						"canEdit" => false,
						"adminCanEdit" => false,
						"dataType" => "int",
						"canBeNull" => false,
						"canBeBlank" => false
					)
			);

		//set up the inverse for the save profile function
		$this->profileFieldsInverse = array();
		foreach( $this->profileFields as $k => $va ) {
			$this->profileFieldsInverse[ $va['name'] ] = $k;
		}

		$this->profile = new \stdClass();
		$this->originalProfile = new \stdClass();

		$this->refreshProfileData();
	}

	/**
	 * @todo needs comments 
	 */
	private function refreshProfileData() {
		global $db;

		/**
		 * @todo needs db integration
		 */
		$query = "SELECT userid, email, password, first_name, last_name, avatar, status, UNIX_TIMESTAMP(join_timestamp) as join_timestamp FROM user WHERE userid={$this->userid}";
		$result = $db->query( $query );

		if( !$result )
			throw new \Exception( "Database error. Error: (" . $db->errno . ") " . $db->error );

		if( !$result->num_rows )
			throw new \Exception( "Could not locate user profile in database." );

		//get the row
		while( $r = $result->fetch_assoc() ) {
			//get the columns
			foreach( $r as $k => $v ) {
				//make sure our system understands this column
				if( !array_key_exists( $k, $this->profileFields ) ) {
					throw new \Exception( "Database design does not match expected paramters in User class. (Unrecognized column '{$k}')" );
				}

				$fieldName = $this->profileFields[ $k ]['name'];
				$this->profile->$fieldName = $v;
				$this->originalProfile->$fieldName = $v; //save a copy
			}
		}
	}

	/**
	 * @todo needs comments 
	 */
	public function &get() {
		return $this->profile;
	}

	/**
	 * @todo needs comments 
	 */
	public function save() {

		global $db;

		//start the query
		/**
		 * @todo replace with $db class later
		 */
		$query = "UPDATE user SET ";

		//make sure we updated something
		$updates = 0;

		//loop through profile fields
		foreach( $this->profile as $k => $v ):
			// is this field value changed?
			if( $this->originalProfile->$k == $this->profile->$k ) 
				continue;

			//is this field read-only?
			$columnName = $this->profileFieldsInverse[ $k ];
			if( ! $this->profileFields[ $columnName ]['canEdit'] ) {
				//cannot edit this field, 
				throw new \Exception( "The profile field '{$k}' is not editable. No changes were saved." );
			}

			/**
			 * @todo check if current user is admin and if admin can edit 
			 */

			//check if the value is acceptable
			if( $v === null ) {
				//can the value be null?
				if( ! $this->profileFields[ $columnName ]['canBeNull'] ) {
					//cannot have a null value here
					throw new \Exception( "The profile field '{$k}' cannot be null. No changes were saved." );
				}

				//okay to be null
				$query .= "{$columName}=NULL, ";
				$updates++;

				//we've added to our query, move along.
				continue;
			}

			//keep checking value
			$v = trim( $v );
			/**
			 * @todo replace with sanitization from db class
			 */
			$v = $db->real_escape_string( $v );

			if( $v === "" && ! $this->profileFields[ $columnName ]['canBeBlank'] ) {
				//cannot have a blank value here
				throw new \Exception( "The profile field '{$k}' cannot be blank. No changes were saved." );
			}

			//add on to our query
			$query .= "{$columnName}='{$v}', ";
			$updates++;
		endforeach;

		if( !$updates ) {
			//no updates, but no errors either, just return true.
			return true;
		}

		//we have updates to process, but we also need to finish our query
		//first remove the trailing comma
		$query = substr( $query, 0, -2 ); //remove the last 2 characters
		$query .= " WHERE userid={$this->userid} LIMIT 1";

		//process the query 
		$result = $db->query( $query );
		if( !$result ) {
			throw new \Exception( "Database error.  Error: (" . $db->errno . ") " . $db->error );
		}

		//update the original profile to match
		foreach( $this->profile as $k => $v )
			$this->originalProfile->$k = $v;

		//return affected rows from query (should always be 1/true )
		return (bool) $db->affected_rows;
	}
}