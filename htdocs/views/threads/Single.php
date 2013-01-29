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
		$user = Helpers_User::getLoggedIn();
		$user_id = $user ? $user->id : 0;
		$user_role = $user ? $user->role : 0;

		$t = $this->thread;

		if ($t->status < 1 && $user_role != Models_User::ROLE_ADMIN) {
			echo "<h1>Deze vraag is niet meer beschikbaar.</h1>";
			return;
		}

		echo "<div class='threads_single_thread_container'>";

		##### start actionbar
		echo "<div class='threads_single_actionbar'>";

		//what if the current user is an admin
		if ($user_role == Models_User::ROLE_ADMIN) {
			echo "<a href='./threads/single/{$t->id}?hide_thread=$t->id''><img src='./assets/images/icons/16x16/application_delete.png' width='16' height='16' alt='verberg' title='verberg' /></a>";
		}

		if ($user_role == Models_User::ROLE_ADMIN || $t->user_id == $user_id) {
			echo "<a href=''><img src='./assets/images/icons/16x16/application_edit.png' width='16' height='16' alt='bewerk' title='bewerk' /></a>";
		}
		echo "</div>";
		##### end actionbar

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
		echo "Gesteld door <a href='./threads/user/{$t->user_id}'>{$t->user->firstname} {$t->user->lastname}</a> <br/>";
		echo $t->ts_modified ? date("d-m-Y H:i", $t->ts_modified) : date("d-m-Y H:i", $t->ts_created);
		echo "</p>";
		echo "</div>";
		echo "</div>";


		//@todo: figure out a nice way to first render the accepted answer, then the rest of the replies
		//render all the replies
		if (count($this->replies)) {
			foreach ($this->replies as $r) {
				if ($r->visibility == 0 && $user_role != Models_User::ROLE_ADMIN) {
					continue;
				}
				$user = Models_User::fetchById($r->user_id);
				$accepted = "";
				if( $t->answer_id == $r->id ) 
					$accepted = ' threads_single_reply_accepted';
				echo "<div class='threads_single_reply_container{$accepted}'>";
				
				if( !empty( $accepted ) ) {
					echo "<img class='accepted_hover' src='./assets/images/icons/32x32/accept.png' alt='Goedgekeurd door vraagsteller' title='Goedgekeurd door vraagsteller' />";
				}
				
				##### start actionbar
				echo "<div class='threads_single_actionbar_reply'>";

				if ($user_role == Models_User::ROLE_ADMIN) {
					echo "<a href='./threads/single/{$t->id}?hide_reply=$r->id'><img src='./assets/images/icons/16x16/application_delete.png' width='16' height='16' alt='verberg' title='verberg' /></a>";
				}

				if ($user_role == Models_User::ROLE_ADMIN || $r->user_id == $user_id) {
					echo "<a href=''><img src='./assets/images/icons/16x16/application_edit.png' width='16' height='16' alt='bewerk' title='bewerk' /></a>";
				}
				
				if ( 
					( $user_id == $t->user_id || $user_role == Models_User::ROLE_ADMIN )
					&& $t->answer_id == 'NULL'			
				) {
					echo "<a href='./threads/single/{$t->id}?accept=$r->id'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='Accepteer antwoord' title='Accpeteer antwoord' /></a>";
				}
				
				if ( 
					( $user_id == $t->user_id || $user_role == Models_User::ROLE_ADMIN )
					&& $t->answer_id == $r->id			
				) {
					echo "<a href='./threads/single/{$t->id}?deaccept=$r->id'><img src='./assets/images/icons/16x16/delete.png' width='16' height='16' alt='Verwijder acceptatie' title='Verwijder acceptatie' /></a>";
				}
				
				echo "</div>";
				##### end actionbar

				echo "<h3 class='threads_single_reply_header'>{$r->title}</h3>";
				echo "<p class='threads_single_reply_content'>{$r->content}</p>";

				//details
				echo "<div class='threads_single_reply_details'>";
				echo "<p>";
				echo "<a href='./threads/user/{$user->id}'>{$user->firstname} {$user->lastname}</a> <br/>";
				echo $r->ts_modified ? date("d-m-Y H:i", $r->ts_modified) : date("d-m-Y H:i", $r->ts_created);
				echo "</p>";
				echo "</div>";
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
	}

}
