<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Reply-class with fiels en methods to make and display Replies and its Credits
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Reply extends Models_Base {
	
	/**
	 * The name of the table in the Database associated with this Model.
	 */
	const TABLENAME = "replies";

	/**
	 * Unique id of this reply. Auto incremented by the database. Retrieved at first save using {@link Helpers_Db::getId()}.
	 * @var int 
	 */
	public $id;

	/**
	 * Relation with the user that posted this reply, see {@link Models_User::$id}.
	 * @var int 
	 */
	public $user_id;

	/**
	 * Relation with the thread this reply belongs to, see {@link Models_Thread::$id}.
	 * @var int 
	 */
	public $thread_id;

	/**
	 * Unix Timestamp indicating the creation of this reply.
	 * @var int 
	 */
	public $ts_created;

	/**
	 * Unix Timestamp indicating the latest update of this reply. Null if never updated.
	 * @var int|null 
	 */
	public $ts_modified;

	/**
	 * The title of this reply.
	 * @var string 
	 */
	public $title;

	/**
	 * The actual contents of this reply.
	 * @var string 
	 */
	public $content;

	/**
	 * This integer defines the visibility of this reply
	 * 
	 * Possible values:
	 * - 0 - hidden
	 * - 1 - visible
	 * 
	 * @var int 
	 */
	public $visibility;

	/**
	 * Cache of the current nett number of credits for this reply. Needs refreshing at every relating {@link Models_Credit} insert or update, using {@link calcNettCredits()}.
	 * @var int 
	 */
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
	 * Most values of this Models-object are determined with user-input. However, the creation time needs to be set at insert-time.
	 */
	protected function insert() {
		$this->ts_created = time();
		$this->ts_modified = time();
		return parent::insert();
	}

	/**
	 * Most values of this Models-object are determined with user-input. However, the modification time needs to be set at update-time.
	 */
	protected function update() {
		$this->ts_modified = time();
		return parent::update();
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
	 * @todo Implementatie checken. (Per user of per IP-adres? Heb nu gekozen voor loggen van alle gebruikers als User, zodat we hier user_id kunnen gebruiken.)
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