<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Category-class with fiels en methods to make and display Credits
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Credit extends Models_Base {

	const TABLENAME = "credits";

	public $id;
	public $ipaddress;
	public $value;			//gewone users mogen allen +1 en -1 doen. Admins ook hogere waardes? (DB veld: int(4))
	public $reply_id;

	/**
	 * Names of the relevant fields of this object, the must correspond with the
	 * column-names of the associated table in the database.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 * @author Ramon Cregython <r.creyghton@gmail.com>
	 * @return string[] Array with the names of all relevant fields exept id in this object
	 */
	public function declareFields() {
		$fields = array(
				"ipaddress",
				"value",
				"reply_id"
		);
		return $fields;
	}
	
	
	/**
	 * Determines whether the calling user is allowed to add a credit to the curren Reply.
	 * How to do this?
	 * 
	 * @todo Everything in  here
	 */
	public function isAllowed() {
		
	}
}

?>