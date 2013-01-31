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
			
			//start actionbar, has contents if admin only.
			echo "						<div class='threads_single_actionbar'>";
			if ($admin) {
				if ( $c->status == 1 )
					echo "<a href='./categories/overview/?restrict_status={$c->id}'><img src='./assets/images/icons/16x16/application_delete.png' width='16' height='16' alt='restrict' title='restrict' /></a>";
				else
					echo "<a href='./categories/overview/?open_status={$c->id}'><img src='./assets/images/icons/16x16/application_accept.png' width='16' height='16' alt='open' title='open' /></a>";
				echo "<a href='./categories/categoryform/?id={$c->id}'><img src='./assets/images/icons/16x16/application_edit.png' width='16' height='16' alt='bewerk' title='bewerk' /></a>";
			}
			echo "</div>\n";
			//end actionbar
			
			
			echo "						<h3 class='categories_overview_header'><a href=\"./threads/category/{$c->id}\" class='headerlink'>{$c->name}</a></h3>
						<p class='categories_overview_content'>{$c->description}</p>
					</div>\n";
		}
	}
}