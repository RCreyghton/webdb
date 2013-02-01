<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Category-class with fiels en methods to make and display Categories
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 * @author Shafiq Ahmadi <s.ah@live.nl>
 */
class Models_Category extends Models_Base {
	
	/**
	 * The name of the table in the Database associated with this Model.
	 */
	const TABLENAME = "categories";

	/**
	 *
	 * @var type 
	 */
	public $id;

	/**
	 *
	 * @var type 
	 */
	public $name;

	/**
	 *
	 * @var type 
	 */
	public $description;

	/**
	 * This integer defines whether open threads in this Category are moderated or not.
	 * 
	 * {@link Models_Reply}-objects are to inherit this setting at their making in {@link Models_Reply::$visibility}.
	 * 
	 * Possible values:
	 * - -1 - hidden. Categoies are not displayed except for admins.
	 * - 0 - restricted, replies to threads are hidden by default, to be opened by admins.
	 * - 1 - open, replies to threads are visible by default.
	 * 
	 * @var int 
	 */
	public $status;

	/**
	 * Names of the relevant fields of this object, the must correspond with the
	 * column-names of the associated table in the database.
	 * 
	 * @author Frank van Luijn <frank@accode.nl>
	 * @return string[] The names of all relevant fields exept id in this object
	 */
	public function declareFields() {
		$fields = array(
				"name",
				"description",
				"status"
		);
		return $fields;
	}

	/**
	 * gets an array of Thread-objects in this category
	 *
	 * @return Models_Thread[] array of Thread-objects.
	 * @uses Models_Base::fetchByQuery()	
	 * @uses Models_Base::getSelect()	
	 */
	public function getThreads() {
		$query = Models_Thread::getSelect() . " WHERE category_id=" . $this->id . ";";
		return Models_Thread::fetchByQuery($query);
	}
	
}