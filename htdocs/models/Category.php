<?php

if( ! defined("WEBDB_EXEC") ) die("No direct access!");

/**
 * Category-class with fiels en methods to make and display Categories
 *
 * Voorbeeld van hoe andere Models-subklasses geimplemendeerd moeten worden.
 * Zie ook de class-structue pdf
 */

class Models_Category extends Models_Base {
    const TABLENAME = "categories";
    public $id;
    public $name;
    public $description;
    public $status;
    
    /**
     * 
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
     * @returns array[Objects] 
     */
    public function getThreads() {
        $query = $this->getSelect() . " WHERE category_id = `" . $this->id . "`";
        //SQL injection controleren!!
        return $this->fetchByQuery($query);
    }
    
    
    /**
     * gets an array of Reply-objects in this category
     *
     * @returns array[Objects] 
     */
    public function getReplies() {
        
    }
}

?>