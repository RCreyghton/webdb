<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

abstract class Controllers_Base {
	
    public $params = array();
    public $view;
	    
	
	/**
	 * Adds a parameter (key => value) to the param list in this object.
	 *
	 * @param	string	$key	Keyname.
	 * @param	string	$value	Value.
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
    public function setParam($key, $value) {
		$this->params[$key] = $value;
    }
	
	
	/**
	 * Checks if a method named $task exists in this Controller-object, and executes it.
	 *
	 * @param	string	$task	Name of the method asked to execute.
	 * @return	boolean|mixed	Return value of the method called or false.
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 * @todo	What if return false.. error handling where?
	 */
	public function execute($task) {
		if ( $task == NULL )
			$task = static::DEFAULTTASK;
        if ( ! method_exists($this, $task) ) {
            throw new Exception("Task not found");
        } 
		return $this->$task(); 
	}
	
	
	public function display() {
		//eerst de content renderen en in buffer opslaan.
		ob_start();
		$this->view->render();
		$rendered = ob_get_contents();
		ob_end_clean();
		
		//nu kunnen we de template openen en onze render op de plek van de content injecteren.
		echo str_replace("<!-- CONTENT -->", $rendered, $this->view->getTemplate());
		
	}
	
	
	/**
	 * Searches own params, post and get in that order for an integer at the given index
	 * If no matches are found, return a default value if prodived, otherwise NULL
	 * 
	 * @param string $key
	 * @param int $default
	 * @return int
	 */
	public function getInt( $key, $default = NULL ) {
		//first check own params
		if( isset( $this->params[ $key ] ) && is_numeric( $this->params[ $key ] ) )
			$rv = intval ( $this->params[ $key ] );
		
		//then check post
		elseif( isset( $_POST[ $key ] ) && is_numeric( $_POST[ $key ] ) )
			$rv = intval ( $_POST[ $key ] );
		
		//then check get
		elseif( isset( $_GET[ $key ] ) && is_numeric( $_GET[ $key ] ) )
			$rv = intval ( $_GET[ $key ] );
		
		//return default, if default was not passed it will be NULL
		$rv = $default;
		
		return Helpers_Db::escape( $rv );	 
	}
	
	
	/**
	 * Checks the (1) the params-array (2) the $_POST variables and (3) the $_GET variables for a key named $getkey and returns it.
	 *
	 * @param   string  $getkey	The name of the paramter to look for in the
	 * @return  string|boolean  Either the string asked for, or false if the searched arrays did not provide it.
	 * @author  Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getString( $key, $default = NULL ) {
		//first check own params
		if( isset( $this->params[ $key ] ) && is_string( $this->params[ $key ] ) )
			$rv = "{$this->params[ $key ]}";
		
		//then check post
		elseif( isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] ) )
			$rv = "{$_POST[ $key ]}";
		
		//then check get
		elseif( isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] ) )
			$rv = "{$_GET[ $key ]}";
		
		//return default, if default was not passed it will be NULL
		$rv = $default;
		
		return Helpers_Db::escape( $rv );
	}
}
