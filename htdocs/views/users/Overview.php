<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_Users_Overview extends Views_Base {

	/**
	 *
	 * @var Models_User[]	Array met alle category-objecten. 
	 */
	public $users;
	
	public function render() {
		$this->title = "Alle gebruikers";
		$user = Helpers_User::getLoggedIn();
		$user_id = $user ? $user->id : 0;
		$user_role = $user ? $user->role : 0;
		$admin = false;
		if ($user_role == Models_User::ROLE_ADMIN)
			$admin = true;
		
		
		echo "<h2>Alle gebruikers:</h2>\n";
		
		foreach ($this->users as $u) {
			echo "					<div class=\"categories_overview_container\">\n";
			
			//start actionbar, has contents if admin only.
			echo "						<div class='threads_single_actionbar'>";
			if ($admin) {
				if ( $u->status == 1 )
					echo "<a href='./users/overview/?make_user={$u->id}'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='make_user' title='Huidige status: admin. Klik om user te maken.' /></a>";
				else
					echo "<a href='./users/overview/?make_admin={$u->id}'><img src='./assets/images/icons/16x16/delete.png' width='16' height='16' alt='make_admin' title='Huidige status: user. Klik om admin te maken.' /></a>";
				echo "<a href='./users/Registrationform/?id={$u->id}'><img src='./assets/images/icons/16x16/application_edit.png' width='16' height='16' alt='bewerk' title='Bewerk deze user' /></a>";
			}
			echo "</div>\n";
			//end actionbar
			
			
			echo "						<h3 class='categories_overview_header'><a href=\"./threads/user/{$u->id}\" class='headerlink'>{$u->firstname} {$u->lastname}</a></h3>
						<p class='categories_overview_content'>Geregistreerd op " . date("d-m-Y H:i", $u->ts_registered) . " met {$u->email}</p>
					</div>\n";
		}
	}
}
