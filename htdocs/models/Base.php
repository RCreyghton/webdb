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
		foreach( $fields as $field ) {
			$values[] = "'" . mysql_real_escape_string( $this->$field ) . "'";
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
	 * 
	 * @param string $sql
	 * @return array[Object]
	 */
    public function fetchByQuery($sql='') {
        return $this->db->query($sql);
    }


    /**
     * FetchById gets a full record of the table corresponding with a <Models>-object and returns them
     *
     * Ik snap eerlijk gezegd het nut hier niet van... aangezien we een relationele database hebben elk <Models>-object 
	 * meer nodig heeft dan alleen zijn eigen DB-record willen we uitegebreidere queries... 
	 * Of laten we dat de classes onderling uitzoeken... dat zou zonde zijn van de kracht van SQL?
     *
     * @author RCreyghton
     */
    public function fetchById($model_id) {
        //De boel hieronder moet afhankelijk van de huidige object-naam. En SQL-injection safe bovendien...
        //return $this->fechtByQuery('SELECT * FROM $modelname? WHERE $modelname?_id=?');
        //
    }
}
