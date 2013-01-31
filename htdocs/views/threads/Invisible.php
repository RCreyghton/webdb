<?php

class Views_Threads_Invisible extends Views_Threads_Base {
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$this->title = "Alle verborgen vragen";		
		$this->printHead();
		$this->printThreads();
		$this->printPagination();
	}
	
}
