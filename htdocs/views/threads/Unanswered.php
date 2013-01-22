<?php

class Views_Threads_Unanswered extends Views_Base {
	
	public $threads;
	public $start;
	public $end;
	
	public function render() {
		echo "<h2>Hello World!</h2>";
		foreach ($this->threads as $thread) {
			$user = $thread->getForeignModels( "Models_User" );
			echo "<div class=\"element\"><h3>Thread:</h3><p>";
			echo var_dump( $thread );
			echo "</p><h3>Door user:</h3><p>";
			echo var_dump( $user[0] );
			echo "</p></div>";
		}
	}
	
}