<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Controllers_Categories extends Controllers_Base {

	public function overview() {
		$this->view = new Views_Categories_Overview();
		$query = Models_Category::getSelect() . "ORDER BY `name`;";
		$categories = Models_Category::fetchByQuery( $query );
		$this->view->categories = $categories;
		$this->display();
	}
	
	
}
