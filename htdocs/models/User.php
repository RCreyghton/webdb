<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * User-class with fiels en methods to make and display Users, and get all DB-manipulations they've done on this site (i.e.: Threads, Replies, Credits...)
 * 
 * @author Shafiq Ahmadi <s.ah@live.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_User extends Models_Base {
	/**
	 * @var string Name of the DB-table corresponding with this class.
	 */

	const TABLENAME = "users";
	const ROLE_USER = 0;
	const ROLE_ADMIN = 1;

	/**
	 * Unique ID of this User-object, auto_incremented in the DB.
	 * @var int
	 */
	public $id;
	
	/**
	 * MD5-hash of the password the user registered with
	 * @var string
	 */
	public $pass;
	
	/**
	 * Email address of the user, must be unique within the system
	 * @var string
	 */
	public $email;
	
	/**
	 * timestamp of when the user was registered
	 * @var int
	 */
	public $ts_registered;
	
	/**
	 * Role of the user
	 * @var int [0: registered user, 1: admin]
	 */
	public $role;
	
	/**
	 * first name of the user
	 * @var string
	 */
	public $firstname;
	
	/**
	 * last name of the user
	 * @var string
	 */
	public $lastname;
	
	/**
	 * Names of the relevant fields of this object, the must correspond with the
	 * column-names of the associated table in the database.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 * @author Shafiq Ahmadi <s.ah@live.nl>
	 * @return string[] Array with the names of all relevant fields exept id in this object
	 */
	public function declareFields() {
		$fields = array(
			"id",
			"pass",
			"email",
			"ts_registered",
			"role",
			"firstname",
			"lastname"
		);
		return $fields;
	}

	protected function insert() {
		$this->ts_registered = time();
		parent::insert();
	}

	/**
	 * gets an array of Credit-objects, that this user got for his Replies.
	 *
	 * @return Models_Credit[] array of Credit-objects
	 * @uses Models_Base::fetchByQuery()	
	 * @uses Models_Base::getSelect()
	 * @todo SQL-query controleren!!!
	 * @todo Zoiets als getCreditCount maken, waarin we een user z'n netto waarde bepalen?
	 * @todo Dit is dubbelop met Models_Reply->calcNettCredits. Beter een static methode maken in Models_Credit en die dan her en der callen?
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getCredits() {
		$query = Models_Credit::getSelect() . " JOIN `replies` ON `credits`.reply_id = `replies`.id WHERE `replies`.user_id=" . $this->id . ";";
		$creditsArray = Models_Credit::fetchByQuery($query);
		$totalcredits = 0;
		foreach ($creditsArray as $credit) {
			$totalcredits += $credit->value;
		}
		return $totalcredits;
	}

}

?>
