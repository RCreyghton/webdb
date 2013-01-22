<?php

abstract class Views_Threads_Base extends Views_Base {
	
	public $threads;
	public $page;
	public $pagesize;
	public $nopages;
	public $nothreads;
	
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->$task()
	 * 
	 * @uses	Models_Base::fetchById()
	 * @uses	Models_Base->getForeignCount()
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function printThreads() {
		foreach ($this->threads as $thread) {
			$user = Models_User::fetchById( $thread->user_id );
			$noReplies = $thread->getForeignCount( "Models_Reply" );
			echo "<div class=\"element\"><h3>Thread:</h3><p>";
			echo var_dump( $thread );
			echo "</p><h3>Door user:</h3><p>";
			echo var_dump( $user );
			echo "</p><h3>Deze thread heeft {$noReplies} replies.</h3></div>";
		}
	}
	
	public function printPagination() {
		echo "<div class=\"pagination\">";
		for ($i = 1; $i <= $this->nopages; $i++) {
			if ($i == $this->page)
				echo "<em>$i</em>";
			else
				echo "<a href=\"" . getUrl("threads","unanswered") . "\">$i</a>";
		}
		echo "</div>";
	}
	
}
