<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Credit-class with fiels en methods to make and display Credits and to determine credit-possibilities
 * 
 * @todo Implementation of credits accros controllers and views.
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Credit extends Models_Base {
	
	/**
	 * The name of the table in the Database associated with this Model.
	 */
	const TABLENAME = "credits";

	/**
	 * Unique id of this credit. Auto incremented by the database.
	 * @var int 
	 */
	public $id;

	/**
	 * Relation with the user that posted this reply, see {@link Models_User::$id}.
	 * @var int 
	 */
	public $user_id;

	/**
	 * The value of this credit. Ordinary users can add +1 and -1. Admins more?
	 * @var int 
	 */
	public $value;

	/**
	 * Relation with the reply to which this object adds credit, see {@link Models_Reply::$id}.
	 * @var int 
	 */
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
				"user_id",
				"value",
				"reply_id"
		);
		return $fields;
	}

	/**
	 * Determines whether the calling user is allowed to add a credit to the curren Reply.
	 * How to do this? Ah, with Dependency Injection!
	 * Altijd nieuw credit object aanmaken en in DB checken of dat dan mag of niet?
	 * Of de controller met behulp van deze functie laten bepalen of er uberhaupt een nieuwe credit mag komen?
	 * 
	 * 
	 * @param Models_User $callingUser
	 * @param Models_Reply $checkReply
	 * @param int $changeAsked
	 * @return Models_Credit|boolean	Either the existing Credit-Object if you'r allowed to change it in this way; or a boolean indicating (true) you're allowed to make a new Credit-object of (false) you are denied to update your credit.
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @todo Check this stuff. Dubbelop met andere creditsmethodes?
	 */
	public static function howToCredit($callingUser, $checkReply, $changeAsked) {
		$oldCredit = $checkReply->getYourCredit($callingUser);
		if (!$oldCredit) {
			//If there is no Credit already, you're allowed to make a new one
			return true;
		} else {
			//You cannot alter your credit in the same way again (avoiding endless ++ of --), but otherwise it's fine: here's your Credit-object.
			return ($oldCredit->value != $changeAsked) ? $oldCredit : false;
		}
	}

}