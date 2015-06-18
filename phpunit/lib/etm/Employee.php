<?php
/**
 *
 * @todo needs comment
 */
namespace etm;

/**
 * @todo remove on framework init
 */
require_once 'User.php';

class Employee extends User {

	protected $employeeid;

	public function __construct( $userid, $employeeid = null ) {
		parent::__construct( $userid );
	}

	public function isEmployee() {
		return true; 
	}
}
