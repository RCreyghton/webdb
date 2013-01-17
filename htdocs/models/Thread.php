<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Category-class with fiels en methods to make and display Threads
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Models_Thread extends Models_Base {

	const TABLENAME = "threads";

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
	 * @return array[Strings] The names of all relevant fields exept id in this object
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

}

?>