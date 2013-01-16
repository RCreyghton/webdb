<?php

if( ! defined("WEBDB_EXEC") ) die("No direct access!");

/*
 * Base class
 * 
 * This class sits atop all classes that need database interaction.
 * When a class extends this one it automatically inherits all db functions such
 * as save, fetchById and fetchByQuery.
 */

abstract class Models_Base {
	
	/*
	 * Each child class must return an array including it's fields
	 * Each field need also be present in the corresponding database
	 */
	abstract function declareFields();
	
	public function save() {
		if( isset( $this->id ) ) {
			$this->update();
		} else {
			$this->insert();
		}
	}
	
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
		
		//
		
		//build the query
		$query = "INSERT INTO `" . $this::TABLENAME . "` ( `" . $fieldsstring . "` ) VALUES (" . implode(", ", $values ). ");";
		echo $query;
	}
	
	public function getSelect( $fields = "*" ) {
		return "SELECT " . $fields . " FROM `" . $this::TABLENAME . "` ";
	}
	
	/**
	 * fetchByQuery kan 1 _of_ meer objecten terugkrijgen, dus een array, want heel algemeen
	 * RCreyghton: Maakt de DBH die array die objecten zelf aan? Ah, we gaan rowToObject(..) gebruiken
	 * 
	 * @param string $query
	 * @return array[Object]
	 */
    public function fetchByQuery($query='') {
        return $this->db->run($query);
    }


		/**
		 * FetchById gets a full record of the table corresponding with a <Models>-object and returns them
		 * 
		 * @author Ramon Creyghton <r.creyghton@gmail.com>
		 * @param int $model_id
		 * @return Object	zou enkel new <model> object moeten returnen?
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
		 * @return Object A childe object of this Base class
		 */
		private function rowToObject($sqlresult){
			//Hoe gaan we bepalen wat voor'n object we eigenlijk willen? Dat kan indirect adv
			//de database-output... maar willen we dat?
//			$modeltype = "Category"
//			$model = new Models_$modeltype;
			if ($fieldValuesArray = $sqlresult->fetch_assoc()) {
				foreach ($fieldValuesArray as $field => $value) {
//					$model->$field = $value;
					
				}
			}
    }

		
}
