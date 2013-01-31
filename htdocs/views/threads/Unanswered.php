<?php

class Views_Threads_Unanswered extends Views_Threads_Base {
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$this->title = "Alle onbeantwoorde vragen";
		
		$this->printHead();
		$this->printThreads();
		$this->printPagination();
		
		if( Helpers_User::isLoggedIn() )
			echo "<a class='threads_add_thread' href='./threads/threadform'>Stel een nieuwe vraag</a>\n";
	}
	
}
