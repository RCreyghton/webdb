<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Base class
 * 
 * This class sits atop all classes that need database interaction.
 * When a class extends this one it automatically inherits all db functions such
 * as save, fetchById and fetchByQuery.
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 * @author Shafiq Ahmadi <s.ah@live.nl>
 * @todo	Maybe drop the declareFields()-method altogher, since it is implemented not-static in the Childs: inefficient. Use get_class_vars() instead?.
 * @todo	Check whether get_called_class() en static:: references indeed work as intended.
 */
abstract class Models_Base {
	/*
	 * Each child class must return an array including it's fields
	 * Each field need also be present in the corresponding database
	 */

	abstract function declareFields();

	/**
	 * saves a model-object to the database
	 * 
	 * Updates the corresponding record in the database,
	 * or inserts a new one if there is none.
	 * 
	 * @uses update, insert For the database interactions
	 * @author Frank van Luijn <frank@accode.nl>
	 * @return boolean true if saved succesfully
	 */
	public function save() {
		if (isset($this->id)) {
			return($this->update());
		} else {
			return($this->insert());
		}
	}

	/**
	 * inserts the data of the current ($this) object to the database as a new record
	 * 
	 * Iteratates over alle fields of this object, and saves them in the corresponding
	 * columns of the database-table associated with this object (as in TABLENAME)
	 * 
	 * @uses declareFields() To determine wich fields and values thera are to be saved
	 * @author Frank van Luijn <frank@accode.nl>
	 * @todo echo uitzetten.
	 * @todo testen id teruguitlezen uit db auto_increment
	 */
	private function insert() {
		$fields = $this->declareFields();
		$values = array();

		//get the field list
		$fieldsstring = implode("`, `", $fields);

		//iterate over object to get corresponding values
		//voor test-doeneinde mysql_rea_escape_string verwijderd
		foreach ($fields as $field) {
			$values[] = "'" . $this->$field . "'";
		}

		//build the query
		$query = "INSERT INTO `" . static::TABLENAME . "` ( `" . $fieldsstring . "` ) VALUES (" . implode(", ", $values) . ");";
echo $query;
		$result = Helpers_Db::run($query);

		if ($result !== true) {
			throw new Exception(Helpers_Db::getError());
		} else {
			$this->id = Helpers_Db::getId();
		}
		//this returns true if no errors were found.
		return $result;
	}

	/**
	 * updates the data of the current ($this) object in the existing corresponding record in the database
	 * 
	 * Iteratates over alle fields of this object, and saves them in the corresponding
	 * columns of the database-table associated with this object (as in TABLENAME)
	 * 
	 * @uses declareFields() To determine wich fields and values thera are to be saved
	 * @uses catFieldValue To concatenate field and value in the fields-array.
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @return boolean true if inserted succesfully
	 * @todo echo uitzetten
	 */
	private function update() {
		$fields = $this->declareFields();
		
		$concat = array();
		foreach( $fields as $field ) {
			$concat[] = "`{$field}`='{$this->$field}'";
		}

		//build the query
		$query = "UPDATE `" . static::TABLENAME . "` SET " . implode(", ", $concat) . " WHERE `id`='" . $this->id . "';";

		$result = Helpers_Db::run($query);

		if (!$result) {
			throw new Exception(Helpers_Db::getError());
		}
		return $result;
	}

	/**
	 * Deletes 
	 * 
	 * @return boolean true if succesful delete of this object's record in the database AND all its dependencies!
	 * @todo everything
	 * @todo dependencies!
	 */
	public function delete() {
		$query = "DELETE FROM `" . static::TABLENAME . "` WHERE `id`='" . $this->id . "';";
		
		$result = Helpers_Db::run($query);
		if ( ! $result ) {
			throw new Exception(Helpers_Db::getError());
		}
		return $result;
	}

	/**
	 * Exists a record with the given ID in the database???
	 * Ramon: ik begrijp niet precies wanneer dit nuttig is?
	 * 
	 * @return boolean true if this object has a corresponding database record?
	 * @todo everything
	 */
	public static function exists() {
		//get_called_class();
		//SQL exists ding in fetchByQuery gooien.
		return true;
	}

	/**
	 * getSelect returns the first part of a SQL query that makes a SELECT from the 
	 * table associated with the current object
	 * 
	 * @param String $fields
	 * @return String First part of a SQL-query of the form SELECT ... FROM ...
	 * @todo Werkt alleen met child-objecten die inderdaad een TABLENAME hebben. Mag dit ding dan wel hier in base zo staan?
	 */
	public static function getSelect($fields = "*") {
		return "SELECT " . $fields . " FROM `" . static::TABLENAME . "` ";
	}

	/**
	 * FetchById gets a full record of the table corresponding with a {model}-object and returns them
	 * 
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @param int $model_id
	 * @return Object	Van type {model}-child.
	 * @todo	Net als bij getSelect controleren of get_called_class()-> wel werkt...
	 */
	public static function fetchById($model_id) {
		//make quary based on the called class.
		$resultarray = static::fetchByQuery(getSelect() . " WHERE id=" . $model_id);
		return $resultarray[0];
	}

	/**
	 * Assamles an array of {models}-objects from the result returned by the given SQL query (SELECT).
	 * 
	 * @param string $query
	 * @return Models_Base[] rechstreeks het mysqli-geval, of een bewerkte rij(en) eruit?
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public static function fetchByQuery($query) {
		$result = Helpers_Db::run($query);
		if ( ! $result ) {
			throw new Exception( Helpers_Db::getError() );
		}

		//Converting each row as the object (type according to called class) and add to array.
		$objectsarray = array();
		while ( $model = $result->fetch_object() ) {
			$objectsarray[] = self::rowToObject( $model );
		}
		return $objectsarray;
	}

	/**
	 * converts a stdClass to an object of the current type
	 * 
	 * @param assoc_array[Strings] $sqlrow
	 * @return Object A child object of this Base class
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @todo Deze functie slaat alle id's over, omdat die niet in declareFields zitten. We kunnen denk ik beter gebruik maken van de mysqi method $result->fetch_object en deze rowToObject weggooien.
	 */
	private static function rowToObject( $object ) {
		$model = new static();
		$fields = $model->declareFields();
		//Elke element in deze assoc_array in het object plakken
		foreach ($fields as $field) {
			$model->$field = $object->$field;
		}
		return $model;
	}

	/**
	 * Fetches all records in DB that have n:1 relation (param $modelType : $this) with this {models}-object.
	 * 
	 * Does not use the $modelType's declareFields()-method, for these are not static, and therefore not callebale without an instance. Uses get_class_vars instead.
	 * 
	 * @uses Models_Base::fetchByQuery()	
	 * @uses Models_Base::getSelect()	
	 * @param string	Classname of the type of {modeosl}-object asked for.
	 * @return Models_Base[]|boolean	An array of {models}-objects of the type specified by $modelType, OR false if no such DB-relation is found.
	 * @author Ramon Creyghton <r.creyghton@gmail.com>
	 * @todo	Testing
	 */
	public function getForeignModels( $connectedModel ) {
		$prefix = strtolower( end( explode("_", $connectedModel) ) );
		$query = $connectedModel::getSelect() . " WHERE `{$prefix}_id`='" . $this->id . "';";
		return $connectedModel::fetchByQuery($query);
	}

}
