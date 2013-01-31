<?php

/*
 * All classes and scripts must be loaded via index.php, where WEBDB_EXEC is set,
 * and stop executing immediatly if otherwise.
 */
if (!defined("WEBDB_EXEC"))
	die("No direct access!");

class Views_Categories_Overview extends Views_Base {

	/**
	 *
	 * @var Models_Category[]	Array met alle category-objecten. 
	 */
	public $categories;
	
	public function render() {
		$this->title = "Alle categorieen";
		$user = Helpers_User::getLoggedIn();
		$user_id = $user ? $user->id : 0;
		$user_role = $user ? $user->role : 0;
		$admin = false;
		if ($user_role == Models_User::ROLE_ADMIN)
			$admin = true;
		
		
		echo "<h2>Alle categorieen:</h2>\n";
		
		foreach ($this->categories as $c) {
			echo "					<div class=\"categories_overview_container\">\n";
			
			//start actionbar, if admin only.
			if ($admin) {
				$status = "categories_overview_statusopen";
				if ( $c->status < 0 )
					$status = "categories_overview_statusblocked";
				if ( $c->status == 0 )
					$status = "categories_overview_statusrestricted";
				
				echo "						<div class='threads_single_actionbar {$status}'>";
				
				if ( $c->status == 1 )
					echo "<a href='./categories/overview/?restrict_status={$c->id}'><img src='./assets/images/icons/16x16/accept.png' width='16' height='16' alt='restrict' title='Huidige status: open. Klik om te restricten.' /></a>";
				else
					echo "<a href='./categories/overview/?open_status={$c->id}'><img src='./assets/images/icons/16x16/delete.png' width='16' height='16' alt='open' title='Huidige status: restricted. Klik om te openen.' /></a>";
				echo "<a href='./categories/overview/?hide_status={$c->id}'><img src='./assets/images/icons/16x16/delete.png' width='16' height='16' alt='hide' title='Verberg deze categorie' /></a>";
				echo "<a href='./categories/categoryform/?id={$c->id}'><img src='./assets/images/icons/16x16/application_edit.png' width='16' height='16' alt='bewerk' title='Bewerk titel en omsschrijving.' /></a>";
			
				echo "</div>\n";
			//end actionbar
			}
			
			echo "						<h3 class='categories_overview_header'><a href=\"./threads/category/{$c->id}\" class='headerlink'>{$c->name}</a></h3>
						<p class='categories_overview_content'>{$c->description}</p>
					</div>\n";
		}
	}
}
