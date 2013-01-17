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
	 * @return array[Strings] The names of all relevant fields exept id in this object
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

}

?>