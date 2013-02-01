<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

/**
 * Produces a list of users. Possibly adding admin-options like editing the user-role.
 * 
 * @todo Implementing user-editing.
 * @author Ramon Creyghton <r.creyghton@gmail.com>
 */
class Views_Users_Overview extends Views_Base {

	/**
	 * Array met alle user-objecten. 
	 * 
	 * @var Models_User[]
	 */
	public $users;

	/**
	 * Rendering the Users_Overview view.
	 * 
	 * @uses Models_Base::getForeignCount()
	 */
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
			$noThreads = $u->getForeignCount("Models_Thread");
			$noReplies = $u->getForeignCount("Models_Reply");

			echo "					<div class=\"categories_overview_container\">\n";

			//start actionbar, if admin only.
			if ($admin) {
				$status = "categories_overview_statusopen";
				if ($u->role < 0)
					$status = "categories_overview_statusblocked";
				if ($u->role == 0)
					$status = "categories_overview_statusrestricted";

				echo "						<div class='categories_overview_actionbar {$status}'>";

				if ($u->role == 1)
					echo "<a href='./users/overview/?role=0&user={$u->id}'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='make_user' title='Huidige status: admin. Klik om user te maken.' /></a>";
				else
					echo "<a href='./users/overview/?role=1&user={$u->id}'><img src='./assets/images/icons/16x16/delete.png' width='16' height='16' alt='make_admin' title='Huidige status: user. Klik om admin te maken.' /></a>";
				if ($u->role == -1)
					echo "<a href='./users/overview/?role=0&user={$u->id}'><img src='./assets/images/icons/16x16/block.png' width='16' height='16' alt='hide' title='Gebruiker nu open. Klik hier om te unblocken.' /></a>";
				else
					echo "<a href='./users/overview/?role=-1&user={$u->id}'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='hide' title='Gebruiker nu open. Klik hier om te blocken.' /></a>";
				echo "</div>\n";
				//end actionbar
			}

			echo "						<h3 class='categories_overview_header'><a href=\"./threads/user/{$u->id}\" class='headerlink'>{$u->firstname} {$u->lastname}</a></h3>
						<p class='categories_overview_content'>Geregistreerd op " . date("d-m-Y H:i", $u->ts_registered) . " met {$u->email}. {$noThreads} vra" . ($noThreads == 1 ? "ag" : "gen") . " gesteld, {$noReplies} antwoo" . ($noReplies == 1 ? "rd" : "rden") . " gegeven.</p>
					</div>\n";
		}
	}

}
