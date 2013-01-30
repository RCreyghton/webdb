<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_Search_Quick extends Views_Base {

	public $posts;
	
	public function render() {
		foreach ($this->posts as $post) {
			echo "<li><a href=\"./threads/single/{$post[0]}\" >{$post[1]}</a></li>";
		}
	}
}