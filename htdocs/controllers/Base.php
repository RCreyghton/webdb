<?php


abstract class Controllers_Base {
	
    public $params = array();    
	    
	
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
	 */
	public function execute($task) {
		if ( $task == "" )
			$task = static::DEFAULTTASK;
		return ( method_exists($this, $task) ) ? 
			$this->$task() :
			false;
	}
	
	
	public static function display(Views_Base $view) {
		//eerst de content renderen en in buffer opslaan.
		ob_start();
		$view->render();
		$rendered = ob_get_contents();
		ob_end_clean();
		
		//nu kunnen we de template openen en onze render op de plek van de content injecteren.
		echo str_replace("<!-- CONTENT -->", $rendered, $view->getTemplate());
		
	}
	
	
	/**
	 * Checks the (1) the params-array (2) the $_POST variables and (3) the $_GET variables for a key named $getkey _with an integer value_ and returns it.
	 *
	 * @param   string  $getkey	The name of the paramter to look for in the
	 * @return  int|boolean  Either the integer asked for, or false if the searched arrays did not provide it.
	 * @author  Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getInt( $getkey ) {
		/*
		if ( ( array_key_exists($getkey), $this->params) && ( ! empty($this->params["$getkey"]) ) && ( (int) $this->params["$getkey"] != 0) ) {
			return (int) $this->params["$getkey"];
		} else if ( isset($_POST["$getkey"]) && !empty($_POST["$getkey"]) && ( (int) $_POST["$getkey"] != 0) ) {
			return (int) $_POST["$getkey"];
		} else if ( isset($_GET["$getkey"]) && !empty($_GET["$getkey"]) && ( (int) $_GET["$getkey"] != 0) ) {
			return (int) $_GET["$getkey"];
		} else {
			return false;
	    }
		 * */
		 
	}
	
	
	/**
	 * Checks the (1) the params-array (2) the $_POST variables and (3) the $_GET variables for a key named $getkey and returns it.
	 *
	 * @param   string  $getkey	The name of the paramter to look for in the
	 * @return  string|boolean  Either the string asked for, or false if the searched arrays did not provide it.
	 * @author  Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function getString( $getkey ) {
		/*
		if ( ( array_key_exists($getkey), $this->params) && ( ! empty($this->params["$getkey"]) ) ) {
			return $this->params["$getkey"];
		} else if ( isset($_POST["$getkey"]) && !empty($_POST["$getkey"]) ) {
			return $_POST["$getkey"];
		} else if ( isset($_GET["$getkey"]) && !empty($_GET["$getkey"]) ) {
			return $_GET["$getkey"];
		} else {
			return false;
	    }*/
	}
}
