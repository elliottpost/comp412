<?php
/**
* Class Users
* defined as final with a private constructor since PHP does not allow both
* abstract and final classes
* 
* @since March 12, 2015
* @author Ellytronic Media
*/

namespace etm;

/**
 * @todo remove on framework init
 */
require_once 'I_Users.php';
require_once 'User.php';
require_once 'Account.php';

final class Users implements I_Users {
	const TABLE_USER = "user";
	const TABLE_CLIENT = "client";
	const TABLE_EMPLOYEE = "employee";
	const TABLE_ADMIN = "admin";

	private function __construct() {
		//Do Nothing
	}

 	/**
    * returns an array of userids
    * @param [String[] $filters: an assoc array of filters that can include:
    *                     [userid => Int[] $userids: an array of userids]
    *                     [userid_operator => either "IN" or "NOT IN"]
    *                     [status => int $status: the integer status code in the DB]
    *                     [status_operator  => String $statusOperator: the operator to use to compare status, eg: >, =, !=, etc]
    *                     [join_timestamp => $joinTimestamp]
    *                     [join_operator  => String $statusOperator: the operator to use to compare join_timestamp, eg: >, =, !=, etc]
    *       ]
    */
    public static function getAllUserids( $filters = null ) {
    	global $db;

		$useridOperators = array( ">", "<", ">=", "<=", "!=", "=", "IN", "NOT IN" );
    	$statusOperators = array( ">", "<", ">=", "<=", "!=", "=" );
    	$joinOperators = array( ">", "<", ">=", "<=", "!=", "=" );

    	$query = "SELECT userid FROM " . self::TABLE_USER;

    	if( $filters !== null ) {
    		if( !is_array( $filters ) || empty( $filters ) )
    			throw new \InvalidArgumentException( "Invalid Argument. Filters must be null or an non-empty array.");

    		$query .= " WHERE 1=1";

    		//build our query
    		foreach( $filters as $k => $v ):

    			$k = strtolower( $k );

    			switch( $k ) {
    				case "userid":
    					if( !isset( $filters['userid_operator'] ) || empty( $filters['userid_operator'] ) )
    						throw new \Exception( "Userid operator must be provided if filtering by userid." );

    					if( !in_array( $filters['userid_operator'], $useridOperators ) )
    						throw new \Exception( "Userid operator value not allowed." );

    					//figure out the operator
    					$operator = strtolower( $filters['userid_operator'] );

    					//it's an array, so we need to do something special
    					if( $operator == "not in" || $operator == "in" ) {
    						//we are expecting an array
    						if( !is_array( $v ) || empty( $v ) )
    							throw new \Exception( "Userid operator IN and NOT IN both require userid filter's value to be passed as a non-empty array." );

    						//get the list
    						$list = "(0"; //there will never be a 0 in the ID list, so put this here for easy comma separating.
    						foreach( $v as $id )
    							$list .= "," . $db->real_escape_string( $id );
    						$list .= ")";
    					}
                        else {
                            //list is required for $query
                            $list = $v;
                        }

    					$query .= " AND {$k} {$operator} {$list}";

    					break;

    				case "userid_operator":
    					//do nothing
    					break;

    				case "status":
    					if( !isset( $filters['status_operator'] ) || empty( $filters['status_operator'] ) )
    						throw new \Exception( "Status operator must be provided if filtering by status." );

    					if( !in_array( $filters['status_operator'], $statusOperators ) )
    						throw new \Exception( "Status operator value not allowed." );

		    			//sanitize input
		    			$value = $db->real_escape_string( $v );
						$query .= " AND {$k} {$filters['status_operator']} '{$value}'";

    					break;

    				case "status_operator":
    					//do nothing
    					break;

    				case "join_timestamp":
    					if( !isset( $filters['join_operator'] ) || empty( $filters['join_operator'] ) )
    						throw new \Exception( "Join operator must be provided if filtering by join timestamp." );

    					if( !in_array( $filters['join_operator'], $joinOperators ) )
    						throw new \Exception( "Join operator value not allowed." );

    					//sanitize input
    					$value = $db->real_escape_string( $v );
						$query .= " AND {$k} {$filters['status_operator']} '{$value}'";

    					break;

    				case "join_operator":
    					//do nothing
    					break;

    				default:
    					throw new \Exception( "Invalid filter received." );
    					break;
    			}	
    		endforeach;

    	}

    	$result = $db->query( $query );
    	if(! $result ) {
    		throw new \Exception( "Bad query $query " );
    	}

    	if( !$result->num_rows )
    		return array();

    	$userids = array();
   		while( $row = $result->fetch_row() )
   			$userids[] = $row[0];

    	return $userids;
    }
    
    
    /**
    * returns an array of users in array( $userid => I_User user ) format
    * @param [String[] $filters: an assoc array of filters that can include:
    *                     [userid => Int[] $userids: an array of userids]
    *                     [userid_operator => either "IN" or "NOT IN"]
    *                     [status => int $status: the integer status code in the DB]
    *                     [status_operator  => String $statusOperator: the operator to use to compare status, eg: >, =, !=, etc]
    *                     [join_timestamp => $joinTimestamp]
    *                     [join_operator  => String $statusOperator: the operator to use to compare join_timestamp, eg: >, =, !=, etc]
    *       ]
    * 
    * @throws \Exception if call to create user object fails
    * @return I_User[] $user
    * @since March 12, 2015
    */
    public static function getAllUsersObjects( $filters = null ) {
    	$userids = self::getAllUserids( $filters );
    	$userObjects = array();
    	foreach( $userids as $userid )
   			$userObjects[] = self::getUserObjectByUserid( $userid );

    	return $userObjects;
    }
    

    /**
    * returns an array of userids that belong to a specific account
    * @param int $accountNumber: the account number to query users from
    * @throws \InvalidArgumentException if invalid account number
    * @throws \Exception on database error
    * @throws \Exception if no users found 
    * @return int[] $userids
    * @since March 12, 2015
    */
    public static function getUseridsByAccountNumber( $accountNumber ) {
        global $db;

        //validate the account number
        if( ! Account::isValidAccountNumber( $accountNumber ) ) 
            throw new \InvalidArgumentException( "Invalid account number." );

        $accountNumber = $db->real_escape_string( $accountNumber ); //should be fine already, but just incase.

        $query = "SELECT userid FROM client WHERE account_number={$accountNumber}";
        $result = $db->query( $query );
        if( !$result )
            throw new \Exception( "Database error. Error: (" . $db->errno . ") " . $db->error );

        if( !$result->num_rows )
            throw new \Exception( "No users found for account number {$accountNumber}. The account number may not exist." );

        $userids = array();
        while( $r = $result->fetch_row() )
            $userids[] = $r[0];

        return $userids;
    }
    

    /**
    * returns an array of I_User $users that belong to a specific account
    * @param int $accountNumber: the account number to query users from
    * @throws \InvalidArgumentException if invalid account number
    * @throws \Exception on database error
    * @throws \Exception if no users found 
    * @throws \InvalidArgumentException if userid is non-integer or less than 1
    * @throws \Exception if call to create user object fails
    * @return I_User[] $user
    * @since March 12, 2015
    */
    public static function getUserObjectsByAccountNumber( $accountNumber ) {
        $userids = self::getUseridsByAccountNumber( $accountNumber );

        $users = array();
        foreach( $userids as $userid )
            $users[] = self::getUserObjectByUserid( $userid );

        return $users;
    }
    
    
    /**
    * getUserObjectByUserid
    * returns a single I_User $user    
    * @param int $userid: the userid to query
    * @throws \InvalidArgumentException if userid is non-integer or less than 1
    * @throws \Exception if call to create user object fails
    * @return (I_User) $user: an employee, admin, or client
    * @example 
    * try { $user = Users::getUserByUserid( $myLoginid ); } catch( Exception $e ) { //do something }
    */
    public static function getUserObjectByUserid( $userid ) {
        global $db;
        
    	//sanitize input
    	$userid = (int) $db->real_escape_string( $userid );
    	if( !is_int( $userid ) || $userid < 1 )
    		throw new \InvalidArgumentException( "Userid should be an integer greater than 0." );

    	//build our queries
    	$clientQuery = "SELECT * FROM " . self::TABLE_CLIENT . " WHERE userid={$userid} LIMIT 1;";
    	$adminQuery = "SELECT * FROM " . self::TABLE_ADMIN . " WHERE userid={$userid} LIMIT 1;";

    	//start processing our queries
    	//check for client
    	$result = $db->query( $clientQuery );
    	if( !$result )
    		throw new \Exception( "Database error. Error: (" . $db->errno . ") " . $db->error );
    	if( $result->num_rows ) {
    		//user is a client
    		while( $r = $result->fetch_assoc() ) {
    			try {
    				return new Client( $userid, $r['account_number'] );
    			} catch( \Exception $e ) {
    				throw new \Exception( "Error creating Client: " . $e->getMessage() );
    			}
    		}
    	}

    	//if we're still here, the user is not a client, they _must_ be an employee, but 
    	//they can also be an admin
    	$result = $db->query( $adminQuery );
    	if( !$result )
    		throw new \Exception( "Database error. Error: (" . $db->errno . ") " . $db->error );
    	if( $result->num_rows ) {
    		//user is a client
    		while( $r = $result->fetch_assoc() ) {
    			try {
    				return new Admin( $userid, $r['employeeid'] );
    			} catch( \Exception $e ) {
    				throw new \Exception( "Error creating Admin: " . $e->getMessage() );
    			}
    		}
    	}

		try {
			return new Employee( $userid );
		} catch( \Exception $e ) {
			throw new \Exception( "Error creating Employee: " . $e->getMessage() );
		}

    }
	
} //Users