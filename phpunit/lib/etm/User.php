<?php
/**
 *
 * @todo needs comment
 */

namespace etm;

/**
 * @todo remove on framework init
 */
require_once 'I_User.php';
require_once 'Profile.php';

abstract class User implements I_User {

	/**
	 * @var the userid for this user
	 */
	public $userid;

	/**
	 * @var the user profile, returned as an object with some fields editable, others not
	 */
	protected $profile;


	public function __construct( $userid ) {

		//validate userid
		if( !is_numeric( $userid ) || empty( $userid ) )
			throw new \InvalidArgumentException( "Invalid userid." );

		$this->userid = (int) $userid;

		$this->profile = new Profile( $userid );	

	}

	public final function &getProfile() {
		return $this->profile->get();

	}

	public final function saveProfile() {
		return $this->profile->save();
	}

	public function isClient() {
		return false;
	}

	public function isEmployee() {
		return false;
	}

	public function isAdmin() {
		return false;
	}
} //class def