<?php

class Views_Threads_Category extends Views_Threads_Base {
	
	public $category;
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
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
