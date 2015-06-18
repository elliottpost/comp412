<?php
/**
 *
 * @todo needs comment
 */
 
namespace etm;

interface I_User {
    
    /** 
     * @todo needs comments
     * @throws 
     */
    public function __construct( $accountNumber );
    

    /* -------------------------------
    -- GETTER METHODS
    -------------------------------- */
    
    /**
     * &getProfile
     * returns by reference the user profile
     * @throws \Exception if database query fails
     * @throws \Exception if database design does not match expected parameters
     * @return &$user->profile //notice the pass by reference!
     * @example
     *    $profile = &$user->getProfile();
     * @since March 12, 2015
     */
    public function &getProfile();
    
    
    
    /* -------------------------------
    -- SETTER METHODS
    -------------------------------- */
    
    /**
     * saveProfile
     * saves changes to the profile that was retreived with getProfile
     * does not save changes to: userid, account_number, employeeid, 
     * @throws \Exception upon invalid value for field 
     * @throws \Exception if read-only field is changed
     * @throws \Exception if database query fails
     * @return (bool) affected rows
     * @example
     *    $profile = &$user->getProfile();
     *    $profile->email = "bill@gates.com"
     *    $user->saveProfile();
     * @since March 12, 2015
     */
    public function saveProfile();
    
    
    
    
    /* -------------------------------
    -- BOOLEAN METHODS
    -------------------------------- */
    
    /**
     * returns a boolean if the user is a client or not
     * @return bool true if Client, else false
     * @since March 12, 2015
     */
    public function isClient(); 

    
    /**
     * returns a boolean if the user is a employee or not
     * note admins are employees so this will return true
     * @return bool true if employee, else false
     * @since March 12, 2015
     */
    public function isEmployee();
    
    
    /**
     * returns a boolean if the user is a admin or not
     * @return bool true if admin, else false
     * @since March 12, 2015
     */
    public function isAdmin();
    
    //TODO 
    //SESSION STUFF
    
    
    /* -------------------------------
    -- COMMAND (DO) METHODS
    -------------------------------- */

    /**
     * 
     */
     
     
    //TODO 
    //Authorize and other session handling
     
    
    
} //I_User

