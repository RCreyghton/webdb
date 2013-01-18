<?php
/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if( ! defined("WEBDB_EXEC") ) die("No direct access!");

/**
 * Category-class with fiels en methods to make and display Categories
 *
 * Voorbeeld van hoe andere Models-subklasses geimplemendeerd moeten worden.
 * Zie ook de class-structue pdf
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 * @author Shafiq Ahmadi <s.ah@live.nl>
 */
class Models_Category extends Models_Base {
    const TABLENAME = "categories";
    public $id;
    public $name;
    public $description;
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
		 * @todo SQL injection check
		 * @todo in welke vorm willen we dit precies hebben?
		 * @todo getSelect en fetchByQuery, van welk object spreken we die aan?
		 * @todo misschien hier al wat voorwerk doen in de vorm van een met JOINS uitgebreide query tbv de category-view?
		 */
    public function getThreads() {
        $query = Models_Thread::getSelect() . " WHERE category_id = `" . $this->id . "`";
        return Models_Thread::fetchByQuery($query);
    }
    
}

?>