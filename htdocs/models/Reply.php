<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Category-class with fiels en methods to make and display Replies
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Reply extends Models_Base {

	const TABLENAME = "replies";

	public $id;
	public $user_id;
	public $thread_id;
	public $ts_created;
	public $ts_modified;
	public $title;
	public $content;
	public $visibility; //visibility. Voorstel voor betekenis integers:
											//0 is hidden
											//1 is visible
	public $credits;

	/**
	 * Names of the relevant fields of this object, the must correspond with the
	 * column-names of the associated table in the database.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 * @author Ramon Cregython <r.creyghton@gmail.com>
	 * @return string[] The names of all relevant fields exept id in this object
	 */
	public function declareFields() {
		$fields = array(
				"user_id",
				"thread_id",
				"ts_created",
				"ts_modified",
				"title",
				"content",
				"visibility",
				"credits"
		);
		return $fields;
	}
	
	
	/**
	 * gets an array of Credit-objects for this Reply
	 *
	 * @return Models_Credit[] array of Credit-objects
	 * @uses Models_Base::fetchByQuery()	
   * @uses Models_Base::getSelect()	
	 * @todo SQL injection check, dwz: checken of inderdaad alle Object-velden safe zijn.
	 * @todo Werkelijk Credit-objects maken... is dat niet heel kostbaar? Misschien eerder ergens array van platte credits opslaan in Reply-object?
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getCredits() {
		$query = Models_Credit::getSelect() . " WHERE reply_id=" . $this->id . ";";
		return Models_Credit::fetchByQuery($query);
	}
	
	
	/**
	 * Recalculates the current nett credit-value for this Reply-object.
	 * 
	 * To be called from a Controller-class that, for example, has just added a credit to this Reply
	 * This method does not store the new netCredits value in this Reply's $credits field, but simply returns it for the Controller to handle.
	 * 
	 * @uses Models_Reply::getCredits() Fetching an array of all Credits-objects for this Reply.
	 * @return int	Nett value of credits for this post.
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function calcNettCredits() {
		$creditsArray = $this->getCredits();
		$nettCredits = 0;
		foreach ($creditsArray as $credit) {
			$nettCredits += $credit->value;
		}
		return $nettCredits;
	}
	
	
	/**
	 * gets an existing Credit-object for this Reply for the calling Session
	 * @todo Or toch per Ipadress? Heb nu gekozen voor loggen van alle gebruikers als User (met role Anonymous oid), zodat we hier user_id kunnen gebruiken.
	 * 
	 * @param Models_User $callingUser Reference to the Session Object that wants to get its credit for this reply
	 * @return Models_Credit a Credit-objects
	 * @uses Models_Base::fetchByQuery()	
   * @uses Models_Base::getSelect()	
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getYourCredit($callingUser) {
		$query = Models_Credit::getSelect() . " WHERE reply_id=" . $this->id . " AND user_id=" . $callingUser->id . ";";
		$creditsArray = Models_Credit::fetchByQuery($query);
		return (empty($creditsArray)) ? false : $creditsArray[1];
	}
}

?>