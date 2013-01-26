<?php

class Views_Threads_Single extends Views_Threads_Base {
	
	public $thread;
	public $replies;
	
	/**
	 * Renders the contents of this view, by parsing the $threads-array made by Controllers_Threads->unanswered().
	 * 
	 * @author	Ramon Creyghton <r.creyghton@gmail.com>
	 */
	public function render() {
		$t = $this->thread;
		
		echo "<div class='threads_single_thread_container'>";
		echo "<h1 class='threads_single_thread_header'>{$t->title}</h1>";
		echo "<p class='threads_single_thread_content'>";
		echo $t->content;
		echo "</p>";
		echo "</div>";
		
		//thread details section
		echo "<div class='threads_single_thread_details_container'>";
			echo "<div class='threads_single_thread_credits'>";
			echo "<p>";
			echo "Credits";
			echo "45";
			echo "</p>";
			echo "</div>";

			//details
			echo "<div class='threads_single_thread_details'>";
			echo "<p>";
			echo "Gesteld door <a href='./threads/user/?usr={$t->user_id}'>{$t->user->firstname} {$t->user->lastname}</a> <br/>";
			echo date("d-m-Y H:i");
			echo "</p>";
			echo "</div>";
		echo "</div>";
		
		
		//@todo: figure out a nice way to first render the accepted answer, then the rest of the replies
		//render all the replies
		if( count( $this->replies ) ) {
			foreach( $this->replies as $r ) {
				$user = Models_User::fetchById( $r->user_id );
				echo "<div class='threads_single_reply_container'>";
				echo "<h3 class='threads_single_reply_header'>{$r->title}</h3>";
				echo "<p>{$r->content}</p>";
				echo "</div>";
			}
		} else {
			echo "<div class='threads_single_notanswered'>";
			echo "Deze vraag is nog niet beantwoord. Kunt u helpen? <br/>";
			//if ( Helpers_User::is_loggedin() ) {
				echo "<a href=''>Beantwoord deze vraag!</a>";
			//} else {
				echo "<a href='./users/login/'>Login om te reageren</a>";
			//}
			echo "</div>";
		}
		
		var_dump( $this->replies );
	}
	
}