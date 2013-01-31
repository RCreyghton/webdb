<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Thread-class with fiels en methods to make and display Threads and get its Replies.
 * Is able to get the Replies associated with this thread.
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Thread extends Models_Base {
	const INVISIBLE = 0;
	const VISIBLE	= 1;
	
	const CLOSED	= 0;
	const OPEN		= 1;
	/**
	 * The name of the table in the Database associated with this Model.
	 */
	const TABLENAME = "threads";

	/**
	 * Unique id of this thread. Auto incremented by the database. Retrieved at first save using {@link Helpers_Db::getId()}.
	 * @var int 
	 */
	public $id;

	/**
	 * Relation with the user that posted this thread, see {@link Models_User::$id}.
	 * @var int
	 */
	public $user_id;

	/**
	 * Relation with the category in which this thread is place, see {@link Models_Category::$id}.
	 * @var int 
	 */
	public $category_id;

	/**
	 * Unix Timestamp indicating the creation of this thread.
	 * @var int 
	 */
	public $ts_created;

	/**
	 * Unix Timestamp indicating the latest update of this thread. Null if never updated.
	 * @var int 
	 */
	public $ts_modified;

	/**
	 * The title of this thread.
	 * @var string 
	 */
	public $title;

	/**
	 * The contents of this thread.
	 * @var string 
	 */
	public $content;

	/**
	 * This integer defines status and visibility at once.
	 * 
	 * Possible values:
	 * VISIBLE
	 * INVISIBLE
	 * @var int 
	 */
	public $status;
	
	/**
	 * This integer defines whether or not replies are allowed
	 * 
	 * Possible values:
	 * OPEN
	 * CLOSED
	 * 
	 * @var int 
	 */
	public $open;

	/**
	 * Relation with the reply that is selected as an answer to this thread. see {@link Models_Reply::$id}.
	 * @var int 
	 */
	public $answer_id;

	/**
	 * Number of views. Needs to be updated every time this thread is viewed in detail.
	 * @var int 
	 */
	public $views;

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
				"category_id",
				"ts_created",
				"ts_modified",
				"title",
				"content",
				"status",
				"open",
				"answer_id",
				"views"
		);
		return $fields;
	}

	/**
	 * Most values of this Models-object are determined with user-input. However, the creation time needs to be set at insert-time.
	 */
	protected function insert() {
		if( ! $this->answer_id ) {
			$this->answer_id = "NULL";
		}
		$this->ts_created = time();
		return parent::insert();
	}

	/**
	 * Most values of this Models-object are determined with user-input. However, the modification time needs to be set at update-time.
	 */
	protected function update() {
		if( ! $this->answer_id ) {
			$this->answer_id = "NULL";
		}
		$this->ts_modified = time();
		return parent::update();
	}

	/**
	 * gets the Reply that is selected as the Answer to this Thread, or false.
	 * 
	 * @return Models_Reply|boolean Description The selected Reply-object or false.
	 * @uses Models_Base::fetchByQuery()	
	 * @uses Models_Base::getSelect()	
	 * @todo The Reply that is selected as the Answer to this Thread is still somewhere amidst the other Replies. Controller needs to exclude this Reply from the array of other replies!
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getAnswer() {
		if ($this->answer_id == NULL)
			return false;
		$query = Models_Reply::getSelect() . " WHERE id=" . $this->answer_id . ";";
		$repliesArray = Models_Reply::fetchByQuery($query);
		return (empty($repliesArray)) ? false : $repliesArray[1];
	}
	
	
	/**
	 * Very ugly function that forces the connected models into a non-existing
	 * object-variable. This way you can get a quick (and dirty) instantiation
	 * of all related objects.
	 */
	public function loadConnections() {
		//load the user
		$this->user = Models_User::fetchById( $this->user_id );
		
		//load the category
		$this->category = Models_User::fetchById( $this->category_id );	
	}

}
