<?php

if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Contains a number of global control methods for every controller, for example to execute a task (i.e. view) or to get session parameters.
 * 
 * @author Frank van Luijn <frank@accode.nl>
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
abstract class Controllers_Base {

	/**
	 * Associative array containing parameters for this controller.
	 * 
	 * To be set using {@link setParam()}.
	 * Precedes POST en GET variables in importance (see {@link getInt()}, {@link getString()}).
	 * @var string[]|int[] 
	 */
	public $params = array();

	/**
	 * This controller controls a view, to which a reference is stored in this variable.
	 * @var Views_Base	A child of the Views_Base-class. 
	 */
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
	 */
	public function execute($task) {
		if ($task == NULL)
			throw new Exception("Onvolledige URL: task not found.");
		$methodCall = array($this, $task);
		if ( ! is_callable($methodCall) || ( in_array( $task, get_class_methods( get_parent_class( $this ) ) ) ) ) {
			throw new Exception("Verkeerde URL: Task not found");
		}
		return $this->$task();
	}

	/**
	 * Lets the selected view render it contents and then injects them in the preprocessed template.
	 * 
	 * @uses Views_Base::getTemplate To get a preprocessed (title, menu etc) template.
	 * @uses Views_Base::render To get the actual contents of {@link $view}.
	 */
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
	 * Searches {@link $params}, POST and GET globals (in that order) for an integer at the given index.
	 * If no (numaric!) matches are found, return a default value if prodived, otherwise NULL.
	 * 
	 * @uses	Helpers_Db::escape All possible points of user-input need to be escaped. Hence the use of escape.
	 * @param string $key
	 * @param int $default
	 * @return int|null	The asked integer, or null if not found.
	 */
	public function getInt($key, $default = NULL) {
		//first check own params
		if (isset($this->params[$key]) && is_numeric($this->params[$key])) {
			$rv = intval($this->params[$key]);

			//then check post
		} elseif (isset($_POST[$key]) && is_numeric($_POST[$key])) {
			$rv = intval($_POST[$key]);

			//then check get
		} elseif (isset($_GET[$key]) && is_numeric($_GET[$key])) {
			$rv = intval($_GET[$key]);

			//else let it be NULL
		} else {
			$rv = NULL;
		}

		//return default, if default was not passed it will be NULL
		$rv = is_null($rv) ? $default : $rv;

		//escape the string and retun it
		return Helpers_Db::escape($rv);
	}

	/**
	 * Searches {@link $params}, POST and GET globals (in that order) for an integer at the given index.
	 * If no matches are found, return a default value if prodived, otherwise NULL.
	 * 
	 * @uses	Helpers_Db::escape All possible points of user-input need to be escaped. Hence the use of escape.
	 * @param	string  $key	The name of the paramter to look for in the three parameter arrays.
	 * @param	string	$default
	 * @return	string|null  Either the string asked for, or the default, or null if the searched arrays did not provide it.
	 */
	public function getString($key, $default = NULL) {

		//first check own params
		if (isset($this->params[$key]) && is_string($this->params[$key])) {
			$rv = "{$this->params[$key]}";

			//then check post
		} elseif (isset($_POST[$key]) && is_string($_POST[$key])) {
			$rv = "{$_POST[$key]}";

			//then check get
		} elseif (isset($_GET[$key]) && is_string($_GET[$key])) {
			$rv = "{$_GET[$key]}";

			//else it will be null
		} else {
			$rv = NULL;
		}

		//return default, if default was not passed it will be NULL
		$rv = is_null($rv) ? $default : $rv;

		//escape the string and retun it
		return Helpers_Db::escape($rv);
	}

}
