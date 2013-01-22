<?php

class Views_Threads_Unanswered extends Views_Threads_Base {
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
	 * 
	 * @uses	Models_Base::fetchById()
	 * @uses	Models_Base->getForeignCount()
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		echo "<h2>Unanswered Threads</h2>";
		$this->printThreads();
		$this->printPagination();
	}
	
}
