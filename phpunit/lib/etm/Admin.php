<?php
/**
 *
 * @todo needs comment
 */
namespace etm;

/**
 * @todo remove on framework init
 */
require_once 'Employee.php';

class Admin extends Employee {
	public function __construct( $userid, $employeeid = null ) {
		parent::__construct( $userid );
	}

	public function isAdmin() {
		return true;
	}
}

