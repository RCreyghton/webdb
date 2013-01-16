<?php

if( ! defined("WEBDB_EXEC") ) die("No direct access!");

/*
 * Base class
 * 
 * This class sits atop all classes that need database interaction.
 * When a class extends this one it automatically inherits all db functions such
 * as save, fetchById and fetchByQuery.
 */

class Models_Base {
	
	public function __construct() {
		$db = new Helpers_Db();
		//echo "wiiiiiii";
	}


    /**
     * Executes a SQL-query via a Helpers_Db-object
     *
     * Fetch by Query, suggereert dat alle Models-classes een sql-query kunnen uitvoeren via Helpers_DB, maar ik dacht dat de queries in Helpers_Db gemaakt zouden worden? En dat die Helpers_Db dan op basis van een argument zou beslissen wat voor query hij ging maken? Lijkt me ingewikkeld, dus ja: ik probeer hier nu een fetchByQuery te maken.
     *
     * @author RCreyghton
     * @param A valid SQL query
     * @returns An array of <Models>-objects?
     */
    public function fetchByQuery($sql='') {
        return $this->db->query($sql);
    }


    /**
     * FetchById gets a full record of the table corresponding with a <Models>-object and returns them
     *
     * Ik snap eerlijk gezegd het nut hier niet van... aangezien we een relationele database hebben elk <Models>-object meer nodig heeft dan alleen zijn eigen DB-record willen we uitegebreidere queries... Of laten we dat de classes onderling uitzoeken... dat zou zonde zijn van de kracht van SQL?
     *
     * @author RCreyghton
     */
    public function fetchById($model_id) {
        //De boel hieronder moet afhankelijk van de huidige object-naam. En SQL-injection safe bovendien...
        //return $this->fechtByQuery('SELECT * FROM $modelname? WHERE $modelname?_id=?');
        //
    }
}
