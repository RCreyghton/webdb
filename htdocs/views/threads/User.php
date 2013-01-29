<?php

class Views_Threads_User extends Views_Threads_Base {
	
	public $user;
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
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
