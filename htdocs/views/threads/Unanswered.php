<?php

class Views_Threads_Unanswered extends Views_Base {
	
	public $threads;
	public $start;
	public $end;
	
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
	 * 
	 * @uses	Models_Base::fetchById()
	 * @uses	Models_Base->getForeignCount()
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		echo "<h2>Hello World!</h2>";
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
	
}
