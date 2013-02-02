<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Renders the Threads_Users view: display all threads by a particular user.
 */
class Views_Threads_User extends Views_Threads_Base {
	
	public $user;
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by {@link Controllers_Threads::user()}.
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$this->title = "Alle vragen door {$this->user->firstname} {$this->user->lastname}";
		$this->printHead();
		$this->printThreads();
		$this->printPagination();
	}
	
}
