<?php
/**
 *
 * @todo 
 */
namespace etm;

Interface I_Users { 

    /**
    * returns an array of userids
    * @param [String[] $filters: an assoc array of filters that can include:
    *                     [userid => Int[] $userids: an array of userids]
    *                     [userid_operator => either "IN" or "NOT IN"]
    *                     [status => int $status: the integer status code in the DB]
    *                     [status_operator  => String $statusOperator: the operator to use to compare status, allowed: ">", "<", ">=", "<=", "!=", "="]
    *                     [join_timestamp => $joinTimestamp]
    *                     [join_operator  => String $statusOperator: the operator to use to compare join_timestamp, allowed: ">", "<", ">=", "<=", "!=", "="]
    *       ]
    *       (Note: if you pass status, you must pass status_operator. The same for join.)
    *       (Note: if you pass only the operator but no value, the query will ignore it )
    * @example
    *   $filters = array(
    *       "status" => 1,
    *       "status_operator" => ">"
    *   );
    * $userids = Users::getAllUserids( $filters );
    * 
    * @example
    *  $userids = Users::getAllUserids();
    * 
    * @throws \InvalidArgumtnException if $filters is not null or is an empty array
    * @throws \Exception if filter values are not formatted correctly
    * @return Int[] $userid
    * @since March 12, 2015
    */
    public static function getAllUserids( $filters = null );
    
    
    /**
    * returns an array of users in array( $userid => I_User user ) format
    * @param [String[] $filters: an assoc array of filters that can include:
    *                     [userid => Int[] $userids: an array of userids]
    *                     [userid_operator => either "IN" or "NOT IN"]
    *                     [status => int $status: the integer status code in the DB]
    *                     [status_operator  => String $statusOperator: the operator to use to compare status, allowed: ">", "<", ">=", "<=", "!=", "="]
    *                     [join_timestamp => $joinTimestamp]
    *                     [join_operator  => String $statusOperator: the operator to use to compare join_timestamp, allowed: ">", "<", ">=", "<=", "!=", "="]
    *       ]
    * 
    * @throws \Exception if call to create user object fails
    * @return I_User[] $user
    * @since March 12, 2015
    */
    public static function getAllUsersObjects( $filters = null );
    
    
    /**
    * returns an array of userids that belong to a specific account
    * @param int $accountNumber: the account number to query users from
    * @throws \InvalidArgumentException if invalid account number
    * @throws \Exception on database error
    * @throws \Exception if no users found 
    * @return int[] $userids
    * @since March 12, 2015
    */
    public static function getUseridsByAccountNumber( $accountNumber );
    

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
    public static function getUserObjectsByAccountNumber( $accountNumber );
    
    
    /**
    * getUserObjectByUserid
    * returns a single I_User $user    
    * @param int $userid: the userid to query
    * @throws TODO
    * @return (I_User) $user: an employee, admin, or client
    * @example 
    * try { $user = Users::getUserByUserid( $myLoginid ); } catch( Exception $e ) { //do something }
    */
    public static function getUserObjectByUserid( $userid );
    
} //I_Users
