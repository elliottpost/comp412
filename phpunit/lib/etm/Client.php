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
require_once 'Account.php';

class Client extends User {

	protected $accountNumber;

	/**
	 *
	 * @todo needs comments
	 * @throws \InvalidArgumentException if $userid or $accountNumber invalid
	 */
	public function __construct( $userid, $accountNumber = null ) {
		parent::__construct( $userid );

		//verify userid and account number
		if( $accountNumber !== null ) {
			if( !Account::isValidAccountNumber( $accountNumber ) )
				throw new \InvalidArgumentException( "Account Number is invalid." );

			$this->accountNumber = (int) $accountNumber;
		}
	}


	/**
	 *
	 * @todo needs comment
	 */
	public function isClient() {
		return true;
	}

	
	/**
	 *
	 * @todo
	 */
	public function getAddress() {

	}

}

$db = new \mysqli( "localhost", "root", "root", "etm_billing" );
$client = new Client(1);