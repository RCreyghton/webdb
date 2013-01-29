<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_Categories_Overview extends Views_Base {

	/**
	 *
	 * @var Models_Category[]	Array met alle category-objecten. 
	 */
	public $categories;
	
	public function render() {
		foreach ($this->categories as $category) {
			echo "					<div class=\"categories_overview_container\">
						<h3 class=\"categories_overview_header\"><a href=\"./threads/category/{$category->id}\" class=\"headerlink\">{$category->name}</a></h3>
						<p>{$category->description}</p>
					</div>";
		}
	}
}
