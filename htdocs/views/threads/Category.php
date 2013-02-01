<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Renders the Threads_Category view: display all threads in a particular category.
 */
class Views_Threads_Category extends Views_Threads_Base {

	public $category;

	/**
	 * Renders the contents of this view, by parsing the $threads-array made by {@link Controllers_Threads::category()}.
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$this->title = "Vragen in de categorie {$this->category->name}";
		$this->printHead();
		$this->printThreads();
		$this->printPagination();
	}

}
