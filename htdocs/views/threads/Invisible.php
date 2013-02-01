<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Renders the Threads_Invisible view, meant for admins only.
 */
class Views_Threads_Invisible extends Views_Threads_Base {

	/**
	 * Renders the contents of this view, by parsing the $threads-array made by {@link Controllers_Threads::invisible()}.
	 * 
	 * @author	Frank van Luijn <frank@accode.nl>
	 */
	public function render() {
		$this->title = "Alle verborgen vragen";
		$this->printHead();
		$this->printThreads();
		$this->printPagination();
	}

}
