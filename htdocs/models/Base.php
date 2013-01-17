<?php
/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if( ! defined("WEBDB_EXEC") ) die("No direct access!");


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
		if( isset( $this->id ) ) {
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
	 * @todo Implementeren van succes-return
	 * @todo query uitvoeren
	 * @todo door de insert wordt de id geset door MySQL (auto_increment). Willen we die gelijk teruguitlezen en in het object opslaan?
	 */
	private function insert() {
		$fields		= $this->declareFields();
		$values		= array();
		
		//get the field list
		$fieldsstring = implode("`, `", $fields );
		
		//iterate over object to get corresponding values
		//voor test-doeneinde mysql_rea_escape_string verwijderd
		foreach( $fields as $field ) {
			$values[] = "'" . $this->$field . "'";
		}
		
		//build the query
		$query = "INSERT INTO `" . $this::TABLENAME . "` ( `" . $fieldsstring . "` ) VALUES (" . implode(", ", $values ). ");";
		echo $query;
		
		//TODO uitvoeren en succescheck
		return true;
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
	 * @todo Implementeren van succes-return
	 * @todo query uitvoeren
	 */
	private function update() {
		$fields	= $this->declareFields();
		
		//met array_walk en catFieldValue elk element uit fields van zijn value
		//voorzien volgens field='value'
		array_walk($fields, array($this, 'catFieldValue'));
		$setString = implode(", ", $fields);
		
		//build the query
		$query = "UPDATE `" . $this::TABLENAME . "` SET " . $setString . " WHERE id=" . $this->id . ";";
		echo $query;
		//TODO uitvoeren en succescheck
		return true;
	}
	
	/**
	 * Concatenates field with its corresponding value in this object to the form
	 * {field}='{value}'
	 * 
	 * @param string $field that wil be alterd
	 * @param mixed $key not in use
	 */
	private function catFieldValue(&$field, $key) {
		$field = $field . "='" . $this->$field . "'";
	}
	
	
	/**
	 * Deletes 
	 * 
	 * @return boolean true if succesful delete of this object's record in the database AND all its dependencies!
	 * @todo everything
	 * @todo dependencies!
	 */
	public function delete() {
		return true;
	}
	
	
  /**
	 * Exists the object in the database???
	 * Ramon: ik begrijp niet precies wanneer dit nuttig is?
	 * 
	 * @return boolean true if this object has a corresponding database record?
	 * @todo everything
	 */
	public function exists() {
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
	public static function getSelect( $fields = "*" ) {
		return "SELECT " . $fields . " FROM `" . get_called_class()->TABLENAME . "` ";
	}
	
	
	/**
	 * fetchByQuery kan 1 _of_ meer objecten terugkrijgen, dus een array, want heel algemeen
	 * RCreyghton: Maakt de DBH die array die objecten zelf aan? Ah, we gaan rowToObject(..) gebruiken
	 * 
	 * @param String $query
	 * @return array[Object] rechstreeks het mysqli-geval, of een bewerkte rij(en) eruit?
	 */
    public function fetchByQuery($query='') {
        return $this->Db->run($query);
    }


		/**
		 * FetchById gets a full record of the table corresponding with a {model}-object and returns them
		 * 
		 * @author Ramon Creyghton <r.creyghton@gmail.com>
		 * @param int $model_id
		 * @return Object	zou enkel new {model}-object moeten returnen?
		 */
    public function fetchById($model_id) {
        //De boel hieronder moet afhankelijk van de huidige object-naam. En SQL-injection safe bovendien...
        $resultarray = $this->fetchByQuery(getSelect() . " WHERE id=" . $model_id);
        return $resultarray[0];
    }
		
		
		/**
		 * Moet een child-object van deze base klasse returnen, op basis van de eerste
		 * rij van een mysqli result.
		 * 
		 * @param mysqli_result $sqlresult
		 * @return Object A child object of this Base class
		 * @author Ramon Creyghton <r.creyghton@gmail.com>
		 */
		private static function rowToObject($sqlresult){
			//met get_callec_class() weten we ook in deze static methode wat voor'n
			//object we eigenlijk willen maken.
			$modeltype = get_called_class();
			$model = new $modeltype;
			$fieldsToFill = $model->declareFields();
			//We nemen nu de eerste rij ALS die er is met fetch_assoc();
			if ($fieldValuesArray = $sqlresult->fetch_assoc()) {
				//Elke element in deze assoc_array in het object plakken
				foreach ($fieldsToFill as $field) {
					$model->$field = $fieldValuesArray[$field];
				}
			}
			return $model;
    }
		
}
