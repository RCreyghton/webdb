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

	const TABLENAME = "threads";
	const FOREIGNPREFIX = "thread";
	public $id;
	public $user_id;
	public $category_id;
	public $ts_created;
	public $ts_modified;
	public $title;
	public $content;
	public $status;		//visibility en open/closed in één. Voorstel:
										//0 is hidden & open;
										//1 is visible & open; waarbij open restricted is indien de category dit bepaalt.
										//2 is visible & closed; de Thread is door admin beeindigd.
										//3 hidden $ close; dus nog niet niet verwijderd.
	public $answer_id;
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
				"answer_id",
				"views"
		);
		return $fields;
	}
	
	
	/**
	 * gets an array of Reply-objects under this Thread.
	 *
	 * @return Models_Reply[] array of Reply-objects
	 * @uses Models_Base::fetchByQuery()	
   * @uses Models_Base::getSelect()	
	 * @todo SQL injection check, dwz: checken of inderdaad alle Object-velden safe zijn.
	 * @todo The Reply that is selected as the Answer to this Thread is still somewhere amidst these Replies. Exclude it from here?
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getReplies() {
		$query = Models_Reply::getSelect() . " WHERE thread_id=" . $this->id . ";";
		return Models_Reply::fetchByQuery($query);
	}
	
	/**
	 * gets the Reply that is selected as the Answer to this Thread, or false.
	 * 
	 * @return Models_Reply|boolean Description The selected Reply-object or false.
	 * @uses Models_Base::fetchByQuery()	
   * @uses Models_Base::getSelect()	
	 * @todo SQL injection check, dwz: checken of inderdaad alle Object-velden safe zijn.
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getAnswer() {
		if ($this->answer_id == NULL)
			return false;
		$query = Models_Reply::getSelect() . " WHERE id=" . $this->answer_id . ";";
		$repliesArray = Models_Reply::fetchByQuery($query);
		return (empty($repliesArray)) ? false : $repliesArray[1] ;
	}
}

?>